<?php

namespace App\Http\Controllers;

use App\Models\AcaoTipo;
use Illuminate\Http\Request;
use App\Http\Transformers\AcaoTipoTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;
use Illuminate\Validation\Rule;

class AcaoTipoController extends ApiBaseController
{
	public function opcoes() {
		return $this->response(true, AcaoTipo::select('nome as label', 'id')->get(), 200);
	}

	public function index(Request $request)
	{
		try {
			if(isset($request->termo)) {
				$termo = $request->termo;
	
				$resultado = AcaoTipo::where('nome', 'ilike', '%' . $termo . '%')->get();
	
				if(count($resultado) > 0) return $this->response(true, $resultado, 200);
				else return $this->response(true, 'Nenhum resultado encontrado.', 404);
			} else {
				return $this->response(true, AcaoTipo::orderBy('fav', 'desc')->orderBy('nome')->paginate(), 200);
			}
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
			$acao_tipo = AcaoTipoTransformer::toInstance($request->all());
			$acao_tipo->save();
			DB::commit();

			return $this->response(true, $acao_tipo, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
        $acao_tipo = AcaoTipo::find($id);

        if(isset($acao_tipo)) {
            try {
			
                if(isset($acao_tipo)) 
                    return $this->response(true, $acao_tipo, 200);
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
        
		$acao_tipo = AcaoTipo::find($id);
		if(isset($acao_tipo)) {
			try {
				DB::beginTransaction();
				$acao_tipo = AcaoTipoTransformer::toInstance($request->all(), $acao_tipo);
				$acao_tipo->save();
				DB::commit();

				return $this->response(true, $acao_tipo, 200);
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
		$acao_tipo = AcaoTipo::find($id);
		if(isset($acao_tipo)) {
				try {
						$acao_tipo->delete();
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
