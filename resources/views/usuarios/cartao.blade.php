@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>{{$titulo}}</h1>
@stop

@section('content')
<style media="screen">
  .table td{
    padding: 0 .75rem;
  }
</style>
<style media="print">
  .table td,.table th{
    padding: 0 .75rem;
    border:1px #333 solid;
  }
  .mb-20{
    margin-bottom: 25px;
  }
  .mb-200{
    margin-bottom: 334px;
    page-break-before: always;
  }
</style>
  <div class="row table-responsive">
    <div class="col-md-12 text-right mb-3 d-print-none">
      <div class="mens">
      </div>
      <div class="col-md-4">
        <input type="hidden" name="routAjax_cad" value="{{route('relatorios.store')}}">
        <input type="hidden" name="routAjax_alt" value="{{route('relatorios.update')}}">
        <input type="hidden" name="routAjax_del" value="{{route('relatorios.destroy')}}">
        <form method="GET" id="ano-form" action="{{ $cartao['url'] }}">
          <div class="input-group">
            <input type="number" onchange="document.querySelector('#ano-form').submit()" class="form-control" maxlength="4" name="ano" value="{{ $cartao['ano_servico'] }}" placeholder="Ano de serviço">
            <span class="input-group-btn">
              <button class="btn btn-secondary" type="submit">Ok</button>
            </span>
          </div>

          @csrf
        </form>
      </div>
    </div>

    <div class="row ml-0 mr-0 mb-3">

          <div class="col-md-12 text-center">
            <h5>REGISTRO DE PUBLICADOR DE CONGREGAÇÃO</h5>
          </div>
          <div class="col-md-12">
            <span class="label label-default">Nome:</span> <b> {{ $cartao['dados']->nome }}</b>
          </div>
          <div class="col-8">
            <span class="label label-default">Data de nascimento:</span> <b> {{ $cartao['dados']->data_nasci }}</b>
          </div>
          <div class="col-4 text-right">
            <span class="label label-default">Sexo:</span> <b> {{ $cartao['dados']->sexo }}</b>
          </div>
          <div class="col-8">
            <span class="label label-default">Data de batismo:</span> <b> {{ $cartao['dados']->data_batismo }}</b>
          </div>
          <div class="col-4 text-right">
             <b> {{ $cartao['dados']->tipo }}</b>
          </div>
          <div class="col-12 text-right">
            <b class="mr-3">{{ $cartao['dados']->fun }}</b> <b>{{ $cartao['dados']->pioneiro }}</b>
          </div>
          <div class="col-md-12 table-responsive">
              <table class="table table-bordered table-hover" id="pub-{{$cartao['dados']->id}}">
                <thead>
                  <tr>
                    <th class="text-center">
                        Ano de serviço<br>{{ $cartao['ano_servico'] }}
                    </th>
                    <th class="text-center">
                        Publicações
                    </th>
                    <th class="text-center">
                        Videos mostrados
                    </th>
                    <th class="text-center">
                        Horas
                    </th>
                    <th class="text-center">
                        Revisitas
                    </th>
                    <th class="text-center">
                        Estudos biblicos
                    </th>
                    <th class="text-center" style="width:30%">
                        Observações
                    </th>
                  </tr>
                </thead>
                <tbody>

                  @foreach($cartao['atividade'] As $key=>$relatorio)
                  <tr id="{{$cartao['dados']->id.'_'.$relatorio->mes}}" class="@if(isset($relatorio->class)){{$relatorio->class}}@endif" title="De dois cliques par editar" ondblclick="gerenteAtividade($(this),'{{$relatorio->ac}}')" style="cursor:pointer">
                    <td class="text-left">
                      <input type="hidden" name="var_cartao" value="{{base64_encode(json_encode($cartao))}}">
                      <input type="hidden" name="ano" value="{{ $cartao['Schema'][$key]['ano_servico'] }}">
                      <input type="hidden" name="mes" value="{{ $key }}">
                      <input type="hidden" name="id_publicador" value="{{ $cartao['dados']->id }}">
                      <input type="hidden" name="id_grupo" value="{{ $cartao['dados']->id_grupo }}">
                      <input type="hidden" name="ac" value="{{ $relatorio->ac }}">
                      <input type="hidden" name="id" value="{{ $relatorio->id }}">
                      {{ $cartao['Schema'][$key]['mes'] }}
                    </td>
                    <td class="text-center">{{ $relatorio->publicacao }}</td>
                    <td class="text-center">{{ $relatorio->video }}</td>
                    <td class="text-center">{{ $relatorio->hora }}</td>
                    <td class="text-center">{{ $relatorio->revisita }}</td>
                    <td class="text-center">{{ $relatorio->estudo }}</td>
                    <td class="text-center">{{ $relatorio->obs }}</td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                  <tr class="tf-1">
                    <th>Total</th>
                    @foreach($cartao['totais'] As $k=>$total)
                      <th class="text-center">{{ $total }}</th>
                    @endforeach
                    <th>&nbsp;</th>
                  </tr>
                  <tr class="tf-2">
                    <th>Média</th>
                    @foreach($cartao['medias'] As $k=>$m)
                      <th class="text-center">{{ $m }}</th>
                    @endforeach
                    <th>&nbsp;</th>
                  </tr>
                </tfoot>
              </table>
          </div>
    </div>
    @if(isset($cartao['parent']))
      @foreach($cartao['parent'] As $kp=>$parent)
      <div class="row ml-0 mr-0 {{ $parent['page']['mb'] }}">
            <div class="col-md-12">
              <hr>
            </div>
            <div class="col-md-12 text-center">
              <h5>REGISTRO DE PUBLICADOR DE CONGREGAÇÃO</h5>
            </div>
            <div class="col-md-12">
              <span class="label label-default">Nome:</span> <b> {{ $parent['dados']->nome }}</b>
            </div>
            <div class="col-8">
              <span class="label label-default">Data de nascimento:</span> <b> {{ $parent['dados']->data_nasci }}</b>
            </div>
            <div class="col-4 text-right">
              <span class="label label-default">Sexo:</span> <b> {{ $parent['dados']->sexo }}</b>
            </div>
            <div class="col-8">
              <span class="label label-default">Data de batismo:</span> <b> {{ $parent['dados']->data_batismo }}</b>
            </div>
            <div class="col-4 text-right">
               <b> {{ $parent['dados']->tipo }}</b>
            </div>
            <div class="col-12 text-right">
              <b class="mr-3">{{ $parent['dados']->fun }}</b> <b>{{ $parent['dados']->pioneiro }}</b>
            </div>
            <div class="col-md-12 table-responsive">
                <table class="table table-bordered table-hover" id="pub-{{$parent['dados']->id}}">
                  <thead>
                    <tr>
                      <th class="text-center">
                          Ano de serviço<br>{{ $parent['ano_servico'] }}
                      </th>
                      <th class="text-center">
                          Publicações
                      </th>
                      <th class="text-center">
                          Videos mostrados
                      </th>
                      <th class="text-center">
                          Horas
                      </th>
                      <th class="text-center">
                          Revisitas
                      </th>
                      <th class="text-center">
                          Estudos biblicos
                      </th>
                      <th class="text-center" style="width:30%">
                          Observações
                      </th>
                    </tr>
                  </thead>
                  <tbody>

                    @foreach($parent['atividade'] As $key=>$relatorio)
                    <tr id="{{$parent['dados']->id.'_'.$relatorio->mes}}" title="De dois cliques par editar" ondblclick="gerenteAtividade($(this),'{{$relatorio->ac}}')" style="cursor:pointer">
                      <td class="text-left"><input type="hidden" name="var_cartao" value="{{base64_encode(json_encode($cartao))}}">
                        <input type="hidden" name="var_cartao" value="{{base64_encode(json_encode($parent))}}">
                        <input type="hidden" name="ano" value="{{ $parent['Schema'][$key]['ano_servico'] }}">
                        <input type="hidden" name="mes" value="{{ $key }}">
                        <input type="hidden" name="id_publicador" value="{{ $parent['dados']->id }}">
                        <input type="hidden" name="id_grupo" value="{{ $cartao['dados']->id_grupo }}">
                        <input type="hidden" name="ac" value="{{ $relatorio->ac }}">
                        <input type="hidden" name="id" value="{{ $relatorio->id }}">
                        {{ $parent['Schema'][$key]['mes'] }}
                      </td>
                      <td class="text-center">{{ $relatorio->publicacao }}</td>
                      <td class="text-center">{{ $relatorio->video }}</td>
                      <td class="text-center">{{ $relatorio->hora }}</td>
                      <td class="text-center">{{ $relatorio->revisita }}</td>
                      <td class="text-center">{{ $relatorio->estudo }}</td>
                      <td class="text-center">{{ $relatorio->obs }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr class="tf-1">
                      <th>Total</th>
                      @foreach($parent['totais'] As $kt=>$total)
                        <th class="text-center">{{ $total }}</th>
                      @endforeach
                      <th>&nbsp;</th>
                    </tr>
                    <tr class="tf-2">
                      <th>Média</th>
                      @foreach($parent['medias'] As $km=>$m)
                        <th class="text-center">{{ $m }}</th>
                      @endforeach
                      <th>&nbsp;</th>
                    </tr>
                  </tfoot>
                </table>
            </div>
      </div>
      @endforeach
    @endif
    <div class="col-12 mt-4 d-print-none div-salvar">
      <a href=" {{route('usuarios.index')}} " title="Voltar" class="btn btn-secondary btn-voltar">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
          </svg>
          Voltar
      </a>
      <button type="button" title="Imprimir" onclick="window.print();" class="btn btn-light">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
          <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
          <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
        </svg>
      </button>
      <button type="button" id="registar-compilar" name="registrar" class="btn btn-primary">  Registrar</button>
    </div>
  </div>

  @stop

  @section('css')
      <link rel="stylesheet" href=" {{url('/')}}/css/lib.css">
  @stop

  @section('js')
  <script src=" {{url('/')}}/js/lib.js"></script>
  <script>
      $(function(){
          if($(window).width()>778)
            $('a.nav-link').click();
          $('.btn-voltar').on('click',function(e){
              window.close();
              //openPageLink(e,$(this).attr('href'),$('[name="ano"]').val());
          });
          function relCalback(data){
            document.location.reload(true)
          }
          function registrarCompilar(frm){
            $.ajaxSetup({
                   headers: {
                       'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                   }
            });
            var ajaxurl = "{{route('relatorios.registrar',['id'=>$cartao['dados']->id])}}";

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                dataType: 'json',
                success: function (data) {
                  if(data.exec){
                    //cancelEdit(id);
     							 if(data.mens){
     								 lib_formatMensagem('.mens',data.mens,'success');
     							 }
                   window.opener = relCalback(data);
                   //window.close();
     						 }else{
     							 lib_formatMensagem('.mens',data.mens,'danger');
     						 }
                 
                },
                error: function (data) {
                    console.log(data);
                }
            });
          }
          $('#registar-compilar').on('click',function(e){
              registrarCompilar();

          });
      });
  </script>
  @stop
