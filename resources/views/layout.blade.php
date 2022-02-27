<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Sigestor</title>
  <!-- Scripts -->
  <script src="{{ asset('js/app.js') }}" defer></script>
   
  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <style>
    .main {
      display: flex;
      justify-content: start;
      align-items: start;
    }

    .menu {
      width: 400px;
      padding: 20px;
      text-align: right;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="main">
      <div class="menu">
          <div class="list-group">
            <a href="{{ route('fontes') }}" class="list-group-item list-group-item-action">Fontes</a>
          </div>
          <div class="list-group">
            <a href="{{ route('programas', ['exercicio_id' => 1]) }}" class="list-group-item list-group-item-action">Programas</a>
          </div>
          <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action">Ações</a>
          </div>
          <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action">Recursos</a>
          </div>
          <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action">Distribuição</a>
          </div>
      </div>
      <div class="content">
        @yield('content')
      </div>
    </div>
  </div>
</body>
</html>
