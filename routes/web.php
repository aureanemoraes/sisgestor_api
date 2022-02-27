<?php

use Illuminate\Support\Facades\Route;
use App\Models\Fonte;

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

Route::get('/instituicoes/matriz/fontes', function () {
    $fontes = Fonte::all();
    return view('fontes')->with(['fontes' => $fontes]);
})->name('fontes');

Route::get('/instituicoes/matriz/programas', function () {
    $fontes = Fonte::all();
    return view('programas')->with(['programas' => $programas]);
})->name('programas');