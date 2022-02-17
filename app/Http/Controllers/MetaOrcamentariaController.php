<?php

namespace App\Http\Controllers;

use App\Models\MetaOrcamentaria;
use Illuminate\Http\Request;
use App\Http\Transformers\MetaOrcamentariaTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class MetaOrcamentariaController extends ApiBaseController
{
	public function getOpcoes() 
	{
		try {
			return $this->response(true, MetaOrcamentaria::getOpcoes(), 200);
		} catch(Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function restore($id) {
		$meta_orcamentaria = MetaOrcamentaria::withTrashed()->where('id', $id)->first();
		if(isset($meta_orcamentaria)) {
			try {
				$meta_orcamentaria->restore();
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
			return $this->response(true, MetaOrcamentaria::withTrashed()->paginate(), 200);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function index()
	{
		try {
			return $this->response(true, MetaOrcamentaria::paginate(), 200);
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
			$meta_orcamentaria = MetaOrcamentariaTransformer::toInstance($request->all());
			$meta_orcamentaria->save();
			DB::commit();

			return $this->response(true, $meta_orcamentaria, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
		try {
			$meta_orcamentaria = MetaOrcamentaria::find($id);
			
			if(isset($meta_orcamentaria)) 
				return $this->response(true, $meta_orcamentaria, 200);
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
		
		$meta_orcamentaria = MetaOrcamentaria::find($id);
		if(isset($meta_orcamentaria)) {
			try {
				DB::beginTransaction();
				$meta_orcamentaria = MetaOrcamentariaTransformer::toInstance($request->all(), $meta_orcamentaria);
				$meta_orcamentaria->save();
				DB::commit();

				return $this->response(true, $meta_orcamentaria, 200);
			} catch (Exception $ex) {
				DB::rollBack();
				return $this->response(false, $ex->getMessage(), 409);
			}
		}
	}

	public function destroy($id)
	{
		$meta_orcamentaria = MetaOrcamentaria::find($id);
		try {
			if(isset($meta_orcamentaria)) {
				$meta_orcamentaria->delete();
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
			'qtd_estimada' => ['nullable'],
			'qtd_alcancada' => ['nullable'],
			'natureza_despesa_id' => ['nullable', 'exists:naturezas_despesas,id'],
			'instituicao_id' => ['required', 'exists:instituicoes,id']
		]);

		if ($validator->fails()) {
            return $validator->errors()->toArray();
		}
	}
}
