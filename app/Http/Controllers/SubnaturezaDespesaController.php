<?php

namespace App\Http\Controllers;

use App\Models\SubnaturezaDespesa;
use Illuminate\Http\Request;
use App\Http\Transformers\SubnaturezaDespesaTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class SubnaturezaDespesaController extends ApiBaseController
{
	public function index()
	{
		try {
			return $this->response(true, SubnaturezaDespesa::paginate(), 200);
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
			$subnatureza_despesa_tipo = SubnaturezaDespesaTransformer::toInstance($request->all());
			$subnatureza_despesa_tipo->save();
			DB::commit();

			return $this->response(true, $subnatureza_despesa_tipo, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
        $subnatureza_despesa_tipo = SubnaturezaDespesa::find($id);

        if(isset($subnatureza_despesa_tipo)) {
            try {
			
                if(isset($subnatureza_despesa_tipo)) 
                    return $this->response(true, $subnatureza_despesa_tipo, 200);
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
        
		$subnatureza_despesa_tipo = SubnaturezaDespesa::find($id);
		if(isset($subnatureza_despesa_tipo)) {
			try {
				DB::beginTransaction();
				$subnatureza_despesa_tipo = SubnaturezaDespesaTransformer::toInstance($request->all(), $subnatureza_despesa_tipo);
				$subnatureza_despesa_tipo->save();
				DB::commit();

				return $this->response(true, $subnatureza_despesa_tipo, 200);
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
		$subnatureza_despesa_tipo = SubnaturezaDespesa::find($id);
		if(isset($subnatureza_despesa_tipo)) {
				try {
						$subnatureza_despesa_tipo->delete();
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
			'codigo' => ['required'],
			'natureza_despesa_id' => ['required', 'exists:naturezas_despesas,id']
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}
}
