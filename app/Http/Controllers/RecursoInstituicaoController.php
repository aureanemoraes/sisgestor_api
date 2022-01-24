<?php

namespace App\Http\Controllers;

use App\Models\RecursoInstituicao;
use Illuminate\Http\Request;
use App\Http\Transformers\RecursoInstituicaoTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class RecursoInstituicaoController extends ApiBaseController
{
	public function restore($id) {
		$recurso_instituicao = RecursoInstituicao::withTrashed()->where('id', $id)->first();
		if(isset($recurso_instituicao)) {
			try {
				$recurso_instituicao->restore();
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
			return $this->response(true, RecursoInstituicao::withTrashed()->paginate(), 200);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function index()
	{
		try {
			return $this->response(true, RecursoInstituicao::paginate(), 200);
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
			$recurso_instituicao = RecursoInstituicaoTransformer::toInstance($request->all());
			$recurso_instituicao->save();
			DB::commit();

			return $this->response(true, $recurso_instituicao, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
		try {
			$recurso_instituicao = RecursoInstituicao::find($id);
			
			if(isset($recurso_instituicao)) 
				return $this->response(true, $recurso_instituicao, 200);
			else 
				return $this->response(false,'Not found.', 404);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function update(Request $request, $id)
	{
		$recurso_instituicao = RecursoInstituicao::find($id);
		if(isset($recurso_instituicao)) {
			try {
				DB::beginTransaction();
				$recurso_instituicao = RecursoInstituicaoTransformer::toInstance($request->all(), $recurso_instituicao);
				$recurso_instituicao->save();
				DB::commit();

				return $this->response(true, $recurso_instituicao, 200);
			} catch (Exception $ex) {
				DB::rollBack();
				return $this->response(false, $ex->getMessage(), 409);
			}
		}
	}

	public function destroy($id)
	{
		$recurso_instituicao = RecursoInstituicao::find($id);
		try {
			if(isset($recurso_instituicao)) {
				$recurso_instituicao->delete();
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
			'instituicao_id' => ['required', 'exists:instituicoes,id']
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}
}
