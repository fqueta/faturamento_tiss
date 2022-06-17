<div class="alert alert-{{$config['color']}}" role="alert">
  <!--<h4 class="alert-heading">sucesso</h4>-->
  {{$config['mens']}}
  <!--<p class="mb-0"></p>-->
  <button class="close" type="button" data-dismiss="alert" aria-hidden="true">×</button>
</div>
@if (isset($config['time']))
    <script>
        setTimeout(function(){
            $('.alert').hide('slow');
        }, "{{$config['time']}}");
    </script>
@endif
