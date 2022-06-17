@if (isset($config['type']))
    @if ($config['type']=='select')
        <div class="col-{{$config['tam']}}"  div-id="{{$config['campo']}}" >
            @if ($config['label'])
                <label for="{{$config['campo']}}">{{$config['label']}}:</label>
            @endif
            @if (isset($config['arr_opc']))
                {{@$config['arr_opc'][$config['value']]}}
            @endif
        </div>
    @elseif ($config['type']=='hidden')
    @elseif ($config['type']=='select_multiple')
        @if (isset($config['arr_opc']))
        <div class="col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
            @if ($config['label'])
                <label for="{{$config['campo']}}">{{$config['label']}}:</label>
            @endif
            @foreach ($config['arr_opc'] as $k=>$v)
                @if(isset($config['value']) && is_array($config['value']) && in_array($k,$config['value']))
                    {{@$config['arr_opc'][$k]}},
                @endif
            @endforeach
        </div>
        @endif
    @elseif ($config['type']=='selector')
        @if (isset($config['arr_opc']))
        <div class="col-{{$config['tam']}}" div-id="{{$config['campo']}}">
            @if ($config['label'])
                <label for="{{$config['campo']}}">{{$config['label']}}</label>
            @endif
            {{@$config['arr_opc'][$config['value']]}}
        </div>
        @endif
    @elseif ($config['type']=='radio')
        @if (isset($config['arr_opc']))
        <div class="col-{{$config['tam']}}" div-id="{{$config['campo']}}">
            <label for="{{$config['campo']}}">{{$config['label']}}:</label>
                {{@$config['arr_opc'][$config['value']]}}
        </div>
        @endif
    @elseif ($config['type']=='chave_checkbox')
        <div class="col-{{$config['tam']}}" div-id="{{$config['campo']}}">
            <label class="" for="{{$config['campo']}}">
                @if(isset($config['checked']) && $config['checked'] == $config['value'])
                    <i class="fas fa-check-square"></i>
                @endif
                {{$config['label']}}
            </label>

        </div>
    @elseif ($config['type']=='textarea')
        <!--config['checked'] é o gravado no bando do dedos e o value é o valor para ficar checado-->
        <div class="col-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
            <label for="{{$config['campo']}}">{{$config['label']}}</label><br>
            @if(isset($config['value'])){!! $config['value'] !!}@endif
        </div>
    @elseif ($config['type']=='html')
        @php
           $config['script'] = isset($config['script'])?$config['script']:false;
        @endphp
        <div class="col-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
            @if ($config['script'])
                @if(isset($config['dados']))
                    @include($config['script'],@$config['dados'])
                @else
                    @include($config['script'])
                @endif
            @endif
        </div>
    @elseif ($config['type']=='html_vinculo')
        @php
           $config['script'] = isset($config['script'])?$config['script']:false;
        @endphp
        <div class="col-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
            <div class="card card-secondary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        {{__($config['label'])}}
                    </h3>
                </div>
                <div class="card-body">
                   <div class="row" id="row-{{$config['data_selector']['campo']}}">
                        @if ($config['script'])
                            @if(isset($config['dados']))
                                @include($config['script'],@$config['dados'])
                            @else
                                @include($config['script'])
                            @endif
                        @endif
                        @php
                            $d = $config['data_selector'];
                        @endphp
                        @if (isset($config['data_selector']['table']) && is_array($config['data_selector']['table']))
                        <div class="col-md-12 ">
                                @if (isset($config['data_selector']['list']) && is_array($config['data_selector']['list']) && isset($config['data_selector']['table']) && is_array($config['data_selector']['table']))
                                    @if (@$config['data_selector']['tipo']=='array')
                                        @foreach ($config['data_selector']['list'] as $klis=>$vlis)
                                            <div class="row" id="tr-{{$klis}}-{{@$config['data_selector']['list'][$klis]['id']}}">
                                                @foreach ($config['data_selector']['campos'] as $kb=>$vb)
                                                    @if ($vb['type']=='arr_tab')
                                                        {!! App\Qlib\Qlib::qShow([
                                                            'type'=>@$vb['type'],
                                                            'campo'=>$kb,
                                                            'label'=>$vb['label'],
                                                            'placeholder'=>@$vb['placeholder'],
                                                            'ac'=>$config['ac'],
                                                            'value'=>@$config['data_selector']['list'][$klis][$kb.'_valor'],
                                                            'tam'=>@$vb['tam'],
                                                            'event'=>@$vb['event'],
                                                            'checked'=>@$config['data_selector']['list'][$klis][$kb.'_valor'],
                                                            'selected'=>@$vb['selected'],
                                                            'arr_opc'=>@$vb['arr_opc'],
                                                            'option_select'=>@$vb['option_select'],
                                                            'class'=>@$vb['class'],
                                                            'class_div'=>@$vb['class_div'],
                                                            'rows'=>@$vb['rows'],
                                                            'cols'=>@$vb['cols'],
                                                            'data_selector'=>@$vb['data_selector'],
                                                            'script'=>@$vb['script_show'],
                                                            'valor_padrao'=>@$vb['valor_padrao'],
                                                            'dados'=>@$vb['dados'],
                                                        ]) !!}
                                                    @else
                                                        @php
                                                            if(isset($vb['cp_busca']) && !empty($vb['cp_busca']))
                                                            {
                                                                $ck = explode('][',$vb['cp_busca']);
                                                                if(isset($ck[1])){
                                                                    $value = @$config['data_selector']['list'][$klis][$ck[0]][$ck[1]];
                                                                }else{
                                                                    $value = $config['data_selector']['list'][$klis][$kb];
                                                                }
                                                            }else{
                                                                $vlue = @$config['data_selector']['list'][$klis][$kb];
                                                                if(isset($vb['arr_opc'])){
                                                                    $value = isset($vb['arr_opc'][$vlue])?$vb['arr_opc'][$vlue]:$vlue;
                                                                }else{
                                                                    $value = $vlue;
                                                                }
                                                            }
                                                            if ($vb['label']=='Quadra'){
                                                                $value = $vlue;
                                                            }
                                                        @endphp

                                                    {!! App\Qlib\Qlib::qShow([
                                                        'type'=>@$vb['type'],
                                                        'campo'=>$kb,
                                                        'label'=>$vb['label'],
                                                        'placeholder'=>@$vb['placeholder'],
                                                        'ac'=>$config['ac'],
                                                        'value'=>@$value,
                                                        'tam'=>@$vb['tam'],
                                                        'event'=>@$vb['event'],
                                                        'checked'=>@$value,
                                                        'selected'=>@$vb['selected'],
                                                        'arr_opc'=>@$vb['arr_opc'],
                                                        'option_select'=>@$vb['option_select'],
                                                        'class'=>@$vb['class'],
                                                        'class_div'=>@$vb['class_div'],
                                                        'rows'=>@$vb['rows'],
                                                        'cols'=>@$vb['cols'],
                                                        'data_selector'=>@$vb['data_selector'],
                                                        'script'=>@$vb['script_show'],
                                                        'valor_padrao'=>@$vb['valor_padrao'],
                                                        'dados'=>@$vb['dados'],
                                                        ]) !!}
                                                    @endif
                                                @endforeach
                                                <div class="col-12">
                                                    <hr>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="row" id="tr-{{@$config['data_selector']['list']['id']}}">
                                            @foreach ($config['data_selector']['campos'] as $kb=>$vb)

                                                @if ($vb['type']=='text' || $vb['type']=='date' || $vb['type']=='tel' || $vb['type']=='number')
                                                    @php
                                                        if(isset($vb['cp_busca']) && !empty($vb['cp_busca']))
                                                        {
                                                            $ck = explode('][',$vb['cp_busca']);
                                                            if(isset($ck[1])){
                                                                $value = @$config['data_selector']['list'][$ck[0]][$ck[1]];
                                                            }else{
                                                                $value = $config['data_selector']['list'][$kb];
                                                            }
                                                        }else{
                                                            $value = @$config['data_selector']['list'][$kb];
                                                        }
                                                        if($vb['type']=='date'){
                                                            $value = App\Qlib\Qlib::dataExibe($value);
                                                        }
                                                    @endphp
                                                    {!! App\Qlib\Qlib::qShow([
                                                        'type'=>@$vb['type'],
                                                        'campo'=>$kb,
                                                        'label'=>$vb['label'],
                                                        'placeholder'=>@$vb['placeholder'],
                                                        'ac'=>$config['ac'],
                                                        'value'=>@$value,
                                                        'tam'=>@$vb['tam'],
                                                        'event'=>@$vb['event'],
                                                        'checked'=>@$value,
                                                        'selected'=>@$vb['selected'],
                                                        'arr_opc'=>@$vb['arr_opc'],
                                                        'option_select'=>@$vb['option_select'],
                                                        'class'=>@$vb['class'],
                                                        'class_div'=>@$vb['class_div'],
                                                        'rows'=>@$vb['rows'],
                                                        'cols'=>@$vb['cols'],
                                                        'data_selector'=>@$vb['data_selector'],
                                                        'script'=>@$vb['script_show'],
                                                        'valor_padrao'=>@$vb['valor_padrao'],
                                                        'dados'=>@$vb['dados'],
                                                    ]) !!}

                                                @elseif ($vb['type']=='arr_tab'||$vb['type']=='select')
                                                    @php
                                                        if(isset($vb['cp_busca']) && !empty($vb['cp_busca']))
                                                        {
                                                            $ck = explode('][',$vb['cp_busca']);
                                                            if(isset($ck[1])){
                                                                $value = @$config['data_selector']['list'][$ck[0]][$ck[1]];
                                                            }else{
                                                                //$value = $config['data_selector']['list'][$kb.'_valor']
                                                                $value = $config['data_selector']['list'][$kb];
                                                            }
                                                        }else{
                                                            //$value = $config['data_selector']['list'][$kb.'_valor'];
                                                            $value = $config['data_selector']['list'][$kb];
                                                        }
                                                    @endphp

                                                    {!! App\Qlib\Qlib::qShow([
                                                        'type'=>@$vb['type'],
                                                        'campo'=>$kb,
                                                        'label'=>$vb['label'],
                                                        'placeholder'=>@$vb['placeholder'],
                                                        'ac'=>$config['ac'],
                                                        'value'=>@$value,
                                                        'tam'=>@$vb['tam'],
                                                        'event'=>@$vb['event'],
                                                        'checked'=>@$value,
                                                        'selected'=>@$vb['selected'],
                                                        'arr_opc'=>@$vb['arr_opc'],
                                                        'option_select'=>@$vb['option_select'],
                                                        'class'=>@$vb['class'],
                                                        'class_div'=>@$vb['class_div'],
                                                        'rows'=>@$vb['rows'],
                                                        'cols'=>@$vb['cols'],
                                                        'data_selector'=>@$vb['data_selector'],
                                                        'script'=>@$vb['script_show'],
                                                        'valor_padrao'=>@$vb['valor_padrao'],
                                                        'dados'=>@$vb['dados'],
                                                        ]) !!}
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                                @if ($config['script'])

                                    @if(isset($config['dados']))
                                        @include($config['script'],@$config['dados'])
                                    @else
                                        @include($config['script'])
                                    @endif
                                @endif
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer text-muted">
                        {{@$footer}}
                </div>
            </div>
        </div>
    @elseif($config['type']=='text')
    <div class="col-{{$config['tam']}}" div-id="{{$config['campo']}}">
        <label for="{{$config['campo']}}">{{$config['label']}}:</label>
        {!! @$config['value'] !!}
    </div>
    @elseif($config['type']=='date')
    <div class="col-{{$config['tam']}}" div-id="{{$config['campo']}}">
        <label for="{{$config['campo']}}">{{$config['label']}}:</label>
        {{ @$config['value'] }}
    </div>
    @else
    <div class="col-{{$config['tam']}}" div-id="{{$config['campo']}}">
        <label for="{{$config['campo']}}">{{$config['label']}}:</label>
        {!! @$config['value'] !!}
    </div>
    @endif
@endif
