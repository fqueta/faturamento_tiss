@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Erro: 404</h1>
@stop

@section('content')
<section class="content">
    <div class="error-page">
        <h2 class="headline text-warning">404</h2>
        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Pagina não encontrada.</h3>
            <p>
                Não foi possível encontrar a página que você estava procurando possivelmente está em contrução. Enquanto isso, <a href="{{route('home')}}">você pode retornar ao painel</a>.
            </p>
            <form class="search-form">
                <div class="input-group">
                    <!--<input type="text" name="search" class="form-control" placeholder="Search">
                    <div class="input-group-append">
                        <button type="submit" name="submit" class="btn btn-warning"><i class="fas fa-search"></i>
</button>
                    </div>-->
                </div>

            </form>
        </div>

    </div>

</section>
@stop

@section('css')
    <link rel="stylesheet" href=" {{url('/')}}/css/lib.css">
@stop

@section('js')
    <script src=" {{url('/')}}/js/lib.js"></script>
@stop
