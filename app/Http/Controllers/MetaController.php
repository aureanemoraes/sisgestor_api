<?php

namespace App\Http\Controllers;

use App\Models\Meta;
use Illuminate\Http\Request;
use App\Http\Transformers\MetaTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class MetaController extends ApiBaseController
{
	public function getOpcoes() 
	{
		try {
			return $this->response(true, Meta::getOpcoes(), 200);
		} catch(Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function restore($id) {
		$meta = Meta::withTrashed()->where('id', $id)->first();
		if(isset($meta)) {
			try {
				$meta->restore();
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
			return $this->response(true, Meta::withTrashed()->paginate(), 200);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function index()
	{
		try {
			return $this->response(true, Meta::paginate(), 200);
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
			$meta = MetaTransformer::toInstance($request->all());
			$meta->save();
			DB::commit();

			return $this->response(true, $meta, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
		try {
			$meta = Meta::find($id);
			
			if(isset($meta)) 
				return $this->response(true, $meta, 200);
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
		
		$meta = Meta::find($id);
		if(isset($meta)) {
			try {
				DB::beginTransaction();
				$meta = MetaTransformer::toInstance($request->all(), $meta);
				$meta->save();
				DB::commit();

				return $this->response(true, $meta, 200);
			} catch (Exception $ex) {
				DB::rollBack();
				return $this->response(false, $ex->getMessage(), 409);
			}
		}
	}

	public function destroy($id)
	{
		$meta = Meta::find($id);
		try {
			if(isset($meta)) {
				$meta->delete();
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
            'tipo' => ['required'],
            'valor_inicial' => ['required'],
            'valor_final' => ['required'],
            'valor_atingido' => ['nullable'],
			'objetivo_id' => ['required', 'exists:objetivos,id'],
            'unidade_gestora_id' => ['required', 'exists:unidades_gestoras,id']
		]);

		if ($validator->fails()) {
            return $validator->errors()->toArray();
		}
	}
}
