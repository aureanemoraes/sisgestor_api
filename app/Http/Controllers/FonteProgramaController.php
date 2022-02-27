<?php

namespace App\Http\Controllers;

use App\Models\FontePrograma;
use App\Models\Fonte;
use App\Models\Programa;
use App\Models\UnidadeAdministrativa;
use Illuminate\Http\Request;
use App\Http\Transformers\FonteProgramaTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiBaseController;

class FonteProgramaController extends ApiBaseController
{
	public function index(Request $request)
	{
        if($request->exercicio_id) {
			if(isset($request->instituicao_id)) {
				if($request->order_by == 'fontes') {
                    $dados = Fonte::with(['programas' => function ($query) use($request) {
                        $query->where('fontes_programas.exercicio_id', $request->exercicio_id);
                    }])
                    ->orderBy('fav', 'desc')
                    ->orderBy('id')
                    ->paginate();
                } else {
                    $dados = Programa::with(['fontes' => function ($query) use($request) {
                        $query->where('fontes_programas.exercicio_id', $request->exercicio_id);
                    }])
                    ->orderBy('id')
                    ->paginate();
                }
			} 
		}

		try {
			return $this->response(true, $dados, 200);
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
			$fonte_programa = FonteProgramaTransformer::toInstance($request->all());
            $rule = $this->rules($fonte_programa);
            if($rule['status']) {
                $fonte_programa->save();
                DB::commit();
			    return $this->response($rule['status'], $fonte_programa, 200);
            } else {
                return $this->response($rule['status'], $rule['msg'], 400);
            }
		} catch (Exception $ex) {
			DB::rollBack();
			return $this->response(false, $ex->getMessage(), 409);
		}
	}

	public function show($id)
	{
        $fonte_programa = FontePrograma::find($id);

        if(isset($fonte_programa)) {
            try {
			
                if(isset($fonte_programa)) 
                    return $this->response(true, $fonte_programa, 200);
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
        
		$fonte_programa = FontePrograma::find($id);
		if(isset($fonte_programa)) {
			try {
				DB::beginTransaction();
				$fonte_programa = FonteProgramaTransformer::toInstance($request->all(), $fonte_programa);
				$fonte_programa->save();
				DB::commit();

				return $this->response(true, $fonte_programa, 200);
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
		$fonte_programa = FontePrograma::find($id);
		if(isset($fonte_programa)) {
				try {
						$fonte_programa->delete();
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
            'fonte_id' => ['required', 'exists:fontes,id'],
            'programa_id' => ['required', 'exists:programas,id'],
            'exercicio_id' => ['required', 'exists:exercicios,id'],
		]);

		if ($validator->fails()) {
				return $validator->errors()->toArray();
		}
	}

    protected function rules($fonte_programa) 
    {
        $exists = FontePrograma::where('fonte_id', $fonte_programa->fonte_id)
        ->where('programa_id', $fonte_programa->programa_id)
        ->where('exercicio_id', $fonte_programa->exercicio_id)
        ->exists();

        if($exists) 
            return [
                'status' => false,
                'msg' => 'Este vínculo já foi registrado.'
            ];
        else
            return [
                'status' => true,
                'msg' => ''
            ];
    }
}
