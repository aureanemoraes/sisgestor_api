<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Http\Request;
use App\Http\Transformers\PessoaTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class PessoaController extends ApiBaseController
{
	public function restore($id) {
		$pessoa = Pessoa::withTrashed()->where('id', $id)->first();
		if(isset($pessoa)) {
			try {
				$pessoa->restore();
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
			return $this->response(true, Pessoa::withTrashed()->paginate(), 200);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function index()
	{
		try {
			return $this->response(true, Pessoa::paginate(), 200);
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
			$pessoa = PessoaTransformer::toInstance($request->all());
			$pessoa->save();
			DB::commit();

			return $this->response(true, $pessoa, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
		try {
			$pessoa = Pessoa::find($id);
			
			if(isset($pessoa)) 
				return $this->response(true, $pessoa, 200);
			else 
				return $this->response(false,'Not found.', 404);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function update(Request $request, $id)
	{
		$pessoa = Pessoa::find($id);
		if(isset($pessoa)) {
			try {
				DB::beginTransaction();
				$pessoa = PessoaTransformer::toInstance($request->all(), $pessoa);
				$pessoa->save();
				DB::commit();

				return $this->response(true, $pessoa, 200);
			} catch (Exception $ex) {
				DB::rollBack();
				return $this->response(false, $ex->getMessage(), 409);
			}
		}
	}

	public function destroy($id)
	{
		$pessoa = Pessoa::find($id);
		try {
			if(isset($pessoa)) {
				$pessoa->delete();
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
			'nome' => ['required'],
			'cpf' => ['required'],
            'instituicao_id' => ['required', 'exists:instituicoes,id']
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}
}
