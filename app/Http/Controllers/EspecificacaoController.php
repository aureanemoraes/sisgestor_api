<?php

namespace App\Http\Controllers;

use App\Models\Especificacao;
use Illuminate\Http\Request;
use App\Http\Transformers\EspecificacaoTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class EspecificacaoController extends ApiBaseController
{
	public function pesquisa(Request $request) {
		if(isset($request->termo)) {
			$termo = $request->termo;

			$resultado = Especificacao::where('nome', 'ilike', '%' . $termo . '%')->get();

			if(count($resultado) > 0) return $this->response(true, $resultado, 200);
			else return $this->response(true, 'Nenhum resultado encontrado.', 404);
		} else {
			return $this->response(false, 'Nenhum termo enviado para pesquisa.', 404);
		}
	}

	public function index()
	{
		try {
			return $this->response(true, Especificacao::orderBy('fav', 'desc')->orderBy('nome')->get(), 200);
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
			$especificacao = EspecificacaoTransformer::toInstance($request->all());
			$especificacao->save();
			DB::commit();

			return $this->response(true, $especificacao, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
        $especificacao = Especificacao::find($id);

        if(isset($especificacao)) {
            try {
			
                if(isset($especificacao)) 
                    return $this->response(true, $especificacao, 200);
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
        
		$especificacao = Especificacao::find($id);
		if(isset($especificacao)) {
			try {
				DB::beginTransaction();
				$especificacao = EspecificacaoTransformer::toInstance($request->all(), $especificacao);
				$especificacao->save();
				DB::commit();

				return $this->response(true, $especificacao, 200);
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
		$especificacao = Especificacao::find($id);
		if(isset($especificacao)) {
				try {
						$especificacao->delete();
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
			'id' => ['required', 'unique:especificacoes,id'],
			'nome' => ['required']
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}
}
