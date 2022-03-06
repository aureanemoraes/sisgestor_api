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
    public function relatorioCompletoUA($instituicao_id, $exercicio_id, $unidade_administrativa_id) 
    {
        $instituicao = Instituicao::find($instituicao_id);
        $exercicio = Exercicio::find($exercicio_id);
        $unidade_administrativa = UnidadeAdministrativa::find($unidade_administrativa_id);
        
        $acoes = Acao::where('exercicio_id', $exercicio_id)->where('instituicao_id', $instituicao_id)->get();

        $infos = [];
        $resumo = [];

        foreach ($acoes as $acao) {
            $fontes_acoes_ids[$acao->id] = FonteAcao::where('unidade_administrativa_id', $unidade_administrativa_id)
                ->where('exercicio_id', $exercicio_id)
                ->where('acao_id', $acao->id)
                ->pluck('id')
                ->toArray();


            if(!isset($infos[$acao->id])) {
                $infos[$acao->id] = [];
            }
                
            if(!isset($resumo[$acao->id]))
                $resumo[$acao->id] = [];
        }

        $naturezas_despesas_ids = Despesa::select('natureza_despesa_id')
            ->where('unidade_administrativa_id', $unidade_administrativa_id)
            ->where('exercicio_id', $exercicio_id)
            ->groupBy('natureza_despesa_id')
            ->pluck('natureza_despesa_id');

        $naturezas_despesas = NaturezaDespesa::whereIn('id', $naturezas_despesas_ids)->get();

        foreach($naturezas_despesas as $natureza_despesa) {
            $despesas_fixas = Despesa::with(['fonte_acao:id,acao_id'])
                ->where('unidade_administrativa_id', $unidade_administrativa_id)
                ->where('exercicio_id', $exercicio_id)
                ->where('natureza_despesa_id', $natureza_despesa->id)
                ->whereNull('subnatureza_despesa_id')
                ->where('tipo', 'despesa_fixa')
                ->get();

            if (count($despesas_fixas) > 0) {
                foreach($despesas_fixas as $despesa_fixa) {
                    if(!isset($infos[$despesa_fixa->fonte_acao->acao_id]['nome'])) {
                        $infos[$despesa_fixa->fonte_acao->acao_id]['nome'] = "$natureza_despesa->codigo - $natureza_despesa->nome";
                        $infos[$despesa_fixa->fonte_acao->acao_id]['comentario'] = "$natureza_despesa->comentario";
                    }
                        
                    if($natureza_despesa->tipo == 'Custeio') {
                        $infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['custeio']['custo_fixo'][] = $despesa_fixa->toArray();

                        if(!isset($infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['custeio']['custo_fixo']['total']))
                            $infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['custeio']['custo_fixo']['total'] = $despesa_fixa->valor_total; 
                        else
                            $infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['custeio']['custo_fixo']['total'] += $despesa_fixa->valor_total;
                    } else {
                        $infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['investimento']['custo_fixo'][] = $despesa_fixa->toArray(); 

                        if(!isset($infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['investimento']['custo_fixo']['total']))
                            $infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['investimento']['custo_fixo']['total'] = $despesa_fixa->valor_total; 
                        else
                            $infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['investimento']['custo_fixo']['total'] += $despesa_fixa->valor_total;
                    }

                    if(!isset($infos[$despesa_fixa->fonte_acao->acao_id]['total_acao']))
                        $infos[$despesa_fixa->fonte_acao->acao_id]['total_acao'] = $despesa_fixa->valor_total;
                    else
                        $infos[$despesa_fixa->fonte_acao->acao_id]['total_acao'] += $despesa_fixa->valor_total;
                }
            }

            $despesas_variaveis = Despesa::where('unidade_administrativa_id', $unidade_administrativa_id)
                ->where('exercicio_id', $exercicio_id)
                ->where('natureza_despesa_id', $natureza_despesa->id)
                ->whereNull('subnatureza_despesa_id')
                ->where('tipo', 'despesa_variavel')
                ->get();

            if (count($despesas_variaveis) > 0) {
                foreach($despesas_variaveis as $despesa_variavel) {
                    if(!isset($infos[$despesa_variavel->fonte_acao->acao_id]['nome'])) {
                        $infos[$despesa_variavel->fonte_acao->acao_id]['nome'] = "$natureza_despesa->codigo - $natureza_despesa->nome";
                        $infos[$despesa_variavel->fonte_acao->acao_id]['comentario'] = "$natureza_despesa->comentario";
                    }

                    if($natureza_despesa->tipo == 'Custeio') {
                        $infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['custeio']['custo_variavel'][] = $despesa_variavel->toArray();

                        if(!isset($infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['custeio']['custo_variavel']['total']))
                            $infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['custeio']['custo_variavel']['total'] = $despesa_variavel->valor_total; 
                        else
                            $infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['custeio']['custo_variavel']['total'] += $despesa_variavel->valor_total;
                    } else {
                        $infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['investimento']['custo_variavel'][] = $despesa_variavel->toArray(); 

                        if(!isset($infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['investimento']['custo_variavel']['total']))
                            $infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['investimento']['custo_variavel']['total'] = $despesa_variavel->valor_total; 
                        else
                            $infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['investimento']['custo_variavel']['total'] += $despesa_variavel->valor_total;
                    }

                    if(!isset($infos[$despesa_variavel->fonte_acao->acao_id]['total_acao']))
                        $infos[$despesa_variavel->fonte_acao->acao_id]['total_acao'] = $despesa_variavel->valor_total;
                    else
                        $infos[$despesa_variavel->fonte_acao->acao_id]['total_acao'] += $despesa_variavel->valor_total;
                }
            }
        }
        return view('unidade_administrativa.relatorio_completo')->with([
            'instituicao' => $instituicao,
            'exercicio' => $exercicio,
            'unidade_administrativa' => $unidade_administrativa,
            'acoes' => $acoes,
            'infos' => $infos
        ]);
    }

    public function relatorioCompletoUG($instituicao_id, $exercicio_id, $unidade_gestora_id) 
    {
        $instituicao = Instituicao::find($instituicao_id);
        $exercicio = Exercicio::find($exercicio_id);
        $unidade_gestora = UnidadeGestora::find($unidade_gestora_id);
        
        $acoes = Acao::where('exercicio_id', $exercicio_id)->where('instituicao_id', $instituicao_id)->get();

        $infos = [];
        $resumo = [];

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


            if(!isset($infos[$acao->id])) {
                $infos[$acao->id] = [];
            }
                
            if(!isset($resumo[$acao->id]))
                $resumo[$acao->id] = [];
        }

        $naturezas_despesas_ids = Despesa::select('natureza_despesa_id')
            ->whereHas(
                'unidade_administrativa', function ($query) use($unidade_gestora_id) {
                    $query->where('unidade_gestora_id', $unidade_gestora_id);
                }
            )
            ->where('exercicio_id', $exercicio_id)
            ->groupBy('natureza_despesa_id')
            ->pluck('natureza_despesa_id');

        $naturezas_despesas = NaturezaDespesa::whereIn('id', $naturezas_despesas_ids)->get();

        foreach($naturezas_despesas as $natureza_despesa) {
            $despesas_fixas = Despesa::with(['fonte_acao:id,acao_id'])
                ->whereHas(
                    'unidade_administrativa', function ($query) use($unidade_gestora_id) {
                        $query->where('unidade_gestora_id', $unidade_gestora_id);
                    }
                )
                ->where('exercicio_id', $exercicio_id)
                ->where('natureza_despesa_id', $natureza_despesa->id)
                ->whereNull('subnatureza_despesa_id')
                ->where('tipo', 'despesa_fixa')
                ->get();

            if (count($despesas_fixas) > 0) {
                foreach($despesas_fixas as $despesa_fixa) {
                    if(!isset($infos[$despesa_fixa->fonte_acao->acao_id]['nome'])) {
                        $infos[$despesa_fixa->fonte_acao->acao_id]['nome'] = "$natureza_despesa->codigo - $natureza_despesa->nome";
                        $infos[$despesa_fixa->fonte_acao->acao_id]['comentario'] = "$natureza_despesa->comentario";
                    }
                    if($natureza_despesa->tipo == 'Custeio') {
                        $infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['custeio']['custo_fixo'][] = $despesa_fixa->toArray();

                        if(!isset($infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['custeio']['custo_fixo']['total']))
                            $infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['custeio']['custo_fixo']['total'] = $despesa_fixa->valor_total; 
                        else
                            $infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['custeio']['custo_fixo']['total'] += $despesa_fixa->valor_total;
                    } else {
                        $infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['investimento']['custo_fixo'][] = $despesa_fixa->toArray(); 

                        if(!isset($infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['investimento']['custo_fixo']['total']))
                            $infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['investimento']['custo_fixo']['total'] = $despesa_fixa->valor_total; 
                        else
                            $infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['investimento']['custo_fixo']['total'] += $despesa_fixa->valor_total;
                    }

                    if(!isset($infos[$despesa_fixa->fonte_acao->acao_id]['total_acao']))
                        $infos[$despesa_fixa->fonte_acao->acao_id]['total_acao'] = $despesa_fixa->valor_total;
                    else
                        $infos[$despesa_fixa->fonte_acao->acao_id]['total_acao'] += $despesa_fixa->valor_total;
                }
            }

            $despesas_variaveis = Despesa::whereHas(
                    'unidade_administrativa', function ($query) use($unidade_gestora_id) {
                        $query->where('unidade_gestora_id', $unidade_gestora_id);
                    }
                )
                ->where('exercicio_id', $exercicio_id)
                ->where('natureza_despesa_id', $natureza_despesa->id)
                ->whereNull('subnatureza_despesa_id')
                ->where('tipo', 'despesa_variavel')
                ->get();

            if (count($despesas_variaveis) > 0) {
                foreach($despesas_variaveis as $despesa_variavel) {
                    if(!isset($infos[$despesa_variavel->fonte_acao->acao_id]['nome'])) {
                        $infos[$despesa_variavel->fonte_acao->acao_id]['nome'] = "$natureza_despesa->codigo - $natureza_despesa->nome";
                        $infos[$despesa_variavel->fonte_acao->acao_id]['comentario'] = "$natureza_despesa->comentario";
                    }
                        $infos[$despesa_variavel->fonte_acao->acao_id]['nome'] = "$natureza_despesa->codigo - $natureza_despesa->nome";

                    if($natureza_despesa->tipo == 'Custeio') {
                        $infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['custeio']['custo_variavel'][] = $despesa_variavel->toArray();

                        if(!isset($infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['custeio']['custo_variavel']['total']))
                            $infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['custeio']['custo_variavel']['total'] = $despesa_variavel->valor_total; 
                        else
                            $infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['custeio']['custo_variavel']['total'] += $despesa_variavel->valor_total;
                    } else {
                        $infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['investimento']['custo_variavel'][] = $despesa_variavel->toArray(); 

                        if(!isset($infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['investimento']['custo_variavel']['total']))
                            $infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['investimento']['custo_variavel']['total'] = $despesa_variavel->valor_total; 
                        else
                            $infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['investimento']['custo_variavel']['total'] += $despesa_variavel->valor_total;
                    }

                    if(!isset($infos[$despesa_variavel->fonte_acao->acao_id]['total_acao']))
                        $infos[$despesa_variavel->fonte_acao->acao_id]['total_acao'] = $despesa_variavel->valor_total;
                    else
                        $infos[$despesa_variavel->fonte_acao->acao_id]['total_acao'] += $despesa_variavel->valor_total;
                }
            }
        }
        // dd($infos);

        return view('unidade_gestora.relatorio_completo')->with([
            'instituicao' => $instituicao,
            'exercicio' => $exercicio,
            'unidade_gestora' => $unidade_gestora,
            'acoes' => $acoes,
            'infos' => $infos
        ]);
    }

    public function relatorioCompleto($instituicao_id, $exercicio_id) 
    {
        $instituicao = Instituicao::find($instituicao_id);
        $exercicio = Exercicio::find($exercicio_id);
        
        $acoes = Acao::where('exercicio_id', $exercicio_id)->where('instituicao_id', $instituicao_id)->get();

        $infos = [];
        $resumo = [];

        foreach ($acoes as $acao) {
            $fontes_acoes_ids[$acao->id] = FonteAcao::whereHas(
                    'unidade_administrativa', function ($query) use ($instituicao_id) {
                        $query->whereHas(
                            'unidade_gestora', function ($query) use ($instituicao_id) {
                                $query->where('instituicao_id', $instituicao_id);
                            }
                        );
                    }
                )
                ->where('exercicio_id', $exercicio_id)
                ->where('acao_id', $acao->id)
                ->pluck('id')
                ->toArray();


            if(!isset($infos[$acao->id])) {
                $infos[$acao->id] = [];
            }
                
            if(!isset($resumo[$acao->id]))
                $resumo[$acao->id] = [];
        }

        $naturezas_despesas_ids = Despesa::select('natureza_despesa_id')
            ->whereHas(
                'unidade_administrativa', function ($query) use ($instituicao_id) {
                    $query->whereHas(
                        'unidade_gestora', function ($query) use ($instituicao_id) {
                            $query->where('instituicao_id', $instituicao_id);
                        }
                    );
                }
            )
            ->where('exercicio_id', $exercicio_id)
            ->groupBy('natureza_despesa_id')
            ->pluck('natureza_despesa_id');

        $naturezas_despesas = NaturezaDespesa::whereIn('id', $naturezas_despesas_ids)->get();

        foreach($naturezas_despesas as $natureza_despesa) {
            $despesas_fixas = Despesa::with(['fonte_acao:id,acao_id'])
                ->whereHas(
                    'unidade_administrativa', function ($query) use ($instituicao_id) {
                        $query->whereHas(
                            'unidade_gestora', function ($query) use ($instituicao_id) {
                                $query->where('instituicao_id', $instituicao_id);
                            }
                        );
                    }
                )
                ->where('exercicio_id', $exercicio_id)
                ->where('natureza_despesa_id', $natureza_despesa->id)
                ->whereNull('subnatureza_despesa_id')
                ->where('tipo', 'despesa_fixa')
                ->get();

            if (count($despesas_fixas) > 0) {
                foreach($despesas_fixas as $despesa_fixa) {
                    if(!isset($infos[$despesa_fixa->fonte_acao->acao_id]['nome'])) {
                        $infos[$despesa_fixa->fonte_acao->acao_id]['nome'] = "$natureza_despesa->codigo - $natureza_despesa->nome";
                        $infos[$despesa_fixa->fonte_acao->acao_id]['comentario'] = "$natureza_despesa->comentario";
                    }
                    if($natureza_despesa->tipo == 'Custeio') {
                        $infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['custeio']['custo_fixo'][] = $despesa_fixa->toArray();

                        if(!isset($infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['custeio']['custo_fixo']['total']))
                            $infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['custeio']['custo_fixo']['total'] = $despesa_fixa->valor_total; 
                        else
                            $infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['custeio']['custo_fixo']['total'] += $despesa_fixa->valor_total;
                    } else {
                        $infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['investimento']['custo_fixo'][] = $despesa_fixa->toArray(); 

                        if(!isset($infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['investimento']['custo_fixo']['total']))
                            $infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['investimento']['custo_fixo']['total'] = $despesa_fixa->valor_total; 
                        else
                            $infos[$despesa_fixa->fonte_acao->acao_id]['despesas']['investimento']['custo_fixo']['total'] += $despesa_fixa->valor_total;
                    }

                    if(!isset($infos[$despesa_fixa->fonte_acao->acao_id]['total_acao']))
                        $infos[$despesa_fixa->fonte_acao->acao_id]['total_acao'] = $despesa_fixa->valor_total;
                    else
                        $infos[$despesa_fixa->fonte_acao->acao_id]['total_acao'] += $despesa_fixa->valor_total;
                }
            }

            $despesas_variaveis = Despesa::whereHas(
                    'unidade_administrativa', function ($query) use ($instituicao_id) {
                        $query->whereHas(
                            'unidade_gestora', function ($query) use ($instituicao_id) {
                                $query->where('instituicao_id', $instituicao_id);
                            }
                        );
                    }
                )
                ->where('exercicio_id', $exercicio_id)
                ->where('natureza_despesa_id', $natureza_despesa->id)
                ->whereNull('subnatureza_despesa_id')
                ->where('tipo', 'despesa_variavel')
                ->get();

            if (count($despesas_variaveis) > 0) {
                foreach($despesas_variaveis as $despesa_variavel) {
                    if(!isset($infos[$despesa_variavel->fonte_acao->acao_id]['nome'])) {
                        $infos[$despesa_variavel->fonte_acao->acao_id]['nome'] = "$natureza_despesa->codigo - $natureza_despesa->nome";
                        $infos[$despesa_variavel->fonte_acao->acao_id]['comentario'] = "$natureza_despesa->comentario";
                    }
                        $infos[$despesa_variavel->fonte_acao->acao_id]['nome'] = "$natureza_despesa->codigo - $natureza_despesa->nome";

                    if($natureza_despesa->tipo == 'Custeio') {
                        $infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['custeio']['custo_variavel'][] = $despesa_variavel->toArray();

                        if(!isset($infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['custeio']['custo_variavel']['total']))
                            $infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['custeio']['custo_variavel']['total'] = $despesa_variavel->valor_total; 
                        else
                            $infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['custeio']['custo_variavel']['total'] += $despesa_variavel->valor_total;
                    } else {
                        $infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['investimento']['custo_variavel'][] = $despesa_variavel->toArray(); 

                        if(!isset($infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['investimento']['custo_variavel']['total']))
                            $infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['investimento']['custo_variavel']['total'] = $despesa_variavel->valor_total; 
                        else
                            $infos[$despesa_variavel->fonte_acao->acao_id]['despesas']['investimento']['custo_variavel']['total'] += $despesa_variavel->valor_total;
                    }

                    if(!isset($infos[$despesa_variavel->fonte_acao->acao_id]['total_acao']))
                        $infos[$despesa_variavel->fonte_acao->acao_id]['total_acao'] = $despesa_variavel->valor_total;
                    else
                        $infos[$despesa_variavel->fonte_acao->acao_id]['total_acao'] += $despesa_variavel->valor_total;
                }
            }
        }
        // dd($infos);

        return view('instituicao.relatorio_completo')->with([
            'instituicao' => $instituicao,
            'exercicio' => $exercicio,
            'acoes' => $acoes,
            'infos' => $infos
        ]);
    }

    public function relatorioSimplificadoUA($instituicao_id, $exercicio_id, $unidade_administrativa_id) 
    {
        $instituicao = Instituicao::find($instituicao_id);
        $exercicio = Exercicio::find($exercicio_id);
        $unidade_administrativa = UnidadeAdministrativa::find($unidade_administrativa_id);
        
        $acoes = Acao::where('exercicio_id', $exercicio_id)->where('instituicao_id', $instituicao_id)->get();

        $naturezas_despesas = Despesa::select('natureza_despesa_id')
            ->where('unidade_administrativa_id', $unidade_administrativa_id)
            ->where('exercicio_id', $exercicio_id)
            ->groupBy('natureza_despesa_id')
            ->pluck('natureza_despesa_id');

        $metas_orcamentarias = MetaOrcamentaria::whereHas(
            'unidade_gestora', function ($query) use($unidade_administrativa_id) {
                $query->where('unidade_gestora.unidade_administrativa', $unidade_administrativa_id);
            }
        )
        ->where('instituicao_id', $instituicao_id);

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
        // dd($infos);

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

    public function relatorioSimplificado($instituicao_id, $exercicio_id) 
    {
        $instituicao = Instituicao::find($instituicao_id);
        $exercicio = Exercicio::find($exercicio_id);
        
        $acoes = Acao::where('exercicio_id', $exercicio_id)->where('instituicao_id', $instituicao_id)->get();

        // Tabela principal
        $naturezas_despesas = Despesa::whereHas(
                'unidade_administrativa', function ($query) use ($instituicao_id) {
                    $query->whereHas(
                        'unidade_gestora', function ($query) use ($instituicao_id) {
                            $query->where('instituicao_id', $instituicao_id);
                        }
                    );
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
                    'unidade_administrativa', function ($query) use ($instituicao_id) {
                        $query->whereHas(
                            'unidade_gestora', function ($query) use ($instituicao_id) {
                                $query->where('instituicao_id', $instituicao_id);
                            }
                        );
                    }
                )
                ->where('exercicio_id', $exercicio_id)
                ->where('acao_id', $acao->id)
                ->pluck('id')
                ->toArray();

            $resumo_acoes['acoes'][$acao->acao_tipo->codigo]['valores']['matriz'] = FonteAcao::whereHas(
                    'unidade_administrativa', function ($query) use ($instituicao_id) {
                        $query->whereHas(
                            'unidade_gestora', function ($query) use ($instituicao_id) {
                                $query->where('instituicao_id', $instituicao_id);
                            }
                        );
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
                'unidade_administrativa', function ($query) use ($instituicao_id) {
                    $query->whereHas(
                        'unidade_gestora', function ($query) use ($instituicao_id) {
                            $query->where('instituicao_id', $instituicao_id);
                        }
                    );
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
                'unidade_administrativa', function ($query) use ($instituicao_id) {
                    $query->whereHas(
                        'unidade_gestora', function ($query) use ($instituicao_id) {
                            $query->where('instituicao_id', $instituicao_id);
                        }
                    );
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
                    'unidade_administrativa', function ($query) use ($instituicao_id) {
                        $query->whereHas(
                            'unidade_gestora', function ($query) use ($instituicao_id) {
                                $query->where('instituicao_id', $instituicao_id);
                            }
                        );
                    }
                )
                ->where('exercicio_id', $exercicio_id)
                ->where('natureza_despesa_id', $natureza_despesa_id)
                ->whereNull('subnatureza_despesa_id')
                ->where('tipo', 'despesa_fixa')
                ->sum('valor_total');
            $dados['custo_fixo'] = $custo_fixo;
            $custo_variavel = Despesa::whereHas(
                    'unidade_administrativa', function ($query) use ($instituicao_id) {
                        $query->whereHas(
                            'unidade_gestora', function ($query) use ($instituicao_id) {
                                $query->where('instituicao_id', $instituicao_id);
                            }
                        );
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
                    'unidade_administrativa', function ($query) use ($instituicao_id) {
                        $query->whereHas(
                            'unidade_gestora', function ($query) use ($instituicao_id) {
                                $query->where('instituicao_id', $instituicao_id);
                            }
                        );
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
                            'unidade_administrativa', function ($query) use ($instituicao_id) {
                                $query->whereHas(
                                    'unidade_gestora', function ($query) use ($instituicao_id) {
                                        $query->where('instituicao_id', $instituicao_id);
                                    }
                                );
                            }
                        )
                        ->where('exercicio_id', $exercicio_id)
                        ->where('subnatureza_despesa_id', $subnatureza_despesa_id)
                        ->where('tipo', 'despesa_fixa')
                        ->sum('valor_total');
                    $dados['subnaturezas_despesas'][$key_sub]['custo_fixo'] = $custo_fixo_sub;
                    $custo_variavel_sub = Despesa::whereHas(
                            'unidade_administrativa', function ($query) use ($instituicao_id) {
                                $query->whereHas(
                                    'unidade_gestora', function ($query) use ($instituicao_id) {
                                        $query->where('instituicao_id', $instituicao_id);
                                    }
                                );
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
                    'unidade_administrativa', function ($query) use ($instituicao_id) {
                        $query->whereHas(
                            'unidade_gestora', function ($query) use ($instituicao_id) {
                                $query->where('instituicao_id', $instituicao_id);
                            }
                        );
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

        return view('instituicao.relatorio_simplificado')->with([
            'instituicao' => $instituicao,
            'exercicio' => $exercicio,
            'acoes' => $acoes,
            'infos' => $infos,
            'total_custo_fixo' => $total_custo_fixo,
            'total_custo_variavel' => $total_custo_variavel,
            'resumo_acoes' => $resumo_acoes
        ]);
    }
}
