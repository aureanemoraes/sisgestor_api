<?php

namespace App\Http\Controllers;

use App\Models\MovimentoGestora;
use Illuminate\Http\Request;
use App\Http\Transformers\MovimentoGestoraTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class MovimentoGestoraController extends ApiBaseController
{
	public function restore($id) {
		$movimento_gestora = MovimentoGestora::withTrashed()->where('id', $id)->first();
		if(isset($movimento_gestora)) {
			try {
				$movimento_gestora->restore();
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
			return $this->response(true, MovimentoGestora::withTrashed()->paginate(), 200);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function index()
	{
		try {
			return $this->response(true, MovimentoGestora::paginate(), 200);
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
			$movimento_gestora = MovimentoGestoraTransformer::toInstance($request->all());
			$movimento_gestora->save();
			DB::commit();

			return $this->response(true, $movimento_gestora, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
        $movimento_gestora = MovimentoGestora::find($id);

        if(isset($movimento_gestora)) {
            try {
			
                if(isset($movimento_gestora)) 
                    return $this->response(true, $movimento_gestora, 200);
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
		$movimento_gestora = MovimentoGestora::find($id);
		if(isset($movimento_gestora)) {
			try {
				DB::beginTransaction();
				$movimento_gestora = MovimentoGestoraTransformer::toInstance($request->all(), $movimento_gestora);
				$movimento_gestora->save();
				DB::commit();

				return $this->response(true, $movimento_gestora, 200);
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
		$movimento_gestora = MovimentoGestora::find($id);
        if(isset($movimento_gestora)) {
            try {
                $movimento_gestora->delete();
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
			'instituicao_id' => ['required', 'exists:instituicoes,id'],
			'recurso_gestora_id' => ['required', 'exists:recursos_gestoras,id'],
			'movimento_instituicao_id' => ['required', 'exists:movimentos_instituicoes,id']
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}
}
