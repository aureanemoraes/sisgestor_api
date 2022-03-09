<?php

namespace App\Http\Controllers;

use App\Models\Dimensao;
use Illuminate\Http\Request;
use App\Http\Transformers\DimensaoTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class DimensaoController extends ApiBaseController
{
	public function getOpcoes() 
	{
		try {
			return $this->response(true, Dimensao::getOpcoes(), 200);
		} catch(Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function restore($id) {
		$dimensao = Dimensao::withTrashed()->where('id', $id)->first();
		if(isset($dimensao)) {
			try {
				$dimensao->restore();
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
			return $this->response(true, Dimensao::withTrashed()->paginate(), 200);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function index()
	{
		try {
			return $this->response(true, Dimensao::paginate(), 200);
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
			$dimensao = DimensaoTransformer::toInstance($request->all());
			$dimensao->save();
			DB::commit();

			return $this->response(true, $dimensao, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
		try {
			$dimensao = Dimensao::find($id);
			
			if(isset($dimensao)) 
				return $this->response(true, $dimensao, 200);
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
		
		$dimensao = Dimensao::find($id);
		if(isset($dimensao)) {
			try {
				DB::beginTransaction();
				$dimensao = DimensaoTransformer::toInstance($request->all(), $dimensao);
				$dimensao->save();
				DB::commit();

				return $this->response(true, $dimensao, 200);
			} catch (Exception $ex) {
				DB::rollBack();
				return $this->response(false, $ex->getMessage(), 409);
			}
		}
	}

	public function destroy($id)
	{
		$dimensao = Dimensao::find($id);
		try {
			if(isset($dimensao)) {
				$dimensao->delete();
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
			'descricao' => ['nullable'],
			'instituicao_id' => ['required', 'exists:instituicoes,id'],
            'exercicio_id' => ['required', 'exists:exercicios,id']
		]);

		if ($validator->fails()) {
            return $validator->errors()->toArray();
		}
	}
}
