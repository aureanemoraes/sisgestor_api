<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstituicaoController;
use App\Http\Controllers\ExercicioController;
use App\Http\Controllers\UnidadeGestoraController;
use App\Http\Controllers\UnidadeAdministrativaController;
use App\Http\Controllers\ManutencoesController;
use App\Http\Controllers\ProgramaController;
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
use App\Http\Controllers\DespesaController;
use App\Http\Controllers\MetaOrcamentariaController;
use App\Http\Controllers\DistribuicaoController;
use App\Http\Controllers\MovimentoController;
use App\Http\Controllers\LimiteOrcamentarioController;
use App\Http\Controllers\CreditoPlanejadoController;
use App\Http\Controllers\CreditoDisponivelController;
use App\Http\Controllers\EmpenhoController;
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

Route::apiResource('programas', ProgramaController::class);
Route::apiResource('grupos_fontes', GrupoFonteController::class);
Route::get('especificacoes/pesquisa', [EspecificacaoController::class, 'pesquisa']);
Route::apiResource('especificacoes', EspecificacaoController::class);
Route::get('fontes_tipos/pesquisa', [FonteTipoController::class, 'pesquisa']);
Route::apiResource('fontes_tipos', FonteTipoController::class);
Route::get('acoes_tipos/pesquisa', [AcaoTipoController::class, 'pesquisa']);
Route::apiResource('acoes_tipos', AcaoTipoController::class);
Route::get('naturezas_despesas/pesquisa', [NaturezaDespesaController::class, 'pesquisa']);
Route::apiResource('naturezas_despesas', NaturezaDespesaController::class);
Route::apiResource('subnaturezas_despesas', SubnaturezaDespesaController::class);
Route::apiResource('centros_custos', CentroCustoController::class);

Route::get('acoes/pesquisa', [AcaoController::class, 'pesquisa']);
Route::apiResource('acoes', AcaoController::class);
Route::get('fontes/pesquisa', [FonteController::class, 'pesquisa']);
Route::apiResource('fontes', FonteController::class);
Route::apiResource('fontes_acoes', FonteAcaoController::class);
Route::apiResource('despesas', DespesaController::class);
Route::apiResource('metas_orcamentarias', MetaOrcamentariaController::class);
Route::apiResource('movimentos', MovimentoController::class);
Route::apiResource('limites_orcamentarios', LimiteOrcamentarioController::class);
Route::apiResource('creditos_planejados', CreditoPlanejadoController::class);
Route::apiResource('creditos_disponiveis', CreditoDisponivelController::class);
Route::apiResource('empenhos', EmpenhoController::class);

Route::get('/index_instituicao/{unidade_gestora_id}/{exercicio_id}', [DistribuicaoController::class, 'index_instituicao']);
Route::get('/index_unidade_gestora/{unidade_administrativa_id}/{exercicio_id}', [DistribuicaoController::class, 'index_unidade_gestora']);
Route::get('/index_unidade_administrativa/{unidade_administrativa_id}/{exercicio_id}', [DistribuicaoController::class, 'index_unidade_administrativa']);

Route::get('/teste', [ManutencoesController::class, 'teste']);

