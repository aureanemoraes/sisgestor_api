<?php

namespace App\Http\Controllers;

use App\Models\FonteAcao;
use App\Models\Fonte;
use App\Models\UnidadeAdministrativa;
use Illuminate\Http\Request;
use App\Http\Transformers\FonteAcaoTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class FonteAcaoController extends ApiBaseController
{
	public function index()
	{
		try {
			return $this->response(true, FonteAcao::paginate(), 200);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function store(Request $request)
	{
		$invalido = $this->validation($request);

		if($invalido) return $this->response(false, $invalido, 422);

		try {
			DB::beginTransaction();
			$fonte_acao = FonteAcaoTransformer::toInstance($request->all());
            $rule = $this->rules($fonte_acao);
            if($rule['status']) {
                $fonte_acao->save();
                DB::commit();
			    return $this->response($rule['status'], $fonte_acao, 200);
            } else {
                return $this->response($rule['status'], $rule['msg'], 400);
            }
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
        $fonte_acao = FonteAcao::find($id);

        if(isset($fonte_acao)) {
            try {
			
                if(isset($fonte_acao)) 
                    return $this->response(true, $fonte_acao, 200);
                else 
                    return $this->response(false,'Not found.', 404);
            } catch (Exception $ex) {
                return $this->response(false, $ex->getMessage(), 409);
            }
        } else {
            return $this->response(false, 'Not found.', 404); 
        }
	
	}

	public function update(Request $request, $id)
	{
		$invalido = $this->validation($request);

		if($invalido) return $this->response(false, $invalido, 422);
        
		$fonte_acao = FonteAcao::find($id);
		if(isset($fonte_acao)) {
			try {
				DB::beginTransaction();
				$fonte_acao = FonteAcaoTransformer::toInstance($request->all(), $fonte_acao);
				$fonte_acao->save();
				DB::commit();

				return $this->response(true, $fonte_acao, 200);
			} catch (Exception $ex) {
				DB::rollBack();
				return $this->response(false, $ex->getMessage(), 409);
			}
		} else {
            return $this->response(false, 'Not found.', 404); 
        }
	}

	public function destroy($id)
	{
		$fonte_acao = FonteAcao::find($id);
		if(isset($fonte_acao)) {
				try {
						$fonte_acao->delete();
						return $this->response(true, 'Item deleted.', 200);
				} catch(Exception $ex) {
						return $this->response(false, $ex->getMessage(), 409);
				}
		} else {
				return $this->response(false, 'Item not found.', 404);
			}
	}

	protected function validation($request) 
	{
		$validator = Validator::make($request->all(), [
            'fonte_id' => ['required', 'exists:fontes,id'],
            'acao_id' => ['required', 'exists:acoes,id'],
            'exercicio_id' => ['required', 'exists:exercicios,id'],
            'valor' => ['required', 'numeric'],
            'instituicao_id' => ['nullable', 'exists:instituicoes,id'],
            'unidade_gestora_id' => ['nullable', 'exists:unidades_gestoras,id'],
            'unidade_administrativa' => ['nullable', 'exists:unidades_administrativas_id,id'],
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}

    protected function rules($fonte_acao) {
        $contador_orgao = 0;
        if(isset($fonte_acao->instituicao_id)) $contador_orgao++;
        if(isset($fonte_acao->unidade_gestora_id)) $contador_orgao++;
        if(isset($fonte_acao->unidade_administrativa_id)) $contador_orgao++;

        if($contador_orgao == 1) {
            if(isset($fonte_acao->instituicao_id)) {
                $exists = FonteAcao::where('fonte_id', $fonte_acao->fonte_id)
                ->where('instituicao_id', $fonte_acao->instituicao_id)->where('acao_id', $fonte_acao->acao_id)->exists();

                if(!$exists) {
                    $valor_configurado = Fonte::find($fonte_acao->fonte_id)->valor;
                    $valor_utilizado = FonteAcao::where('fonte_id', $fonte_acao->fonte_id)
                        ->where('instituicao_id', $fonte_acao->instituicao_id)
                        ->sum('valor');
                } else {
                    return [
                        'status' => false,
                        'msg' => 'Esta ação já possui vínculo com esta fonte.'
                    ];
                }
            } else if(isset($fonte_acao->unidade_gestora_id)) {
                $exists = FonteAcao::where('fonte_id', $fonte_acao->fonte_id)
                ->where('acao_id', $fonte_acao->acao_id)
                ->where('unidade_gestora_id', $fonte_acao->unidade_gestora_id)
                ->exists();

                if(!$exists) {
                    $valor_configurado = FonteAcao::where('fonte_id', $fonte_acao->fonte_id)
                        ->where('acao_id', $fonte_acao->acao_id)
                        ->sum('valor');
                    $valor_utilizado = FonteAcao::where('fonte_id', $fonte_acao->fonte_id)
                        ->where('acao_id', $fonte_acao->acao_id)
                        ->whereNotNull('unidade_gestora_id')
                        ->sum('valor');
                } else {
                    return [
                        'status' => false,
                        'msg' => 'Esta ação já possui vínculo com esta fonte nesta unidade gestora.'
                    ];
                }
            } else if(isset($fonte_acao->unidade_administrativa_id)) {
                $exists = FonteAcao::where('fonte_id', $fonte_acao->fonte_id)
                    ->where('acao_id', $fonte_acao->acao_id)
                    ->where('unidade_administrativa_id', $fonte_acao->unidade_administrativa_id)
                    ->exists();
                if(!$exists) {
                    $unidade_gestora_id = UnidadeAdministrativa::where('id', $fonte_acao->unidade_administrativa_id)->first()->unidade_gestora_id;
                    $valor_configurado = FonteAcao::where('fonte_id', $fonte_acao->fonte_id)
                        ->where('acao_id', $fonte_acao->acao_id)
                        ->where('unidade_gestora_id', $unidade_gestora_id)
                        ->sum('valor');
                    $valor_utilizado = FonteAcao::where('fonte_id', $fonte_acao->fonte_id)
                        ->where('acao_id', $fonte_acao->acao_id)
                        ->whereNotNull('unidade_administrativa_id')
                        ->sum('valor');
                } else {
                    return [
                        'status' => false,
                        'msg' => 'Esta ação já possui vínculo com esta fonte nesta unidade administrativa.'
                    ];
                }
            }
    
            $valor_disponivel = $valor_configurado - $valor_utilizado;
    
            if($valor_disponivel >= $fonte_acao->valor) {
                return [
                    'status' => true,
                    'msg' => ''
                ];
            } else {
                return [
                    'status' => false,
                    'msg' => 'Este valor não está disponível'
                ];
            }
        } else if ($contador_orgao >= 2) {
            return [
                'status' => false,
                'msg' => 'Selecione somente uma orgão para vincular a ação: ou uma instituição, ou uma unidade gestora ou uma unidade administrativa'
            ];
        } else if ($contador_orgao == 0) {
            return [
                'status' => false,
                'msg' => 'É necessário inserir uma instituição, unidade gestora ou unidade administrativa'
            ];
        }
    }   
}
