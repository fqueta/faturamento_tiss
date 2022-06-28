@extends('adminlte::page')

@section('title', 'Fechamento de Guias')

@section('content_header')
    <h3>{{$titulo}}</h3>
@stop
@section('content')
  <!--<p>Selecione os publicadores do seu familia para enviar o relatorio para o secretário</p>-->
  <style media="print">
      #DataTables_Table_0_wrapper .row:first-child{
          display: none;
      }
      .table td{
          padding: 0%;
      }
      .table thead th{
          padding: 0%;
      }
      #lista .card-body{
          padding: 0%;
      }
  </style>
  <div class="row">
    {{-- @include($view.'.config_exibe') --}}
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h4 class="card-title">Localizar Guias</h4>
            </div>
            <div class="card-body">
                <form action="" method="get">
                    <div class="row">
                    @if (isset($config['campos_busca']))
                        @foreach ($config['campos_busca'] as $k=>$v)
                            {{App\Qlib\Qlib::qForm([
                                'type'=>@$v['type'],
                                'campo'=>@'filter['.$k.']',
                                'label'=>$v['label'],
                                'id'=>@$v['id'],
                                'placeholder'=>@$v['placeholder'],
                                'ac'=>$config['ac'],
                                'value'=>@$_GET['filter'][$k],
                                'tam'=>@$v['tam'],
                                'event'=>@$v['event'],
                                'checked'=>@$value[$k],
                                'selected'=>@$_GET['filter'][$k],
                                'arr_opc'=>@$v['arr_opc'],
                                'option_select'=>@$v['option_select'],
                                'class'=>@$v['class'],
                                'class_div'=>@$v['class_div'],
                                'rows'=>@$v['rows'],
                                'cols'=>@$v['cols'],
                                'data_selector'=>@$v['data_selector'],
                                'script'=>@$v['script'],
                                'valor_padrao'=>@$v['valor_padrao'],
                                'title'=>@$v['title'],
                                'dados'=>@$v['dados'],
                            ])}}
                        @endforeach
                    @endif
                        <div class="col-md-2 pt-4">
                            <button type="submit" class="btn btn-secondary mt-1 btn-block">{{__('Pesquisar Guias')}}</button>
                        </div>
                        <div class="col-md-2 pt-4">
                            <button type="button" id="btn-fechar-lote" class="btn btn-primary mt-1 btn-block">{{__('Fechar Lote')}}</button>
                        </div>
                    </div>
                </form>
          </div>
        </div>
    </div>
    <div class="col-md-12 mens">
    </div>
    {{-- @can('is_admin')
    <div class="col-md-12 d-print-none">
      <div class="row pl-2 pr-2">
          <div class="col-md-3 info-box mb-3">
              <span class="info-box-icon bg-default elevation-1"><i class="fas fa-users"></i></span>
              <div class="info-box-content">
                  <span class="info-box-text">Total de Famílias</span>
                  <span class="info-box-number">{{ @$familia_totais->todos }}</span>
              </div>
          </div>
          <div class="col-md-3 info-box mb-3">
              <span class="info-box-icon bg-default elevation-1"><i class="fas fa-calendar"></i></i></span>
              <div class="info-box-content">
                  <span class="info-box-text">Cadastros deste Mês</span>
                  <span class="info-box-number">{{ @$familia_totais->esteMes }}</span>
              </div>
          </div>
          <div class="col-md-3 info-box mb-3">
              <span class="info-box-icon bg-default elevation-1"><i class="fas fa-male"></i></span>
              <div class="info-box-content">
                  <span class="info-box-text">Famílias com Idosos</span>
                  <span class="info-box-number">{{ @$familia_totais->idoso }}</span>
              </div>
          </div>
          <div class="col-md-3 info-box mb-3">
              <span class="info-box-icon bg-default elevation-1"><i class="fas fa-child"></i></i></span>
              <div class="info-box-content">
                  <span class="info-box-text">Crianças e adolescentes</span>
                  <span class="info-box-number">{{ @$familia_totais->criancas }}</span>
              </div>
          </div>
      </div>
    </div>
@endcan --}}
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
                    {{ $titulo }}
                @endif
            </h4>

            @can('is_admin')
            <div class="card-tools d-flex d-print-none">
                    {{-- @include('familias.dropdow_actions')
                    @include('qlib.dropdow_acaomassa') --}}
            </div>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                @include('faturamento.table_faturamento')
            </div>
        </div>
        <div class="card-footer d-print-none">
            <div class="table-responsive">
                @if (@$config['limit']!='todos')
                {{ $guias->appends($_GET)->links() }}
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
        function fecharLote(objs){
            if(objs=='' || typeof objs=='undefinid'){
                var msg = '<div class="row"><div id="modal-m" class="col-md-12 text-center"><p>É necessário selecionar uma guia para fechar um lote.</p></div></div>';
                alerta(msg,'modal-faturamento-erro','Alerta','',true);
                return
            }
            var btna = '<p><button type="button" onclick="executarFechamento(\''+objs+'\');" class="btn btn-primary">Fechar este lote</button></p>';
            var msg = '<div class="row"><div id="modal-m" class="col-md-12 text-center"><p>Para fechar o lote informe um mês de referência e o tipo de ordenação.</p></div></div>';
            alerta(msg,'modal-faturamento','Atenção','',true);
            $(btna).insertAfter('#modal-faturamento .modal-footer button');

            //console.log(objs);


        }
        function executarFechamento(ids){
            getAjax({
                url:"/faturamentos/gerar-lote/"+ids,
            },function(res){
                $('#preload').fadeOut("fast");
                var btna = '';
                var msg = '<div class="row"><div id="modal-m" class="col-md-12 text-center"><p>'+res.mes+'</p></div></div>';
                alerta(msg,'modal-mens','Alerta','',true);
                if(res.exec){
                    btna = '<p><a href="'+res.geraGuia.link+'" class="btn btn-secondary" target="_BLANK" download>Baixar Arquivo XML de Lote</a> <a href="{{route('faturamento.gerenciar')}}" class="btn btn-primary">Gerenciar Lote</a></p>';
                    $(btna).insertAfter('#modal-mens .modal-footer button');
                    $('#modal-faturamento').modal('hide');
                    $('#lista-guias tbody').html('');
                    //$('#modal-mens [data-dismiss="modal"]').on('click',function(){

                    //});
                }

                /*if(m=res.value.matricula){
                    $('[name="matricula"]').val(m);
                    $('#txt-matricula').html(m);
                }else{
                    $('[name="matricula"]').val('');
                    $('#txt-matricula').html('');
                }*/
            });
        }
        $(function(){
            $('[exportar-filter]').on('click',function(e){
                e.preventDefault();
                var urlAtual = window.location.href;
                var d = urlAtual.split('?');
                url = '';
                if(d[1]){
                    url = $(this).attr('href');
                    url = url+'?'+d[1];
                }else{
                    url = $(this).attr('href');
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
            $('[name="filter[cpf]"],[name="filter[cpf_conjuge]"]').inputmask('999.999.999-99');
            $(' [order="true"] ').on('click',function(){
                var val = $(this).val();
                var url = lib_trataAddUrl('order',val);
                window.location = url;
            });
            $('#btn-fechar-lote').on('click',function(){
                var selecionados = coleta_checked($('.table .checkbox:checked'));
                fecharLote(selecionados);
                //janelaEtapaMass(selecionados);
            });
            $('#auto-proprietario').autocomplete({
                source: '{{route('beneficiarios.index')}}?ajax=s',
                select: function (event, ui) {
                    if(ui.item.id){
                        $('[name="filter[id_beneficiario]"]').val(ui.item.id);
                        $('#frm-consulta').submit();
                    }
                    //lib_listarCadastro(ui.item,$(this));
                },
            });
            $('#auto-proprietario').on('click',function(){
                $(this).val('');
            });
        });
    </script>
  @stop
