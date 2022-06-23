
<style>
.celula_item {
    font-size: 12px;
    color: #666;
    border: 1px solid gray;
    height: 25px;
    background-color: #ffffff;
}
</style>
<div class="row">
    <div class="col-md-12 px-0">
        <table width="100%">
            <tbody>
                <tr>
                    <td class="celula_item" align="center" width="20">&nbsp;</td>
                    <td class="celula_item" align="center" width="65">Data</td>
                    <td class="celula_item" align="center" width="65">Hora Inicial</td>
                    <td class="celula_item" align="center" width="60">Hora Final</td>
                    <td class="celula_item" align="center" width="40">Tabela</td>
                    <td class="celula_item" align="center" width="70">Código</td>
                    <td class="celula_item" align="center">Descrição</td>
                    <td class="celula_item" align="center" width="40">Qtde</td>
                    <td class="celula_item" align="center" width="35">Via</td>
                    <td class="celula_item" align="center" width="35">Téc</td>
                    <td class="celula_item" align="center" width="50">Fator</td>
                    <td class="celula_item" align="center" width="80">Valor Unitário</td>
                    <td class="celula_item" align="center" width="60">Valor Total</td>
                    <td class="celula_item" align="center" width="30">&nbsp;</td>
                </tr>
            </tbody>
            <tbody class="list-procedimentos" id="demo_procedimentos">
                <!--
                <tr>
                    <td class="celula_item" align="center" width="20" id="td_contador_linha_1">1</td>
                    <td class="celula_item" align="center" width="65">01/06/2022</td>
                    <td class="celula_item" align="center" width="65">12:00</td>
                    <td class="celula_item" align="center" width="60">15:23</td>
                    <td class="celula_item" align="center" width="40">18</td>
                    <td class="celula_item" align="center" width="70">10102019</td>
                    <td class="celula_item" width="240">VISITA HOPITALAR</td>
                    <td class="celula_item" align="center" width="40">30</td>
                    <td class="celula_item" align="center" width="35">1</td>
                    <td class="celula_item" align="center" width="35">1</td>
                    <td class="celula_item" align="center" width="50">1.00</td>
                    <td class="celula_item" align="right" width="80">0.01</td>
                    <td class="celula_item" align="right" width="60">0.30</td>
                    <td class="celula_item" align="center" width="30"><input type="button" class="botao" value="-" style="height: 15px; font-weight: bold;" onclick="procedimento_remove_item('1')" /></td>
                </tr>
            -->
            </tbody>
        </table>
    </div>
</div>
@php

@endphp
<div class="row mt-2">
    <div class="col-md-12 px-0">
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h4 class="card-title">
                    {{__('Painel para adição de procedimentos')}}
                </h4>
            </div>
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
            <div class="card-footer text-right">
                <button class="btn btn-primary" onclick="alimenta_procedimento()" type="button" title="{{__('Adicionar procedimento nesta guia')}}"><i class="fas fa-plus"></i> {{__('Adicionar')}}</button>
            </div>
        </div>
    </div>
</div>
