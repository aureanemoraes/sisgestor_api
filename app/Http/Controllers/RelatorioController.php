<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acao;
use App\Models\Despesa;
use App\Models\NaturezaDespesa;
use App\Models\SubnaturezaDespesa;
use App\Models\FonteAcao;

class RelatorioController extends Controller
{
    public function relatorio_simplificado(Request $request) {

        $acoes = Acao::where('exercicio_id', 1)->where('instituicao_id', 1)->get();

        $naturezas_despesas = Despesa::select('natureza_despesa_id')
            ->where('unidade_administrativa_id', 1)
            ->where('exercicio_id', 1)
            ->groupBy('natureza_despesa_id')
            ->pluck('natureza_despesa_id');


        $infos = [];
        $total_custo_fixo = 0;
        $total_custo_variavel = 0;

        foreach ($acoes as $acao) {
            $fontes_acoes_ids[$acao->id] = FonteAcao::where('unidade_administrativa_id', 1)
                ->where('exercicio_id', 1)
                ->where('acao_id', $acao->id)
                ->pluck('id')
                ->toArray();
            if(!isset($infos[$acao->id]))
                $infos[$acao->id] = [];
        }

        foreach ($naturezas_despesas as $key => $natureza_despesa_id) {
            $dados = [];
            $custo_variavel = 0;
            $custo_fixo = 0;
            $valor_total = 0;
            $custo_variavel_sub = 0;
            $custo_fixo_sub = 0;
            $valor_total_sub = 0;

            $natureza_despesa = NaturezaDespesa::find($natureza_despesa_id);
       
            $dados['nome'] = "$natureza_despesa->codigo - $natureza_despesa->nome";
            $dados['tipo'] = $natureza_despesa->tipo;
            $custo_fixo = Despesa::where('unidade_administrativa_id', 1)
                ->where('exercicio_id', 1)
                ->where('natureza_despesa_id', $natureza_despesa_id)
                ->where('tipo', 'despesa_fixa')
                ->sum('valor_total');
            $dados['custo_fixo'] = $custo_fixo;
            $custo_variavel = Despesa::where('unidade_administrativa_id', 1)
                ->where('exercicio_id', 1)
                ->where('natureza_despesa_id', $natureza_despesa_id)
                ->where('tipo', 'despesa_variavel')
                ->sum('valor_total');
            $dados['custo_variavel'] = $custo_variavel;
            $valor_total = $custo_fixo + $custo_variavel;
            $dados['custo_total'] = $valor_total;

            // SubNatureza
            $subnaturezas_despesas = Despesa::select('subnatureza_despesa_id')
                ->where('unidade_administrativa_id', 1)
                ->where('exercicio_id', 1)
                ->where('natureza_despesa_id', $natureza_despesa_id)
                ->whereNotNull('subnatureza_despesa_id')
                ->pluck('subnatureza_despesa_id');

            if (count($subnaturezas_despesas) > 0) {
                foreach($subnaturezas_despesas as $key_sub => $subnatureza_despesa_id) {
                    $subnatureza_despesa = SubnaturezaDespesa::find($subnatureza_despesa_id);
                    $dados['subnaturezas_despesas'][$key_sub]['nome'] = "$subnatureza_despesa->codigo - $subnatureza_despesa->nome";
                    $custo_fixo_sub = Despesa::where('unidade_administrativa_id', 1)
                        ->where('exercicio_id', 1)
                        ->where('subnatureza_despesa_id', $subnatureza_despesa_id)
                        ->where('tipo', 'despesa_fixa')
                        ->sum('valor_total');
                    $dados['subnaturezas_despesas'][$key_sub]['custo_fixo'] = $custo_fixo_sub;
                    $custo_variavel_sub = Despesa::where('unidade_administrativa_id', 1)
                        ->where('exercicio_id', 1)
                        ->where('subnatureza_despesa_id', $subnatureza_despesa_id)
                        ->where('tipo', 'despesa_variavel')
                        ->sum('valor_total');
                    $dados['subnaturezas_despesas'][$key_sub]['custo_variavel'] = $custo_variavel_sub;
                    $valor_total_sub = $custo_fixo_sub + $custo_variavel_sub;
                    $dados['subnaturezas_despesas'][$key_sub]['custo_total'] = $valor_total_sub;
                }
            }

             // Verificando acao
             $fontes_acoes = Despesa::where('unidade_administrativa_id', 1)
                ->where('exercicio_id', 1)
                ->where('natureza_despesa_id', $natureza_despesa_id)
                ->pluck('fonte_acao_id');
            
            foreach($infos as $acao_id => $value) {
                if(in_array($fontes_acoes[0], $fontes_acoes_ids[$acao_id])) {
                    $total_custo_fixo += $custo_fixo;
                    $total_custo_fixo += $custo_fixo_sub;
                    $total_custo_variavel += $custo_variavel;
                    $total_custo_variavel += $custo_variavel_sub;
                    $infos[$acao_id][$natureza_despesa->tipo]['naturezas_despesas'][] = $dados;
                    if(!isset($infos[$acao_id][$natureza_despesa->tipo]['total_fixo']))
                        $infos[$acao_id][$natureza_despesa->tipo]['total_fixo'] = 0;
                    $infos[$acao_id][$natureza_despesa->tipo]['total_fixo'] += $custo_fixo + $custo_fixo_sub;
                    if(!isset($infos[$acao_id][$natureza_despesa->tipo]['total_variavel']))
                        $infos[$acao_id][$natureza_despesa->tipo]['total_variavel'] = 0;
                    $infos[$acao_id][$natureza_despesa->tipo]['total_variavel'] += $custo_variavel + $custo_variavel_sub;
                    if(!isset($infos[$acao_id][$natureza_despesa->tipo]['total']))
                        $infos[$acao_id][$natureza_despesa->tipo]['total'] = 0;
                    $infos[$acao_id][$natureza_despesa->tipo]['total'] += $valor_total + $valor_total_sub;
                }
            }
        }

        return view('relatorio_simplificado')->with([
            'acoes' => $acoes,
            'infos' => $infos,
            'total_custo_fixo' => $total_custo_fixo,
            'total_custo_variavel' => $total_custo_variavel
        ]);
    }
}
