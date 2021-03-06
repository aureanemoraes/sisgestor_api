<?php

namespace App\Http\Controllers;

use App\Models\NaturezaDespesa;
use Illuminate\Http\Request;
use App\Http\Transformers\NaturezaDespesaTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class NaturezaDespesaController extends ApiBaseController
{
	public function opcoes() 
	{
		return $this->response(true, NaturezaDespesa::select('nome as label', 'id')->orderBy('fav', 'desc')->orderBy('nome')->get(), 200);
	}

	public function index(Request $request)
	{
		try {
			if(isset($request->termo)) {
				$termo = $request->termo;
	
				$resultado = NaturezaDespesa::where('nome', 'ilike', '%' . $termo . '%')
					->orWhere('codigo', 'ilike', '%' . $termo . '%')
					->orderBy('fav', 'desc')
					->paginate();
	
				if(count($resultado) > 0) return $this->response(true, $resultado, 200);
				else return $this->response(true, 'Nenhum resultado encontrado.', 404);
			} else {
				return $this->response(true, NaturezaDespesa::orderBy('fav', 'desc')->orderBy('nome')->paginate(), 200);
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
			$natureza_despesa_tipo = NaturezaDespesaTransformer::toInstance($request->all());
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
        $natureza_despesa_tipo = NaturezaDespesa::find($id);

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
        
		$natureza_despesa_tipo = NaturezaDespesa::find($id);
		if(isset($natureza_despesa_tipo)) {
			try {
				DB::beginTransaction();
				$natureza_despesa_tipo = NaturezaDespesaTransformer::toInstance($request->all(), $natureza_despesa_tipo);
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
		$natureza_despesa_tipo = NaturezaDespesa::find($id);
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
			'nome' => ['required'],
			'codigo' => ['required'],
			'tipo' => ['required']
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}
}
