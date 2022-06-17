@if (isset($config['svg_file']) && isset($config['dados']))
<style type="text/css">
	    .mapa .lote {
            opacity: 0;
            transition: all ease 0.4s;
            cursor: pointer;
            fill: #39c8ef;
        }
        .mapa .lote:hover {
            opacity: 0.4;
            transition: all ease 0.4s;
        }
        .input-group-mapa {
            max-width: 250px;
            z-index: 2;
        }

        .mini-card {
            position: absolute;
            min-width: 250px;
            display: none;
            z-index: 2;
        }

        .mini-card.active {
            position: absolute;
            min-width: 250px;
            display: block;
        }

        .mini-card-geral {
            position: absolute;
            right: 1rem;
            bottom: 1rem;
            min-width: 250px;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 2;
        }

        .mini-card-geral h6 {
            font-size: 15px;
        }

        .mini-card-geral p {
            font-size: 13px;
            margin-bottom: 8px;
        }
        #view-mapa{
            overflow: auto;height: 600px;
        }
</style>
<style media="print">
    #view-mapa{
        height: 100%;
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between d-print-none">
        <div class="input-group input-group-sm input-group-mapa">
            <div class="input-group-prepend">
                <label class="input-group-text" for="select_bairro">{{__('Bairro')}}</label>
            </div>
            <select class="custom-select" onchange="carregaQuadras($(this).val());" id="select_bairro" id-atual="{{$config['dados']['bairro']}}">
                <option value="" class="option_select" selected>{{__('Selecione o bairro')}}...</option>
                @if (isset($config['dados']['arr_bairros']) && is_array($config['dados']['arr_bairros']))
                    @foreach ($config['dados']['arr_bairros'] as $kb=>$vb)
                        <option class="opcs" @if($config['dados']['bairro']==$kb) selected @endif value="{{$kb}}">{{$vb}}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="input-group input-group-sm input-group-mapa" div-id="quadra">
            <div class="input-group-prepend">
                <label class="input-group-text" for="select_quadra">{{__('Quadra')}}</label>
            </div>
            <select class="custom-select" id="select_quadra" id-atual="{{$config['dados']['id']}}">
                <option class="option_select" value="" selected>{{__('Selecione a quadra')}}...</option>
                @if (isset($config['dados']['arr_quadras']) && is_array($config['dados']['arr_quadras']))
                    @foreach ($config['dados']['arr_quadras'] as $kb=>$vb)
                        <option class="opcs" @if($config['dados']['id']==$kb) selected @endif value="{{$kb}}">{{$vb}}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="card-body" id="view-mapa">
            <div class="mini-card border-0 active">
            </div>
            <div class="card mini-card-geral map border-light">
                <div class="card-body text-light text-center p-2">
                    <h6 class="border-bottom border-secondary pb-2">{{@$config['dados']['arr_bairros'][$config['dados']['bairro']]}}</h6>
                    <p><b>{{__('Quadra')}}:</b> {{$config['dados']['nome']}}</p>
                    <p><b>{{__('Total de lotes')}}:</b> <span class="total-lotes">{{@$config['dados']['lotes']}}</span></p>
                    <p><b>{{__('Total de famílias')}}:</b> <span class="total-familias">{{@$config['dados']['familias']}}</span></p>
                </div>
            </div>
            <!--include('mapas.'.$config['local'].'.'.$config['dados']['id'])-->
            @php
                $p = $_SERVER['DOCUMENT_ROOT'].$config['svg_file'];
                include $p;
            @endphp
        <div class="painel-zoom d-print-none" style="position: absolute;left:10px;bottom:50%;width: 40px;">
            <button type="button" onclick="zoom('p')" title="{{__('Aumentar mapa')}}" id="zoom-p" class="btn btn-outline-primary mb-1"><i class="fas fa-plus"></i></button>
            <button type="button" onclick="zoom('r')" id="zoom-r"  title="{{__('Restaurar tamanho')}}" class="btn btn-outline-secondary mb-1" ><i class="fas fa-sync"></i></button>
            <button type="button" onclick="zoom('m')" title="{{__('Diminuir mapa')}}" id="zoom-m" class="btn btn-outline-primary" ><i class="fas fa-minus"></i></button>
        </div>
    </div>
</div>
@endif
