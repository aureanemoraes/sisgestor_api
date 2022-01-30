<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstituicaoController;
use App\Http\Controllers\ExercicioController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\UnidadeGestoraController;
use App\Http\Controllers\UnidadeAdministrativaController;
use App\Http\Controllers\RecursoInstituicaoController;
use App\Http\Controllers\RecursoGestoraController;
use App\Http\Controllers\RecursoAdministrativaController;
use App\Http\Controllers\MovimentoInstituicaoController;
use App\Http\Controllers\MovimentoGestoraController;
use App\Http\Controllers\MovimentoAdministrativaController;
use App\Http\Controllers\ManutencoesController;
use App\Http\Controllers\ProgramaTipoController;
use App\Http\Controllers\GrupoFonteController;
use App\Http\Controllers\EspecificacaoController;
use App\Http\Controllers\FonteTipoController;
use App\Http\Controllers\AcaoTipoController;
use App\Http\Controllers\AcaoController;
use App\Http\Controllers\NaturezaDespesaController;
use App\Http\Controllers\SubNaturezaDespesaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('instituicoes/opcoes', [InstituicaoController::class, 'getOpcoes']);
Route::get('instituicoes/todos', [InstituicaoController::class, 'indexWithTrashed']);
Route::get('instituicoes/restore/{id}', [InstituicaoController::class, 'restore']);
Route::apiResource('instituicoes', InstituicaoController::class);

Route::get('exercicios/opcoes', [ExercicioController::class, 'getOpcoes']);
Route::get('exercicios/todos', [ExercicioController::class, 'indexWithTrashed']);
Route::get('exercicios/restore/{id}', [ExercicioController::class, 'restore']);
Route::apiResource('exercicios', ExercicioController::class);

Route::get('pessoas/todos', [PessoaController::class, 'indexWithTrashed']);
Route::get('pessoas/restore/{id}', [PessoaController::class, 'restore']);
Route::apiResource('pessoas', PessoaController::class);

Route::get('unidades_gestoras/opcoes', [UnidadeGestoraController::class, 'getOpcoes']);
Route::get('unidades_gestoras/todos', [UnidadeGestoraController::class, 'indexWithTrashed']);
Route::get('unidades_gestoras/restore/{id}', [UnidadeGestoraController::class, 'restore']);
Route::apiResource('unidades_gestoras', UnidadeGestoraController::class);

Route::get('unidades_administrativas/opcoes', [UnidadeAdministrativaController::class, 'getOpcoes']);
Route::get('unidades_administrativas/todos', [UnidadeAdministrativaController::class, 'indexWithTrashed']);
Route::get('unidades_administrativas/restore/{id}', [UnidadeAdministrativaController::class, 'restore']);
Route::apiResource('unidades_administrativas', UnidadeAdministrativaController::class);

Route::get('recursos_instituicoes/todos', [RecursoInstituicaoController::class, 'indexWithTrashed']);
Route::get('recursos_instituicoes/restore/{id}', [RecursoInstituicaoController::class, 'restore']);
Route::apiResource('recursos_instituicoes', RecursoInstituicaoController::class);

Route::get('recursos_gestoras/todos', [RecursoGestoraController::class, 'indexWithTrashed']);
Route::get('recursos_gestoras/restore/{id}', [RecursoGestoraController::class, 'restore']);
Route::apiResource('recursos_gestoras', RecursoGestoraController::class);

Route::get('recursos_administrativas/todos', [RecursoAdministrativaController::class, 'indexWithTrashed']);
Route::get('recursos_administrativas/restore/{id}', [RecursoAdministrativaController::class, 'restore']);
Route::apiResource('recursos_administrativas', RecursoAdministrativaController::class);

Route::get('movimentos_instituicoes/todos', [MovimentoInstituicaoController::class, 'indexWithTrashed']);
Route::get('movimentos_instituicoes/restore/{id}', [MovimentoInstituicaoController::class, 'restore']);
Route::apiResource('movimentos_instituicoes', MovimentoInstituicaoController::class);

Route::get('movimentos_gestoras/todos', [MovimentoGestoraController::class, 'indexWithTrashed']);
Route::get('movimentos_gestoras/restore/{id}', [MovimentoGestoraController::class, 'restore']);
Route::apiResource('movimentos_gestoras', MovimentoGestoraController::class);

Route::get('movimentos_administrativas/todos', [MovimentoAdministrativaController::class, 'indexWithTrashed']);
Route::get('movimentos_administrativas/restore/{id}', [MovimentoAdministrativaController::class, 'restore']);
Route::apiResource('movimentos_administrativas', MovimentoAdministrativaController::class);

Route::apiResource('programas_tipos', ProgramaTipoController::class);
Route::apiResource('grupos_fontes', GrupoFonteController::class);
Route::apiResource('especificacoes', EspecificacaoController::class);
Route::apiResource('fontes_tipos', FonteTipoController::class);
Route::apiResource('acoes_tipos', AcaoTipoController::class);
Route::apiResource('acoes', AcaoController::class);
Route::apiResource('naturezas_despesas', NaturezaDespesaController::class);
Route::apiResource('subnaturezas_despesas', SubNaturezaDespesaController::class);

Route::get('/teste', [ManutencoesController::class, 'teste']);

