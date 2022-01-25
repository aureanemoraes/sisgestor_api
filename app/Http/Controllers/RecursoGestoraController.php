<?php

namespace App\Http\Controllers;

use App\Models\RecursoGestora;
use Illuminate\Http\Request;
use App\Http\Transformers\RecursoGestoraTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class RecursoGestoraController extends ApiBaseController
{
	public function restore($id) {
		$recurso_gestora = RecursoGestora::withTrashed()->where('id', $id)->first();
		if(isset($recurso_gestora)) {
			try {
				$recurso_gestora->restore();
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
			return $this->response(true, RecursoGestora::withTrashed()->paginate(), 200);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function index()
	{
		try {
			return $this->response(true, RecursoGestora::paginate(), 200);
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
			$recurso_gestora = RecursoGestoraTransformer::toInstance($request->all());
			$recurso_gestora->save();
			DB::commit();

			return $this->response(true, $recurso_gestora, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
		try {
			$recurso_gestora = RecursoGestora::find($id);
			
			if(isset($recurso_gestora)) 
				return $this->response(true, $recurso_gestora, 200);
			else 
				return $this->response(false,'Not found.', 404);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function update(Request $request, $id)
	{
		$recurso_gestora = RecursoGestora::find($id);
		if(isset($recurso_gestora)) {
			try {
				DB::beginTransaction();
				$recurso_gestora = RecursoGestoraTransformer::toInstance($request->all(), $recurso_gestora);
				$recurso_gestora->save();
				DB::commit();

				return $this->response(true, $recurso_gestora, 200);
			} catch (Exception $ex) {
				DB::rollBack();
				return $this->response(false, $ex->getMessage(), 409);
			}
		}
	}

	public function destroy($id)
	{
		$recurso_gestora = RecursoGestora::find($id);
		try {
			if(isset($recurso_gestora)) {
				$recurso_gestora->delete();
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
			'recurso_instituicao_id' => ['required', 'exists:recursos_instituicoes,id']
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}
}