<div class="row">
    <style>
        .grade-img img{
            object-fit: cover;
            height: 150px;
        }
    </style>
    @if (isset($files)&&is_object($files))
        @foreach ($files as $k=>$file)
            @php
                if(isset($file->config)){
                    $file->config = App\Qlib\Qlib::lib_json_array($file->config);
                    $dominio_arquivo=App\Qlib\Qlib::qoption('dominio_arquivos').'/';
                    if(isset($file->config['extenssao']) && !empty($file->config['extenssao']))
                    {
                        if($file->config['extenssao'] == 'jpg' || $file->config['extenssao']=='png' || $file->config['extenssao'] == 'jpeg'){
                            $tipo = 'image';
                        }elseif($file->config['extenssao'] == 'doc' || $file->config['extenssao'] == 'docx') {
                            $tipo = 'word';
                        }elseif($file->config['extenssao'] == 'xls' || $file->config['extenssao'] == 'xlsx') {
                            $tipo = 'excel';
                        }else{
                            $tipo = 'download';
                        }
                    }else{
                        $tipo = 'download';
                    }
                }
            @endphp
            <div class="col-md-2 grade-img mb-2 text-center">
                @if ($tipo=='image')
                    <a href="{{$dominio_arquivo.$file->pasta}}" data-maxwidth="80%" title="{{$file->nome}}" data-gall="gall1" class="venobox">
                        <img src="{{$dominio_arquivo.$file->pasta}}" class="shadow w-100" alt="{{$file->nome}}" srcset="">
                    </a>
                @else
                    <a href="{{$dominio_arquivo.$file->pasta}}" target="_BLANK" data-maxwidth="80%" title="{{$file->nome}}" class="">
                        <i class="fas fa-file-download  fa-3x mt-4" aria-hidden="true"></i><br>
                        {{$file->nome}}
                    </a>
                @endif

            </div>
        @endforeach
    @endif
</div>
