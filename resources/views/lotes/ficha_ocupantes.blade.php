@extends('layout.app')
@section('content')
    @if (isset($dados))
        <table class="table">
            <!--
            <thead>
                <tr>
                    <td>
                        <div align="center">
                             $cabecario !!}
                        </div>
                    </td>
                </tr>
            </thead>-->
            <tbody>
                    <tr>
                        <td>{!! $dados !!}</td>
                    </tr>
            </tbody>
        </table>

    @endif
<div class="div-salvar d-print-none" style="background-color: #fff">
    <button class="btn btn-outline-secondary" onclick="window.close()"> <i class="fa fa-chevron-left" aria-hidden="true"></i> Fechar</button>
    <button class="btn btn-primary" onclick="window.print()"> <i class="fa fa-print" aria-hidden="true"></i> {{__('Imprimir')}}</button>
</div>
@endsection
@section('css')
    @include('qlib.csslib')
@stop

