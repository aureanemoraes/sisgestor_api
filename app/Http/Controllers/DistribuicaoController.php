<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FonteAcao;
use App\Models\Acao;

class DistribuicaoController extends Controller
{
    public function index_instituicao($unidade_gestora_id, $exercicio_id) 
    {
        // FontesAcoes
        $dados = Acao::withAndWhereHas(
            'fontes', function ($query) use ($unidade_gestora_id, $exercicio_id) {
                $query->where('fontes_acoes.unidade_gestora_id', $unidade_gestora_id)->where('fontes_acoes.exercicio_id', $exercicio_id);
            }
        )->get();
        return $dados;
    }

    public function index_unidade_gestora($unidade_administrativa_id, $exercicio_id) 
    {
        // FontesAcoes
    }

    public function index_unidade_administrativa($unidade_administrativa_id, $exercicio_id) 
    {
        // Despesas
    }
}
