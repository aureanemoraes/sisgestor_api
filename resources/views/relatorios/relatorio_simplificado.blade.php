<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Relatório Simplificado</title>
   <!-- Scripts -->
   <script src="{{ asset('js/app.js') }}" defer></script>
   
   <!-- Styles -->
   <link href="{{ asset('css/app.css') }}" rel="stylesheet">

   <style>
     * {
       font-size: 10px;
       margin: 0;
       padding: 0;
       offset: 0;
       box-sizing: border-box;
     }

     html {
       display: flex;
       align-items: center;
       justify-content: center;
     }
     @media print {
      table { page-break-inside:auto }
      tr    { page-break-inside:avoid; page-break-after:auto }
   
     }

     .container {
       padding: 30px;
     }

     .title {
       text-align: right;
     }

     .header {
       display: flex;
       justify-content: space-around;
       align-items: center;
     }

     .acao {
       background: #0f0f0f;
       color: white;
       padding: 2px;
     }

     .tipo-despesa {
       text-align: center;
     }

     td.subnatureza-nome {
       padding-left: 20px;
     }
     th.total-custeio {
       text-align: right;
     }

     .resumo {
       display: flex;
       justify-content: space-between;
       align-items: center;
       gap: 30px;
     }

     .valor-matriz {
        vertical-align: middle;
        text-align: center;
     }

     .table {
      vertical-align: middle;
     }

     .resumo-valores {
       text-align: center;
     }
   </style>
