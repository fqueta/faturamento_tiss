@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>{{$titulo}}</h1>
@stop
@section('content')
<div class="row">
  <div class="col-md-12">
    <form class="" action="{{ route('grupos-update',['id'=>$grupos->id]) }}" method="post">
      @method('PUT')
      <div classor="grupo">Nome do grupo</label>
        <input type="text" class="form-control" id="grupo" value="{{$grupos->grupo}}" name="grupo" aria-describedby="grupo" placeholder="Nome do grupo">
      </div>
      <div class="form-group">
        <label for="ativo">Ativo</label><br>
        <select class="form-control" name="ativo">
            <option value="s" @if($grupos->ativo=='s') selected @endif >Ativar</option>
            <option value="n"  @if($grupos->ativo=='n') selected @endif >Destivar</option>
        </select>
      </div>
      <div class="form-group">
        <label for="obs">Observação</label><br>
        <textarea name="obs" class="form-control" rows="8" cols="80">{{$grupos->obs}}</textarea>
      </div>
      <div class=form-group"">
        <a href=" {{route('grupos-index')}} " class="btn btn-light"> Voltar</a>
        <button type="submit" class="btn btn-primary">Atualizar</button>
      </div>
      @csrf
    </form>
  </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
