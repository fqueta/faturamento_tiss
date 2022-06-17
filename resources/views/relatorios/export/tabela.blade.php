<table>
    <thead>
        <tr>
        @if (isset($campos_tabela) && is_array($campos_tabela))
                @foreach ($campos_tabela as $kh=>$vh)
                    @if (isset($vh['label']) && $vh['active'])
                        <th style="{{ @$vd['style'] }}">{{$vh['label']}}</th>
                    @endif
                @endforeach

        @else
            <th>#</th>
            <th>Nome</th>
            <th>Area</th>
            <th>Obs</th>
        @endif
        </tr>
    </thead>
    <tbody>
        @if(isset($familias))
            @foreach($familias as $key => $familia)
            <tr>
                @if (isset($campos_tabela) && is_array($campos_tabela))
                    @foreach ($campos_tabela as $kd=>$vd)
                        @if (isset($vd['label']) && $vd['active'])
                            @if (isset($vd['type']) && ($vd['type']=='select' || $vd['type']=='selector'))
                                <td>{{@$vd['arr_opc'][$familia->$kd]}}</td>
                            @elseif(isset($vd['type']) && ($vd['type']=='select_multiple'))
                                @php
                                    $nk = str_replace('[]','',$kd);
                                    $arr = $familia->$nk;
                                    $td = false;
                                    if(is_array($arr)){
                                        foreach ($arr as $k => $v) {
                                            $td .= $vd['arr_opc'][$v].',';
                                        }
                                    }
                                @endphp
                                <td class="{{$kd}}">{{@$td}}</td>
                            @elseif(isset($vd['cp_busca']) && !empty($vd['cp_busca']))
                                @php
                                    $cp = explode('][',$vd['cp_busca']);
                                @endphp
                                @if (isset($cp[1]))
                                    <td class="{{$cp[1]}}">{{ @$familia[$cp[0]][$cp[1]] }}</td>
                                @endif
                            @else
                                <td>{{$familia->$kd}}</td>
                            @endif
                        @endif
                    @endforeach
                @else

                    <td> {{$familia->id}} </td>
                    <td> {{$familia->nome_completo}} </td>
                    <td> {{$familia->area_alvo}} </td>
                    <td> {{$familia->obs}} </td>
                @endif
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
