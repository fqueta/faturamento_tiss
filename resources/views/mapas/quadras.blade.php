@extends('adminlte::page')

@section('title', 'Data Brasil - Mapas Quadras')

@section('content_header')
    <!--<h3>{{$titulo}}</h3>-->
@stop
@section('content')
<div class="row">
    <div class="col-md-12 mens">
    </div>
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">{{$titulo}}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool d-print-none" data-card-widget="collapse" title="Collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                {!! App\Http\Controllers\MapasController::exibeMapas($config) !!}
            </div>
        </div>
    </div>
    @include('qlib.btnedit')
</div>
@stop

@section('css')
    @include('qlib.csslib')
@stop

@section('js')
    @include('qlib.jslib')
    @include('mapas.jslib')
@stop

