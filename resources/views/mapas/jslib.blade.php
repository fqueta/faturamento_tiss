
<script>
    $( function() {
        if($(window).width()>368 ){
            $( "#svg-img" ).draggable();
        }
        $(".lote").dblclick(function(){
            var id = $(this).attr('id');
            lib_conteudoMapa(id,'lotes','quadras');
        });
    });
    </script>
    <script>
        let lotes = document.querySelectorAll('#svg-img .lote').length;
        document.querySelector('.total-lotes').innerHTML=lotes;
        let select_bairro = document.querySelector('#select_bairro');
        let select_quadra = document.querySelector('#select_quadra');
        window.onload = function () {
            select_bairro.addEventListener('change',function () {
                let id_atual = this.getAttribute('id-atual');
                if(id_atual==this.value){
                    document.querySelector('#svg-img').style.display = 'block';
                    document.querySelector('.mini-card-geral').style.display = 'block';
                    document.querySelector('.painel-zoom').style.display = 'block';
                }else{
                    document.querySelector('#svg-img').style.display = 'none';
                    document.querySelector('.mini-card-geral').style.display = 'none';
                    document.querySelector('.painel-zoom').style.display = 'none';
                }

            });
            select_quadra.addEventListener('change',function () {
                if(this.value!=''){
                    let url = window.location.href;
                    let quadraAt = this.getAttribute('id-atual');
                    let urlPart = window.location.pathname;
                    if(urlPart=='/home'){
                        url = '/mapas/quadras/'+this.value+'?redirect=/quadras?idCad='+this.value;
                    }else{
                        url = url.replaceAll(quadraAt,this.value);
                    }
                    window.location = url;
                }
            });
        }
    </script>
