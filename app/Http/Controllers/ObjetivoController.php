<?php

namespace App\Http\Controllers;

use App\Models\Objetivo;
use Illuminate\Http\Request;
use App\Http\Transformers\ObjetivoTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class ObjetivoController extends ApiBaseController
{
	public function opcoes() 
	{
		return $this->response(true, Objetivo::select('nome as label', 'id')->where('ativo', 1)->get(), 200);
	}

	public function index()
	{
		try {
			return $this->response(true, Objetivo::paginate(), 200);
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
			$objetivo = ObjetivoTransformer::toInstance($request->all());
			$objetivo->save();
			DB::commit();

			return $this->response(true, $objetivo, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
        $objetivo = Objetivo::find($id);

        if(isset($objetivo)) {
            try {
			
                if(isset($objetivo)) 
                    return $this->response(true, $objetivo, 200);
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
        
		$objetivo = Objetivo::find($id);
		if(isset($objetivo)) {
			try {
				DB::beginTransaction();
				$objetivo = ObjetivoTransformer::toInstance($request->all(), $objetivo);
				$objetivo->save();
				DB::commit();

				return $this->response(true, $objetivo, 200);
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
		$objetivo = Objetivo::find($id);
		if(isset($objetivo)) {
				try {
						$objetivo->delete();
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
            'nome' => ['required'],
            'ativo' => ['nullable'],
            'dimensao_id' => ['required', 'exists:dimensoes,id']
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}
}
