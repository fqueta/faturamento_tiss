<table class="table table-hover table-striped dataTable {{$routa}}" style="width: 200%">
    <thead>
        <tr>
            <th class="text-center d-print-none"><input type="checkbox" name="todos" id=""></th>
            <th class="text-center d-print-none">...</th>
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
            <tr ondblclick="window.location='{{ route('familias.edit',['id'=>$familia->id]) }}'" class="@if (isset($_GET['idCad']) && $_GET['idCad']==$familia->id) bg-info @endif ">
                <td>
                    <input type="checkbox" name="check_{{$familia->id}}" id="check_{{$familia->id}}">
                </td>
                @if ($config['exibe']=='html')
                    <td class="text-right d-flex d-print-none">
                        <a href=" {{ route('familias.edit',['id'=>$familia->id]) }} " class="btn btn-light mr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                            </svg>
                        </a>
                        <form id="frm-{{ $familia->id }}" action="{{ route('familias.destroy',['id'=>$familia->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" data-del="true" data-id="{{$familia->id}}" name="button" title="Excluir" class="btn btn-light">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                </svg>
                            </button>
                        </form>
                    </td>
                @endif
                @if (isset($campos_tabela) && is_array($campos_tabela))
                    @foreach ($campos_tabela as $kd=>$vd)
                        @if (isset($vd['label']) && $vd['active'])
                            @if (isset($vd['type']) && $vd['type']=='select')
                                <td>{{@$vd['arr_opc'][$familia->$kd]}}</td>
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
