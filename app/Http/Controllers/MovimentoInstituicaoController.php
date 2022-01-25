<?php

namespace App\Http\Controllers;

use App\Models\MovimentoInstituicao;
use Illuminate\Http\Request;
use App\Http\Transformers\MovimentoInstituicaoTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class MovimentoInstituicaoController extends ApiBaseController
{
	public function restore($id) {
		$movimento_instituicao = MovimentoInstituicao::withTrashed()->where('id', $id)->first();
		if(isset($movimento_instituicao)) {
			try {
				$movimento_instituicao->restore();
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
			return $this->response(true, MovimentoInstituicao::withTrashed()->paginate(), 200);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function index()
	{
		try {
			return $this->response(true, MovimentoInstituicao::paginate(), 200);
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
			$movimento_instituicao = MovimentoInstituicaoTransformer::toInstance($request->all());
			$movimento_instituicao->save();
			DB::commit();

			return $this->response(true, $movimento_instituicao, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
        $movimento_instituicao = MovimentoInstituicao::find($id);

        if(isset($movimento_instituicao)) {
            try {
			
                if(isset($movimento_instituicao)) 
                    return $this->response(true, $movimento_instituicao, 200);
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
		$movimento_instituicao = MovimentoInstituicao::find($id);
		if(isset($movimento_instituicao)) {
			try {
				DB::beginTransaction();
				$movimento_instituicao = MovimentoInstituicaoTransformer::toInstance($request->all(), $movimento_instituicao);
				$movimento_instituicao->save();
				DB::commit();

				return $this->response(true, $movimento_instituicao, 200);
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
		$movimento_instituicao = MovimentoInstituicao::find($id);
        if(isset($movimento_instituicao)) {
            try {
                $movimento_instituicao->delete();
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
			'recurso_instituicao_id' => ['required', 'exists:recursos_instituicoes,id']
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}
}
