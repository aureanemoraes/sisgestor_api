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
     }
     
     .natureza-despesa-title {
       background: #5f9ea0;
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
          <table class="table">
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
                  <tr class="natureza-despesa-title"> 
                    <th>{{ $item['nome'] }}</th>
                    <th>QTD. 1</th>
                    <th>QTD. 2</th>
                    <th>VALOR UNITÁRIO</th>
                    <th>VALOR TOTAL</th>
                    <th>QTD. 1</th>
                    <th>QTD. 2</th>
                    <th>VALOR UNITÁRIO</th>
                    <th>VALOR TOTAL</th>
                  </tr>
                  @if(count($item['despesas']) > 0)
                    @foreach($item['despesas'] as $key => $despesas)
                      @if($key == 'custo_fixo')
                        @foreach($despesas as $keyD => $despesa)
                        <tr>
                          <td>
                            {{ $despesa['descricao'] }}
                          </td>
                          <td>
                            {{ $despesa['qtd'] == 1 ? '' : $despesa['qtd'] }}
                          </td>
                          <td>
                            {{ $despesa['qtd_pessoas'] == 1 ? '' : $despesa['qtd_pessoas'] }}
                          </td>
                          <td>
                            {{ $despesa['valor'] }}
                          </td>
                          <td>
                            {{ $despesa['valor_total'] }}
                          </td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                        @endforeach
                      @elseif($key == 'custo_variavel')
                        <tr>
                          <td>
                            {{ $despesa['descricao'] }}
                          </td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td>
                            {{ $despesa['qtd'] == 1 ? '' : $despesa['qtd'] }}
                          </td>
                          <td>
                            {{ $despesa['qtd_pessoas'] == 1 ? '' : $despesa['qtd_pessoas'] }}
                          </td>
                          <td>
                            {{ $despesa['valor'] }}
                          </td>
                          <td>
                            {{ $despesa['valor_total'] }}
                          </td>
                        </tr>
                      @endif
                    @endforeach
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