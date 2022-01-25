<?php

namespace App\Http\Controllers;

use App\Models\RecursoAdministrativa;
use Illuminate\Http\Request;
use App\Http\Transformers\RecursoAdministrativaTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class RecursoAdministrativaController extends ApiBaseController
{
	public function restore($id) {
		$recurso_administrativa = RecursoAdministrativa::withTrashed()->where('id', $id)->first();
		if(isset($recurso_administrativa)) {
			try {
				$recurso_administrativa->restore();
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
			return $this->response(true, RecursoAdministrativa::withTrashed()->paginate(), 200);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function index()
	{
		try {
			return $this->response(true, RecursoAdministrativa::paginate(), 200);
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
			$recurso_administrativa = RecursoAdministrativaTransformer::toInstance($request->all());
			$recurso_administrativa->save();
			DB::commit();

			return $this->response(true, $recurso_administrativa, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
		try {
			$recurso_administrativa = RecursoAdministrativa::find($id);
			
			if(isset($recurso_administrativa)) 
				return $this->response(true, $recurso_administrativa, 200);
			else 
				return $this->response(false,'Not found.', 404);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function update(Request $request, $id)
	{
		$recurso_administrativa = RecursoAdministrativa::find($id);
		if(isset($recurso_administrativa)) {
			try {
				DB::beginTransaction();
				$recurso_administrativa = RecursoAdministrativaTransformer::toInstance($request->all(), $recurso_administrativa);
				$recurso_administrativa->save();
				DB::commit();

				return $this->response(true, $recurso_administrativa, 200);
			} catch (Exception $ex) {
				DB::rollBack();
				return $this->response(false, $ex->getMessage(), 409);
			}
		}
	}

	public function destroy($id)
	{
		$recurso_administrativa = RecursoAdministrativa::find($id);
		try {
			if(isset($recurso_administrativa)) {
				$recurso_administrativa->delete();
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
			'valor' => ['required'],
			'instituicao_id' => ['required', 'exists:instituicoes,id'],
			'recurso_gestora_id' => ['required', 'exists:recursos_gestoras,id']
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}
}