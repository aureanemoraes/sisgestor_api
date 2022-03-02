<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Relatório Completo</title>
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

     .title {
       text-align: right;
     }
     td.resumo-total {
       background: #ffff4d;
       color: black;
       font-weight: bold;
     }

     .table-resumo {
       text-align: right;
       width: 450px;
     }
     
     .natureza-despesa-title {
       background: #5f9ea0;
     }

     .table, td, th {
      vertical-align: middle;
     }

     .resumo {
       display: flex;
       justify-content: end;
     }

     .text-attribute {
       font-size: 8px;
     }

     td.borderless {
       border: none;
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
        UNIDADE ADMINISTRATIVA: <span class="bg-dark text-white">{{ $unidade_administrativa->nome }}</span>
      </p>
    </div>
    <div class="content">
      @foreach($acoes as $acao)
        <p class="bg-dark text-white">
          <span class="bg-danger ">AÇÃO</span> {{ $acao->acao_tipo->codigo }} - {{ $acao->acao_tipo->nome }}
        </p>
        @if(isset($infos[$acao->id]) && count($infos[$acao->id]) > 0)
          @php
            $item = $infos[$acao->id];
          @endphp
          @if(count($item['despesas']) > 0)
            @foreach($item['despesas'] as $tipo_natureza => $tipos_despesas)
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th class="text-center table-active"> {{ $tipo_natureza == 'custeio' ? 'CUSTEIO' : 'INVESTIMENTO' }}</th>
                      <th class="text-center" colspan="4">CUSTO FIXO</th>
                      <th class="text-center" colspan="4">CUSTO VARIÁVEL</th>
                    </tr>
                    <tr>
                      <th>NATUREZA DA DESPESA DETALHADA</th>
                      <th colspan="4">ESTIMATIVA DE QUANTIDADES E VALORES PARA {{ $exercicio->nome }}</th>
                      <th colspan="4">ESTIMATIVA DE QUANTIDADES E VALORES PARA {{ $exercicio->nome }}</th>
                    </tr>
                    
                    <tr class="natureza-despesa-title"> 
                      <th>{{ $item['nome'] }}</th>
                      <th class="text-center text-attribute">QTD. 1</th>
                      <th class="text-center text-attribute">QTD. 2</th>
                      <th class="text-center text-attribute">VALOR UNITÁRIO</th>
                      <th class="text-center text-attribute">VALOR TOTAL</th>
                      <th class="text-center text-attribute">QTD. 1</th>
                      <th class="text-center text-attribute">QTD. 2</th>
                      <th class="text-center text-attribute">VALOR UNITÁRIO</th>
                      <th class="text-center text-attribute">VALOR TOTAL</th>
                    </tr>
                      @foreach($tipos_despesas as $key2 => $despesas)
                        @if($key2 == 'custo_fixo')
                          @foreach($despesas as $key3 => $despesa)
                            @if($key3 != 'total')
                              <tr>
                                <td>
                                  {{ $despesa['descricao'] }}
                                </td>
                                <td class="text-center" width="4%">
                                  {{ $despesa['qtd'] == 1 ? '' : $despesa['qtd'] }}
                                </td>
                                <td class="text-center" width="4%">
                                  {{ $despesa['qtd_pessoas'] == 1 ? '' : $despesa['qtd_pessoas'] }}
                                </td>
                                <td class="text-center" width="10%">
                                  @php
                                    $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                                    $despesa_valor = $formatter->formatCurrency($despesa['valor'], "BRL");
                                  @endphp
                                  {{ $despesa_valor }}
                                </td>
                                <td class="text-center" width="10%">
                                  @php
                                    $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                                    $despesa_valor_total = $formatter->formatCurrency($despesa['valor_total'], "BRL");
                                  @endphp
                                  {{ $despesa_valor_total }}
                                </td>
                                <td class="text-center" width="4%"></td>
                                <td class="text-center" width="4%"></td>
                                <td class="text-center" width="10%"></td>
                                <td class="text-center" width="10%"></td>
                              </tr>
                            @endif
                          @endforeach
                        @elseif($key2 == 'custo_variavel')
                          @foreach($despesas as $key3 => $despesa)
                            @if($key3 != 'total')
                              <tr>
                                <td>
                                  {{ $despesa['descricao'] }}
                                </td>
                                <td class="text-center" width="4%"></td>
                                <td class="text-center" width="4%"></td>
                                <td class="text-center" width="10%"></td>
                                <td class="text-center" width="10%"></td>
                                <td class="text-center" width="4%">
                                  {{ $despesa['qtd'] == 1 ? '' : $despesa['qtd'] }}
                                </td>
                                <td class="text-center" width="4%">
                                  {{ $despesa['qtd_pessoas'] == 1 ? '' : $despesa['qtd_pessoas'] }}
                                </td>
                                <td class="text-center" width="10%">
                                  @php
                                    $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                                    $despesa_valor = $formatter->formatCurrency($despesa['valor'], "BRL")
                                  @endphp
                                  {{ $despesa_valor }}
                                </td>
                                <td class="text-center" width="10%">
                                  @php
                                    $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                                    $despesa_valor_total = $formatter->formatCurrency($despesa['valor_total'], "BRL")
                                  @endphp
                                  {{ $despesa_valor_total }}
                                </td>
                              </tr>
                            @endif
                          @endforeach
                        @endif
                      @endforeach
                      <tr>
                        <th class="borderless text-end" colspan="4">Total Custo Fixo:</th>
                        <td class="text-center">
                          @php
                            if($tipo_natureza == 'custeio') {
                              if(isset($item['despesas']['custeio']['custo_fixo']['total'])) {
                                $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                                $total_custo_fixo = $formatter->formatCurrency($item['despesas']['custeio']['custo_fixo']['total'], "BRL");
                              } else 
                                $total_custo_fixo = '';
                            } else {
                              if(isset($item['despesas']['investimento']['custo_fixo']['total'])) {
                                $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                                $total_custo_fixo = $formatter->formatCurrency($item['despesas']['investimento']['custo_fixo']['total'], "BRL");
                              } else 
                                $total_custo_fixo = '';
                            }
                          @endphp
                          {{ $total_custo_fixo }}
                        </td>
                        <th class="borderless text-end" colspan="3">Total Custo Variável:</th>
                        <td class="text-center">
                          @php
                            if($tipo_natureza == 'custeio') {
                              if(isset($item['despesas']['custeio']['custo_variavel']['total'])) {
                                $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                                $total_custo_variavel = $formatter->formatCurrency($item['despesas']['custeio']['custo_variavel']['total'], "BRL");
                              } else 
                                $total_custo_variavel = '';
                            } else {
                              if(isset($item['despesas']['investimento']['custo_variavel']['total'])) {
                                $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                                $total_custo_variavel = $formatter->formatCurrency($item['despesas']['investimento']['custo_variavel']['total'], "BRL");
                              } else 
                                $total_custo_variavel = '';
                            }
                          @endphp
                          {{ $total_custo_variavel }}
                        </td>
                      </tr>
                  </thead>
                </table>
            @endforeach
            <table class="table">
              <tr>
                <th class="text-end table-active">Total Ação:</th>
                <td width="10%">
                  @php
                    if(isset($item['total_acao'])) {
                      $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                      $total_acao = $formatter->formatCurrency($item['total_acao'], "BRL");
                    } else 
                      $total_acao = '';
                  @endphp
                  {{ $total_acao }}
                </td>
              </tr>
            </table>
          @endif
        @else
          <p>Esta ação não possui despesas vínculadas.</p>
        @endif
      @endforeach
    </div>
  </div>
</body>
</html>