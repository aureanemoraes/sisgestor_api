@extends('layout')

@section('content')
  <table class="table">
    <thead>
      <tr>
        <th>Nome</th>
        <th>Detalhes</th>
      </tr>
    </thead>
    <tbody>
      @foreach($fontes as $fonte)
        <tr>
          <td>{{ $fonte->fonte_tipo->nome }}
            @foreach($fonte->acoes as $acoes)
              <li class="list-group-item">{{ $acoes->acao_tipo->nome }}</li>
            @endforeach
          </td>
          <td></td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection