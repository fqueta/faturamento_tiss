<script>
    $(function(){
        $("#{{$config['frm_id']}}").validate({
            submitHandler: function(form) {
                // some other code
                // maybe disabling submit button
                // then:
                //sub(form);
                //alert('exetua');
                submitFormulario($("#{{$config['frm_id']}}"),function(res){

                    let btn_press = $('#btn-press-salv').html();
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
                /*
                $(form).submit(function(e){
                    e.preventDefault();

                });
                */

            }
        });
        function btnPres(obj){
            $('#btn-press-salv').remove();
            var btn = '<span id="btn-press-salv" class="d-none">'+obj.attr('btn')+'</span>';
            $(btn).insertAfter(obj);
        }
        $('[type="submit"]').on('click',function(e){
            btnPres($(this));
        });
    });
</script>
