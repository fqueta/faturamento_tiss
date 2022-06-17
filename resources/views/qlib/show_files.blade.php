@can('ler_arquivos',$routa)
        <div class="card card-primary card-outline mb-5">
            <div class="card-header">
                <h3 class="card-title">{{__('Arquivos')}}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                {!! App\Qlib\Qlib::show_files([
                    'token'=>$value['token'],
                ]) !!}
            </div>
        </div>
        @endcan
