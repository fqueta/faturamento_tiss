
<style>
.celula_item {
    font-size: 12px;
    color: #666;
    border: 1px solid gray;
    height: 25px;
    background-color: #ffffff;
}
.font-tab{
    font-weight: bold;
    color: blue;
}
</style>
    <div class="card">
        <div class="card-body pl-2 pr-2">
            <div class="row">
                <div class="col-md-12 px-0">
                        <div class="list-despesass" id="demo_despesas">
                            @php
                                $arr_list = !empty($_REQUEST['dados']['config'])?$_REQUEST['dados']['config']: false;
                                if($arr_list){
                                    $arr_list = App\Qlib\Qlib::lib_json_array($arr_list);
                                }
                                //dd($arr_list);
                                $i=0;
                            @endphp
                            @if (isset($arr_list['despesas']) && is_array($arr_list['despesas']))
                                @php
                                    $arr_tipo = App\Qlib\Qlib::sql_array("SELECT value,nome FROM tags WHERE ativo='s' AND pai='7'",'nome','value');
                                @endphp
                                @foreach ($arr_list['despesas'] as $k=>$vt)
                                    @php
                                        $i++;
                                        $inpu = '<input type="hidden" name="config[despesas]['.$k.'][item]" value="'.$k.'"/>';
                                        $arr_campos = ['tipo','data','hora1','hora2','anvisa','fabricante','autorizacao','tabela','codigo','descricao','quantidade','fator','valor_unitario','valor_total','unidade'];
                                        if(is_array($arr_campos)){
                                            foreach ($arr_campos as $key => $vat) {
                                                $inpu .= '<input type="hidden" class="tipo-'.$vat.'-'.@$vt['tipo'].'" name="config[despesas]['.$k.']['.$vat.']" value="'.@$vt[$vat].'"/>';
                                            }
                                        }
                                    @endphp
                                    <table id="tr_despesa_linha_{{@$k}}" width="100%" class="mt-1">
                                        <tbody>
                                            <tr>
                                                <td class="celula_item" width="125">
                                                    <font class="font-tab" style="font-size:9px;">CD</font>
                                                    <div align="left">{{@$arr_tipo[@$vt['tipo']]}}</div>
                                                </td>
                                                <td class="celula_item" width="75">
                                                    <font class="font-tab" style="font-size:9px;">Data</font>
                                                    <div align="right">{!!@$inpu!!}{{App\Qlib\Qlib::dataExibe(@$vt['data'])}}</div>
                                                </td>
                                                <td class="celula_item" width="50">
                                                    <font class="font-tab" style="font-size:9px;">Hora Inicial</font>
                                                    <div align="right">{{@$vt['hora1']}}</div>
                                                </td>
                                                <td class="celula_item" width="50">
                                                    <font class="font-tab" style="font-size:9px;">Hora Final</font>
                                                    <div align="right">{{@$vt['hora2']}}</div>
                                                </td>
                                                <td class="celula_item" width="35">
                                                    <font class="font-tab" style="font-size:9px;">Tabela</font>
                                                    <div align="right">{{@$vt['tabela']}}</div>
                                                </td>
                                                <td class="celula_item">
                                                    <font class="font-tab" style="font-size:9px;">Código</font>
                                                    <div align="right">{{@$vt['codigo']}}</div>
                                                </td>
                                                <td class="celula_item" width="35">
                                                    <font class="font-tab" style="font-size:9px;">Qtde</font>
                                                    <div align="right">{{@$vt['quantidade']}}</div>
                                                </td>
                                                <td class="celula_item" width="70">
                                                    <font class="font-tab" style="font-size:9px;">Fator Red/Acres.</font>
                                                    <div align="right">{{@$vt['fator']}}</div>
                                                </td>
                                                <td class="celula_item" width="55">
                                                    <font class="font-tab" style="font-size:9px;">V. Unitário</font>
                                                    <div align="right">{{@$vt['valor_unitario']}}</div>
                                                </td>
                                                <td class="celula_item" width="55">
                                                    <font class="font-tab" style="font-size:9px;">Valor Total</font>
                                                    <div align="right">{{@$vt['valor_total']}}</div>
                                                </td>
                                                <td class="celula_item">
                                                    <font class="font-tab" style="font-size:9px;">Registro ANVISA</font>
                                                    <div align="right">{{@$vt['anvisa']}}</div>
                                                </td>
                                                <td class="celula_item">
                                                    <font class="font-tab" style="font-size:9px;">Ref. Fabricante</font>
                                                    <div align="right">{{@$vt['fabricante']}}</div>
                                                </td>
                                                <td class="celula_item" align="center" width="30" rowspan="2">
                                                    {{-- <input
                                                        type="button" class="botao" value="-"
                                                        style="height:55px; font-weight:bold" onclick="od_remove_item('2')"> --}}
                                                    <button type="button" class="btn btn-outline-danger" title="{{__('Remover')}}" onclick="despesas_remove_item('{{@$k}}','{{@$vt['tipo']}}')">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary" title="{{__('Editar')}}" onclick="editarDespesa('{{@$k}}','{{@$vt['tipo']}}')">
                                                        <i class="fa fa-pen"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="celula_item" colspan="3">
                                                    <font class="font-tab" style="font-size:9px;">Nº Autorização</font>
                                                    <div align="right">{{@$vt['autorizacao']}}</div>
                                                </td>
                                                <td class="celula_item" colspan="9" width="780">
                                                    <font class="font-tab" style="font-size:9px;">Descrição</font>
                                                    <div align="left">{{@$vt['descricao']}}</div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    {{-- <tr id="tr_despesa_linha_{{@$k}}" style="cursor: pointer" title="DOIS CLIQUES PARA EDITAR" ondblclick="editarProcedimento('{{@$k}}')" onmouseover="btnEdit(this)" onmouseout="btnEdit(this,'oc')">
                                        <td class="celula_item" align="center" width="20" >
                                            {{@$i}}
                                            <span class="btnEditar" style="position: absolute;left: 5px;display:none">
                                                <button title="Clique para editar" type="button" class="btn btn-link" onclick="editarProcedimento('{{@$k}}')">
                                                    Editar
                                                </button>
                                            </span>
                                        </td>
                                        <td class="celula_item" align="center" width="35">{{@$arr_tipo[@$vt['tipo']]}}</td>
                                        <td class="celula_item" align="center" width="65">{!!@$inpu!!}{{App\Qlib\Qlib::dataExibe(@$vt['data'])}}</td>
                                        <td class="celula_item" align="center" width="65">{{@$vt['hora1']}}</td>
                                        <td class="celula_item" align="center" width="60">{{@$vt['hora2']}}</td>
                                        <td class="celula_item" align="center" width="40">{{@$vt['tabela']}}</td>
                                        <td class="celula_item" align="center" width="70">{{@$vt['codigo']}}</td>
                                        <td class="celula_item" width="240">{{@$vt['descricao']}}</td>
                                        <td class="celula_item" align="center" width="40">{{@$vt['quantidade']}}</td>
                                        <td class="celula_item" align="center" width="50">{{@$vt['fator']}}</td>
                                        <td class="celula_item" align="right" width="80">{{@$vt['valor_unitario']}}</td>
                                        <td class="celula_item" align="right" width="60">{{@$vt['valor_total']}}</td>
                                        <td class="celula_item" align="center" width="30">
                                            <button type="button" class="btn btn-outline-danger" title="{{__('Remover')}}" onclick="despesas_remove_item('{{@$k}}','{{@$vt['tipo']}}')">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </td>
                                    </tr> --}}
                                @endforeach
                            @endif
                        </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <button type="button" title="" class="btn btn-outline-primary" data-toggle="modal" onclick="telaAdicionarDespesas()" data-target="#add-despesas">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar despesas
            </button>
        </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="add-despesas" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
                <div class="modal-header">
                        <h5 class="modal-title">{{__('Painel para adição de despesas')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row mt-2">
                        <div class="col-md-12 px-0">
                            <div class="card card-secondary card-outline">
                                <div class="card-body">
                                    <div class="row">
                                        @if (isset($config['dados']))
                                            @foreach ($config['dados'] as $k=>$v)
                                            {!!
                                                App\Qlib\Qlib::qForm([
                                                    'type'=>@$v['type'],
                                                    'campo'=>$k,
                                                    'label'=>$v['label'],
                                                    'id'=>@$v['id'],
                                                    'placeholder'=>@$v['placeholder'],
                                                    'ac'=>$config['ac'],
                                                    'value'=>isset($v['value'])?$v['value']: @$value[$k],
                                                    'tam'=>@$v['tam'],
                                                    'event'=>@$v['event'],
                                                    'checked'=>@$value[$k],
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
                                            ])
                                            !!}
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Fechar')}}</button>
                <button type="button" id="btn-add-despesas" class="btn btn-primary" onclick="alimenta_despesas()" title="{{__('Adicionar despesas nesta guia')}}">
                    <i class="fas fa-plus"></i> {{__('Adicionar')}}
                </button>
                <button type="button" id="btn-upd-despesas" class="btn btn-primary" style="display: none;" onclick="alimenta_despesas()" title="{{__('Editar despesas nesta guia')}}">
                    <i class="fas fa-plus"></i> {{__('Editar')}}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
/*
    $('#exampleModal').on('show.bs.modal', event => {
        var button = $(event.relatedTarget);
        var modal = $(this);
        // Use above variables to manipulate the DOM

    });*/
</script>

