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
                            @if (isset($vd['type']) && $vd['type']=='select')
                                <td>{{$vd['arr_opc'][$familia->$kd]}}</td>
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
