<?php

use Illuminate\Support\Facades\Route;
use App\Models\Fonte;
use App\Models\Programa;
use Illuminate\Http\Request;
use App\Http\Controllers\RelatorioController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return "OK";
});

Route::get('/relatorio_simplificado/unidade_administrativa/{instituicao_id}/{exercicio_id}/{unidade_administrativa_id}', [RelatorioController::class, 'relatorioSimplificadoUA']);

Route::get('/relatorio_simplificado/unidade_gestora/{instituicao_id}/{exercicio_id}/{unidade_gestora_id}', [RelatorioController::class, 'relatorioSimplificadoUG']);

Route::get('/instituicoes/matriz/fontes', function (Request $request) {
    $fontes = FonteController::index_teste($request);
    return view('fontes')->with(['fontes' => $fontes]);
})->name('fontes');

Route::get('/instituicoes/matriz/programas', function (Request $request) {
    if($request->exercicio_id) {
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
    return view('programas')->with(['dados' => $dados, 'order_by' => $request->order_by]);
})->name('programas');