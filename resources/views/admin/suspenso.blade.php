@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    {{-- <h1>Suspenso</h1> --}}
@stop

@section('content')
@include('admin.partes.header')
<section class="content">
    <div class="error-page text-center">
        {{-- <div class="error-content"> --}}
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Acesso temporariamente suspenso.</h3>
            <p>
                Entre em contato com o suporte<!--<a href="{{route('home')}}">você pode retornar ao painel</a>-->.
            </p>
        {{-- </div> --}}

    </div>

</section>
@stop

@section('css')
    <link rel="stylesheet" href=" {{url('/')}}/css/lib.css">
@stop

@section('js')
    <script src=" {{url('/')}}/js/lib.js"></script>
@stop
