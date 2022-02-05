<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstituicaoController;
use App\Http\Controllers\ExercicioController;
use App\Http\Controllers\UnidadeGestoraController;
use App\Http\Controllers\UnidadeAdministrativaController;
use App\Http\Controllers\ManutencoesController;
use App\Http\Controllers\ProgramaTipoController;
use App\Http\Controllers\GrupoFonteController;
use App\Http\Controllers\EspecificacaoController;
use App\Http\Controllers\FonteTipoController;
use App\Http\Controllers\AcaoTipoController;
use App\Http\Controllers\AcaoController;
use App\Http\Controllers\NaturezaDespesaController;
use App\Http\Controllers\SubnaturezaDespesaController;
use App\Http\Controllers\CentroCustoController;
use App\Http\Controllers\FonteController;
use App\Http\Controllers\FonteAcaoController;
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

Route::get('unidades_gestoras/opcoes', [UnidadeGestoraController::class, 'getOpcoes']);
Route::get('unidades_gestoras/todos', [UnidadeGestoraController::class, 'indexWithTrashed']);
Route::get('unidades_gestoras/restore/{id}', [UnidadeGestoraController::class, 'restore']);
Route::apiResource('unidades_gestoras', UnidadeGestoraController::class);

Route::get('unidades_administrativas/opcoes', [UnidadeAdministrativaController::class, 'getOpcoes']);
Route::get('unidades_administrativas/todos', [UnidadeAdministrativaController::class, 'indexWithTrashed']);
Route::get('unidades_administrativas/restore/{id}', [UnidadeAdministrativaController::class, 'restore']);
Route::apiResource('unidades_administrativas', UnidadeAdministrativaController::class);

Route::apiResource('programas_tipos', ProgramaTipoController::class);
Route::apiResource('grupos_fontes', GrupoFonteController::class);
Route::apiResource('especificacoes', EspecificacaoController::class);
Route::apiResource('fontes_tipos', FonteTipoController::class);
Route::apiResource('acoes_tipos', AcaoTipoController::class);
Route::apiResource('naturezas_despesas', NaturezaDespesaController::class);
Route::apiResource('subnaturezas_despesas', SubnaturezaDespesaController::class);
Route::apiResource('centros_custos', CentroCustoController::class);

Route::apiResource('acoes', AcaoController::class);
Route::apiResource('fontes', FonteController::class);
Route::apiResource('fontes_acoes', FonteAcaoController::class);

Route::get('/teste', [ManutencoesController::class, 'teste']);

