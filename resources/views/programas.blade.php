@extends('layout')

@section('content')
  <form class="form-inline" action="{{ route('programas') }}">
    <input type="hidden" name="exercicio_id" value="1">
    <select class="custom-select my-1 mr-sm-2" id="order_by" name="order_by">
      <option selected>Visualizar por...</option>
      <option value="">Programas</option>
      <option value="fontes">Fontes</option>
    </select>

    <button type="submit" class="btn btn-primary  btn-sm my-1">Atualizar</button>
  </form>

  <table class="table">
    <thead>
      <tr>
        <th>Nome</th>
      </tr>
    </thead>
    <tbody>
      @if($order_by == 'fontes')
        @foreach($dados as $dado)
          <tr>
            <td>
              {{ $dado->fonte_tipo->nome }}
              <ul class="list-group list-group-flush">
                @foreach($dado->programas as $programa)
                  <li class="list-group-item">{{ $programa->nome }}</li>
                @endforeach
              </ul>
            </td>
          </tr>
        @endforeach
      @else
        @foreach($dados as $dado)
          <tr>
            <td>{{ $dado->nome }}
              <ul class="list-group list-group-flush">
                @foreach($dado->fontes as $fonte)
                  <li class="list-group-item">{{ $fonte->fonte_tipo->nome }}</li>
                @endforeach
              </ul>
            </td>
          </tr>
        @endforeach
      @endif
    </tbody>
  </table>
@endsection