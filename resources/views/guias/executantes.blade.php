
<style>
    .celula_item {
        font-size: 12px;
        color: #666;
        border: 1px solid gray;
        height: 25px;
        background-color: #ffffff;
    }
    </style>
        <div class="card">
            <div class="card-body pl-2 pr-2">
                <div class="row">
                    <div class="col-md-12 px-0">
                        <table width="100%" class="px-0">
                            <tbody>
                                <tr>
                                    <td class="celula_item" align="center" width="50">Seq. Ref.</td>
                                    <td class="celula_item" align="center" width="70">Grau Part.</td>
                                    <td class="celula_item" align="center">Nome do Profissional</td>
                                    <td class="celula_item" align="center" width="140">Cod. na Operadora/CPF</td>
                                    <td class="celula_item" align="center" width="60">Conselho</td>
                                    <td class="celula_item" align="center" width="95">Nº no Conselho</td>
                                    <td class="celula_item" align="center" width="30">UF</td>
                                    <td class="celula_item" align="center" width="75">Código CBO</td>
                                    <td class="celula_item" align="center" width="30">&nbsp;</td>
                                </tr>
                            </tbody>
                            <tbody class="list-executantes" id="demo_executantes">
                                @php
                                    $arr_list = !empty($_REQUEST['dados']['config'])?$_REQUEST['dados']['config']: false;
                                    if($arr_list){
                                        $arr_list = App\Qlib\Qlib::lib_json_array($arr_list);
                                    }
                                    //dd($arr_list);
                                    $i=0;
                                @endphp
                                @if (isset($arr_list['executantes']) && is_array($arr_list['executantes']))
                                    @foreach ($arr_list['executantes'] as $k=>$vt)
                                        @php
                                            $i++;
                                            $inpu = '<input type="hidden" name="config[executantes]['.$k.'][item]" value="'.$k.'"/>';
                                            $arr_campos = ['data','hora1','hora2','via','tec','tabela','codigo','descricao','quantidade','fator','valor_unitario','valor_total'];
                                            if(is_array($arr_campos)){
                                                foreach ($arr_campos as $key => $vat) {
                                                    $inpu .= '<input type="hidden" name="config[executantes]['.$k.']['.$vat.']" value="'.@$vt[$vat].'"/>';
                                                }
                                            }
                                        @endphp
                                        <tr id="executor_linha_{{@$vt['item']}}">
                                            <td class="celula_item" align="center" width="50">{{@$vt['seq']}}</td>
                                            <td class="celula_item" align="center" width="70">{{@$vt['grau_part']}}</td>
                                            <td class="celula_item" width="370">{{@$vt['ex_nome']}}</td>
                                            <td class="celula_item" align="center" width="140">{{@$vt['codigo']}}</td>
                                            <td class="celula_item" align="center" width="60">{{@$vt['conselho']}}</td>
                                            <td class="celula_item" align="center" width="95">{{@$vt['conselho_numero']}}</td>
                                            <td class="celula_item" align="center" width="30">{{@$vt['conselho_uf']}}</td>
                                            <td class="celula_item" align="center" width="75">{{@$vt['cbo']}}</td>
                                            <td class="celula_item" align="center" width="30">
                                                <button type="button" class="btn btn-outline-danger" title="{{__('Remover')}}" onclick="executante_remove_item('{{@$vt['item']}}')">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="button" title="" class="btn btn-outline-primary" data-toggle="modal" data-target="#add-executantes">
                    <i class="fa fa-plus" aria-hidden="true"></i> {{__('Adicionar executante')}}
                </button>
            </div>
        </div>

    <!-- Modal -->
    <div class="modal fade" id="add-executantes" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                            <h5 class="modal-title">{{__('Painel para adição de executantes')}}</h5>
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
                    <button type="button" class="btn btn-primary" onclick="alimenta_executante()" title="{{__('Adicionar executantes nesta guia')}}"><i class="fas fa-plus"></i> {{__('Adicionar')}}</button>
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

