@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <!--<h1>{{$titulo}}</h1>-->
@stop

@extends('layout.busca')

@section('content')
  <div class="row">
    <!--
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
            <form id="frm-filtar" action="{{ route('usuarios.index') }}" method="GET">
                <div class="form-row">
                  <div class="form-group col-md-2">
                    <label for="inputState">Situação</label>
                    <select id="ativo" title="Ativo ou inativo" name="fil[inativo]" class="form-control">
                      <option value="t" @if(isset($_GET['fil']['inativo'])&&$_GET['fil']['inativo']=='t') selected @endif>Todos</option>
                      <option value="n" @if(isset($_GET['fil']['inativo'])&&$_GET['fil']['inativo']=='n') selected @endif>Ativo</option>
                      <option value="s" @if(isset($_GET['fil']['inativo'])&&$_GET['fil']['inativo']=='s') selected @endif>Inativo</option>
                    </select>
                  </div>
                  @if(isset($grupos))
                  <div class="form-group col-md-3">
                    <label for="inputState">grupo</label>
                    <select id="grupos" name="fil[id_grupo]" class="form-control">
                      <option selected value="">Todos grupos</option>
                      @foreach ($grupos as $k => $grupo)
                      <option @if(isset($_GET['fil']['id_grupo']) && $_GET['fil']['id_grupo']==$grupo->id) selected @endif value="{{$grupo->id}}">{{$grupo->grupo}}</option>
                      @endforeach
                    </select>
                  </div>
                  @endif
                  <div class="form-group col-md-4">
                    <label for="inputState">Privilegio</label>
                    <div class="btn-group" data-toggle="buttons">
                      <label class="btn btn-secondary active">
                        <input type="checkbox" name="fil[priv][0]" class="d-none" value="p" id="option1" autocomplete="off"  @if(isset($_GET['fil']['priv'][0])&&$_GET['fil']['priv'][0]=='p') checked @endif> Publicador
                      </label>
                      <label class="btn btn-secondary">
                        <input type="checkbox" name="fil[priv][1]" @if(isset($_GET['fil']['priv'][1])&&$_GET['fil']['priv'][1]=='pa') checked @endif class="d-none" value="pa" id="option2" autocomplete="off"> P. Auxiliar
                      </label>
                      <label class="btn btn-secondary">
                        <input type="checkbox" name="fil[priv][2]" @if(isset($_GET['fil']['priv'][2])&&$_GET['fil']['priv'][2]=='pr') checked @endif class="d-none" value="pr" id="option3" autocomplete="off"> P. Regular
                      </label>
                    </div>
                  </div>
                  <div class="form-group col-md-3">
                    <label for="inputState">Desiginação</label>
                    <div class="btn-group" data-toggle="buttons">
                      <label class="btn btn-secondary">
                        <input type="checkbox" @if(isset($_GET['fil']['func'][0])&&$_GET['fil']['func'][0]=='anc') checked @endif name="fil[func][0]" class="d-none" value="anc" id="f-option1" autocomplete="off" > Ancião
                      </label>
                      <label class="btn btn-secondary">
                        <input type="checkbox" @if(isset($_GET['fil']['func'][1])&&$_GET['fil']['func'][1]=='sm') checked @endif name="fil[func][1]" class="d-none" value="sm" id="f-option2" autocomplete="off"> S. ministerial
                      </label>
                    </div>
                  </div>
                  <div class="form-group col-md-6">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <button type="button" id="imprimir-lista-pub" class="btn btn-light">Imprimir</button>
                  </div>
                  <div class="form-group col-md-6 text-right">
                    <label for="">Legenda dos status dos reletórios deste mês</label><br>
                    <label title="Relatório deste mês pendente" style="font-weight:500"><i class="fa fa-circle text-danger"></i> Relatório Pendente</label>
                    <label title="Relatório deste mês enviado para o secretário" style="font-weight:500"><i class="fa fa-circle text-warning"></i> Relatório Enviado</label>
                    <label title="Relatório deste mês foi compilado pelo secretário" style="font-weight:500"><i class="fa fa-circle text-success"></i> Relatório Enviado</label>
                  </div>
                </div>
            </form>
          </div>
        </div>
    </div>-->

    <div class="col-md-12">
      <div class="card">
        <div class="card-header"><h5>{{$titulo}}</h5></div>
        <div class="card-body table-responsive">


              <table class="table dataTable dtr-inline table-hover">
                <thead>
                  <tr>
                    <th><input type="checkbox" name="check-all" value=""></th>
                    <th>Id</th>
                    <th>Nome</th>
                    <th>Privilêgio</th>
                    <th class="text-right">Status</th>
                    <th class="text-center">...</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($usuarios as $key => $usuario)
                    <tr class="{{$usuario->class}}">
                      <td> <input type="checkbox" class="checkbox-table" name="check_{{$usuario->id}}" value="s"> </td>
                      <td> {{$usuario->id}} </td>
                      <td> {{$usuario->nome}} </td>
                      <td> @if($usuario->pioneiro=='pa') P. Auxiliar @elseif($usuario->pioneiro=='pr') P. Regular  @elseif($usuario->pioneiro=='p') Publicador @else Publicador @endif </td>
                      <td><div class="text-right" title="Relatório {{$usuario->status}} "> {{$usuario->status}} </div></td>
                      <td class="d-flex text-right">
                         <a href="{{ route('relatorios.create',['id'=>$usuario->id]) }}" title="Relatório mensal" class="btn btn-primary relatorios-create mr-1">
                           <i class="fa fa-list"></i> Relatório
                        </a>
                         <a href="{{ route('usuarios.cartao',['id'=>$usuario->id]) }}" title="Cartão do publicador" class="btn btn-secondary mr-1 print-card">
                           <i class="fa fa-file-pdf"></i> Cartão
                        </a>
                        <a href="{{ route('usuarios.edit',['id'=>$usuario->id]) }}" title="Editar Cadastro" class="btn btn-light mr-1">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                          </svg>
                        </a>
                         
                        <form action="{{ route('usuarios-destroy',['id'=>$usuario->id]) }}" id="frm-{{$usuario->id}}" method="POST">
                          @csrf
                          @method('DELETE')
                          <button type="button" data-del="true" data-id="{{$usuario->id}}" name="button" title="Excluir" class="btn btn-light">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                              <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                              <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                              </svg>
                          </button>
                        </form>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
    </div>
  </div>
  @stop
  @section('css')
    <!--  <link rel="stylesheet" href="/css/admin_custom.css">-->
  @stop
  @section('plugins.Datatables', true)

  @section('js')
      <script src=" {{url('/')}}/js/lib.js"></script>
      <script>
          function confirmDeleteUsuario(obj){
              var id = obj.data('id');
              if(window.confirm('DESEJA MESMO EXCLUIR?')){
                $('#frm-'+id).submit();
              }
              //alert('modal agora');
          }
          $(function(){
              $('.dataTable').DataTable({
                "paging":   false,
                stateSave: true
              });
              $('.print-card').on('click',function(e){
                  openPageLink(e,$(this).attr('href'),$('#painel-ano').val());
              });
              $('.relatorios-create').on('click',function(e){
                  e.preventDefault();
                  url = $(this).attr('href');
                  //alert( window.location.href);
                  window.location = url+'?redirect='+btoa(window.location.href);
                  //openPageLink(e,$(this).attr('href'),$('#painel-ano').val());
              });
              $('#open-all-cards').on('click',function(e){
                  openPageLink(e,$(this).attr('href'),$('#painel-ano').val());
              });
              $('[data-del="true"]').on('click',function(e){
                e.preventDefault();
                confirmDeleteUsuario($(this));
              });
              $('#grupos').on('change',function(){
                  var grupo = $(this).val();
                  //alert(grupo);
                  $('#frm-filtar').submit();
              });
              $('.btn').on('click', function () {
                $(this).find('input').removeAttr('checked');
                $(this).removeClass('active');
              });
              $('#imprimir-lista-pub').on('click', function () {
                var url = $('#frm-filtar').serialize();
                abrirjanelaPadrao("{{route('publicadores.lista')}}?"+url);
              });
              if($(window).width()>778)
                $('a.nav-link').click();
          
          });
      </script>
  @stop
