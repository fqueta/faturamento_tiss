@if (isset($config['parte']) && $config['parte'] =='modal')
    <!-- Button trigger modal -->
    @if (isset($config['botao'])&& $config['botao'])
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#{{@$config['id']}}">
        Abrir
    </button>
    @endif

    <!-- Modal -->
    <div class="modal fade" id="{{@$config['id']}}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog {{@$config['tam']}}" role="document">
            <div class="modal-content">
                    <div class="modal-header">
                            <h5 class="modal-title">{{@$config['titulo']}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="col-md-12 conteudo">
                            {{@$config['conteudo']}}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @if (isset($config['botao_fechar']))
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Fechar') }}</button>
                    @endif
                    @if (isset($config['botao_acao']))
                        <button type="button" class="btn btn-primary">Save</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#exampleModal').on('show.bs.modal', event => {
            var button = $(event.relatedTarget);
            var modal = $(this);
            // Use above variables to manipulate the DOM

        });
    </script>
@elseif($config['parte'] =='resumo_index')
    <div class="col-md-12 d-print-none">
        <div class="row pl-2 pr-2">
            @foreach ($config['resumo'] as $k=>$v)

                <div class="col-md-3 info-box mb-3">
                    <span class="info-box-icon bg-default elevation-1"><i class="{{$v['icon']}}"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{$v['label']}}</span>
                        <span class="info-box-number">{{ $v['value'] }}</span>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endif
