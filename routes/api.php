<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstituicaoController;
use App\Http\Controllers\ExercicioController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\UnidadeGestoraController;
use App\Http\Controllers\UnidadeAdministrativaController;

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
