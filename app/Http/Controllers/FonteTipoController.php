<?php

namespace App\Http\Controllers;

use App\Models\FonteTipo;
use Illuminate\Http\Request;
use App\Http\Transformers\FonteTipoTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class FonteTipoController extends ApiBaseController
{
	public function index()
	{
		try {
			return $this->response(true, FonteTipo::paginate(), 200);
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
			$fonte_tipo = FonteTipoTransformer::toInstance($request->all());
			$fonte_tipo->save();
			DB::commit();

			return $this->response(true, $fonte_tipo, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
        $fonte_tipo = FonteTipo::find($id);

        if(isset($fonte_tipo)) {
            try {
			
                if(isset($fonte_tipo)) 
                    return $this->response(true, $fonte_tipo, 200);
                else 
                    return $this->response(false,'Not found.', 404);
            } catch (Exception $ex) {
                return $this->response(false, $ex->getMessage(), 409);
            }
        } else {
            return $this->response(false, 'Not found.', 404); 
        }
	
	}

	public function update(Request $request, $id)
	{
		$invalido = $this->validation($request);

		if($invalido) return $this->response(false, $invalido, 422);
        
		$fonte_tipo = FonteTipo::find($id);
		if(isset($fonte_tipo)) {
			try {
				DB::beginTransaction();
				$fonte_tipo = FonteTipoTransformer::toInstance($request->all(), $fonte_tipo);
				$fonte_tipo->save();
				DB::commit();

				return $this->response(true, $fonte_tipo, 200);
			} catch (Exception $ex) {
				DB::rollBack();
				return $this->response(false, $ex->getMessage(), 409);
			}
		} else {
            return $this->response(false, 'Not found.', 404); 
        }
	}

	public function destroy($id)
	{
		$fonte_tipo = FonteTipo::find($id);
		if(isset($fonte_tipo)) {
				try {
						$fonte_tipo->delete();
						return $this->response(true, 'Item deleted.', 200);
				} catch(Exception $ex) {
						return $this->response(false, $ex->getMessage(), 409);
				}
		} else {
				return $this->response(false, 'Item not found.', 404);
			}
	}

	protected function validation($request) 
	{
		$validator = Validator::make($request->all(), [
			'grupo_fonte_id' => ['required', 'exists:grupos_fontes,id'],
			'especificacao_id' => ['required', 'exists:especificacoes,id']
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}
}
