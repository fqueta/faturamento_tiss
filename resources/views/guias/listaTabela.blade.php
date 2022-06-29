@php
    $style = isset($config['style']) ? $config['style'] : false;
    $redirect = isset($config['redirect']) ? $config['redirect'] : @$routa;
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
            <th class="text-center d-print-none">&nbsp;</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
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
                            <a href=" {{ route($routa.'.edit',['id'=>$val->id]) }}?redirect={{$redirect.'idCad='.$val->id}} " title="Editar" class="btn btn-sm btn-outline-secondary mr-1">
                                <i class="fas fa-pen    "></i>
                            </a>
                            <a href=" {{ route('guias.print',['id'=>$val->id]) }}?redirect={{$redirect.'idCad='.$val->id}} " title="Imprimir" class="btn btn-sm btn-outline-secondary mr-1" btn-print>
                                <i class="fas fa-print"></i>
                            </a>
                            @else
                            @if ($routa=='familias')
                                <a href=" {{ route($routa.'.show',['id'=>$val->id]) }}?redirect={{$redirect.'idCad='.$val->id}} " title="visualizar" class="btn btn-sm btn-outline-secondary mr-1">
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
                                    <i class="fas fa-trash    "></i>
                                </button>
                            </form>
                        @endcan
                </td>
                <td>
                    <div class="col-md-12">
                        <label for="n-guia">N° Guia:</label>
                        <span>
                            {{$val->numero_guia}}
                        </span>
                    </div>
                    <div class="col-md-12">
                        <label for="n-paciente">Paciente:</label>
                        <span>
                            {{$val->nome}}
                        </span>
                    </div>
                    <div class="col-md-12">
                        <label for="n-executante">Executante:</label>
                        <span>
                            @php
                                $executante = isset($val->config['nomeContratado'])?$val->config['nomeContratado']:'NÃO INFORMADO';
                                echo $executante;
                            @endphp
                        </span>
                    </div>
                </td>
                <td>
                    <div class="col-md-12">
                        <label for="n-guia">Preechimento:</label>
                        <span>
                            {{App\Qlib\Qlib::dataExibe($val->created_at)}}
                        </span>
                    </div>
                    <div class="col-md-12">
                        <label for="n-paciente">Situação:</label>
                        <span>
                            @php
                                $situacao = 'SEM LOTE';
                                if($val->id_lote){
                                    $situacao = 'LOTE N° '.$val->id_lote;
                                }
                                echo $situacao;
                            @endphp
                        </span>
                    </div>
                </td>
            </tr>
            @endforeach
        @endif
    </tbody>
</table>
