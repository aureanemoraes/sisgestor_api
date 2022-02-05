<?php

namespace App\Http\Controllers;

use App\Models\Fonte;
use Illuminate\Http\Request;
use App\Http\Transformers\FonteTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class FonteController extends ApiBaseController
{
	public function index()
	{
		try {
			return $this->response(true, Fonte::paginate(), 200);
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
			$fonte = FonteTransformer::toInstance($request->all());
			$fonte->save();
			DB::commit();

			return $this->response(true, $fonte, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
        $fonte = Fonte::find($id);

        if(isset($fonte)) {
            try {
			
                if(isset($fonte)) 
                    return $this->response(true, $fonte, 200);
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
        
		$fonte = Fonte::find($id);
		if(isset($fonte)) {
			try {
				DB::beginTransaction();
				$fonte = FonteTransformer::toInstance($request->all(), $fonte);
				$fonte->save();
				DB::commit();

				return $this->response(true, $fonte, 200);
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
		$fonte = Fonte::find($id);
		if(isset($fonte)) {
				try {
						$fonte->delete();
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
            'fonte_tipo_id' => ['required', 'exists:fontes_tipos,id'],
            'exercicio_id' => ['required', 'exists:exercicios,id'],
            'valor' => 'required'
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}
}
