@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h3>{{$titulo}}</h3>
@stop
@section('content')
  <!--<p>Selecione os publicadores do seu familia para enviar o relatorio para o secretário</p>-->
  <div class="row">
      @include('qlib.config_exibe')
      <div class="col-md-12 mens">
    </div>
    @can('is_admin')
    @include('qlib.partes_html',[
        'config'=>[
            'parte'=>'resumo_index',
            'resumo'=>$config['resumo'],
            ]
    ])
    @endcan
    <div class="col-md-12" id="lista">
      <div class="card">
        <div class="card-header">
            <h4 class="card-title">
                @if (!empty($arr_titulo))
                    Lista de:

                    @foreach ($arr_titulo as $k=>$pTitulo)
                        <label for=""> Todo com {{ $k }}</label> = {{ $pTitulo }}, e
                    @endforeach
                @else
                    {{ $titulo_tabela }}
                @endif
            </h4>
            @can('is_admin')
            <div class="card-tools d-flex d-print-none">
                @include('qlib.dropdow_actions')
                @include('qlib.dropdow_acaomassa')
            </div>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                {{-- {{
                    App\Qlib\Qlib::listaTabela([
                    'campos_tabela'=>$campos_tabela,
                    'config'=>$config,
                    'dados'=>$dados,
                    'routa'=>$routa,
                ])}} --}}
                @include('guias.listaTabela')
            </div>
        </div>
        <div class="card-footer d-print-none">
            <div class="table-responsive">
                @if ($config['limit']!='todos')
                {{ $dados->appends($_GET)->links() }}
                @endif
            </div>
        </div>
      </div>
    </div>
  </div>
  @stop

  @section('css')
    @include('qlib.csslib')
  @stop

  @section('js')
    @include('qlib.jslib')

    <script>
        $(function(){
            $('[exportar-filter]').on('click',function(e){
                e.preventDefault();
                var urlAtual = window.location.href;
                var d = urlAtual.split('?');
                url = '';
                if(d[1]){
                    url = $(this).attr('href');
                    url = url+'?'+d[1];
                }
                if(url)
                    abrirjanelaPadrao(url);
                    //window.open(url, "_blank", "toolbar=1, scrollbars=1, resizable=1, width=" + 1015 + ", height=" + 800);
                //confirmDelete($(this));
            });
            $('[data-del="true"]').on('click',function(e){
                e.preventDefault();
                confirmDelete($(this));
            });
            $('[btn-print]').on('click',function(e){
                e.preventDefault();
                var href = $(this).attr('href');
                abrirjanelaPadrao(href);
            });
            $('[name="filter[cpf]"],[name="filter[cpf_conjuge]"]').inputmask('999.999.999-99');
            $(' [order="true"] ').on('click',function(){
                var val = $(this).val();
                var url = lib_trataAddUrl('order',val);
                window.location = url;
            });
        });
    </script>
  @stop
