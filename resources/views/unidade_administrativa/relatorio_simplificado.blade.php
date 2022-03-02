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
       justify-content: space-around;
       align-items: center;
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
        UNIDADE ADMINISTRATIVA: <span class="acao">{{ $unidade_administrativa->nome }}</span>
      </p>
    </div>
    <div class="resumo">
      <div class="resumo-metas">
        <table class="table">
          <p>Metas Orçamentárias</p>
        </table>
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
                @php
                  $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                  $total_matriz = $formatter->formatCurrency($resumo_acoes['total_matriz'], "BRL")
                @endphp
                {{ $total_matriz }}
              </td>
              <td class="resumo-valores">
                @php
                  $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                  $total_planejado = $formatter->formatCurrency($resumo_acoes['total_planejado'], "BRL")
                @endphp
                {{ $total_planejado }}
              </td>
              <td class="resumo-valores">
                @php
                  $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                  $total_diferenca = $formatter->formatCurrency($resumo_acoes['total_diferenca'], "BRL")
                @endphp
                {{ $total_diferenca }}
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
                      @php
                        if($acao['valores']['matriz'] > 0) {
                          $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                          $valor_matriz = $formatter->formatCurrency($acao['valores']['matriz'], "BRL");
                        } else 
                          $valor_matriz = '';
                      @endphp
                      {{ $valor_matriz }}
                    </td>
                    <td>
                      @php
                        if($acao['valores']['planejado']['custeio'] > 0) {
                          $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                          $valor_planejado_custeio = $formatter->formatCurrency($acao['valores']['planejado']['custeio'], "BRL");
                        } else 
                          $valor_planejado_custeio = '';
                      @endphp
                      {{ $valor_planejado_custeio }}
                    </td>
                    <td rowspan="2" class="valor-matriz">
                      @php
                        if($acao['valores']['diferenca'] > 0) {
                          $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                          $valor_diferenca = $formatter->formatCurrency($acao['valores']['diferenca'], "BRL");
                        } else 
                          $valor_diferenca = '';
                      @endphp
                      {{ $valor_diferenca }}
                    </td>
                  </tr>
                  <tr>
                    <td>Investimento</td>
                    <td>
                      @php
                        if($acao['valores']['planejado']['investimento'] > 0) {
                          $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                          $valor_planejado_investimento = $formatter->formatCurrency($acao['valores']['planejado']['investimento'], "BRL");
                        } else 
                          $valor_planejado_investimento = '';
                      @endphp
                      {{ $valor_planejado_investimento }}
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
                @php
                  $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                  $total_custo_fixo = $formatter->formatCurrency($total_custo_fixo, "BRL")
                @endphp
                {{ $total_custo_fixo }}
              </td>
              <td>
                @php
                  $total_custo_variavel_formatado = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                  $total_custo_variavel = $total_custo_variavel_formatado->formatCurrency($total_custo_variavel, "BRL")
                @endphp
                {{ $total_custo_variavel }}
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
                          @php
                            $custo_fixo = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                            $custo_fixo = $custo_fixo->formatCurrency($item['custo_fixo'], "BRL");
                          @endphp
                          {{ $custo_fixo }}
                        </td>
                        <td>
                          @php
                            $custo_variavel = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                            $custo_variavel = $custo_variavel->formatCurrency($item['custo_variavel'], "BRL");
                          @endphp
                          {{ $custo_variavel }}
                        </td>
                        <td>
                          @php
                            $custo_total = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                            $custo_total = $custo_total->formatCurrency($item['custo_total'], "BRL");
                          @endphp
                          {{ $custo_total }}
                        </td>
                      </tr>
                      @if(isset($item['subnaturezas_despesas']) && count($item['subnaturezas_despesas']) > 0)
                        @foreach($item['subnaturezas_despesas'] as $keyC => $subnatureza)
                          <tr>
                            <td class="subnatureza-nome">
                              {{ $subnatureza['nome'] }}
                            </td>
                            <td>
                              @php
                                $custo_fixo = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                                $custo_fixo = $custo_fixo->formatCurrency($subnatureza['custo_fixo'], "BRL");
                              @endphp
                              {{ $custo_fixo }}
                            </td>
                            <td>
                              @php
                                $custo_variavel = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                                $custo_variavel = $custo_variavel->formatCurrency($subnatureza['custo_variavel'], "BRL");
                              @endphp
                              {{ $custo_variavel }}
                            </td>
                            <td>
                              @php
                                $custo_total = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                                $custo_total = $custo_total->formatCurrency($subnatureza['custo_total'], "BRL");
                              @endphp
                              {{ $custo_total }}
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
                
                $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                $total_fixo = $formatter->formatCurrency($total_fixo, "BRL");

                $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                $total_variavel = $formatter->formatCurrency($total_variavel, "BRL");

                $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                $total = $formatter->formatCurrency($total, "BRL");
              @endphp
              <th class="total-custeio">TOTAL CUSTEIO:</th>
              <td>{{ $total_fixo }}</td>
              <td>{{ $total_variavel }}</td>
              <td>{{ $total }}</td>
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
                          @php
                            $custo_fixo = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                            $custo_fixo = $custo_fixo->formatCurrency($item['custo_fixo'], "BRL");
                          @endphp
                          {{ $custo_fixo }}
                        </td>
                        <td>
                          @php
                            $custo_variavel = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                            $custo_variavel = $custo_variavel->formatCurrency($item['custo_variavel'], "BRL");
                          @endphp
                          {{ $custo_variavel }}
                        </td>
                        <td>
                          @php
                            $custo_total = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                            $custo_total = $custo_total->formatCurrency($item['custo_total'], "BRL");
                          @endphp
                          {{ $custo_total }}
                        </td>
                      </tr>
                      @if(isset($item['subnaturezas_despesas']) && count($item['subnaturezas_despesas']) > 0)
                        @foreach($item['subnaturezas_despesas'] as $keyC => $subnatureza)
                          <tr>
                            <td class="subnatureza-nome">
                              {{ $subnatureza['nome'] }}
                            </td>
                            <td>
                              @php
                                $custo_fixo = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                                $custo_fixo = $custo_fixo->formatCurrency($subnatureza['custo_fixo'], "BRL");
                              @endphp
                              {{ $custo_fixo }}
                            </td>
                            <td>
                              @php
                                $custo_variavel = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                                $custo_variavel = $custo_variavel->formatCurrency($subnatureza['custo_variavel'], "BRL");
                              @endphp
                              {{ $custo_variavel }}
                            </td>
                            <td>
                              @php
                                $custo_total = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                                $custo_total = $custo_total->formatCurrency($subnatureza['custo_total'], "BRL");
                              @endphp
                              {{ $custo_total }}
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
                
                $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                $total_fixo = $formatter->formatCurrency($total_fixo, "BRL");
            
                $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                $total_variavel = $formatter->formatCurrency($total_variavel, "BRL");
            
                $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                $total = $formatter->formatCurrency($total, "BRL");
              @endphp
              <th class="total-custeio">TOTAL INVESTIMENTO:</th>
              <td>{{ $total_fixo }}</td>
              <td>{{ $total_variavel }}</td>
              <td>{{ $total }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>