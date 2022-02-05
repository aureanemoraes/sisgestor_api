<?php

namespace App\Http\Controllers;

use App\Models\CentroCusto;
use Illuminate\Http\Request;
use App\Http\Transformers\CentroCustoTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class CentroCustoController extends ApiBaseController
{
	public function index()
	{
		try {
			return $this->response(true, CentroCusto::paginate(), 200);
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
			$acao = CentroCustoTransformer::toInstance($request->all());
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
        $acao = CentroCusto::find($id);

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
        
		$acao = CentroCusto::find($id);
		if(isset($acao)) {
			try {
				DB::beginTransaction();
				$acao = CentroCustoTransformer::toInstance($request->all(), $acao);
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
		$acao = CentroCusto::find($id);
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
        'matriz_id' => ['required', 'exists:matrizes,id']
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}
}
