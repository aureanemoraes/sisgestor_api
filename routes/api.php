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
use App\Http\Controllers\FonteProgramaController;
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
use App\Http\Controllers\DimensaoController;
use App\Http\Controllers\ObjetivoController;
use App\Http\Controllers\MetaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlanoEstrategicoController;
use App\Http\Controllers\PlanoAcaoController;
use App\Http\Controllers\EixoEstrategicoController;

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
Route::apiResource('especificacoes', EspecificacaoController::class);
Route::apiResource('fontes_tipos', FonteTipoController::class);
Route::get('acoes_tipos/opcoes', [AcaoTipoController::class, 'opcoes']);
Route::apiResource('acoes_tipos', AcaoTipoController::class);
Route::get('naturezas_despesas/opcoes', [NaturezaDespesaController::class, 'opcoes']);
Route::apiResource('naturezas_despesas', NaturezaDespesaController::class);
Route::apiResource('subnaturezas_despesas', SubnaturezaDespesaController::class);
Route::apiResource('centros_custos', CentroCustoController::class);

Route::get('acoes/opcoes', [AcaoController::class, 'opcoes']);
Route::apiResource('acoes', AcaoController::class);
Route::get('fontes/opcoes/{unidade_administrativa_id}/{acao_id}', [FonteController::class, 'opcoes']);
Route::apiResource('fontes', FonteController::class);
Route::apiResource('fontes_acoes', FonteAcaoController::class);
Route::apiResource('fontes_programas', FonteProgramaController::class);
Route::apiResource('despesas', DespesaController::class);
Route::apiResource('metas_orcamentarias', MetaOrcamentariaController::class);
Route::apiResource('movimentos', MovimentoController::class);
Route::apiResource('limites_orcamentarios', LimiteOrcamentarioController::class);
Route::apiResource('creditos_planejados', CreditoPlanejadoController::class);
Route::apiResource('creditos_disponiveis', CreditoDisponivelController::class);
Route::apiResource('empenhos', EmpenhoController::class);
Route::get('planos_estrategicos/opcoes', [PlanoEstrategicoController::class, 'opcoes']);
Route::apiResource('planos_estrategicos', PlanoEstrategicoController::class);
Route::get('planos_acoes/opcoes', [PlanoAcaoController::class, 'opcoes']);
Route::apiResource('planos_acoes', PlanoAcaoController::class);
Route::get('eixos_estrategicos/opcoes', [EixoEstrategicoController::class, 'opcoes']);
Route::apiResource('eixos_estrategicos', EixoEstrategicoController::class);
Route::get('dimensoes/opcoes', [DimensaoController::class, 'opcoes']);
Route::apiResource('dimensoes', DimensaoController::class);
Route::get('objetivos/opcoes', [ObjetivoController::class, 'opcoes']);
Route::apiResource('objetivos', ObjetivoController::class);
Route::apiResource('metas', MetaController::class);

Route::get('/index_instituicao/{unidade_gestora_id}/{exercicio_id}', [DistribuicaoController::class, 'index_instituicao']);
Route::get('/index_unidade_gestora/{unidade_administrativa_id}/{exercicio_id}', [DistribuicaoController::class, 'index_unidade_gestora']);
Route::get('/index_unidade_administrativa/{unidade_administrativa_id}/{exercicio_id}', [DistribuicaoController::class, 'index_unidade_administrativa']);

Route::get('/teste', [ManutencoesController::class, 'teste']);

Route::post('/login', [AuthController::class, 'login']);


