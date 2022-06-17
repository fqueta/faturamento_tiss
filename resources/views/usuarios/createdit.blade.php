@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>{{$titulo}}</h1>
@stop
@section('content')

<form class="" action="@if($usuario['ac']=='cad'){{ route('usuarios.store') }}@elseif($usuario['ac']=='alt'){{ route('usuarios.update',['id'=>$usuario['id']]) }}@endif" method="post">
    @if($usuario['ac']=='alt')
    @method('PUT')
    @endif
    <div class="row">
      <div class="form-group col-md-8">
          <label for="nome">Nome completo</label>
          <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" aria-describedby="nome" placeholder="Nome do publicador" value="@if(isset($usuario['nome'])){{$usuario['nome']}}@elseif($usuario['ac']=='cad'){{old('nome')}}@endif" />
          @error('nome')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror
      </div>
      <div class="form-group col-md-4">
          <label for="tel">Celular</label>
          <input type="tel" class="form-control @error('tel') is-invalid @enderror" id="tel" data-mask="(99)9999-99999" name="tel" aria-describedby="tel" placeholder="(00)00000-0000" value="@if(isset($usuario['tel'])){{$usuario['tel']}}@elseif($usuario['ac']=='cad'){{old('tel')}}@endif">
          @error('tel')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror
      </div>
      <div class="form-group col-md-12">
          <label for="endereco">Endereço</label>
          <input type="text" class="form-control @error('endereco') is-invalid @enderror" id="endereco" name="endereco" aria-describedby="endereco" placeholder="Ex: Rua Costa reis,234 Bela Aurora" value="@if(isset($usuario['endereco'])){{$usuario['endereco']}}@elseif($usuario['ac']=='cad'){{old('endereco')}}@endif">
          @error('endereco')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror
      </div>
      <div class="form-group col-md-4">
          <label for="data_nasci">Data de nascimento</label>
          <input type="tel" class="form-control @error('data_nasci') is-invalid @enderror" id="data_nasci" name="data_nasci" aria-describedby="data_nasci" placeholder="00/00/0000" value="@if(isset($usuario['data_nasci'])){{$usuario['data_nasci']}}@elseif($usuario['ac']=='cad'){{old('data_nasci')}}@endif">
          @error('data_nasci')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror
      </div>
      <div class="form-group col-md-2">
          <label for="data_batismo">Data de batismo</label>
          <input type="tel" class="form-control" id="data_batismo" name="data_batismo" aria-describedby="data_batismo" placeholder="00/00/0000" value="@if(isset($usuario['data_batismo'])){{$usuario['data_batismo']}} @elseif($usuario['ac']=='cad'){{old('data_batismo')}}@endif">
      </div>
      <div class="form-group col-md-2">
          <label for="inputState">Sexo</label>
          <select id="inputState" title="genero" name="genero" class="form-control">
            <option value="m" @if(isset($usuario['genero'])&&$usuario['genero']=='m') selected @endif>Masculino</option>
            <option value="f" @if(isset($usuario['genero'])&&$usuario['genero']=='f') selected @endif>Feminino</option>
          </select>
      </div>
      <div class="form-group col-md-4">
          <label for="ativo">Arquivar</label>
          <select class="form-control" name="ativo">
            <option value="s" @if(isset($usuario['ativo'])&&$usuario['ativo']=='s') selected @endif >Não</option>
            <option value="n"  @if(isset($usuario['ativo'])&&$usuario['ativo']=='n') selected @endif >Sim</option>
          </select>
      </div>
      <div class="form-group col-md-2">
          <label for="inputState">Situação</label>
          <select id="inputState" title="Ativo ou inativo" name="inativo" class="form-control">
            <option value="n" @if(isset($usuario['inativo'])&&$usuario['inativo']=='n') selected @endif>Ativo</option>
            <option value="s" @if(isset($usuario['inativo'])&&$usuario['inativo']=='s') selected @endif>Inativo</option>
          </select>
      </div>
      @if(isset($grupos))
      <div class="form-group col-md-3">
        <label for="inputState">Grupo</label>
        <select id="inputState" name="id_grupo" class="form-control">
          <option value="">Nenhum grupos</option>
          @foreach ($grupos as $k => $grupo)
          <option @if(isset($usuario['id_grupo'])&&$usuario['id_grupo']==$grupo->id) selected @endif value="{{$grupo->id}}">{{$grupo->grupo}}</option>
          @endforeach
        </select>
      </div>
      @endif
      <div class="form-group col-md-4">
        <label for="inputState">Privilegio</label>
        <div class="btn-group" data-toggle="buttons">
          <label class="btn btn-secondary active">
            <input type="radio" name="pioneiro" class="d-none" value="p" id="option1" autocomplete="off"  @if(isset($usuario['pioneiro']) && (empty($usuario['pioneiro']) || $usuario['pioneiro']=='p')) checked @endif> Publicador
          </label>
          <label class="btn btn-secondary">
            <input type="radio" name="pioneiro" @if(isset($usuario['pioneiro'])&&$usuario['pioneiro']=='pa') checked @endif class="d-none" value="pa" id="option2" autocomplete="off"> P. Auxiliar
          </label>
          <label class="btn btn-secondary">
            <input type="radio" name="pioneiro" @if(isset($usuario['pioneiro'])&&$usuario['pioneiro']=='pr') checked @endif class="d-none" value="pr" id="option3" autocomplete="off"> P. Regular
          </label>
        </div>
      </div>
      <div class="form-group col-md-3">
        <label for="inputState">Desiginação</label>
        <div class="btn-group" data-toggle="buttons">
          <label class="btn btn-secondary">
            <input type="radio" @if(isset($usuario['fun'])&&$usuario['fun']=='anc') checked @endif name="fun" class="d-none" value="anc" id="f-option1" autocomplete="off" > Ancião
          </label>
          <label class="btn btn-secondary">
            <input type="radio" @if(isset($usuario['fun'])&&$usuario['fun']=='sm') checked @endif name="fun" class="d-none" value="sm" id="f-option2" autocomplete="off"> S. ministerial
          </label>
        </div>
      </div>
      <div class="col-md-12 mb-5">
        <div class="form-group">
          <label for="obs">Observação</label><br>
          <textarea name="obs" class="form-control" rows="8" cols="80">@if(isset($usuario['obs'])){{$usuario['obs']}}@elseif($usuario['ac']=='cad'){{old('obs')}}@endif</textarea>
        </div>
      </div>
      <div class="col-md-12 div-salvar">
        <div class=form-group"">
          <a href=" {{route('usuarios.index')}} " class="btn btn-light"><i class="fa fa-chevron-left"></i> Voltar</a>
          @if(isset($usuario['id']))
          <a href="{{ route('usuarios.cartao',['id'=>$usuario['id']]) }}" title="Cartão do publicador" class="btn btn-light print-card">
              <i class="fa fa-file-pdf"></i> Cartão
           </a>
           @endif
          <button type="submit" class="btn btn-primary">Salvar <i class="fa fa-chevron-right"></i></button>
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
    <script type="text/javascript">
          $(function(){
            $('a.print-card').on('click',function(e){
                openPageLink(e,$(this).attr('href'),"{{date('Y')}}");
            });
          });
    </script>
@stop
