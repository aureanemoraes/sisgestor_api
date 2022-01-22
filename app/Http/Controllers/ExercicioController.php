<?php

namespace App\Http\Controllers;

use App\Models\Exercicio;
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

    public function restore($id) {
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

    public function index()
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

    public function show($id)
    {
        try {
			$exercicio = Exercicio::find($id);
			
			if(isset($exercicio)) 
				return $this->response(true, $exercicio, 200);
			else 
				return $this->response(false,'Not found.', 404);
		} catch (Exception $ex) {
			return $this->response(false, $ex->getMessage(), 409);
		}
    }

    public function update(Request $request, $id)
    {
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
		}
    }

    public function destroy($id)
    {
        $exercicio = Exercicio::find($id);
		try {
			if(isset($exercicio)) {
				$exercicio->delete();
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