</head>
<body>
  <div class="container">
    <div class="title">
      <p>
        PLANEJAMENTO ORÇAMENTÁRIO <strong>{{ Str::upper($instituicao->nome) }}</strong> - EXERCÍCIO <strong>{{ Str::upper($exercicio->nome) }}</strong> - {{ $exercicio->aprovado ? 'LOA' : 'PLOA' }}
      </p>
      <p>
        @switch($tipo)
            @case('unidade_administrativa')
                UNIDADE ADMINISTRATIVA: <span class="acao">{{ $unidade_administrativa->nome }}</span>
                @break
            @case('unidade_gestora')
                UNIDADE GESTORA: <span class="acao">{{ $unidade_gestora->nome }}</span>
                @break
            @default
        @endswitch
      </p>
    </div>
    
    <div class="resumo">
      <div class="resumo-metas">
        @if($tipo == 'unidade_gestora' ||$tipo == 'instituicao')
          <table class="table table-bordered">
            <thead>
              <th>Ação</th>
              <th>Natureza de Despesa</th>
              <th>Meta</th>
              <th>Qtd. Estimada</th>
              <th>Qtd. Alcançada</th>
            </thead>
            <tbody>
              @php $contador=0 @endphp
              @foreach($resumo_metas as $acao_id => $resumo_meta)
                <tr>
                  <td rowspan={{ count($resumo_meta['metas']) }}>{{ $resumo_meta['acao'] }}</td>
                  @foreach($resumo_meta['metas'] as $index => $meta)
                    @if($contador > 1)
                      <tr>
                        @if(isset($meta['natureza_despesa']))
                          <td>{{ $meta['natureza_despesa'] }}</td>
                        @else
                          <td class="text-center"> - </td>
                        @endif
                        <td>{{ $meta['nome_meta'] }}</td>
                        <td class="text-center">
                          @php
                              $qtd_estimada = $meta['qtd_estimada'];
                          @endphp
                          {{ 'R$ ' . number_format($qtd_estimada, 2) }}
                        </td>
                        <td class="text-center">
                          @php
                              $qtd_alcancada = $meta['qtd_alcancada'];
                          @endphp
                          {{ 'R$ ' . number_format($qtd_alcancada, 2) }}
                        </td>
                      </tr>
                    @else
                      @if(isset($meta['natureza_despesa']))
                        <td>{{ $meta['natureza_despesa'] }}</td>
                      @else
                        <td class="text-center"> - </td>
                      @endif
                      <td>{{ $meta['nome_meta'] }}</td>
                      <td class="text-center">
                        @php
                            $qtd_estimada = $meta['qtd_estimada'];
                        @endphp
                        {{ 'R$ ' . number_format($qtd_estimada, 2)}}
                      </td>
                      <td class="text-center">
                        @php
                            $qtd_alcancada = $meta['qtd_alcancada'];
                        @endphp
                        {{ 'R$ ' . number_format($qtd_alcancada, 2) }}
                      </td>
                    @endif
                    @php $contador++ @endphp
                  @endforeach
                </tr>
              @endforeach
            </tbody>
          </table>
        @endif
      </div>

      <div class="resumo-acoes">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th></th>
              <th></th>
              <th>MATRIZ</th>
              <th>PLANEJADO</th>
              <th>SALDO A PLANEJAR</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th>AÇÃO</th>
              <th>DETALHAMENTO</th>
              <td class="resumo-valores">
                {{ 'R$ ' . number_format($resumo_acoes['total_matriz'], 2) }}
              </td>
              <td class="resumo-valores">
                {{ 'R$ ' . number_format($resumo_acoes['total_planejado']) }}
              </td>
              <td class="resumo-valores">
                {{ 'R$ ' . number_format($resumo_acoes['total_diferenca'], 2) }}
              </td>
            </tr>
            @foreach($resumo_acoes as $keyR => $resumo_acao)
              @if($keyR == 'acoes')
                @foreach($resumo_acao as $codigo_acao => $acao)
                  <tr>
                    <td rowspan="3">{{ $codigo_acao }}</td>
                  </tr>
                  <tr>
                    <td>Custeio</td>
                    <td rowspan="2" class="valor-matriz">
                      {{ 'R$ ' . number_format($acao['valores']['matriz'], 2) }}
                    </td>
                    <td>
                      {{ 'R$ ' . number_format($acao['valores']['planejado']['custeio'], 2) }}
                    </td>
                    <td rowspan="2" class="valor-matriz">
                      {{ 'R$ ' . number_format($acao['valores']['diferenca'], 2) }}
                    </td>
                  </tr>
                  <tr>
                    <td>Investimento</td>
                    <td>
                      {{ 'R$ ' . number_format($acao['valores']['planejado']['investimento'], 2) }}
                    </td>
                  </tr>
                @endforeach
              @endif
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="header">
      <div class="item">
        <h4>Relatório Geral Simplificado</h4>
      </div>
      <div class="item">
        <table class="table">
          <thead>
            <tr>
              <td>Total Custo Fixo</td>
              <td>Total Custo Variável</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                {{ 'R$ ' . number_format($total_custo_fixo, 2) }}
              </td>
              <td>
                {{ 'R$ ' . number_format($total_custo_variavel, 2) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="content">
      <table class="table">
        <tbody>
          @foreach($acoes as $acao)
            <tr>
              <th colspan="100%">
                <span class='acao'>AÇÃO</span>
                {{ $acao->acao_tipo->codigo }} - {{ $acao->acao_tipo->nome }}
              </th>
            </tr>
            <tr>
              <th class="tipo-despesa" colspan="100%">Custeio</th>
            </tr>
            <tr>
              <th></th>
              <th>Custo Fixo</th>
              <th>Despesas Variável</th>
              <th>Valor Total</th>
            </tr>
            @if(isset($infos[$acao->id]) && count($infos[$acao->id]) > 0)
              @if(isset($infos[$acao->id]['Custeio']) && count($infos[$acao->id]['Custeio']) > 0)
                @foreach($infos[$acao->id]['Custeio'] as $key => $custeio)
                  @if($key == 'naturezas_despesas')
                    @foreach($custeio as $item)
                      <tr>
                        <td>
                          {{ $item['nome'] }}
                        </td>
                        <td>
                          {{ 'R$ ' . number_format($item['custo_fixo'], 2) }}
                        </td>
                        <td>
                          {{ 'R$ ' . number_format($item['custo_variavel'], 2) }}
                        </td>
                        <td>
                          {{ 'R$ ' . number_format($item['custo_total'], 2) }}
                        </td>
                      </tr>
                      @if(isset($item['subnaturezas_despesas']) && count($item['subnaturezas_despesas']) > 0)
                        @foreach($item['subnaturezas_despesas'] as $keyC => $subnatureza)
                          <tr>
                            <td class="subnatureza-nome">
                              {{ $subnatureza['nome'] }}
                            </td>
                            <td>
                              {{ 'R$ ' . number_format($subnatureza['custo_fixo'], 2) }}
                            </td>
                            <td>
                              {{ 'R$ ' . number_format($subnatureza['custo_variavel'], 2) }}
                            </td>
                            <td>
                              {{ 'R$ ' . number_format($subnatureza['custo_total'], 2) }}
                            </td>
                          </tr>
                        @endforeach
                      @endif
                    @endforeach
                  @endif
                @endforeach
              @endif
            @endif
            <tr>
              @php
                if(isset($infos[$acao->id]['Custeio'])) {
                  $total_fixo = $infos[$acao->id]['Custeio']['total_fixo'];
                  $total_variavel = $infos[$acao->id]['Custeio']['total_variavel'];
                  $total = $infos[$acao->id]['Custeio']['total'];
                } else {
                  $total_fixo = 0;
                  $total_variavel = 0;
                  $total = 0;
                }

              @endphp
              <th class="total-custeio">TOTAL CUSTEIO:</th>
              <td>{{ 'R$ ' . number_format($total_fixo, 2) }}</td>
              <td>{{ 'R$ ' . number_format($total_variavel, 2) }}</td>
              <td>{{ 'R$ ' . number_format($total, 2) }}</td>
            </tr>
            <tr>
              <th class="tipo-despesa" colspan="100%">Investimento</th>
            </tr>
            <tr>
              <th></th>
              <th>Custo Fixo</th>
              <th>Despesas Variável</th>
              <th>Valor Total</th>
            </tr>
            @if(isset($infos[$acao->id]) && count($infos[$acao->id]) > 0)
              @if(isset($infos[$acao->id]['Investimento']) && count($infos[$acao->id]['Investimento']) > 0)
                @foreach($infos[$acao->id]['Investimento'] as $key => $investimento)
                  @if($key == 'naturezas_despesas')
                    @foreach($investimento as $item)
                      <tr>
                        <td>
                          {{ $item['nome'] }}
                        </td>
                        <td>
                          {{ 'R$ ' . number_format($item['custo_fixo'], 2) }}
                        </td>
                        <td>
                          {{ 'R$ ' . number_format($item['custo_variavel'], 2) }}
                        </td>
                        <td>
                          {{ 'R$ ' . number_format($item['custo_total'], 2) }}
                        </td>
                      </tr>
                      @if(isset($item['subnaturezas_despesas']) && count($item['subnaturezas_despesas']) > 0)
                        @foreach($item['subnaturezas_despesas'] as $keyC => $subnatureza)
                          <tr>
                            <td class="subnatureza-nome">
                              {{ $subnatureza['nome'] }}
                            </td>
                            <td>
                              {{ 'R$ ' . number_format($subnatureza['custo_fixo'], 2) }}
                            </td>
                            <td>
                              {{ 'R$ ' . number_format($subnatureza['custo_variavel'], 2) }}
                            </td>
                            <td>
                              {{ 'R$ ' . number_format($subnatureza['custo_total'], 2) }}
                            </td>
                          </tr>
                        @endforeach
                      @endif
                    @endforeach
                  @endif
                @endforeach
              @endif
            @endif
            <tr>
              @php
                if(isset($infos[$acao->id]['Investimento'])) {
                  $total_fixo = $infos[$acao->id]['Investimento']['total_fixo'];
                  $total_variavel = $infos[$acao->id]['Investimento']['total_variavel'];
                  $total = $infos[$acao->id]['Investimento']['total'];
                } else {
                  $total_fixo = 0;
                  $total_variavel = 0;
                  $total = 0;
                }
              @endphp
              <th class="total-custeio">TOTAL INVESTIMENTO:</th>
              <td>{{ 'R$ ' . number_format($total_fixo, 2) }}</td>
              <td>{{ 'R$ ' . number_format($total_variavel, 2) }}</td>
              <td>{{ 'R$ ' . number_format($total, 2) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>