@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <!--<h1>{{$titulo}}</h1>-->
@stop

@section('content')
  <div class="row">
    <div class="mens">

    </div>
    <div class="col-md-12 mb-5">
      <input type="hidden" name="raiz" value="{{url('/')}}">
      <form class="" id="frm-relatorio" action="" method="post">
        <div class="card card-primary card-outline">
            <div class="card-header">
              <h4>{{$titulo}}</h4>
            </div>
            <div class="card-body box-profile">
              <div class="mens">
              </div>
              @if(isset($dados))
                    <table class="table table-bordered table-hover">
                      <thead>

                      </thead>
                      <tbody>
                        <tr>
                          <th colspan="2"> <span class="label label-default">Nome: </span> {{$dadosPub['nome']}}</th>
                        </tr>
                        <tr>
                          <th colspan="2"><span class="label label-default">Mês: </span>{{$mesExt}} - {{$ano}}</th>
                        </tr>
                        @foreach ($dados as $k1 => $v1)
                          @if($v1['type']=='hidden')
                            <input class="form-control text-center" type="{{$v1['type']}}" name="{{$v1['campo']}}" value="{{$v1['valor']}}">
                          @else
                          <tr>
                            @if($v1['campo']=='obs')
                              <td colspan="2">{{$v1['label']}}<br><input onclick="$(this).select();" class="form-control text-left" type="{{$v1['type']}}" name="{{$v1['campo']}}" value="{{$v1['valor']}}"></td>

                            @else
                              <td width="80%">{{$v1['label']}}</td>
                              <td width="20%"><input onclick="$(this).select();" class="form-control text-center" type="{{$v1['type']}}" name="{{$v1['campo']}}" value="{{$v1['valor']}}"></td>
                            @endif
                          </tr>
                          @endif
                        @endforeach
                      </tbody>
                    </table>

              @endif
            </div>
            <div class="col-md-12 div-salvar d-print-none">
              <div class="form-group">
                <!--
                <button type="button" onclick="window.close()" class="btn btn-light" name="button">
                  <i class="fa fa-chevron-left"></i> Voltar
                </button>-->
                <a href="@if(isset($_GET['redirect']) && !empty($_GET['redirect'])) {{ base64_decode($_GET['redirect'])}} @else{{route('usuarios.index')}}@endif" class="btn btn-light"><i class="fa fa-chevron-left"></i> Voltar</a>
                @if(isset($dadosPub['id']))
                <a href="{{ route('usuarios.cartao',['id'=>$dadosPub['id']]) }}" title="Cartão do publicador" class="btn btn-light print-card">
                    <i class="fa fa-file-pdf"></i> Cartão
                 </a>
                 @endif
                <button type="submit" class="btn btn-primary">Salvar <i class="fa fa-chevron-right"></i></button>
              </div>
            </div>
        </div>
        @csrf
      </form>
    </div>
  </div>
@stop

@section('css')
    <link rel="stylesheet" href=" {{url('/')}}/css/lib.css">
@stop

@section('js')
    <script src=" {{url('/')}}/js/lib.js"></script>
    <script>
        function relCalback(data){
          document.location.reload(true)
        }
        function submitRelatorioPub(frm){
          var ajaxurl = "{{route('relatorios.store')}}";
          $.ajax({
              type: 'POST',
              url: ajaxurl,
              data: frm.serialize(),
              dataType: 'json',
              success: function (data) {
                if(data.exec){
                  //cancelEdit(id);
   							 if(data.mens){
   								 lib_formatMensagem('.mens',data.mens,'success');
   							 }
                 //window.opener = relCalback(data);
                 //window.close();
                 @if(isset($_GET['redirect']))
                   window.location = atob('{{$_GET['redirect']}}');
                 @endif
   						 }else{
   							 lib_formatMensagem('.mens',data.mens,'danger');
   						 }
              },
              error: function (data) {
                  console.log(data);
              }
          });
        }
        $(function(){
          var frm = $('#frm-relatorio');
          frm.submit(function(e){
            e.preventDefault();
            submitRelatorioPub(frm);
          });
        });
    </script>
@stop
