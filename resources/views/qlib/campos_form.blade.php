@if (isset($config['type']))
    @if ($config['type']=='select')
        @if (isset($config['arr_opc']))
        <div class="form-group col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
            @if ($config['label'])
                 <label for="{{$config['campo']}}">{{$config['label']}}</label><br>
            @endif
            <select name="{{$config['campo']}}" {{$config['event']}} id="{{$config['id']}}" class="form-control custom-select selectpicker {{$config['class']}} @error($config['campo']) is-invalid @enderror">
                @if ($config['option_select'])
                    <option value="" class="option_select"> {{$config['label_option_select']}} </option>
                @endif
                @foreach ($config['arr_opc'] as $k=>$v)
                    @php
                        $arr_v = explode('@#',$v);
                        $attr_data = false;
                        if(isset($arr_v[1])&& !empty($arr_v[1])){
                            $v = $arr_v[0];
                            $attr_data = 'data-values="'.$arr_v[1].'"';
                        }

                    @endphp
                    <option value="{{$k}}" {!!$attr_data!!} class="opcs" @if(isset($config['value']) && $config['value'] == $k) selected @endif>{{$v}}</option>
                @endforeach
            </select>
            @error($config['campo'])
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        @endif
    @elseif ($config['type']=='select_multiple')
        @if (isset($config['arr_opc']))
        <div class="form-group col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
            @php
                //$config['value'] = json_decode($config['value'],true);
            @endphp
            @if ($config['label'])
                <label for="{{$config['campo']}}">{{$config['label']}}</label>
            @endif
            <select name="{{$config['campo']}}" multiple="true" {{$config['event']}} id="sele-{{$config['campo']}} @error($config['campo']) is-invalid @enderror" class="form-control custom-select select2 {{$config['class']}}">
                @if ($config['option_select'])
                    <option value="" class="option_select"> {{$config['label_option_select']}} </option>
                @endif
                @foreach ($config['arr_opc'] as $k=>$v)
                    <option class="opcs" value="{{$k}}" @if(isset($config['value']) && is_array($config['value']) && in_array($k,$config['value'])) selected @endif>{{$v}}</option>
                @endforeach
            </select>
            @error($config['campo'])
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        @endif
    @elseif ($config['type']=='selector')
        @if (isset($config['arr_opc']))
            <div class="form-group col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
                @if ($config['label'])
                    <label for="{{$config['campo']}}">{{$config['label']}}</label><br>
                @endif
                <select name="{{$config['campo']}}" {{$config['event']}} data-selector="{{App\Qlib\Qlib::encodeArray(@$config['data_selector'])}}" selector-event id="sele-{{$config['campo']}} @error($config['campo']) is-invalid @enderror" class="form-control custom-select selectpicker {{$config['class']}}">
                    @if ($config['option_select'])
                        <option value=""> {{$config['label_option_select']}} </option>
                    @endif
                    <option value="cad"> {{__('Cadastrar')}} {{$config['label']}}</option>
                    <option value="ger"> {{__('Gerenciar Cadastros ')}} </option>
                    <option value="" disabled class="option_select">--------------</option>

                    @foreach ($config['arr_opc'] as $k=>$v)
                        <option value="{{$k}}" class="opcs" @if(isset($config['value']) && $config['value'] == $k) selected @endif>{{$v}}</option>
                    @endforeach
                </select>
                @error($config['campo'])
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
        @endif
    @elseif ($config['type']=='radio')
        <div class="form-group col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}} @error($config['campo']) is-invalid @enderror">
            <div class="btn-group" data-toggle="buttons">
                @if ($config['label'])
                    <label for="{{$config['campo']}}">{{$config['label']}}</label>
                @endif
                @foreach ($config['arr_opc'] as $k=>$v)
                <label class="{{ $config['class'] }} @if(isset($config['value']) && $config['value'] == $k) active @endif ">
                    <input type="radio" name="{{ $config['campo']}}" {{$config['event']}} value="{{$k}}" id="" autocomplete="off" @if(isset($config['value']) && $config['value'] == $k) checked @endif > {{ $v }}
                </label>
                @endforeach
            </div>
        </div>
    @elseif ($config['type']=='hidden')
        <div class="form-group col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}} d-none" div-id="{{$config['campo']}}" >
            @if ($config['label'])
                <label for="{{$config['campo']}}">{{$config['label']}}</label>
            @endif
            <input type="{{$config['type']}}" class="form-control @error($config['campo']) is-invalid @enderror {{$config['class']}}" id="{{$config['id']}}" name="{{$config['campo']}}" aria-describedby="{{$config['campo']}}" placeholder="{{$config['placeholder']}}" value="@if(isset($config['value'])){{$config['value']}}@elseif($config['ac']=='cad'){{old($config['campo'])}}@endif" {{$config['event']}} />
            @error($config['campo'])
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    @elseif ($config['type']=='hidden_text')
        <div class="form-group col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}" >
            @if ($config['label'])
                <label for="{{$config['campo']}}">{{$config['label']}}:</label>
            @endif
            <span id="txt-{{$config['campo']}}">
                @if (isset($config['arr_opc']) && is_array($config['arr_opc']))
                {{@$config['arr_opc'][$config['value']]}}
                @else
                {{$config['value']}}
                @endif
            </span>
            <input type="hidden" class="form-control @error($config['campo']) is-invalid @enderror {{$config['class']}}" id="inp-{{$config['campo']}}" name="{{$config['campo']}}" aria-describedby="{{$config['campo']}}" placeholder="{{$config['placeholder']}}" value="@if(isset($config['value'])){{$config['value']}}@elseif($config['ac']=='cad'){{old($config['campo'])}}@endif" {{$config['event']}} />
            @error($config['campo'])
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    @elseif ($config['type']=='chave_checkbox')
        <!--config['checked'] é o gravado no bando do dedos e o value é o valor para ficar checado-->
        <div class="form-group col-{{$config['col']}}-{{$config['tam']}}">
            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success @error($config['campo']) is-invalid @enderror {{$config['class']}}">
                <input type="checkbox" class="custom-control-input" @if(isset($config['checked']) && $config['checked'] == $config['value']) checked @endif  value="{{$config['value']}}"  name="{{$config['campo']}}" id="{{$config['campo']}}">
                <label class="custom-control-label" for="{{$config['campo']}}">{{$config['label']}}</label>
            </div>
            @error($config['campo'])
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    @elseif ($config['type']=='textarea')
        <!--config['checked'] é o gravado no bando do dedos e o value é o valor para ficar checado-->
        <div class="col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
            <div class="form-group">
            <label for="{{$config['campo']}}">{{$config['label']}}</label><br>
            <textarea name="{{$config['campo']}}" class="form-control @error($config['campo']) is-invalid @enderror {{$config['class']}}" rows="{{@$config['rows']}}" cols="{{@$config['cols']}}">@if(isset($config['value'])){{$config['value']}}@elseif($config['ac']=='cad'){{old($config['campo'])}}@endif</textarea>
            </div>
        </div>
    @elseif ($config['type']=='html_script')
        @php
           $config['script'] = isset($config['script'])?$config['script']:false;
        @endphp
        <div class="col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
            @if ($config['script'])
                {!!$config['script']!!}
            @endif
        </div>
    @elseif ($config['type']=='html')
        @php
           $config['script'] = isset($config['script'])?$config['script']:false;
        @endphp
        <div class="col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
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
        <div class="col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">
                        {{__($config['label'])}}
                    </h3>
                </div>
                <div class="card-body">
                   <div class="row" id="row-{{$config['data_selector']['campo']}}">
                        <div class="col-6 mb-2 btn-consulta-vinculo">
                            <button type="button" class="btn btn-default btn-block" data-toggle="button" aria-pressed="false" onclick="lib_abrirModalConsultaVinculo('{{@$config['data_selector']['campo']}}','abrir');"> <i class="fa fa-search" aria-hidden="true"></i> {{__('Usar cadastrado')}}</button>
                        </div>
                        <div class="col-6 mb-2 btn-voltar-vinculo" style="display: none">
                            <button type="button" class="btn btn-default btn-block" data-toggle="button" aria-pressed="false" onclick="lib_abrirModalConsultaVinculo('{{@$config['data_selector']['campo']}}','fechar');">
                                <span class="pull-left">
                                    <i class="fa fa-chevron-left " aria-hidden="true"></i> {{__('Voltar')}}
                                </span>
                            </button>
                        </div>
                        <div class="col-6 mb-2">
                            <button type="button" class="btn btn-outline-primary btn-block" data-ac="{{$config['ac']}}" data-selector="{{App\Qlib\Qlib::encodeArray(@$config['data_selector'])}}" onclick="lib_vinculoCad($(this));" > <i class="fa fa-plus" aria-hidden="true"></i> {{__('Cadastrar')}}</button>
                        </div>
                        <div class="col-md-12 mb-2" style="display: none;" id="inp-cad-{{$config['data_selector']['campo']}}">
                            <input id="inp-auto-{{$config['data_selector']['campo']}}" type="text"
                                url="{{$config['data_selector']['route_index']}}"
                                class="autocomplete form-control"
                                data-selector="{{App\Qlib\Qlib::encodeArray(@$config['data_selector'])}}"
                                placeholder="{{__(@$config['data_selector']['placeholder'])}}"
                                onclick="this.value=''"
                                />
                        </div>

                        @if (isset($config['data_selector']['table']) && is_array($config['data_selector']['table']))
                        <div class="col-md-12 ">
                            @php
                                $tema = '<td id="td-{k}">{v}</td>';
                                @endphp
                            <tm class="d-none">{{$tema}}</tm>
                            <table class="table table-hover" id="table-{{$config['type']}}-{{$config['data_selector']['campo']}}">
                                <thead>
                                    <tr>
                                        @foreach ($config['data_selector']['table'] as $kh=>$vh)
                                        <th id="th-{{$kh}}">{{$vh['label']}}</th>
                                        @endforeach
                                        <th class="text-right">{{__('Ação')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if (isset($config['data_selector']['list']) && is_array($config['data_selector']['list']) && isset($config['data_selector']['table']) && is_array($config['data_selector']['table']))
                                    @if (@$config['data_selector']['tipo']=='array')
                                        @foreach ($config['data_selector']['list'] as $klis=>$vlis)
                                            <tr id="tr-{{$klis}}-{{@$config['data_selector']['list'][$klis]['id']}}"><input id="inp-{{$klis}}-{{@$config['data_selector']['list'][$klis]['id']}}" type="hidden" name="{{@$config['campo']}}[]" value="{{@$config['data_selector']['list'][$klis]['id']}}"><input type="hidden" value="{{App\Qlib\Qlib::encodeArray(@$config['data_selector']['list'][$klis])}}" id="inp-list-{{$klis}}-{{@$config['data_selector']['list'][$klis]['id']}}">
                                                @foreach ($config['data_selector']['table'] as $kb=>$vb)
                                                    @if ($vb['type']=='text')
                                                        <td id="td-{{$kb}}">{{@$config['data_selector']['list'][$klis][$kb]}}</td>
                                                    @elseif ($vb['type']=='arr_tab')
                                                        <td id="td-{{$kb}}_valor">{{@$config['data_selector']['list'][$klis][$kb.'_valor']}}</td>
                                                    @endif
                                                @endforeach
                                                <td class="text-right">
                                                    <button type="button" btn-alt onclick="lib_htmlVinculo2('alt','{{App\Qlib\Qlib::encodeArray(@$config['data_selector'])}}','{{@$config['data_selector']['list'][$klis]['id']}}','{{$klis}}')" title="{{__('Editar')}}" class="btn btn-outline-secondary"><i class="fas fa-pencil-alt"></i> </button>
                                                    <button type="button" onclick="lib_htmlVinculo2('del','{{App\Qlib\Qlib::encodeArray(@$config['data_selector'])}}','{{@$config['data_selector']['list'][$klis]['id']}}','{{$klis}}')" class="btn btn-outline-danger" title="{{__('Remover')}}" > <i class="fa fa-trash" aria-hidden="true"></i> </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr id="tr-{{$config['data_selector']['list']['id']}}"><input type="hidden" name="{{$config['campo']}}" value="{{$config['data_selector']['list']['id']}}">
                                            @foreach ($config['data_selector']['table'] as $kb=>$vb)
                                                @if ($vb['type']=='text')
                                                    <td id="td-{{$kb}}">{{$config['data_selector']['list'][$kb]}}</td>
                                                @elseif ($vb['type']=='arr_tab')
                                                    <td id="td-{{$kb}}_valor">{{$config['data_selector']['list'][$kb.'_valor']}}</td>
                                                @endif
                                            @endforeach
                                            <td class="text-right">
                                                <button type="button" btn-alt onclick="lib_htmlVinculo('alt','{{App\Qlib\Qlib::encodeArray(@$config['data_selector'])}}')" title="{{__('Editar')}}" class="btn btn-outline-secondary"><i class="fas fa-pencil-alt"></i> </button>
                                                <button type="button" onclick="lib_htmlVinculo('del','{{App\Qlib\Qlib::encodeArray(@$config['data_selector'])}}')" class="btn btn-outline-danger" title="{{__('Remover')}}" > <i class="fa fa-trash" aria-hidden="true"></i> </button>
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                                </tbody>
                            </table>
                        </div>
                        @endif
                        @if ($config['script'])
                            @if(isset($config['dados']))
                                @include($config['script'],@$config['dados'])
                            @else
                                @include($config['script'])
                            @endif
                        @endif

                   </div>
                </div>
                <div class="card-footer text-muted">
                    {{@$footer}}
                </div>
            </div>
        </div>
    @elseif($config['type']=='text_array')
    <div class="form-group col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}" >
        @if ($config['label'])
        <label for="{{$config['campo']}}">{{$config['label']}}</label>
        @endif
        <input type="{{$config['type']}}" title="{{@$config['title']}}" class="form-control @error($config['campo']) is-invalid @enderror {{$config['class']}}" id="inp-{{$config['campo']}}" name="{{$config['campo']}}" aria-describedby="{{$config['campo']}}" placeholder="{{$config['placeholder']}}" value="@if(isset($config['value'])){{$config['value']}}@elseif($config['ac']=='cad'){{old($config['campo'])}}@endif" {{$config['event']}} />
        @error($config['campo'])
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    @elseif($config['type']=='text_upcase')
    <div class="form-group col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}" >
        @if ($config['label'])
        <label for="{{$config['campo']}}">{{$config['label']}}</label>
        @endif
        <input type="{{$config['type']}}" title="{{@$config['title']}}" class="form-control @error($config['campo']) is-invalid @enderror {{$config['class']}}" id="{{$config['id']}}" name="{{$config['campo']}}" aria-describedby="{{$config['campo']}}" placeholder="{{$config['placeholder']}}" onkeyup="upcase(event,this)" value="@if(isset($config['value'])){{$config['value']}}@elseif($config['ac']=='cad'){{old($config['campo'])}}@endif" {{$config['event']}} />
        @error($config['campo'])
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    @else
    <div class="form-group col-{{$config['col']}}-{{$config['tam']}} {{$config['class_div']}}" div-id="{{$config['campo']}}" >
        @if ($config['label'])
            <label for="{{$config['campo']}}">{{$config['label']}}</label>
        @endif
        <input type="{{$config['type']}}" title="{{@$config['title']}}" class="form-control @error($config['campo']) is-invalid @enderror {{$config['class']}}" id="{{$config['id']}}" name="{{$config['campo']}}" aria-describedby="{{$config['campo']}}" placeholder="{{$config['placeholder']}}" value="@if(isset($config['value'])){{$config['value']}}@elseif($config['ac']=='cad'){{old($config['campo'])}}@endif" {{$config['event']}} />
        @error($config['campo'])
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    @endif
@endif
