<?php

namespace App\Http\Controllers;

use App\Models\LimiteOrcamentario;
use Illuminate\Http\Request;
use App\Http\Transformers\LimiteOrcamentarioTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class LimiteOrcamentarioController extends ApiBaseController
{
	public function getOpcoes() 
	{
		try {
			return $this->response(true, LimiteOrcamentario::getOpcoes(), 200);
		} catch(Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function restore($id) {
		$limite_orcamentario = LimiteOrcamentario::withTrashed()->where('id', $id)->first();
		if(isset($limite_orcamentario)) {
			try {
				$limite_orcamentario->restore();
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
			return $this->response(true, LimiteOrcamentario::withTrashed()->paginate(), 200);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function index()
	{
		try {
			return $this->response(true, LimiteOrcamentario::paginate(), 200);
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
			$limite_orcamentario = LimiteOrcamentarioTransformer::toInstance($request->all());
			$limite_orcamentario->save();
			DB::commit();

			return $this->response(true, $limite_orcamentario, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
		try {
			$limite_orcamentario = LimiteOrcamentario::find($id);
			
			if(isset($limite_orcamentario)) 
				return $this->response(true, $limite_orcamentario, 200);
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
		
		$limite_orcamentario = LimiteOrcamentario::find($id);
		if(isset($limite_orcamentario)) {
			try {
				DB::beginTransaction();
				$limite_orcamentario = LimiteOrcamentarioTransformer::toInstance($request->all(), $limite_orcamentario);
				$limite_orcamentario->save();
				DB::commit();

				return $this->response(true, $limite_orcamentario, 200);
			} catch (Exception $ex) {
				DB::rollBack();
				return $this->response(false, $ex->getMessage(), 409);
			}
		}
	}

	public function destroy($id)
	{
		$limite_orcamentario = LimiteOrcamentario::find($id);
		try {
			if(isset($limite_orcamentario)) {
				$limite_orcamentario->delete();
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
			'numero_processo' => ['required'],
			'despesa_id' => ['required', 'exists:despesas,id'],
			'unidade_administrativa_id' => ['required', 'exists:unidades_administrativas,id']
		]);

		if ($validator->fails()) {
            return $validator->errors()->toArray();
		}
	}
}
