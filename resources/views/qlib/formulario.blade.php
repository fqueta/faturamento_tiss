@php
    $config = $conf['config'];
    $campos = $conf['campos'];
    $value = $conf['value'];
@endphp

<form id="{{$config['frm_id']}}" class="" action="@if($config['ac']=='cad'){{ route($config['route'].'.store') }}@elseif($config['ac']=='alt'){{ route($config['route'].'.update',['id'=>$config['id']]) }}@endif" method="post">
    @if($config['ac']=='alt')
    @method('PUT')
    @endif
    <div class="row">
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
                @if (isset($v['cp_busca'])&&!empty($v['cp_busca']))
                    @php
                        $cf = explode('][',$v['cp_busca']);
                        if(isset($cf[1])){
                            $value[$k] = @$value[$cf[0]][$cf[1]];
                        }
                    @endphp
                @endif
                @if ($v['type']=='select_multiple')
                    @php
                        $nk = str_replace('[]','',$k);
                        $value[$k] = isset($value[$nk])?$value[$nk]:false;
                    @endphp
                @endif

            {{App\Qlib\Qlib::qForm([
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
                    'script'=>@$v['script'],
                    'valor_padrao'=>@$v['valor_padrao'],
                    'dados'=>@$v['dados'],
            ])}}
            @endforeach
        @endif
        @csrf

        @include('qlib.btnsalvar')
    </div>
</form>
