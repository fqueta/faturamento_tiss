@if ($config['parte']=='painel')
        <link rel="stylesheet" href="{{url('/')}}/css/dropzone.min.css" type="text/css" />
        <script src="{{url('/')}}/js/dropzone.min.js"></script>
        <style>
            .grade-img img{
                object-fit: cover;
                height: 50px;
                border-radius: 50%;
            }
        </style>
        <!-- Button trigger modal -->
        <div class="row">
            <div class="col-md-12 mb-2">
                <span id="lista-files">
                    {{App\Qlib\Qlib::gerUploadAquivos([
                        'parte'=>'lista',
                        'token_produto'=>$config['token_produto'],
                        'tipo'=>'list',
                        'listFiles'=>@$config['listFiles'],
                        'routa'=>@$config['routa'],
                        'arquivos'=>@$config['arquivos'],
                        ])}}

                </span>
            </div>
            <div class="col-md-12 text-center">
                @can('create',$config['routa'])
                    @if (isset($config['arquivos']) && $config['arquivos'])
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modelId"> <i class="fas fa-upload"></i>
                            {{ __('Enviar arquivos') }}
                        </button>
                    @endif
                @endcan

            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Uploads de arquivos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form id="file-upload" action="{{route('uploads.store')}}" method="post" class="dropzone" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="token_produto" value="{{$config['token_produto']}}" />
                                <input type="hidden" name="pasta" value="{{$config['pasta']}}" />
                                <input type="hidden" name="arquivos" value="{{$config['arquivos']}}" />
                                <input type="hidden" name="typeN" value="{{@$config['typeN']}}" />
                                <div class="fallback">
                                    <input name="file" type="file" multiple />
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  onclick="visualizaArquivos('{{$config['token_produto']}}','{{route('uploads.index')}}')" data-dismiss="modal">{{__('Fechar')}}</button>
                        <!--<button type="button" class="btn btn-primary">{{__('Visualizar')}}</button>-->
                    </div>
                </div>
            </div>
        </div>
        <!--
            <script>
                $('#exampleModal').on('show.bs.modal', event => {
                    var button = $(event.relatedTarget);
                    var modal = $(this);
                    // Use above variables to manipulate the DOM

                });
            </script>
        -->
@endif
@if ($config['parte']=='lista' && isset($config['listFiles']) && is_object($config['listFiles']))
    <ul class="list-group">
        @php
            $dominio_arquivo=App\Qlib\Qlib::qoption('dominio_arquivos').'/';
        @endphp
        @foreach ($config['listFiles'] as $k=>$vl)

        <li class="list-group-item d-flex justify-content-between align-items-center" id="item-{{$vl['id']}}">
                @if ($vl['tipo_icon']=='image')
                <div class="row w-100">
                    <div class="col-3 grade-img">
                        <a href="{{$dominio_arquivo.$vl['pasta']}}" data-maxwidth="80%" title="{{$vl['nome']}}" data-gall="gall0" class="venobox">
                            <img class="shadow w-100" src="{{$dominio_arquivo.$vl['pasta']}}" alt="{{$vl['nome']}}">
                        </a>
                    </div>
                    <div class="col-9">
                        {{$vl['nome']}}
                    </div>
                </div>
                @else
                <div class="row w-100">
                    <div class="col-3 grade-img text-center">
                        <a href="{{url('/storage')}}/{{$vl['pasta']}}" target="_blank" rel="">
                            <i class="fas fa-file-{{$vl['tipo_icon']}}  fa-2x"></i>
                        </a>
                    </div>
                    <div class="col-9">
                        <a href="{{url('/storage')}}/{{$vl['pasta']}}" target="_blank" rel="">
                        {{$vl['nome']}}
                        </a>
                    </div>
                </div>
                @endif
            @can('delete',$config['routa'])
                <button type="button" onclick="excluirArquivo('{{$vl['id']}}','{{route('uploads.destroy',['id'=>$vl['id']])}}')" class="btn btn-default" title="Excluir"><i class="fas fa-trash "></i></button type="button">
            @endcan
        </li>
        @endforeach
    </ul>
@endif

