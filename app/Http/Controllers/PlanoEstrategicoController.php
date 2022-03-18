<?php

namespace App\Http\Controllers;

use App\Models\PlanoEstrategico;
use Illuminate\Http\Request;
use App\Http\Transformers\PlanoEstrategicoTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class PlanoEstrategicoController extends ApiBaseController
{
	public function opcoes() 
	{
		return $this->response(true, PlanoEstrategico::select('nome as label', 'id')->get(), 200);
	}

	public function index()
	{
		try {
			return $this->response(true, PlanoEstrategico::paginate(), 200);
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
			$plano_estrategico = PlanoEstrategicoTransformer::toInstance($request->all());
			$plano_estrategico->save();
			DB::commit();

			return $this->response(true, $plano_estrategico, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
		try {
			$plano_estrategico = PlanoEstrategico::find($id);
			
			if(isset($plano_estrategico)) 
				return $this->response(true, $plano_estrategico, 200);
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
		
		$plano_estrategico = PlanoEstrategico::find($id);
		if(isset($plano_estrategico)) {
			try {
				DB::beginTransaction();
				$plano_estrategico = PlanoEstrategicoTransformer::toInstance($request->all(), $plano_estrategico);
				$plano_estrategico->save();
				DB::commit();

				return $this->response(true, $plano_estrategico, 200);
			} catch (Exception $ex) {
				DB::rollBack();
				return $this->response(false, $ex->getMessage(), 409);
			}
		}
	}

	public function destroy($id)
	{
		$plano_estrategico = PlanoEstrategico::find($id);
		try {
			if(isset($plano_estrategico)) {
				$plano_estrategico->delete();
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
			'data_inicio' => ['required'],
			'data_fim' => ['required'],
			'instituicao_id' => ['required', 'exists:instituicoes,id']
		]);

		if ($validator->fails()) {
            return $validator->errors()->toArray();
		}
	}
}
