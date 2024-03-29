@extends('adminlte::page')

@section('title', 'Fechamento de Guias')

@section('content_header')
    <h3>{{$titulo}}</h3>
@stop
@section('content')
    @include('admin.partes.header')
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
                <form action="" id="frm-busca" method="get">
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
                                'tam'=>'3',
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
                        @if (isset($config['campos_data']))
                            @foreach ($config['campos_data'] as $k=>$v)
                                {{App\Qlib\Qlib::qForm([
                                    'type'=>@$v['type'],
                                    'campo'=>@$k,
                                    'label'=>$v['label'],
                                    'id'=>@$v['id'],
                                    'placeholder'=>@$v['placeholder'],
                                    'ac'=>$config['ac'],
                                    'value'=>@$v['selected'],
                                    'tam'=>'3',
                                    'event'=>@$v['event'],
                                    'checked'=>@$v['selected'],
                                    'selected'=>@$v['selected'],
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
                    @endif
                        <div class="col-md-2">
                            <button type="submit" id="pesquisar" class="btn btn-primary mt-1 btn-block">{{__('Pesquisar')}}</button>
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
                @include('faturamento.table_lotes')

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
            var checkbox = verificaCheckbox('.table .checkbox:checked');
            if(checkbox)
                $('#facharLote').modal('show');

        }
        function executarFechamento(){
            var ids = verificaCheckbox('.table .checkbox:checked');
            var d = $('#referencia').serialize();
            getAjax({
                url:"/faturamentos/gerar-lote/"+ids,
                data: d,
                type: 'POST'
            },function(res){
                $('#preload').fadeOut("fast");
                var btna = '';
                var msg = '<div class="row"><div id="modal-m" class="col-md-12 text-center"><p>'+res.mes+'</p></div></div>';
                alerta(msg,'modal-mens','Alerta','',true);
                if(res.exec){
                    btna = '<p><a href="'+res.geraGuia.link+'" class="btn btn-secondary" target="_BLANK" download>Baixar Arquivo XML de Lote</a> <a href="{{route('faturamento.gerenciar')}}" class="btn btn-primary">Gerenciar Lote</a></p>';
                    $(btna).insertAfter('#modal-mens .modal-footer button');
                    $('#facharLote').modal('hide');
                    $('#lista-guias tbody').html('');
                    //$('#modal-mens [data-dismiss="modal"]').on('click',function(){

                    //});
                }
            });
        }
        function atualizarFechamento(id_lote,ids){
            if(typeof id_lote == undefined){
                id_lote==null;
            }
            if(typeof ids == undefined){
                var msg = '<div class="row"><div id="modal-m" class="col-md-12 text-center"><p>Guias Inválidas entre em contato com o suporte</p></div></div>';
                alerta(msg,'modal-mens','Atenção','',true);
                return;
            }
            //var ids = verificaCheckbox('.table .checkbox:checked');
            var d = 'id_lote='+id_lote+'&acao=alt';
            getAjax({
                url:"/faturamentos/gerar-lote/"+ids,
                data: d,
                type: 'POST'
            },function(res){
                $('#preload').fadeOut("fast");
                var btna = '';
                //var msg = '<div class="row"><div id="modal-m" class="col-md-12 text-center"><p>'+res.mes+'</p></div></div>';
                //alerta(msg,'modal-mens','Alerta','',true);
                if(res.exec && res.geraGuia.link){
                    download(res.geraGuia.link);
                }
            });
        }
        function gerenciarLote(id_lote,ids){
            if(typeof id_lote == undefined){
                id_lote==null;
            }
            if(typeof ids == undefined){
                var msg = '<div class="row"><div id="modal-m" class="col-md-12 text-center"><p>Guias Inválidas entre em contato com o suporte</p></div></div>';
                alerta(msg,'modal-mens','Atenção','',true);
                return;
            }
            //var ids = verificaCheckbox('.table .checkbox:checked');
            var d = 'id_lote='+id_lote+'&acao=alt';
            getAjax({
                url:"/faturamentos/listar-lote/"+ids,
                data: d,
                type: 'GET'
            },function(res){
                $('#preload').fadeOut("fast");
                var btnaremove = document.createElement('button');
                btnaremove.innerHTML = 'Remover Guia';
                btnaremove.classList.add("btn","btn-primary");
                btnaremove.setAttribute("onclick","lib_removerGuiasLote('"+id_lote+"');");
                // btnaremove.classList;
                var msg = '<div class="row"><div id="modal-m" class="col-md-12 text-center"><h4>Guias do lote</h4></div></div>';
                msg += lib_listaGuiasLote(res.lista);
                alerta(msg,'modal-ger-lote','Gerenciar Lote '+id_lote,'modal-xl',true);
                $(btnaremove).insertAfter('#modal-ger-lote .modal-footer button');
                // console.log(res);
                // if(res.exec && res.geraGuia.link){
                //     download(res.geraGuia.link);
                // }
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
            $('[type="submit"]').on('click',function(e){
                e.preventDefault();
                $('#frm-busca').submit();
            });
        });
    </script>
  @stop
