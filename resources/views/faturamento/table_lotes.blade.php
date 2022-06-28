<table class="table table-hover" id="lista-guias">
    <thead>
        <tr>
            <th>
                #
            </th>
            <th>
                {{__('N° do Lote')}}
            </th>
            <th>
                {{__('Qtde. Guias')}}
            </th>
            <th>
                {{__('Valor do Lote')}}
            </th>
            <th>
                {{__('Referência')}}
            </th>
            <th>
                {{__('Acão')}}
            </th>
        </tr>
    </thead>
    <tbody>
        @if (isset($guias) && is_object($guias))
            @foreach ($guias as $kt=>$vt)
            @php
                $gArq = $vt['config']['geraGuia'];
                $xml = App\Qlib\Qlib::lib_json_array($gArq['dadosXml']);
                $link = isset($gArq['link'])?$gArq['link']:false;
                $vtGuias = 0;
                $qtdGuias = 0;
                if(isset($xml['dados'])&&is_array($xml['dados'])){
                    $qtdGuias = count($xml['dados']);
                    foreach ($xml['dados'] as $kto => $vto) {
                        $vtGuias += (double)$vto['valorTotalGeral'];
                    }
                }

            @endphp
            <tr>
                <td>
                    <input type="checkbox" class="checkbox" name="check-{{$vt['id']}}" value="{{$vt['id']}}">
                </td>
                <td>
                    {{$vt['id']}}
                </td>
                <td>
                    {{$qtdGuias}}
                </td>

                <td>
                    {{@$vtGuias}}
                </td>
                <td>
                    {{$vt['mes']}}/{{$vt['ano']}}
                </td>
                <td>
                    <a href="{{$link}}" target="_blank" class="btn btn-secondary"  rel="noopener noreferrer" download="">{{__('Baixar XML')}}</a>
                </td>
            </tr>
            @endforeach
        @endif

    </tbody>
</table>
