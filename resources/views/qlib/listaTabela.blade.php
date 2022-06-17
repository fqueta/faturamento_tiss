@php
    $style = isset($conf['style']) ? $conf['style'] : false;
    $routa = isset($conf['routa']) ? $conf['routa'] : false;
    $redirect = isset($conf['redirect']) ? $conf['redirect'] : @$routa;
    $campos_tabela = isset($conf['campos_tabela']) ? $conf['campos_tabela'] : false;
    $dados = isset($conf['dados']) ? $conf['dados'] : false;
    $sb = '?';
    if(isset($_GET['page'])){
        $sb = '?page='.$_GET['page'].'&';
    }

    $redirect = route($redirect.'.index').$sb;
@endphp
<style media="print">
    #DataTables_Table_0_wrapper .row:first-child{
        display: none;
    }
    .table td{
        padding: 0%;
    }
    .table thead th{
        padding: 0%;
    }
    #lista .card-body{
        padding: 0%;
    }
</style>
<table class="table table-hover table-striped dataTable {{$routa}}" style="{{@$style}}">
    <thead>
        <tr>
            <th class="text-center d-print-none"><input onclick="gerSelect($(this));" type="checkbox" name="todos" id=""></th>
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
        @if(isset($dados))
            @foreach($dados as $key => $val)
            <tr style="cursor: pointer" ondblclick="window.location='{{ route($routa.'.edit',['id'=>$val->id]) }}?redirect={{urlencode($redirect.'idCad='.$val->id)}}'"  id="tr_{{$val->id}}" class="@if (isset($_GET['idCad']) && $_GET['idCad']==$val->id) table-info @endif" title="DÊ DOIS CLIQUES PARA ABRIR">
                    <td>
                        <input type="checkbox" class="checkbox" onclick="color_select1_0(this.checked,this.value);" value="{{$val->id}}" name="check_{{$val->id}}" id="check_{{$val->id}}">
                    </td>

                    <td class="text-right d-flex d-print-none">
                        @can('update',$routa)
                            @if ($routa=='quadras')
                                <a title="Mapa" href=" {{ route('mapas.'.$routa,['id'=>$val->id]) }}?redirect={{$redirect.'idCad='.$val->id}} " title="visualizar" class="btn btn-sm btn-outline-secondary mr-2">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </a>
                            @endif
                            @if ($routa=='familias')
                                <a href=" {{ route($routa.'.show',['id'=>$val->id]) }}?redirect={{$redirect.'idCad='.$val->id}} " title="visualizar" class="btn btn-sm btn-outline-secondary mr-2">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @endif
                            <a href=" {{ route($routa.'.edit',['id'=>$val->id]) }}?redirect={{$redirect.'idCad='.$val->id}} " title="Editar" class="btn btn-sm btn-outline-secondary mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                </svg>
                            </a>
                            @else
                            @if ($routa=='familias')
                                <a href=" {{ route($routa.'.show',['id'=>$val->id]) }}?redirect={{$redirect.'idCad='.$val->id}} " title="visualizar" class="btn btn-sm btn-outline-secondary mr-2">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @else
                                <a href=" {{ route($routa.'.edit',['id'=>$val->id]) }}?redirect={{$redirect.'idCad='.$val->id}} " class="btn btn-sm btn-outline-primary mr-2" title="Visualizar">
                                    <i class="fas fa-search"></i>
                                </a>
                            @endif

                        @endcan
                        @can('delete',$routa)
                            <form id="frm-{{ $val->id }}" action="{{ route($routa.'.destroy',['id'=>$val->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" data-del="true" data-id="{{$val->id}}" name="button" title="Excluir" class="btn btn-sm btn-outline-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                    </svg>
                                </button>
                            </form>
                        @endcan
                    </td>
                @if (isset($campos_tabela) && is_array($campos_tabela))
                    @foreach ($campos_tabela as $kd=>$vd)
                        @if (isset($vd['label']) && $vd['active'])
                            @if (isset($vd['type']) && ($vd['type']=='select' || $vd['type']=='selector'))
                                <td class="{{$kd}}">{{@$vd['arr_opc'][$val->$kd]}}</td>
                            @elseif (isset($vd['type']) && ($vd['type']=='select_multiple'))
                                @php
                                    $nk = str_replace('[]','',$kd);
                                    $arr = $val->$nk;
                                    $td = false;
                                    if(is_array($arr)){
                                        foreach ($arr as $k => $v) {
                                            $td .= $vd['arr_opc'][$v].',';
                                        }
                                    }
                                @endphp
                                <td class="{{$kd}}">{{@$td}}</td>
                            @elseif (isset($vd['type']) && $vd['type']=='chave_checkbox' && isset($vd['arr_opc'][$val->$kd]))
                                <td class="{{$kd}}">{{$vd['arr_opc'][$val->$kd]}}</td>
                            @elseif(isset($vd['cp_busca']) && !empty($vd['cp_busca']))
                                @php
                                    $cp = explode('][',$vd['cp_busca']);
                                @endphp
                                @if (isset($cp[1]))
                                    <td class="{{$cp[1]}}">{{ @$val[$cp[0]][$cp[1]] }}</td>
                                @endif
                            @else
                                <td class="{{$kd}}">
                                    @php
                                        if(isset($vd['arr_opc']) && isset($vd['arr_opc'][$val->$kd])){
                                            $td = $vd['arr_opc'][$val->$kd];
                                        }else{
                                            $td = $val->$kd;
                                        }
                                    @endphp
                                    {{$td}}
                                </td>
                            @endif
                        @endif
                    @endforeach
                @else

                    <td> {{$val->id}} </td>
                    <td> {{$val->nome_completo}} </td>
                    <td> {{$val->area_alvo}} </td>
                    <td> {{$val->obs}} </td>
                @endif
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
