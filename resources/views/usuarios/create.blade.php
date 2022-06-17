@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>{{$titulo}}</h1>
@stop

@section('content')
<form class="" action="{{ route('usuarios.store') }}" method="post">
    <div class="row">
      <div class="col-md-8">
        <div class="form-group">
          <label for="nome">Nome completo</label>
          <input type="text" class="form-control" id="grupo" name="nome" aria-describedby="nome" placeholder="Nome do publicador">
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label for="tel">Celular</label>
          <input type="tel" class="form-control" id="tel" data-mask="(99)9999-99999" name="tel" aria-describedby="tel" placeholder="(00)00000-0000">
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="endereco">Endereço</label>
          <input type="text" class="form-control" id="endereco" name="endereco" aria-describedby="endereco" placeholder="Ex: Rua Costa reis,234 Bela Aurora">
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <label for="data_nasci">Data de nascimento</label>
          <input type="tel" class="form-control" id="data_nasci" name="data_nasci" aria-describedby="data_nasci" placeholder="00/00/0000">
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <label for="data_batismo">Data de batismo</label>
          <input type="tel" class="form-control" id="data_batismo" name="data_batismo" aria-describedby="data_batismo" placeholder="00/00/0000">
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <label for="ativo">Ativo</label>
          <select class="form-control" name="ativo">
              <option value="s">Ativar</option>
              <option value="n">Destivar</option>
          </select>
        </div>
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <label for="obs">Observação</label><br>
          <textarea name="obs" class="form-control" rows="8" cols="80"></textarea>
        </div>
      </div>
      <div class="col-md-12">
        <div class=form-group"">
          <a href=" {{route('usuarios.index')}} " class="btn btn-light"> Voltar</a>
          <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
      </div>
      @csrf
    </div>
</form>
@stop

@section('css')
    <link rel="stylesheet" href=" {{url('/')}}/css/lib.css">
@stop

@section('js')
    <script src=" {{url('/')}}/js/lib.js"></script>
@stop
