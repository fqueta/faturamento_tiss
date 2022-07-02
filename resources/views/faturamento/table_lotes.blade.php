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
                $qtdGuias = 0;
                $vtGuias = 0;
                $link = false;
                if($vt['config']){
                    $gArq = isset($vt['config'])?$vt['config']:false;
                    $link = isset($gArq['link'])?$gArq['link']:false;
                    $xml = App\Qlib\Qlib::lib_json_array($gArq['dadosXml']);
                    if(isset($xml['dados'])){
                        if(is_array($xml['dados'])){
                            foreach ($xml['dados'] as $kto => $vto) {
                                $qtdGuias ++;
                                $vtGuias += @$vto['valorTotalGeral'];
                            }
                        }
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
                    {{@$qtdGuias}}
                </td>

                <td>
                    {{@$vtGuias}}
                </td>
                <td>
                    {{$vt['mes']}}/{{$vt['ano']}}
                </td>
                <td>
                    {{-- <a href="{{@$link}}" target="_blank" class="btn btn-secondary" download="" rel="noopener noreferrer">{{__('Baixar XML')}}</a> --}}
                    <button type="button"  onclick="atualizarFechamento('{{$vt['id']}}','{{implode('_',App\Qlib\Qlib::lib_json_array($vt['guias']))}}')" class="btn btn-secondary">{{__('Baixar XML')}}</button>
                </td>
            </tr>
            @endforeach
        @endif

    </tbody>
</table>
