<div class="col-md-12 d-print-none">
    <div class="card">
        <form action="" id="frm-consulta" method="GET">
            <div class="row mr-0 ml-0">
                <!--
                <div class="col-md-2 pt-4 pl-2">
                    <a class="btn @if(isset($_GET['filter'])) btn-link @else btn-default @endif" data-toggle="collapse" href="#busca-id" aria-expanded="false" aria-controls="busca-id">
                        <i class="fas fa-search-location    "></i> @if(isset($_GET['filter'])) Mostrar Critérios de pesquisa @else Pesquisar @endif
                    </a>
                </div>
            -->
                {{App\Qlib\Qlib::qForm([
                    'type'=>'select',
                    'campo'=>'filter[bairro]',
                    'placeholder'=>'',
                    'label_option_select'=>'Todas',
                    'label'=>'Áreas',
                    'ac'=>'alt',
                    'value'=>@$_GET['filter']['bairro'],
                    'tam'=>'2',
                    'arr_opc'=>App\Qlib\Qlib::sql_array("SELECT id,nome FROM bairros WHERE ativo='s'",'nome','id'),
                    'event'=>'onchange=carregaQuadras($(this).val(),\'quadra\')',
                    'option_select'=>true,
                    'class'=>'text-left',
                    'class_div'=>'text-left',
                ])}}
                @php
                    $arr_opc_quadra = [];
                    if(isset($_GET['filter']['bairro']) && !empty($_GET['filter']['bairro'])){
                        $arr_opc_quadra = App\Qlib\Qlib::sql_array("SELECT id,nome FROM quadras WHERE ativo='s' AND bairro='".$_GET['filter']['bairro']."'",'nome','id');

                    }

                @endphp

                {{App\Qlib\Qlib::qForm([
                    'type'=>'select',
                    'campo'=>'filter[quadra]',
                    'placeholder'=>'',
                    'label_option_select'=>'Todas',
                    'label'=>'Quadras',
                    'ac'=>'alt',
                    'value'=>@$_GET['filter']['quadra'],
                    'tam'=>'2',
                    'arr_opc'=>$arr_opc_quadra,
                    //'arr_opc'=>[],
                    'event'=>'onchange=$(\'#frm-consulta\').submit();',
                    'option_select'=>true,
                    'class'=>'text-left',
                    'class_div'=>'text-left',
                ])}}
                {{App\Qlib\Qlib::qForm([
                    'type'=>'select',
                    'campo'=>'limit',
                    'placeholder'=>'',
                    'label'=>'Por página',
                    'ac'=>'alt',
                    'value'=>@$config['limit'],
                    'tam'=>'2',
                    'arr_opc'=>['20'=>'20','50'=>'50','100'=>'100','200'=>'200','500'=>'500','todos'=>'Todos'],
                    'event'=>'onchange=$(\'#frm-consulta\').submit();',
                    'option_select'=>false,
                    'class'=>'text-center',
                    'class_div'=>'text-center',
                ])}}
                {{App\Qlib\Qlib::qForm([
                    'type'=>'radio',
                    'campo'=>'order',
                    'placeholder'=>'',
                    'label'=>false,
                    'ac'=>'alt',
                    'value'=>@$config['order'],
                    'tam'=>'4',
                    'arr_opc'=>['asc'=>'Ordem crescente','desc'=>'Ordem decrescente'],
                    'event'=>'order=true',
                    'class'=>'btn btn-light',
                    'option_select'=>false,
                    'class_div'=>'pt-4 text-right',
                ])}}
                @can('create',$routa)

                <div class="col-md-2 text-right mt-4">
                    <a href="{{ route($routa.'.create') }}" class="btn btn-success btn-block">
                        <i class="fa fa-plus" aria-hidden="true"></i> Cadastrar
                    </a>
                </div>
                @endcan
                    <div class="form-group col-md-9 text-left" div-id="auto-proprietario">
                        <label for="auto-proprietario">Proprietário</label>
                        <input type="text" name="auto-proprietario" class="form-control  text-left" id="auto-proprietario" aria-describedby="auto-proprietario" placeholder="" value="{{@$_GET['auto-proprietario']}}" >
                    </div>
                    {{App\Qlib\Qlib::qForm([
                        'type'=>'hidden',
                        'campo'=>'filter[id_beneficiario]',
                        'placeholder'=>'',
                        'label'=>'Proprietário',
                        'ac'=>'alt',
                        'value'=>@$_GET['filter']['id_beneficiario'],
                        'tam'=>'9',
                        //'arr_opc'=>$arr_opc_quadra,
                        //'arr_opc'=>[],
                        //'event'=>'onchange=$(\'#frm-consulta\').submit();',
                        'option_select'=>true,
                        'class'=>'text-left',
                        'class_div'=>'text-left',
                    ])}}
                    <div class="col-md-3 mb-3 pt-4 mt-2 text-right">
                        <div class="btn-group" style="width: 100%">
                            <button style="width: 50%" class="btn btn-primary" type="submit"> <i class="fas fa-search"></i> Localizar</button>
                            <a href=" {{route('familias.index')}} " class="btn btn-default" title="Limpar Filtros" type="button"> <i class="fas fa-times"></i> Limpar</a>
                            <!--include('familias.dropdow_actions')-->
                        </div>
                    </div>
                <!--
                <div class="collapse" id="busca-id">
                        include('qlib.busca')
                </div>
                -->
            </div>
        </form>
    </div>
</div>
