<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acao;
use App\Models\Despesa;
use App\Models\NaturezaDespesa;
use App\Models\SubnaturezaDespesa;
use App\Models\FonteAcao;
use App\Models\Instituicao;
use App\Models\Exercicio;
use App\Models\UnidadeAdministrativa;
use App\Models\UnidadeGestora;

class RelatorioController extends Controller
{
    public function relatorioSimplificadoUA($instituicao_id, $exercicio_id, $unidade_administrativa_id) 
    {
        $instituicao = Instituicao::find($instituicao_id);
        $exercicio = Exercicio::find($exercicio_id);
        $unidade_administrativa = UnidadeAdministrativa::find($unidade_administrativa_id);
        
        $acoes = Acao::where('exercicio_id', $exercicio_id)->where('instituicao_id', $instituicao_id)->get();

        // Tabela principal
        $naturezas_despesas = Despesa::select('natureza_despesa_id')
            ->where('unidade_administrativa_id', $unidade_administrativa_id)
            ->where('exercicio_id', $exercicio_id)
            ->groupBy('natureza_despesa_id')
            ->pluck('natureza_despesa_id');

        $infos = [];
        $total_custo_fixo = 0;
        $total_custo_variavel = 0;

        $resumo_acoes['total_matriz'] = 0;
        $resumo_acoes['total_planejado'] = 0;
        $resumo_acoes['total_diferenca'] = 0;

        foreach ($acoes as $acao) {
            $fontes_acoes_ids[$acao->id] = FonteAcao::where('unidade_administrativa_id', $unidade_administrativa_id)
                ->where('exercicio_id', $exercicio_id)
                ->where('acao_id', $acao->id)
                ->pluck('id')
                ->toArray();

            $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['matriz'] = FonteAcao::where('unidade_administrativa_id', $unidade_administrativa_id)
                ->where('exercicio_id', $exercicio_id)
                ->where('acao_id', $acao->id)
                ->sum('valor');

            $resumo_acoes['total_matriz'] += $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['matriz'];

            $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['planejado']['custeio'] = Despesa::whereHas(
                'fonte_acao', function ($query) use ($acao) {
                    $query->where('fontes_acoes.acao_id', $acao->id);
                }
            )->whereHas('natureza_despesa', function ($query) {
                $query->where('tipo', 'Custeio');
            })
            ->where('unidade_administrativa_id', $unidade_administrativa_id)
            ->where('exercicio_id', $exercicio_id)
            ->sum('valor_total');

            $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['planejado']['investimento'] = Despesa::whereHas(
                'fonte_acao', function ($query) use ($acao) {
                    $query->where('fontes_acoes.acao_id', $acao->id);
                }
            )->whereHas('natureza_despesa', function ($query) {
                $query->where('tipo', 'Investimento');
            })
            ->where('unidade_administrativa_id', $unidade_administrativa_id)
            ->where('exercicio_id', $exercicio_id)
            ->sum('valor_total');

            $resumo_acoes['total_planejado'] += $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['planejado']['custeio'] + $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['planejado']['investimento'];

            $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['diferenca'] = $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['matriz'] - ($resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['planejado']['custeio'] + $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['planejado']['investimento']);

            $resumo_acoes['total_diferenca'] += $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['diferenca'];
            
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
            $custo_fixo = Despesa::where('unidade_administrativa_id', $unidade_administrativa_id)
                ->where('exercicio_id', $exercicio_id)
                ->where('natureza_despesa_id', $natureza_despesa_id)
                ->whereNull('subnatureza_despesa_id')
                ->where('tipo', 'despesa_fixa')
                ->sum('valor_total');
            $dados['custo_fixo'] = $custo_fixo;
            $custo_variavel = Despesa::where('unidade_administrativa_id', $unidade_administrativa_id)
                ->where('exercicio_id', $exercicio_id)
                ->where('natureza_despesa_id', $natureza_despesa_id)
                ->whereNull('subnatureza_despesa_id')
                ->where('tipo', 'despesa_variavel')
                ->sum('valor_total');
            $dados['custo_variavel'] = $custo_variavel;
            $valor_total = $custo_fixo + $custo_variavel;
            $dados['custo_total'] = $valor_total;

            // SubNatureza
            $subnaturezas_despesas = Despesa::select('subnatureza_despesa_id')
                ->where('unidade_administrativa_id', $unidade_administrativa_id)
                ->where('exercicio_id', $exercicio_id)
                ->where('natureza_despesa_id', $natureza_despesa_id)
                ->whereNotNull('subnatureza_despesa_id')
                ->pluck('subnatureza_despesa_id');

            if (count($subnaturezas_despesas) > 0) {
                foreach($subnaturezas_despesas as $key_sub => $subnatureza_despesa_id) {
                    $subnatureza_despesa = SubnaturezaDespesa::find($subnatureza_despesa_id);
                    $dados['subnaturezas_despesas'][$key_sub]['nome'] = "$subnatureza_despesa->codigo - $subnatureza_despesa->nome";
                    $custo_fixo_sub = Despesa::where('unidade_administrativa_id', $unidade_administrativa_id)
                        ->where('exercicio_id', $exercicio_id)
                        ->where('subnatureza_despesa_id', $subnatureza_despesa_id)
                        ->where('tipo', 'despesa_fixa')
                        ->sum('valor_total');
                    $dados['subnaturezas_despesas'][$key_sub]['custo_fixo'] = $custo_fixo_sub;
                    $custo_variavel_sub = Despesa::where('unidade_administrativa_id', $unidade_administrativa_id)
                        ->where('exercicio_id', $exercicio_id)
                        ->where('subnatureza_despesa_id', $subnatureza_despesa_id)
                        ->where('tipo', 'despesa_variavel')
                        ->sum('valor_total');
                    $dados['subnaturezas_despesas'][$key_sub]['custo_variavel'] = $custo_variavel_sub;
                    $valor_total_sub = $custo_fixo_sub + $custo_variavel_sub;
                    $dados['subnaturezas_despesas'][$key_sub]['custo_total'] = $valor_total_sub;
                }
            }

             // Verificando acao
             $fontes_acoes = Despesa::where('unidade_administrativa_id', $unidade_administrativa_id)
                ->where('exercicio_id', $exercicio_id)
                ->where('natureza_despesa_id', $natureza_despesa_id)
                ->groupBy('fonte_acao_id')
                ->pluck('fonte_acao_id')
                ->toArray();
            
            foreach($infos as $acao_id => $value) {
                if(count(array_intersect($fontes_acoes, $fontes_acoes_ids[$acao_id])) > 0) {
                    // dd($fontes_acoes_ids[$acao_id], $fontes_acoes, $acao->id, $natureza_despesa_id);
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
        // dd($resumo_acoes);

        // Resumo

        return view('unidade_administrativa.relatorio_simplificado')->with([
            'instituicao' => $instituicao,
            'exercicio' => $exercicio,
            'unidade_administrativa' => $unidade_administrativa,
            'acoes' => $acoes,
            'infos' => $infos,
            'total_custo_fixo' => $total_custo_fixo,
            'total_custo_variavel' => $total_custo_variavel,
            'resumo_acoes' => $resumo_acoes
        ]);
    }

    public function relatorioSimplificadoUG($instituicao_id, $exercicio_id, $unidade_gestora_id) 
    {
        $instituicao = Instituicao::find($instituicao_id);
        $exercicio = Exercicio::find($exercicio_id);
        $unidade_gestora = UnidadeGestora::find($unidade_gestora_id);
        
        $acoes = Acao::where('exercicio_id', $exercicio_id)->where('instituicao_id', $instituicao_id)->get();

        // Tabela principal
        $naturezas_despesas = Despesa::whereHas(
                'unidade_administrativa', function ($query) use($unidade_gestora_id) {
                    $query->where('unidade_gestora_id', $unidade_gestora_id);
                }
            )
            ->select('natureza_despesa_id')
            ->where('exercicio_id', $exercicio_id)
            ->groupBy('natureza_despesa_id')
            ->pluck('natureza_despesa_id');

        $infos = [];
        $total_custo_fixo = 0;
        $total_custo_variavel = 0;

        $resumo_acoes['total_matriz'] = 0;
        $resumo_acoes['total_planejado'] = 0;
        $resumo_acoes['total_diferenca'] = 0;

        foreach ($acoes as $acao) {
            $fontes_acoes_ids[$acao->id] = FonteAcao::whereHas(
                    'unidade_administrativa', function ($query) use($unidade_gestora_id) {
                        $query->where('unidade_gestora_id', $unidade_gestora_id);
                    }
                )
                ->where('exercicio_id', $exercicio_id)
                ->where('acao_id', $acao->id)
                ->pluck('id')
                ->toArray();

            $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['matriz'] = FonteAcao::whereHas(
                    'unidade_administrativa', function ($query) use($unidade_gestora_id) {
                        $query->where('unidade_gestora_id', $unidade_gestora_id);
                    }
                )
                ->where('exercicio_id', $exercicio_id)
                ->where('acao_id', $acao->id)
                ->sum('valor');

            $resumo_acoes['total_matriz'] += $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['matriz'];

            $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['planejado']['custeio'] = Despesa::whereHas(
                'fonte_acao', function ($query) use ($acao) {
                    $query->where('fontes_acoes.acao_id', $acao->id);
                }
            )->whereHas('natureza_despesa', function ($query) {
                $query->where('tipo', 'Custeio');
            })->whereHas(
                'unidade_administrativa', function ($query) use($unidade_gestora_id) {
                    $query->where('unidade_gestora_id', $unidade_gestora_id);
                }
            )
            ->where('exercicio_id', $exercicio_id)
            ->sum('valor_total');

            $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['planejado']['investimento'] = Despesa::whereHas(
                'fonte_acao', function ($query) use ($acao) {
                    $query->where('fontes_acoes.acao_id', $acao->id);
                }
            )->whereHas('natureza_despesa', function ($query) {
                $query->where('tipo', 'Investimento');
            })->whereHas(
                'unidade_administrativa', function ($query) use($unidade_gestora_id) {
                    $query->where('unidade_gestora_id', $unidade_gestora_id);
                }
            )
            ->where('exercicio_id', $exercicio_id)
            ->sum('valor_total');

            $resumo_acoes['total_planejado'] += $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['planejado']['custeio'] + $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['planejado']['investimento'];

            $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['diferenca'] = $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['matriz'] - ($resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['planejado']['custeio'] + $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['planejado']['investimento']);

            $resumo_acoes['total_diferenca'] += $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['diferenca'];
            
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
            $custo_fixo = Despesa::whereHas(
                    'unidade_administrativa', function ($query) use($unidade_gestora_id) {
                        $query->where('unidade_gestora_id', $unidade_gestora_id);
                    }
                )
                ->where('exercicio_id', $exercicio_id)
                ->where('natureza_despesa_id', $natureza_despesa_id)
                ->whereNull('subnatureza_despesa_id')
                ->where('tipo', 'despesa_fixa')
                ->sum('valor_total');
            $dados['custo_fixo'] = $custo_fixo;
            $custo_variavel = Despesa::whereHas(
                    'unidade_administrativa', function ($query) use($unidade_gestora_id) {
                        $query->where('unidade_gestora_id', $unidade_gestora_id);
                    }
                )
                ->where('exercicio_id', $exercicio_id)
                ->where('natureza_despesa_id', $natureza_despesa_id)
                ->whereNull('subnatureza_despesa_id')
                ->where('tipo', 'despesa_variavel')
                ->sum('valor_total');
            $dados['custo_variavel'] = $custo_variavel;
            $valor_total = $custo_fixo + $custo_variavel;
            $dados['custo_total'] = $valor_total;

            // SubNatureza
            $subnaturezas_despesas = Despesa::select('subnatureza_despesa_id')
                ->whereHas(
                    'unidade_administrativa', function ($query) use($unidade_gestora_id) {
                        $query->where('unidade_gestora_id', $unidade_gestora_id);
                    }
                )
                ->where('exercicio_id', $exercicio_id)
                ->where('natureza_despesa_id', $natureza_despesa_id)
                ->whereNotNull('subnatureza_despesa_id')
                ->pluck('subnatureza_despesa_id');

            if (count($subnaturezas_despesas) > 0) {
                foreach($subnaturezas_despesas as $key_sub => $subnatureza_despesa_id) {
                    $subnatureza_despesa = SubnaturezaDespesa::find($subnatureza_despesa_id);
                    $dados['subnaturezas_despesas'][$key_sub]['nome'] = "$subnatureza_despesa->codigo - $subnatureza_despesa->nome";
                    $custo_fixo_sub = Despesa::whereHas(
                            'unidade_administrativa', function ($query) use($unidade_gestora_id) {
                                $query->where('unidade_gestora_id', $unidade_gestora_id);
                            }
                        )
                        ->where('exercicio_id', $exercicio_id)
                        ->where('subnatureza_despesa_id', $subnatureza_despesa_id)
                        ->where('tipo', 'despesa_fixa')
                        ->sum('valor_total');
                    $dados['subnaturezas_despesas'][$key_sub]['custo_fixo'] = $custo_fixo_sub;
                    $custo_variavel_sub = Despesa::whereHas(
                            'unidade_administrativa', function ($query) use($unidade_gestora_id) {
                                $query->where('unidade_gestora_id', $unidade_gestora_id);
                            }
                        )
                        ->where('exercicio_id', $exercicio_id)
                        ->where('subnatureza_despesa_id', $subnatureza_despesa_id)
                        ->where('tipo', 'despesa_variavel')
                        ->sum('valor_total');
                    $dados['subnaturezas_despesas'][$key_sub]['custo_variavel'] = $custo_variavel_sub;
                    $valor_total_sub = $custo_fixo_sub + $custo_variavel_sub;
                    $dados['subnaturezas_despesas'][$key_sub]['custo_total'] = $valor_total_sub;
                }
            }

             // Verificando acao
             $fontes_acoes = Despesa::whereHas(
                    'unidade_administrativa', function ($query) use($unidade_gestora_id) {
                        $query->where('unidade_gestora_id', $unidade_gestora_id);
                    }
                )
                ->where('exercicio_id', $exercicio_id)
                ->where('natureza_despesa_id', $natureza_despesa_id)
                ->pluck('fonte_acao_id')
                ->toArray();
            
            foreach($infos as $acao_id => $value) {
                if(count(array_intersect($fontes_acoes, $fontes_acoes_ids[$acao_id])) > 0) {
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

        // Resumo

        return view('unidade_gestora.relatorio_simplificado')->with([
            'instituicao' => $instituicao,
            'exercicio' => $exercicio,
            'unidade_gestora' => $unidade_gestora,
            'acoes' => $acoes,
            'infos' => $infos,
            'total_custo_fixo' => $total_custo_fixo,
            'total_custo_variavel' => $total_custo_variavel,
            'resumo_acoes' => $resumo_acoes
        ]);
    }
}
