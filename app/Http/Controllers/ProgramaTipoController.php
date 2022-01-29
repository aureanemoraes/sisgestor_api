<?php

namespace App\Http\Controllers;

use App\Models\ProgramaTipo;
use Illuminate\Http\Request;
use App\Http\Transformers\ProgramaTipoTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class ProgramaTipoController extends ApiBaseController
{
	public function index()
	{
		try {
			return $this->response(true, ProgramaTipo::paginate(), 200);
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
			$programa_tipo = ProgramaTipoTransformer::toInstance($request->all());
			$programa_tipo->save();
			DB::commit();

			return $this->response(true, $programa_tipo, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
        $programa_tipo = ProgramaTipo::find($id);

        if(isset($programa_tipo)) {
            try {
			
                if(isset($programa_tipo)) 
                    return $this->response(true, $programa_tipo, 200);
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
		$programa_tipo = ProgramaTipo::find($id);
		if(isset($programa_tipo)) {
			try {
				DB::beginTransaction();
				$programa_tipo = ProgramaTipoTransformer::toInstance($request->all(), $programa_tipo);
				$programa_tipo->save();
				DB::commit();

				return $this->response(true, $programa_tipo, 200);
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
		$programa_tipo = ProgramaTipo::find($id);
        if(isset($programa_tipo)) {
            try {
                $programa_tipo->delete();
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
			'codigo' => ['required'],
			'nome' => ['required']
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}
}
