
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
            <tbody class="list-procedimentos" id="demo_executantes">
                <tr>
                    <td class="celula_item" align="center" width="50">3</td>
                    <td class="celula_item" align="center" width="70">01</td>
                    <td class="celula_item" width="370">CLINICA SUPERAR</td>
                    <td class="celula_item" align="center" width="140">27058451001</td>
                    <td class="celula_item" align="center" width="60">6</td>
                    <td class="celula_item" align="center" width="95">42837</td>
                    <td class="celula_item" align="center" width="30">MG</td>
                    <td class="celula_item" align="center" width="75">225125</td>
                    <td class="celula_item" align="center" width="30"><input type="button" class="botao" value="-" style="height:15px; font-weight:bold" onclick="executante_remove_item('1')"></td>
                </tr>
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
                    {{__('Painel para adição de Profissional Executante')}}
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
                <button class="btn btn-primary" onclick="alimenta_executante()" type="button" title="{{__('Adicionar procedimento nesta guia')}}"><i class="fas fa-plus"></i> {{__('Adicionar')}}</button>
            </div>
        </div>
    </div>
</div>
