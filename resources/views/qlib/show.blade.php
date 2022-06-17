@php
    $config = $conf['config'];
    $campos = $conf['campos'];
    $value = $conf['value'];
@endphp

    @if($config['ac']=='alt')
    @endif
    <div class="row mb-4">
        <div class="col-md-12 text-right">
            @if (isset($value['id']))
                <label for="">Id:</label> {{ $value['id'] }}
            @endif
            @if (isset($value['created_at']))
                <label for="">Cadastro:</label> {{ Carbon\Carbon::parse($value['created_at'])->format('d/m/Y') }}
            @endif

        </div>
        @if (isset($campos) && is_array($campos))
            @foreach ($campos as $k=>$v)
                @if ($v['type']=='select_multiple')
                    @php
                        $nk = str_replace('[]','',$k);
                        $value[$k] = isset($value[$nk])?$value[$nk]:false;
                    @endphp
                @endif
                {!! App\Qlib\Qlib::qShow([
                        'type'=>@$v['type'],
                        'campo'=>$k,
                        'label'=>$v['label'],
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
                        'script'=>@$v['script_show'],
                        'valor_padrao'=>@$v['valor_padrao'],
                        'dados'=>@$v['dados'],
                ]) !!}
            @endforeach
        @endif
        @csrf

    </div>
    @include('qlib.btnedit')
