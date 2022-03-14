<?php

namespace App\Http\Controllers;

use App\Models\Despesa;
use App\Models\Fonte;
use App\Models\FonteAcao;
use Illuminate\Http\Request;
use App\Http\Transformers\DespesaTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class DespesaController extends ApiBaseController
{
	public function index()
	{
		try {
			return $this->response(true, Despesa::paginate(), 200);
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
			$despesa = DespesaTransformer::toInstance($request->all());
			$extras['acao_id'] = $request->acao_id;
			$extras['fonte_id'] = $request->fonte_id;
			$rule = $this->rules($despesa, $extras);
			if($rule['status']) {
					$despesa->save();
					DB::commit();
			return $this->response($rule['status'], $despesa, 200);
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
        $despesa = Despesa::find($id);

        if(isset($despesa)) {
            try {
			
                if(isset($despesa)) 
                    return $this->response(true, $despesa, 200);
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
        
		$despesa = Despesa::find($id);
		if(isset($despesa)) {
			try {
				DB::beginTransaction();
				$despesa = DespesaTransformer::toInstance($request->all(), $despesa);
				$despesa->save();
				DB::commit();

				return $this->response(true, $despesa, 200);
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
		$despesa = Despesa::find($id);
		if(isset($despesa)) {
				try {
						$despesa->delete();
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
			'descricao' => ['required'],
			'valor' => ['required', 'numeric'],
			'qtd' => ['nullable', 'integer'],
			'qtd_pessoas' => ['nullable', 'integer'],
			'tipo' => ['required'],
			'fonte_id' => ['required', 'exists:fontes,id'],
			'acao_id' => ['required', 'exists:acoes,id'],
			'centro_custo_id' => ['required', 'exists:centros_custos,id'],
			'natureza_despesa_id' => ['required', 'exists:naturezas_despesas,id'],
			'subnatureza_despesa_id' => ['nullable', 'exists:subnaturezas_despesas,id'],
			'unidade_administrativa_id'  => ['required', 'exists:unidades_administrativas,id']
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}

    protected function rules($despesa, $extras) 
		{
				$fonte_acao = FonteAcao::where('fonte_id', $extras['fonte_id'])
					->where('acao_id', $extras['acao_id'])
					->where('unidade_administrativa_id', $despesa->unidade_administrativa_id)
					->first();

        if(isset($fonte_acao)) {
					// Verificar se tem recurso disponível para cadastrar a despesa no valor setado
					$recurso_planejado = $fonte_acao->valor;
					$despesas_lancadas = Despesa::where('fonte_acao_id', $fonte_acao->id)->sum('valor_total');
					$recurso_disponivel = $recurso_planejado - $despesas_lancadas;

					if($despesa->valor <= $recurso_disponivel) {
						return [
							'status' => true,
							'msg' => ''
						];
					} else {
						return [
							'status' => false,
							'msg' => 'O valor inserido não está disponível.'
						];
					}
				} else {
					return [
						'status' => false,
						'msg' => 'O vínculo não existe.'
					];
				}
    }   
}
