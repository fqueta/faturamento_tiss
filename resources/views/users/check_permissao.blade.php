
@php
    $value = App\Qlib\Qlib::lib_json_array($config['value']);
@endphp
<div class="col-md-12">
    <form action="/" id="permissoes">
        <div class="row">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2">Permissões de Acessos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($config['dados'] as $k=>$v)
                    <tr for="ver-{{$v->id}}">
                        <td>
                            <label for="ver-{{$v->id}}">
                                <input id="ver-{{$v->id}}" type="checkbox" name="id_menu[{{$v->url}}]" id="ver_{{$v->id}}" @if (isset($value[$v->url] ) && $value[$v->url]=='s') checked @endif value="s" > {{$v->description}}
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

                                                        <input type="checkbox" name="id_menu[{{$val->url}}]"  value="s" id="ver-{{$val->id}}" @if (isset($value[$val->url] ) && $value[$val->url]=='s') checked @endif >
                                                        {{$val->description}}
                                                    </label>
                                                </div>
                                                @if (!empty($v->roles))
                                                <div class="col-6 px-0 text-right">

                                                    @foreach ($v->roles as $vr)
                                                    <label for="{{$val->url}}-{{$vr}}">
                                                        <input type="checkbox" name="id_{{$vr}}[{{$val->url}}]" value="s" id="{{$val->url}}-{{$vr}}"> {{$vr}}
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
                </tbody>
            </table>
        </div>
    </form>
</div>
