<?php

namespace App\Http\Controllers;

use App\Models\CreditoDisponivel;
use Illuminate\Http\Request;
use App\Http\Transformers\CreditoDisponivelTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class CreditoDisponivelController extends ApiBaseController
{
	public function getOpcoes() 
	{
		try {
			return $this->response(true, CreditoDisponivel::getOpcoes(), 200);
		} catch(Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function restore($id) {
		$credito_disponivel = CreditoDisponivel::withTrashed()->where('id', $id)->first();
		if(isset($credito_disponivel)) {
			try {
				$credito_disponivel->restore();
				return $this->response(true, 'Item restored.', 200);
			}	catch(Exception $ex) {
				return $this->response(false, $ex->getMessage(), 409);
			}
		} else {
			return $this->response(false, 'Item not found.', 404);
		}
	}

	public function indexWithTrashed()
	{
		try {
			return $this->response(true, CreditoDisponivel::withTrashed()->paginate(), 200);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function index()
	{
		try {
			return $this->response(true, CreditoDisponivel::paginate(), 200);
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
			$credito_disponivel = CreditoDisponivelTransformer::toInstance($request->all());
			$credito_disponivel->save();
			DB::commit();

			return $this->response(true, $credito_disponivel, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
		try {
			$credito_disponivel = CreditoDisponivel::find($id);
			
			if(isset($credito_disponivel)) 
				return $this->response(true, $credito_disponivel, 200);
			else 
				return $this->response(false,'Not found.', 404);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function update(Request $request, $id)
	{
		$invalido = $this->validation($request);

		if($invalido) return $this->response(false, $invalido, 422);
		
		$credito_disponivel = CreditoDisponivel::find($id);
		if(isset($credito_disponivel)) {
			try {
				DB::beginTransaction();
				$credito_disponivel = CreditoDisponivelTransformer::toInstance($request->all(), $credito_disponivel);
				$credito_disponivel->save();
				DB::commit();

				return $this->response(true, $credito_disponivel, 200);
			} catch (Exception $ex) {
				DB::rollBack();
				return $this->response(false, $ex->getMessage(), 409);
			}
		}
	}

	public function destroy($id)
	{
		$credito_disponivel = CreditoDisponivel::find($id);
		try {
			if(isset($credito_disponivel)) {
				$credito_disponivel->delete();
				return $this->response(true, 'Item deleted.', 200);
			} else {
				return $this->response(false, 'Item not found.', 404);
			}
		} catch(Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	protected function validation($request) 
	{
		$validator = Validator::make($request->all(), [
			'descricao' => ['required'],
			'valor_solicitado' => ['required'],
			'valor_disponivel' => ['nullable'],
			'despesa_id' => ['required', 'exists:despesas,id'],
			'unidade_administrativa_id' => ['required', 'exists:unidades_administrativas,id']
		]);

		if ($validator->fails()) {
            return $validator->errors()->toArray();
		}
	}
}
