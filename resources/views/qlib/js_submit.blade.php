<script>
    $(function(){

        $('[type="submit"]').on('click',function(e){
            e.preventDefault();
            let btn_press = $(this).attr('btn');
            submitFormulario($('#{{$config['frm_id']}}'),function(res){
                if(res.exec){
                    lib_formatMensagem('.mens',res.mens,res.color);
                }
                if(btn_press=='sair'){
                    if(pop){
                            window.opener.popupCallback_vinculo(res); //Call callback function
                            window.close(); // Close the current popup
                            return;
                    }
                    var redirect = $('[btn-volter="true"]').attr('redirect');

                    if(redirect){
                        if(pop){
                            window.opener.popupCallback(function(){
                                alert('pop some data '+redirect);
                            }); //Call callback function
                            window.close(); // Close the current popup
                            return;
                        }else{
                            window.location = redirect;
                        }
                    }else if(res.return){
                        if(pop){
                            window.opener.popupCallback(function(){
                                alert('pop some data '+res.return);
                            }); //Call callback function
                            window.close(); // Close the current popup
                            return;
                        }else{
                            window.location = res.return;
                        }
                    }
                }else if(btn_press=='permanecer'){
                    if(res.redirect){
                        window.location = res.redirect;
                    }
                }
                if(res.errors){
                    alert('erros');
                    console.log(res.errors);
                }
            });
        });
    });
</script>
