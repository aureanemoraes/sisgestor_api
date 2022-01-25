<?php

namespace App\Http\Controllers;

use App\Models\MovimentoAdministrativa;
use Illuminate\Http\Request;
use App\Http\Transformers\MovimentoAdministrativaTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class MovimentoAdministrativaController extends ApiBaseController
{
	public function restore($id) {
		$movimento_administrativa = MovimentoAdministrativa::withTrashed()->where('id', $id)->first();
		if(isset($movimento_administrativa)) {
			try {
				$movimento_administrativa->restore();
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
			return $this->response(true, MovimentoAdministrativa::withTrashed()->paginate(), 200);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function index()
	{
		try {
			return $this->response(true, MovimentoAdministrativa::paginate(), 200);
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
			$movimento_administrativa = MovimentoAdministrativaTransformer::toInstance($request->all());
			$movimento_administrativa->save();
			DB::commit();

			return $this->response(true, $movimento_administrativa, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
        $movimento_administrativa = MovimentoAdministrativa::find($id);

        if(isset($movimento_administrativa)) {
            try {
			
                if(isset($movimento_administrativa)) 
                    return $this->response(true, $movimento_administrativa, 200);
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
		$movimento_administrativa = MovimentoAdministrativa::find($id);
		if(isset($movimento_administrativa)) {
			try {
				DB::beginTransaction();
				$movimento_administrativa = MovimentoAdministrativaTransformer::toInstance($request->all(), $movimento_administrativa);
				$movimento_administrativa->save();
				DB::commit();

				return $this->response(true, $movimento_administrativa, 200);
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
		$movimento_administrativa = MovimentoAdministrativa::find($id);
        if(isset($movimento_administrativa)) {
            try {
                $movimento_administrativa->delete();
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
			'valor' => ['required'],
			'movimento_gestora_id' => ['required', 'exists:movimentos_gestoras,id'],
			'recurso_admistrativa_id' => ['required', 'exists:recursos_administrativas,id'],
			'instituicao_id' => ['required', 'exists:instituicoes,id']
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}
}
