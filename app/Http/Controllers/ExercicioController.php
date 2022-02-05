<?php

namespace App\Http\Controllers;

use App\Models\Exercicio;
use App\Models\FonteAcao;
use Illuminate\Http\Request;
use App\Http\Transformers\ExercicioTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class ExercicioController extends ApiBaseController
{
	public function getOpcoes() 
	{
		try {
			return $this->response(true, Exercicio::getOpcoes(), 200);
		} catch(Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function restore($id) 
	{
		$exercicio = Exercicio::withTrashed()->where('id', $id)->first();
		if(isset($exercicio)) {
			try {
				$exercicio->restore();
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
				return $this->response(true, Exercicio::withTrashed()->paginate(), 200);
			} catch (Exception $ex) {
				return $this->response(false, $ex->getMessage(), 409);
			}
		}

    public function index(Request $request)
    {
			try {
				return $this->response(true, Exercicio::paginate(), 200);
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
				$exercicio = ExercicioTransformer::toInstance($request->all());
				$exercicio->save();
				DB::commit();

				return $this->response(true, $exercicio, 200);
			} catch (Exception $ex) {
				DB::rollBack();
				return $this->response(false, $ex->getMessage(), 409);
			}
    }

    public function show($id, Request $request)
    {
			$exercicio = Exercicio::find($id);

			$tipo = $request->header('tipo', null);
			$id = $request->header('id', null);
			$total_matriz = 0;

			if(isset($tipo) && isset($id)) {
				switch($tipo) {
					case 'instituicao':
						$total_matriz = FonteAcao::where('instituicao_id', $id)->sum('valor');
					break;
					case 'unidade_gestora':
						$total_matriz = FonteAcao::where('unidade_gestora_id', $id)->sum('valor');
					break;
					case 'unidade_administrativa':
						$total_matriz = FonteAcao::where('unidade_administrativa_id', $id)->sum('valor');
					break;
				}
			}

			$exercicio->total_matriz = $total_matriz;

			if(isset($exercicio)) {
				try {
					return $this->response(true, $exercicio, 200);
				} catch (Exception $ex) {
					return $this->response(false, $ex->getMessage(), 409);
				}
			} else {
				return $this->response(false,'Not found.', 404);
			}
    }

    public function update(Request $request, $id)
    {
			$invalido = $this->validation($request);

			if($invalido) return $this->response(false, $invalido, 422);
			
			$exercicio = Exercicio::find($id);
			if(isset($exercicio)) {
				try {
					DB::beginTransaction();
					$exercicio = ExercicioTransformer::toInstance($request->all(), $exercicio);
					$exercicio->save();
					DB::commit();

					return $this->response(true, $exercicio, 200);
				} catch (Exception $ex) {
					DB::rollBack();
					return $this->response(false, $ex->getMessage(), 409);
				}
			} else {
				return $this->response(false, 'Not foud.', 404);
			}
    }

    public function destroy($id)
    {
			$exercicio = Exercicio::find($id);
			if(isset($exercicio)) {
				try {
					$exercicio->delete();
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
			'data_inicio' => ['required'],
			'data_fim' => ['required'],
			'data_inicio_loa' => ['required'],
			'data_fim_loa' => ['required'],
			'instituicao_id' => ['required', 'exists:instituicoes,id']
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}
}
