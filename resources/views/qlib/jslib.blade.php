@include('qlib.partes_html',['config'=>[
    'parte'=>'modal',
    'id'=>'modal-geral',
    'conteudo'=>false,
    'botao'=>false,
    'botao_fechar'=>true,
    'tam'=>'modal-lg',
]])
<script src="{{url('/')}}/js/jquery.maskMoney.min.js"></script>
<script src="{{url('/')}}/js/jquery-ui.min.js"></script>
<script src="{{url('/')}}/js/jquery.inputmask.bundle.min.js"></script>
<script src="{{url('/')}}/vendor/summernote/summernote.min.js"></script>
<script src="{{url('/')}}/vendor/venobox/venobox.min.js"></script>
<script src=" {{url('/')}}/js/lib.js"></script>
<script>
    $(function(){
        $('.dataTable').DataTable({
                "paging":   false,
                stateSave: true,
                language: {
                    url: '/DataTables/datatable-pt-br.json'
                }
        });
        carregaMascaraMoeda(".moeda");
        $('[selector-event]').on('change',function(){
            initSelector($(this));
        });
        $('[vinculo-event]').on('click',function(){
            var funCall = function(res){};
            initSelector($(this));
        });

        $('.select2').select2();
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });
        var urlAuto = $('.autocomplete').attr('url');
        $( ".autocomplete" ).autocomplete({
            source: urlAuto,
            select: function (event, ui) {
                //var sec = $(this).attr('sec');
                lib_listarCadastro(ui.item,$(this));
            },
        });
        $('.summernote').summernote({
            height: 250,
            placeholder: 'Digite o conteudo',
        });
        new VenoBox({
            selector: ".venobox",
            numeration: true,
            infinigall: true,
            share: false,
            spinner: 'rotating-plane'
        });
    });
</script>
