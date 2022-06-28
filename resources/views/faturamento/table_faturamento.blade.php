<table class="table table-hover" id="lista-guias">
    <thead>
        <tr>
            <th>
                #
            </th>
            <th>
                {{__('ID')}}
            </th>
            <th>
                {{__('Data de Preenchimento')}}
            </th>
            <th>
                {{__('Número da Guia')}}
            </th>
            <th>
                {{__('N° da Carteira')}}
            </th>
            <th>
                {{__('Valor da Guia')}}
            </th>
        </tr>
    </thead>
    <tbody>
        @if (isset($guias) && is_object($guias))
            @foreach ($guias as $kt=>$vt)
            <tr>
                <td>
                    <input type="checkbox" class="checkbox" name="check-{{$vt['id']}}" value="{{$vt['id']}}">
                </td>
                <td>
                    {{$vt['id']}}
                </td>
                <td>
                    {{App\Qlib\Qlib::dataExibe($vt['created_at'])}}
                </td>

                <td>
                    {{@$vt['numero_guia']}}
                </td>
                <td>
                    {{@$vt['config']['numeroCarteira']}}
                </td>
                <td>
                    {{@$vt['config']['valorTotalGeral']}}
                </td>
            </tr>
            @endforeach
        @endif

    </tbody>
</table>
