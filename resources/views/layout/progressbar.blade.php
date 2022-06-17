@if(isset($estatisticas['progressBar']['enviado']))
    <div class="col-md-12">
          <div class="progress" title="relatorios enviados">
            <div class="progress-bar" role="progressbar" style="width: {{$estatisticas['progressBar']['enviado']}}%;" aria-valuenow="{{$estatisticas['progressBar']['enviado']}}" aria-valuemin="0" aria-valuemax="100">{{$estatisticas['progressBar']['enviado']}}% relatorios enviados</div>
          </div>
    </div> 
@endif