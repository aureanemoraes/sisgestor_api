<?php

namespace App\Http\Controllers;

use App\Models\Acao;
use Illuminate\Http\Request;
use App\Http\Transformers\AcaoTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class AcaoController extends ApiBaseController
{

	public function pesquisa(Request $request) {
		if(isset($request->termo)) {
			$termo = $request->termo;

			$resultado = Acao::whereHas('acao_tipo', function ($query) use ($termo) {
					$query->where('nome', 'ilike', '%' . $termo . '%')
					->orderBy('fav', 'desc');
				}
			)->get();

			if(count($resultado) > 0) return $this->response(true, $resultado, 200);
			else return $this->response(true, 'Nenhum resultado encontrado.', 404);
		} else {
			return $this->response(false, 'Nenhum termo enviado para pesquisa.', 404);
		}
	}

	public function index(Request $request)
	{
		if ($request->instituicao_id) {
			$acoes = Acao::with([
				'fontes' => function ($query) use ($request) {
					$query->where('fontes_acoes.instituicao_id', $request->instituicao_id);
				}
			])->where('instituicao_id', $request->instituicao_id)->orderBy('fav', 'desc')->orderBy('id')->paginate();
			$acoes = $this->acoes_tratadas($acoes, $request->instituicao_id);
		} else if ($request->unidade_gestora_id) {
			$acoes = Acao::withAndWhereHas(
				'fontes', function ($query) use ($request) {
					$query->where('fontes_acoes.unidade_gestora_id', $request->unidade_gestora_id);
				}
			)->orderBy('fav', 'desc')->orderBy('id')->paginate();
			$acoes = $this->acoes_tratadas($acoes, $request->instituicao_id);
		} else if ($request->unidade_administrativa_id) {
			$acoes = Acao::withAndWhereHas(
				'fontes', function ($query) use ($request) {
					$query->where('fontes_acoes.unidade_administrativa_id', $request->unidade_administrativa_id);
				}
			)->orderBy('fav', 'desc')->orderBy('id')->paginate();
			$acoes = $this->acoes_tratadas($acoes, $request->instituicao_id);
		}
		try {
			return $this->response(true, $acoes, 200);
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
			$acao = AcaoTransformer::toInstance($request->all());
			$acao->save();
			DB::commit();

			return $this->response(true, $acao, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
        $acao = Acao::find($id);

        if(isset($acao)) {
            try {
			
                if(isset($acao)) 
                    return $this->response(true, $acao, 200);
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
        
		$acao = Acao::find($id);
		if(isset($acao)) {
			try {
				DB::beginTransaction();
				$acao = AcaoTransformer::toInstance($request->all(), $acao);
				$acao->save();
				DB::commit();

				return $this->response(true, $acao, 200);
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
		$acao = Acao::find($id);
		if(isset($acao)) {
				try {
						$acao->delete();
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
				'acao_tipo_id' => ['required', 'exists:acoes_tipos,id'],
        'exercicio_id' => ['required', 'exists:exercicios,id']
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}

	protected function acoes_tratadas($acoes, $id, $tipo='instituicao') {
		$valor_total = 0;
		foreach($acoes as $acao) {
			if(count($acao->fontes) > 0) {
				foreach($acao->fontes as $fonte) {
					$valor_total += $fonte->pivot->valor;
				}
			}
			$acao->valor_total = $valor_total;
		}



		return $acoes;
	}
}
