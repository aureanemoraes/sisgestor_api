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
      <table class="table table-sm table-dark">
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
            <td>
              R$ 00,00
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="content">
      @foreach($acoes as $acao)
        <p class="acao">
          <span class="acao-icon">AÇÃO</span> {{ $acao->acao_tipo->codigo }} - {{ $acao->acao_tipo->nome }}
        </p>
      @endforeach
    </div>
  </div>
</body>
</html>