<?php

namespace App\Http\Controllers;

use App\Models\NaturezaDespesaTipo;
use Illuminate\Http\Request;
use App\Http\Transformers\NaturezaDespesaTipoTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class NaturezaDespesaTipoController extends ApiBaseController
{
	public function index()
	{
		try {
			return $this->response(true, NaturezaDespesaTipo::paginate(), 200);
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
			$natureza_despesa_tipo = NaturezaDespesaTipoTransformer::toInstance($request->all());
			$natureza_despesa_tipo->save();
			DB::commit();

			return $this->response(true, $natureza_despesa_tipo, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
        $natureza_despesa_tipo = NaturezaDespesaTipo::find($id);

        if(isset($natureza_despesa_tipo)) {
            try {
			
                if(isset($natureza_despesa_tipo)) 
                    return $this->response(true, $natureza_despesa_tipo, 200);
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
        
		$natureza_despesa_tipo = NaturezaDespesaTipo::find($id);
		if(isset($natureza_despesa_tipo)) {
			try {
				DB::beginTransaction();
				$natureza_despesa_tipo = NaturezaDespesaTipoTransformer::toInstance($request->all(), $natureza_despesa_tipo);
				$natureza_despesa_tipo->save();
				DB::commit();

				return $this->response(true, $natureza_despesa_tipo, 200);
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
		$natureza_despesa_tipo = NaturezaDespesaTipo::find($id);
		if(isset($natureza_despesa_tipo)) {
				try {
						$natureza_despesa_tipo->delete();
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
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}
}
