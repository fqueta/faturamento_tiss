@if (isset($config['acao_massa']) && is_array($config['acao_massa']))

<div class="dropdown open">
    <button class="btn btn-default dropdown-toggle" type="button" id="triggerId" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        Ação em massa
    </button>
    <div class="dropdown-menu" id="dp-exportar" aria-labelledby="triggerId">
        @foreach ($config['acao_massa'] as $k=>$v)
        <a class="dropdown-item" href="{{ $v['link'] }}" {{ @$v['event'] }}> <i class="{{ $v['icon'] }}"></i> {{ $v['label'] }}</a>
        @endforeach
    </div>
</div>
@endif
