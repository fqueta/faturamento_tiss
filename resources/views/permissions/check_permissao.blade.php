
<!--
As permissões são salvas no campo id_menu
são comparadas no metodo ver_permAdmin da class Qlib
ela é usada na difição dos gates
-->
@php
    $value = App\Qlib\Qlib::lib_json_array($config['value']);
@endphp
<div class="col-md-12">
    <form action="/" id="permissoes">
        <div class="row">
            <table class="table" id="table-permission">
                <thead>
                    <tr>
                        <th>Permissões de Acessos

                        </th>
                        <th>
                            <div class="text-right">
                                <label for="mark-all">
                                    <input type="checkbox" onclick="markAll(this)" id="mark-all" id="">
                                    Marcar todos
                                </label>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($config['dados']) && $config['dados'])

                        @foreach ($config['dados'] as $k=>$v)
                        <tr for="ver-{{$v->id}}">
                            <td>
                                <label for="ver-{{$v->id}}">
                                    <input id="ver-{{$v->id}}" type="checkbox" class="check-permission" name="id_menu[ler][{{$v->url}}]" id="ver_{{$v->id}}" @if (isset($value['ler'][$v->url] ) && $value['ler'][$v->url]=='s') checked @endif value="s" > {{$v->description}}
                                </label>
                            </td>
                            <td>

                                @if (!empty($v->submenu))
                                <table class="table table-hover">
                                    @foreach ($v->submenu as $val)
                                    <tr class="">
                                        <td class="d-flex">
                                            <div class="col-6 px-0">
                                                <label for="ver-{{$val->id}}">

                                                    <input type="checkbox" class="check-permission" name="id_menu[ler][{{$val->url}}]"  value="s" id="ver-{{$val->id}}" @if (isset($value['ler'][$val->url] ) && $value['ler'][$val->url]=='s') checked @endif >
                                                    {{$val->description}}
                                                </label>
                                            </div>
                                            @if (!empty($v->roles))
                                            <div class="col-6 px-0 text-right">

                                                @foreach ($v->roles as$kr=>$vr)
                                                <label for="{{$val->url}}-{{$kr}}">
                                                    <input type="checkbox" class="check-permission" name="id_menu[{{$kr}}][{{$val->url}}]" value="s" id="{{$val->url}}-{{$kr}}"  @if (isset($value[$kr][$val->url] ) && $value[$kr][$val->url]=='s') checked @endif > {{$vr}}
                                                </label>
                                                @endforeach
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                                @else

                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </form>
</div>
<script>
    function markAll(val){
        //if(val.checked){
            let checkboxes = document.querySelectorAll('.check-permission');
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i] != val)
                    checkboxes[i].checked = val.checked;
            }
        //}
    }
</script>
