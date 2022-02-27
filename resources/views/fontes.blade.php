@extends('layout')

@section('content')
  <table class="table">
    <thead>
      <tr>
        <th>Nome</th>
        <th>Valor</th>
        <th>Fav</th>
        <th>Exercício</th>
        <th>Instituição</th>
      </tr>
    </thead>
    <tbody>
      @foreach($fontes as $fonte)
        <tr>
          <td>{{ $fonte->fonte_tipo->nome }}</td>
          <td>{{ $fonte->valor }}</td>
          <td>{{ $fonte->fav }}</td>
          <td>{{ $fonte->exercicio->nome }}</td>
          <td>{{ $fonte->instituicao->nome }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection