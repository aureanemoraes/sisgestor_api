<?php

namespace App\Http\Controllers;

use App\Models\GrupoFonte;
use Illuminate\Http\Request;
use App\Http\Transformers\GrupoFonteTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class GrupoFonteController extends ApiBaseController
{
	public function index()
	{
		try {
			return $this->response(true, GrupoFonte::select('nome as label', 'id')->orderBy('fav', 'desc')->orderBy('nome')->get(), 200);
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
			$grupo_fonte = GrupoFonteTransformer::toInstance($request->all());
			$grupo_fonte->save();
			DB::commit();

			return $this->response(true, $grupo_fonte, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
        $grupo_fonte = GrupoFonte::find($id);

        if(isset($grupo_fonte)) {
            try {
			
                if(isset($grupo_fonte)) 
                    return $this->response(true, $grupo_fonte, 200);
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
        
		$grupo_fonte = GrupoFonte::find($id);
		if(isset($grupo_fonte)) {
			try {
				DB::beginTransaction();
				$grupo_fonte = GrupoFonteTransformer::toInstance($request->all(), $grupo_fonte);
				$grupo_fonte->save();
				DB::commit();

				return $this->response(true, $grupo_fonte, 200);
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
		$grupo_fonte = GrupoFonte::find($id);
		if(isset($grupo_fonte)) {
				try {
						$grupo_fonte->delete();
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
			'id' => ['required', 'unique:grupos_fontes,id'],
			'nome' => ['required']
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}
}
