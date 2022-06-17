<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Pesquisar cadastros</h5>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <!--<form action="" method="GET">-->
                <div class="row">
                    @if (isset($campos_tabela))
                        @foreach ($campos_tabela as $kbu=>$vbu)
                            @if ($vbu['active'])
                                {{App\Qlib\Qlib::qForm([
                                    'type'=>isset($vbu['type'])?$vbu['type']:'text',
                                    'campo'=>'filter['.$kbu.']',
                                    'placeholder'=>isset($vbu['placeholder'])?$vbu['placeholder']:'',
                                    'label'=>$vbu['label'],
                                    'ac'=>'alt',
                                    'value'=>@$_GET['filter'][$kbu],
                                    'tam'=>isset($vbu['tam'])?$vbu['tam']:'3',
                                    'class_div'=>$vbu['exibe_busca'],
                                    'event'=>isset($vbu['event'])?$vbu['event']:'',
                                    'arr_opc'=>isset($vbu['arr_opc'])?$vbu['arr_opc']:'',
                                ])}}
                            @endif

                        @endforeach
                    @else
                    {{App\Qlib\Qlib::qForm([
                        'type'=>'text',
                        'campo'=>'filter[loteamento]',
                        'placeholder'=>'Loteamento',
                        'label'=>'Loteamento',
                        'ac'=>'alt',
                        'value'=>@$_GET['filter']['loteamento'],
                        'tam'=>'4',
                        'event'=>'',
                    ])}}
                    {{App\Qlib\Qlib::qForm([
                        'type'=>'text',
                        'campo'=>'filter[area_alvo]',
                        'placeholder'=>'Informe Área',
                        'label'=>'Área Alvo',
                        'ac'=>'alt',
                        'value'=>@$_GET['filter']['area_alvo'],
                        'tam'=>'2',
                        'event'=>'',
                        ])}}
                    @endif
                    <div class="col-md-12">
                        <div class="btn-group">
                            <button class="btn btn-primary" type="submit"> <i class="fas fa-search"></i> Localizar</button>
                            <a href=" {{route('familias.index')}} " class="btn btn-default" title="Limpar Filtros" type="button"> <i class="fas fa-times"></i> Limpar</a>
                            @include('familias.dropdow_actions')
                        </div>
                    </div>
                </div>
            <!--</form>-->
        </div>
    </div>
</div>
