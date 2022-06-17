<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>formulario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  </head>
  <body>
    <div class="col-md-12">
        <form class="" action="{{}}" method="post">
          <div class="form-group">
            <label for="grupo">Nome do grupo</label>
            <input type="tex t" class="form-control" id="grupo" aria-describedby="grupo" placeholder="Nome do grupo">
          </div>
          <div class="form-group">
            <label for="obs">Observação</label><br>
            <textarea name="obs" rows="8" cols="80"></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Enviar</button>
          @csrf
        </form>

    </div>
  </body>
</html>
