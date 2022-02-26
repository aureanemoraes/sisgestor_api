<?php

namespace App\Http\Controllers;

use App\Models\Empenho;
use Illuminate\Http\Request;
use App\Http\Transformers\EmpenhoTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class EmpenhoController extends ApiBaseController
{
	public function getOpcoes() 
	{
		try {
			return $this->response(true, Empenho::getOpcoes(), 200);
		} catch(Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function restore($id) {
		$empenho = Empenho::withTrashed()->where('id', $id)->first();
		if(isset($empenho)) {
			try {
				$empenho->restore();
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
			return $this->response(true, Empenho::withTrashed()->paginate(), 200);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function index()
	{
		try {
			return $this->response(true, Empenho::paginate(), 200);
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
			$empenho = EmpenhoTransformer::toInstance($request->all());
			$empenho->save();
			DB::commit();

			return $this->response(true, $empenho, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
		try {
			$empenho = Empenho::find($id);
			
			if(isset($empenho)) 
				return $this->response(true, $empenho, 200);
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
		
		$empenho = Empenho::find($id);
		if(isset($empenho)) {
			try {
				DB::beginTransaction();
				$empenho = EmpenhoTransformer::toInstance($request->all(), $empenho);
				$empenho->save();
				DB::commit();

				return $this->response(true, $empenho, 200);
			} catch (Exception $ex) {
				DB::rollBack();
				return $this->response(false, $ex->getMessage(), 409);
			}
		}
	}

	public function destroy($id)
	{
		$empenho = Empenho::find($id);
		try {
			if(isset($empenho)) {
				$empenho->delete();
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
			'valor_empenhado' => ['required'],
			'data_empenho' => ['required'],
			'credito_disponivel_id' => ['required', 'exists:creditos_disponiveis,id'],
			'unidade_administrativa_id' => ['required', 'exists:unidades_administrativas,id']
		]);

		if ($validator->fails()) {
            return $validator->errors()->toArray();
		}
	}
}
