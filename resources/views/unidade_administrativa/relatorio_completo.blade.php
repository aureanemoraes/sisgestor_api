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

    .acao {
       background: #0f0f0f;
       color: white;
       padding: 2px;
     }

     .acao-icon {
       background: red;
       color: white;
       padding: 2px;
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

     .table {
      vertical-align: middle;
     }

     .resumo {
       display: flex;
       justify-content: end;
     }

     .text-attribute {
       font-size: 8px;
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
    <div class="content">
      @foreach($acoes as $acao)
        <p class="acao">
          <span class="acao-icon">AÇÃO</span> {{ $acao->acao_tipo->codigo }} - {{ $acao->acao_tipo->nome }}
        </p>
        <div class="resumo">
          <table class="table table-sm table-dark table-resumo">
            <tbody>
              <tr>
                <td>
                  TOTAL ESTIMADO FUNCIONAMENTO - CUSTEIO (CUSTO FIXO)
                </td>
                <td>
                  R$ 00,00
                </td>
              </tr>
              <tr>
                <td>
                  TOTAL ESTIMADO FUNCIONAMENTO - CUSTEIO (CUSTO VARIÁVEL)
                </td>
                <td>
                  R$ 00,00
                </td>
              </tr>
              <tr>
                <td>
                  TOTAL ESTIMADO FUNCIONAMENTO - CUSTEIO (CUSTO FIXO + CUSTO VARIÁVEL)
                </td>
                <td class="resumo-total">
                  R$ 00,00
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th></th>
                <th colspan="4">CUSTO FIXO</th>
                <th colspan="4">CUSTO VARIÁVEL</th>
              </tr>
              <tr>
                <th>NATUREZA DA DESPESA DETALHADA</th>
                <th colspan="4">ESTIMATIVA DE QUANTIDADES E VALORES PARA {{ $exercicio->nome }}</th>
                <th colspan="4">ESTIMATIVA DE QUANTIDADES E VALORES PARA {{ $exercicio->nome }}</th>
              </tr>
              @if(count($infos) > 0)
                @foreach($infos as $key => $item)
                  @if($key == $acao->id)
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
                    @if(count($item['despesas']) > 0)
                      @foreach($item['despesas'] as $key2 => $despesas)
                        @if($key2 == 'custo_fixo')
                          @foreach($despesas as $key3 => $despesa)
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
                            <td class="text-center" width="6%">
                              @php
                                $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                                $despesa_valor = $formatter->formatCurrency($despesa['valor'], "BRL")
                              @endphp
                              {{ $despesa_valor }}
                            </td>
                            <td class="text-center" width="6%">
                              @php
                                $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                                $despesa_valor_total = $formatter->formatCurrency($despesa['valor_total'], "BRL")
                              @endphp
                              {{ $despesa_valor_total }}
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                          </tr>
                          @endforeach
                        @elseif($key2 == 'custo_variavel')
                          @foreach($despesas as $key3 => $despesa)
                          <tr>
                            <td>
                              {{ $despesa['descricao'] }}
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center" width="4%">
                              {{ $despesa['qtd'] == 1 ? '' : $despesa['qtd'] }}
                            </td>
                            <td class="text-center" width="4%">
                              {{ $despesa['qtd_pessoas'] == 1 ? '' : $despesa['qtd_pessoas'] }}
                            </td>
                            <td class="text-center" width="6%">
                              @php
                                $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                                $despesa_valor = $formatter->formatCurrency($despesa['valor'], "BRL")
                              @endphp
                              {{ $despesa_valor }}
                            </td>
                            <td class="text-center" width="6%">
                              @php
                                $formatter = new NumberFormatter( 'pt_BR', NumberFormatter::CURRENCY );
                                $despesa_valor_total = $formatter->formatCurrency($despesa['valor_total'], "BRL")
                              @endphp
                              {{ $despesa_valor_total }}
                            </td>
                          </tr>
                          @endforeach
                        @endif
                      @endforeach
                    @endif
                  @endif
                @endforeach
              @endif
              <tr></tr>
            </thead>
          </table>
        </div>
      @endforeach
    </div>
  </div>
</body>
</html>