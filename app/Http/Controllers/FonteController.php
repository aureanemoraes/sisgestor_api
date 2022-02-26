<?php

namespace App\Http\Controllers;

use App\Models\Fonte;
use App\Models\FonteAcao;
use App\Models\Despesa;
use Illuminate\Http\Request;
use App\Http\Transformers\FonteTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class FonteController extends ApiBaseController
{
	public function pesquisa(Request $request) {
		if(isset($request->termo)) {
			$termo = $request->termo;

			$resultado = Fonte::whereHas('fonte_tipo', function ($query) use ($termo) {
					$query->where('nome', 'ilike', '%' . $termo . '%')
					->orderBy('fav', 'desc');
				}
			)->get();

			if(count($resultado) > 0) return $this->response(true, $resultado, 200);
			else return $this->response(true, 'Nenhum resultado encontrado.', 404);
		} else {
			return $this->response(false, 'Nenhum termo enviado para pesquisa.', 404);
		}
	}

	public function index(Request $request)
	{
		if(isset($request->instituicao_id)) {
			$fontes = Fonte::withAndWhereHas(
				'acoes', function ($query) use($request) {
					$query->where('fontes_acoes.instituicao_id', $request->instituicao_id);
				}
			)
			->where('instituicao_id', $request->instituicao_id)
			->where('exercicio_id', $request->exercicio_id)
			->orderBy('fav', 'desc')
			->orderBy('id')
			->paginate();
	
			$fontes = $this->fontes_tratadas($fontes, $request->instituicao_id);
			
		} else if(isset($request->unidade_gestora_id)) {
			$fontes = Fonte::withAndWhereHas(
				'acoes', function ($query) use ($request) {
					$query->where('fontes_acoes.unidade_gestora_id', $request->unidade_gestora_id);
				}
			)
			->where('exercicio_id', $request->exercicio_id)
			->orderBy('fav', 'desc')
			->orderBy('id')
			->paginate();

			$fontes = $this->fontes_tratadas($fontes, $request->unidade_gestora_id, 'unidade_gestora');
		} else if(isset($request->unidade_administrativa_id)) {
			$fontes = Fonte::withAndWhereHas(
				'acoes', function ($query) use ($request) {
					$query->where('fontes_acoes.unidade_administrativa_id', $request->unidade_administrativa_id);
				}
			)
			->where('exercicio_id', $request->exercicio_id)
			->orderBy('fav', 'desc')
			->orderBy('id')
			->paginate();

			$fontes = $this->fontes_tratadas($fontes, $request->unidade_administrativa_id, 'unidade_administrativa');
		}


		try {
			return $this->response(true, $fontes, 200);
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
			$fonte = FonteTransformer::toInstance($request->all());
			$rule = $this->rules($fonte);
			if($rule['status']) {
				$fonte->save();
				DB::commit();
				return $this->response($rule['status'], $fonte, 200);
			} else {
					return $this->response($rule['status'], $rule['msg'], 400);
			}

			return $this->response(true, $fonte, 200);
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
		$fonte = Fonte::find($id);

		if(isset($fonte)) {
				try {
						if(isset($fonte)) 
								return $this->response(true, $fonte, 200);
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
        
		$fonte = Fonte::find($id);
		if(isset($fonte)) {
			try {
				DB::beginTransaction();
				$fonte = FonteTransformer::toInstance($request->all(), $fonte);
				$fonte->save();
				DB::commit();

				return $this->response(true, $fonte, 200);
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
		$fonte = Fonte::find($id);
		if(isset($fonte)) {
				try {
						$fonte->delete();
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
            'fonte_tipo_id' => ['required', 'exists:fontes_tipos,id'],
            'exercicio_id' => ['required', 'exists:exercicios,id'],
            'valor' => 'required'
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}

	protected function rules($fonte) {
		$exists = Fonte::where('fonte_tipo_id', $fonte->fonte_tipo_id)->where('exercicio_id', $fonte->exercicio_id)->exists();

		if(!$exists) 
			return [
				'status' => true,
				'msg' => ''
			];
		else
			return [
				'status' => false,
				'msg' => 'Fonte já cadastrada.'
			];
	}

	protected function fontes_tratadas($fontes, $id, $tipo='instituicao') {
		foreach($fontes as $fonte) {
			$valor_utilizado = 0;
			if(count($fonte->acoes) > 0) {
				foreach($fonte->acoes as $acao) {
					$valor_utilizado += $acao->pivot->valor;
				}
			}
			$fonte->valor_utilizado = $valor_utilizado;

			switch($tipo) {
				case 'instituicao':
					$valor_distribuido = FonteAcao::whereHas(
						'unidade_gestora', function ($query) use($id) {
							$query->where('instituicao_id', $id);
						}
					)->where('fonte_id', $fonte->id)->sum('valor');
					$fonte->valor_distribuido = $valor_distribuido;
					break;
				case 'unidade_gestora':
					$valor = FonteAcao::where('fonte_id', $fonte->id)->where('unidade_gestora_id', $id)->sum('valor');
					$fonte->valor = $valor;

					$valor_distribuido = FonteAcao::whereHas(
						'unidade_administrativa', function ($query) use($id) {
							$query->where('unidade_gestora_id', $id);
						}
					)->where('fonte_id', $fonte->id)->sum('valor');

					$fonte->valor_distribuido = $valor_distribuido;
					break;
				case 'unidade_administrativa':
					$valor = FonteAcao::where('fonte_id', $fonte->id)->where('unidade_administrativa_id', $id)->sum('valor');
					$fonte->valor = $valor;

					$valor_distribuido = Despesa::whereHas(
						'fonte_acao', function ($query) use ($fonte) {
							$query->where('fonte_id', $fonte->id);
						}
					)->where('unidade_administrativa_id', $id)->sum('valor_total');

					$fonte->valor_distribuido = $valor_distribuido;
					break;
			}
		}

	

		return $fontes;
	}
}
