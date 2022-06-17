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
  <div class="row">
    <div class="col-md-12 text-right mb-3 d-print-none">
      <div class="col-md-4">

        <form method="GET" id="ano-form" action="{{ $cards[0]['url'] }}">
          <div class="input-group">
            <input type="number" onchange="document.querySelector('#ano-form').submit()" class="form-control" maxlength="4" name="ano" value="{{ $cards[0]['ano_servico'] }}" placeholder="Ano de serviço">
            <span class="input-group-btn">
              <button class="btn btn-secondary" type="submit">Ok</button>
            </span>
          </div>

          @csrf
        </form>
      </div>
    </div>

    @foreach($cards As $kca=>$cartao)

    <div class="row  table-responsive ml-0 mr-0 {{ $cartao['page']['mb'] }}">
        <table class="" style="width:100%">
          <thead>
            <tr>
              <th colspan="2">
                <div class="col-md-12 text-center">
                  <h5>REGISTRO DE PUBLICADOR DE CONGREGAÇÃO</h5>
                </div>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td colspan="2">
                <div class="col-md-12">
                  <span class="label label-default">Nome:</span> <b> {{ $cartao['dados']->nome }}</b>
                </div>
              </td>
            </tr>
            <tr>
              <td style="width:50%">
                  <span class="label label-default">Data de nascimento:</span> <b class="d-flex"> {{ $cartao['dados']->data_nasci }}</b>
              </td>
              <td style="width:50%" class="text-right">
                <span class="label label-default">Sexo:</span> <b> {{ $cartao['dados']->sexo }}</b>
              </td>
            </tr>
            <tr>
              <td>
                  <span class="label label-default">Data de batismo:</span> <b> {{ $cartao['dados']->data_batismo }}</b>
              </td>
              <td class="text-right">
                     <b> {{ $cartao['dados']->tipo }}</b>
              </td>

            </tr>
            <tr>
              <td colspan="2">
                  <div class="col-12 text-right">
                    <b class="mr-3">{{ $cartao['dados']->fun }}</b> <b>{{ $cartao['dados']->pioneiro }}</b>
                  </div>
              </td>
            </tr>
          </tbody>
        </table>

              <table class="table table-bordered table-striped table-hover">
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
                  <tr>
                    <td class="text-left">{{ $cartao['Schema'][$key]['mes'] }}</td>
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
                  <tr>
                    <th>Total</th>
                    @foreach($cartao['totais'] As $k=>$total)
                      <th class="text-center">{{ $total }}</th>
                    @endforeach
                    <th>&nbsp;</th>
                  </tr>
                  <tr>
                    <th>Média</th>
                    @foreach($cartao['medias'] As $k=>$m)
                      <th class="text-center">{{ $m }}</th>
                    @endforeach
                    <th>&nbsp;</th>
                  </tr>
                </tfoot>
              </table>
    </div>
    @endforeach
    <div class="col-12 mt-4 d-print-none div-salvar">
      <a href=" {{route('usuarios.index')}} " title="Voltar" id="valor-pagina" class="btn btn-secondary">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-short" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5z"/>
          </svg>
          Voltar
      </a>
      <button type="button" title="Imprimir ou converter em PDF" onclick="window.print();" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
          <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
          <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
        </svg>
      </button>
    </div>
  </div>

  @stop

  @section('css')
      <link rel="stylesheet" href=" {{url('/')}}/css/lib.css">
  @stop

  @section('js')
      <script type="text/javascript" src=" {{url('/')}}/js/lib.js"></script>
      <script>
        $(function(){
            if($(window).width()>778)
              $('a.nav-link').click();

            $('#valor-pagina').on('click',function(e){
              window.close();
               //openPageLink(e,$(this).attr('href'),$('[name="ano"]').val());
            });
        });
       </script>
  @stop
