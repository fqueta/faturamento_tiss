
<!doctype html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>@yield('title')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    @yield('css')

  </head>

  <body>
    <div id="preload">
        <div class="lds-dual-ring"></div>
    </div>
    <header>
      @yield('nav')
    </header>
    <main role="main">
        @yield('content')
    </main>

    <footer class="text-muted">
        @yield('footer')
    </footer>

    @yield('js')
  </body>
</html>
