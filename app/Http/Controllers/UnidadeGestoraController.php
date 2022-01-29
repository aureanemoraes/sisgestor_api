<?php

namespace App\Http\Controllers;

use App\Models\UnidadeGestora;
use Illuminate\Http\Request;
use App\Http\Transformers\UnidadeGestoraTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class UnidadeGestoraController extends ApiBaseController
{
	public function getOpcoes() 
	{
		try {
			return $this->response(true, UnidadeGestora::getOpcoes(), 200);
		} catch(Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function restore($id) {
		$unidade_gestora = UnidadeGestora::withTrashed()->where('id', $id)->first();
		if(isset($unidade_gestora)) {
			try {
				$unidade_gestora->restore();
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
			return $this->response(true, UnidadeGestora::withTrashed()->paginate(), 200);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function index()
	{
		try {
			return $this->response(true, UnidadeGestora::paginate(), 200);
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
			$unidade_gestora = UnidadeGestoraTransformer::toInstance($request->all());
			$unidade_gestora->save();
			DB::commit();

			return $this->response(true, $unidade_gestora, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
		$unidade_gestora = UnidadeGestora::find($id);

		try {
			if(isset($unidade_gestora)) 
				return $this->response(true, $unidade_gestora, 200);
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

		$unidade_gestora = UnidadeGestora::find($id);
		
		try {
			if(isset($unidade_gestora)) {
				DB::beginTransaction();
				$unidade_gestora = UnidadeGestoraTransformer::toInstance($request->all(), $unidade_gestora);
				$unidade_gestora->save();
				DB::commit();
			} else {
				return $this->response(false,'Not found.', 404);
			}
			return $this->response(true, $unidade_gestora, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
		
	}

	public function destroy($id)
	{
		$unidade_gestora = UnidadeGestora::find($id);
		try {
			if(isset($unidade_gestora)) {
				$unidade_gestora->delete();
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
			'cnpj' => ['required'],
			'uasg' => ['required'],
			'data_inicio' => ['required'],
			'data_fim' => ['required'],
			'diretor_geral_id' => ['required', 'exists:pessoas,id'],
			'instituicao_id' => ['required', 'exists:instituicoes,id']
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}
}

