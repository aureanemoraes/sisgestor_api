<?php

namespace App\Http\Controllers;

use App\Models\Movimento;
use Illuminate\Http\Request;
use App\Http\Transformers\MovimentoTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class MovimentoController extends ApiBaseController
{
	public function getOpcoes() 
	{
		try {
			return $this->response(true, Movimento::getOpcoes(), 200);
		} catch(Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function restore($id) {
		$movimento = Movimento::withTrashed()->where('id', $id)->first();
		if(isset($movimento)) {
			try {
				$movimento->restore();
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
			return $this->response(true, Movimento::withTrashed()->paginate(), 200);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function index()
	{
		try {
			return $this->response(true, Movimento::paginate(), 200);
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
			$movimento = MovimentoTransformer::toInstance($request->all());
			$movimento->save();
			DB::commit();

			return $this->response(true, $movimento, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
		$movimento = Movimento::find($id);

		try {
			if(isset($movimento)) 
				return $this->response(true, $movimento, 200);
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

		$movimento = Movimento::find($id);
		
		try {
			if(isset($movimento)) {
				DB::beginTransaction();
				$movimento = MovimentoTransformer::toInstance($request->all(), $movimento);
				$movimento->save();
				DB::commit();
			} else {
				return $this->response(false,'Not found.', 404);
			}
			return $this->response(true, $movimento, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
		
	}

	public function destroy($id)
	{
		$movimento = Movimento::find($id);
		try {
			if(isset($movimento)) {
				$movimento->delete();
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
			'valor' => ['required'],
			'exercicio_id' => ['required', 'exists:exercicios,id'],
			'tipo' => ['required'],
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}
}

