<?php

namespace App\Http\Controllers;

use App\Models\Programa;
use Illuminate\Http\Request;
use App\Http\Transformers\ProgramaTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class ProgramaController extends ApiBaseController
{
	public function index()
	{
		try {
			return $this->response(true, Programa::paginate(), 200);
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
			$programa = ProgramaTransformer::toInstance($request->all());
			$programa->save();
			DB::commit();

			return $this->response(true, $programa, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
        $programa = Programa::find($id);

        if(isset($programa)) {
            try {
			
                if(isset($programa)) 
                    return $this->response(true, $programa, 200);
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
		$programa = Programa::find($id);
		if(isset($programa)) {
			try {
				DB::beginTransaction();
				$programa = ProgramaTransformer::toInstance($request->all(), $programa);
				$programa->save();
				DB::commit();

				return $this->response(true, $programa, 200);
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
		$programa = Programa::find($id);
        if(isset($programa)) {
            try {
                $programa->delete();
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
