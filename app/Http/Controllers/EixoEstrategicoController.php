<?php

namespace App\Http\Controllers;

use App\Models\EixoEstrategico;
use Illuminate\Http\Request;
use App\Http\Transformers\EixoEstrategicoTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class EixoEstrategicoController extends ApiBaseController
{
	public function opcoes() 
	{
		return $this->response(true, EixoEstrategico::select('nome as label', 'id')->get(), 200);
	}

	public function index()
	{
		try {
			return $this->response(true, EixoEstrategico::paginate(), 200);
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
			$eixo_estrategico = EixoEstrategicoTransformer::toInstance($request->all());
			$eixo_estrategico->save();
			DB::commit();

			return $this->response(true, $eixo_estrategico, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
		try {
			$eixo_estrategico = EixoEstrategico::find($id);
			
			if(isset($eixo_estrategico)) 
				return $this->response(true, $eixo_estrategico, 200);
			else 
				return $this->response(false,'Not found.', 404);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function update(Request $request, $id)
	{
		$invalido = $this->validation($request);

		if($invalido) return $this->response(false, $invalido, 422);
		
		$eixo_estrategico = EixoEstrategico::find($id);
		if(isset($eixo_estrategico)) {
			try {
				DB::beginTransaction();
				$eixo_estrategico = EixoEstrategicoTransformer::toInstance($request->all(), $eixo_estrategico);
				$eixo_estrategico->save();
				DB::commit();

				return $this->response(true, $eixo_estrategico, 200);
			} catch (Exception $ex) {
				DB::rollBack();
				return $this->response(false, $ex->getMessage(), 409);
			}
		}
	}

	public function destroy($id)
	{
		$eixo_estrategico = EixoEstrategico::find($id);
		try {
			if(isset($eixo_estrategico)) {
				$eixo_estrategico->delete();
				return $this->response(true, 'Item deleted.', 200);
			} else {
				return $this->response(false, 'Item not found.', 404);
			}
		} catch(Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	protected function validation($request) 
	{
		$validator = Validator::make($request->all(), [
			'nome' => ['required'],
            'plano_estrategico_id' => ['required', 'exists:planos_estrategicos,id'],
			'instituicao_id' => ['required', 'exists:instituicoes,id']
		]);

		if ($validator->fails()) {
            return $validator->errors()->toArray();
		}
	}
}
