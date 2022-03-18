<?php

namespace App\Http\Controllers;

use App\Models\PlanoAcao;
use Illuminate\Http\Request;
use App\Http\Transformers\PlanoAcaoTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class PlanoAcaoController extends ApiBaseController
{
	public function opcoes() 
	{
		return $this->response(true, PlanoAcao::select('nome as label', 'id')->get(), 200);
	}

	public function index()
	{
		try {
			return $this->response(true, PlanoAcao::paginate(), 200);
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
			$plano_acao = PlanoAcaoTransformer::toInstance($request->all());
			$plano_acao->save();
			DB::commit();

			return $this->response(true, $plano_acao, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
		try {
			$plano_acao = PlanoAcao::find($id);
			
			if(isset($plano_acao)) 
				return $this->response(true, $plano_acao, 200);
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
		
		$plano_acao = PlanoAcao::find($id);
		if(isset($plano_acao)) {
			try {
				DB::beginTransaction();
				$plano_acao = PlanoAcaoTransformer::toInstance($request->all(), $plano_acao);
				$plano_acao->save();
				DB::commit();

				return $this->response(true, $plano_acao, 200);
			} catch (Exception $ex) {
				DB::rollBack();
				return $this->response(false, $ex->getMessage(), 409);
			}
		}
	}

	public function destroy($id)
	{
		$plano_acao = PlanoAcao::find($id);
		try {
			if(isset($plano_acao)) {
				$plano_acao->delete();
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
            'plano_estrategico_id' => ['required', 'exists:planos_estrategicos,id'],
			'instituicao_id' => ['required', 'exists:instituicoes,id']
		]);

		if ($validator->fails()) {
            return $validator->errors()->toArray();
		}
	}
}
