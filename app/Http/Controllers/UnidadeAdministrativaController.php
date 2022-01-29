<?php

namespace App\Http\Controllers;

use App\Models\UnidadeAdministrativa;
use Illuminate\Http\Request;
use App\Http\Transformers\UnidadeAdministrativaTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class UnidadeAdministrativaController extends ApiBaseController
{
	public function getOpcoes() 
	{
		try {
			return $this->response(true, UnidadeAdministrativa::getOpcoes(), 200);
		} catch(Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function restore($id) {
		$unidade_administrativa = UnidadeAdministrativa::withTrashed()->where('id', $id)->first();
		if(isset($unidade_administrativa)) {
			try {
				$unidade_administrativa->restore();
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
			return $this->response(true, UnidadeAdministrativa::withTrashed()->paginate(), 200);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function index()
	{
		try {
			return $this->response(true, UnidadeAdministrativa::paginate(), 200);
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
			$unidade_administrativa = UnidadeAdministrativaTransformer::toInstance($request->all());
			$unidade_administrativa->save();
			DB::commit();

			return $this->response(true, $unidade_administrativa, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
		try {
			$unidade_administrativa = UnidadeAdministrativa::find($id);
			
			if(isset($unidade_administrativa)) 
				return $this->response(true, $unidade_administrativa, 200);
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
		
		$unidade_administrativa = UnidadeAdministrativa::find($id);
		if(isset($unidade_administrativa)) {
			try {
				DB::beginTransaction();
				$unidade_administrativa = UnidadeAdministrativaTransformer::toInstance($request->all(), $unidade_administrativa);
				$unidade_administrativa->save();
				DB::commit();

				return $this->response(true, $unidade_administrativa, 200);
			} catch (Exception $ex) {
				DB::rollBack();
				return $this->response(false, $ex->getMessage(), 409);
			}
		}
	}

	public function destroy($id)
	{
		$unidade_administrativa = UnidadeAdministrativa::find($id);
		try {
			if(isset($unidade_administrativa)) {
				$unidade_administrativa->delete();
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
			'ugr' => ['required'],
			'instituicao_id' => ['required', 'exists:instituicoes,id'],
            'unidade_gestora_id' => ['required', 'exists:unidades_gestoras,id']
		]);

		if ($validator->fails()) {
            return $validator->errors()->toArray();
		}
	}
}
