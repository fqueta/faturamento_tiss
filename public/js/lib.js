const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const pop = urlParams.get('popup');
function uniqid(prefix, more_entropy) {
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +    revised by: Kankrelune (http://www.webfaktory.info/)
  // %        note 1: Uses an internal counter (in php_js global) to avoid collision
  // *     example 1: uniqid();
  // *     returns 1: 'a30285b160c14'
  // *     example 2: uniqid('foo');
  // *     returns 2: 'fooa30285b1cd361'
  // *     example 3: uniqid('bar', true);
  // *     returns 3: 'bara20285b23dfd1.31879087'
  if (typeof prefix == "undefined") {
    prefix = "";
  }
  var retId;
  var formatSeed = function (seed, reqWidth) {
    seed = parseInt(seed, 10).toString(16); // to hex str
    if (reqWidth < seed.length) { // so long we split
      return seed.slice(seed.length - reqWidth);
    }
    if (reqWidth > seed.length) { // so short we pad
      return Array(1 + (reqWidth - seed.length)).join('0') + seed;
    }
    return seed;
  };
  // BEGIN REDUNDANT
  if (!this.php_js) {
    this.php_js = {};
  }
  // END REDUNDANT
  if (!this.php_js.uniqidSeed) { // init seed with big random int
    this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
  }
  this.php_js.uniqidSeed++;

  retId = prefix; // start with prefix, add current milliseconds hex string
  retId += formatSeed(parseInt(new Date().getTime() / 1000, 10), 8);
  retId += formatSeed(this.php_js.uniqidSeed, 5); // add seed hex string
  if (more_entropy) {
    // for more entropy we add a float lower to 10
    retId += (Math.random() * 10).toFixed(8).toString();
  }
  return retId;
}
function lib_urlAtual(){
  return window.location.href;
}
function goToByScroll2(seletor) {
    // Remove "link" from the ID
    seletor = seletor.replace("link", "");
    // Scroll
	$('html,body').animate({
        scrollTop: $(seletor).offset().top
    }, 'slow');
}
function redirect_blank(url) {
  var a = document.createElement('a');
  a.target="_blank";
  a.href=url;
  a.click();
}
function encodeArray(arr){
    var ar = JSON.stringify(arr);
    var encode = btoa(ar);
    return encode
}
function decodeArray(arr){
	var decode = JSON.parse(atob(arr));
	return decode
}
function __translate(val,val2){
	return val;
}
function lib_formatMensagem(locaExive,mess,style,tempo){
	var mess = "<div class=\"alert alert-"+style+" alert-dismissable\" role=\"alert\"><button class=\"close\" type=\"button\" data-dismiss=\"alert\" aria-hidden=\"true\">X</button><i class=\"fa fa-exclamation-triangle\"></i>&nbsp;"+mess+"</div>";
	if(typeof(tempo) == 'undefined')
		var tempo = 4000;
	setTimeout(function(){$(".alert").hide('slow')}, tempo);
	$(locaExive).html(mess);
}
function abrirjanela(url, nome, w, h, param){
	if(param.length > 1)
		var popname = window.open(url, nome, 'width='+w+', height='+h+', scrollbars=yes, '+param);
	else
		var popname = window.open(url, nome, 'width='+w+', height='+h+', scrollbars=yes');
	popname.window.focus();
}
function abrirjanela1(url, nome, w, h, param){
	var largura = $( window ).width() - w;
	var altura   = $( window ).height();
	altura =new Number(altura) + new Number(h);
	var left = new Number(largura);
	//left = (left) - (left/Number(2));
	if(param.length > 1)
		var popname = window.open(url, nome, 'width='+largura+', height='+altura+', left='+10+' scrollbars=yes, '+param);
	else
		var popname = window.open(url, nome, 'width='+largura+', height='+altura+', scrollbars=yes,left='+10+'');
	 popname.window.focus();
}
function abrirjanelaPadrao(url,windo){
	if(typeof windo == 'undefined'){
		windo = "novo_cada";
	}
	var meio = (screen.availWidth - 200)/((screen.availWidth-200)/50);
	if($(window).width() > 900){
		var wid = screen.availWidth - 100;
		//var height = $( window ).height() - ($( window ).height()/4);
		var height = screen.availHeight-90;
	}else{
		var wid = $(window).width();
		var height = screen.availHeight;
		//var height = $(document).height();
		//height = new Number(height) - new Number(100);
	}
	//alert(height);
	abrirjanela(url, windo, wid, height, "left="+meio+",toolbar=no, location=no, directories=no, status=no, menubar=no");
}
function abrirjanelaFull(url,windo){
	if(typeof windo == 'undefined'){
		windo = "janelaFull";
	}
	var params = [
		'height='+screen.height,
		'width='+screen.width,
		'fullscreen=yes' // only works in IE, but here for completeness
	].join(',');

	var popup = window.open(url, windo, params);
	popup.window.focus();
	popup.moveTo(0,0);
	//abrirjanela(url, windo, screen.availWidth, screen.availHeight, ",toolbar=no, location=no, directories=no, status=no, menubar=no");
}
function abrirjanelaPadraoConsulta(url){
	if($(window).width() > 800){
		var meio = 1050 / (6);
		var wid = 1050;
		var height = $( window ).height();
	}else{
		var meio = $(window).width() / (6);
		var wid = $(window).width();
		var height = $( window ).height();
	}
	abrirjanela(url, "consultaCliente", wid, height, "left="+meio+",toolbar=no, location=no, directories=no, status=no, menubar=no");
}

function openPageLink(ev,url,ano){
  ev.preventDefault();
  var u = url.trim()+'?ano='+ano;
	abrirjanelaPadrao(u);
	//window.location = u;
}
function gerenteAtividade(obj,ac){
  var id = obj.attr('id');
  var temaImput = '<input type="{type}" {seletor} style="width:{wid}px" name="{name}" value="{value}" class="form-control text-center"> {btn}';
  var arr = ['publicacao','video','hora','revisita','estudo','obs'];
  var selId = $('#'+id);
  var exec = selId.attr('exec');
  if(exec=='s'){
    return
  }
  for (var i = 0; i < arr.length; i++) {
    var eq = (i+1);
    var s = $('#'+id+' td:eq('+eq+')');
    if(i==0){
      selId.attr('exec','s');
    }
    if(i==5){
       var wid='200';
       var t='text';
       var b='<button type="button" onclick="submitRelatorio(\''+id+'\',\''+ac+'\')" title="Salvar" class="btn btn-primary" name="button"><i class="fa fa-check"></i></button>'+
       '<button type="button" onclick="cancelEdit(\''+id+'\')" title="Cancelar edição" data-toggle="tooltip"  class="btn btn-secondary" name="button"><i class="fa fa-times"></i></button>';
			 if(ac=='alt'){
				 b += '<button type="button" onclick="delRegistro(\''+id+'\')" title="Apagar registro" data-toggle="tooltip"  class="btn btn-danger" name="button"><i class="fa fa-trash"></i></button>';
			 }
       s.addClass('d-flex');
    }else{
      var wid='100';
      var b='';
      var t='number';
    }
    var v = s.html();
    var c = temaImput.replace('{name}',id+arr[i]);
    c = c.replace('{value}',v);
    c = c.replace('{wid}',wid);
    c = c.replace('{type}',t);
    c = c.replace('{btn}',b);
    c = c.replace('{seletor}',arr[i]);
    s.html(c);
    //array[i]
  }
  $('#'+id+' td:eq(1) input').select();
}
function cancelEdit(id,ac){
  //var temaImput = '<input type="{type}" style="width:{wid}px" name="{name}" value="{value}" class="form-control text-center"> {btn}';
  if(typeof ac == 'undefined'){
    ac = 'edit';
  }
  var temaImput = '{value}';
  var arr = ['publicacao','video','hora','revisita','estudo','obs'];
  var selId = $('#'+id);
  for (var i = 0; i < arr.length; i++) {
    var eq = (i+1);
    var td = $('#'+id+' td:eq('+eq+')');
    var s = $('#'+id+' input['+arr[i]+']');
    if(i==0){
      selId.removeAttr('exec');
    }
    if(i==5){
       var wid='200';
       var t='text';
       td.removeClass('d-flex');
    }else{
      var wid='100';
      var b='';
      var t='number';
    }
    var v = s.val();
    if(ac=='del'){
      var c = temaImput.replace('{value}','');
    }else{
      var c = temaImput.replace('{value}',v);
    }
    //c = c.replace('{value}',v);
    //c = c.replace('{wid}',wid);
    //c = c.replace('{type}',t);
    //c = c.replace('{btn}',b);
    td.html(c);
  }
}
function delRegistro(id){
  var don = $('#'+id+' input');
  //console.log(don);
  var arr = [];
  var seriali = '';
  $.each(don,function(i,k){
    var ke = k.name;
    ke = ke.replace(id,'');
     arr[ke] = k.value;
     seriali += ke+'='+k.value+'&';
    //console.log(k.name);
  });
  //var var_cartao = atob(arr['var_cartao']);
    $.ajaxSetup({
         headers: {
             'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
         }
     });

     var formData = seriali,state = jQuery('#btn-save').val();
     if(ac=='del'){
       var type = "DELETE";
     }else{
       var type = "POST";
     }
     var ac='del',ajaxurl = $('[name="routAjax_'+ac+'"]').val();
     $.ajax({
         type: type,
         url: ajaxurl,
         data: formData,
         dataType: 'json',
         success: function (data) {

           if(data.exec){
             cancelEdit(id,'del');
             if(data.mens){
               lib_formatMensagem('.mens',data.mens,'success');
             }
           }else{
             lib_formatMensagem('.mens',data.mens,'danger');
           }
           if(data.cartao.totais){
             var array = data.cartao.totais;
             var id_pub = data.cartao.dados.id;
             var eq = 1;
             $.each(array,function(i,k){
                $('#pub-'+id_pub+' .tf-1 th:eq('+(eq)+')').html(k);
               eq++;
             });
           }
           if(data.cartao.medias){
             var array = data.cartao.medias;
             var id_pub = data.cartao.dados.id;
             var eq = 1;
             $.each(array,function(i,k){
                $('#pub-'+id_pub+' .tf-2 th:eq('+(eq)+')').html(k);
               eq++;
             });
           }
           if(data.salvarRelatorios.obs && data.salvarRelatorios.mes){
             var selector = '#'+id_pub+'_'+data.salvarRelatorios.mes+' td';
             $(selector).last().html(data.salvarRelatorios.obs);
           }

         },
         error: function (data) {
             console.log(data);
         }
     });
}
/*
function submitRelatorio(id,ac){
  var don = $('#'+id+' input');
  console.log(don);
  var arr = [];
  var seriali = '';
  $.each(don,function(i,k){
    var ke = k.name;
    ke = ke.replace(id,'');
     arr[ke] = k.value;
     seriali += ke+'='+k.value+'&';
    //console.log(k.name);
  });
  var var_cartao = atob(arr['var_cartao']);
    $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
           }
       });

       var formData = seriali+'compilado=s';
       var state = jQuery('#btn-save').val();
       if(ac=='cad'){
         var type = "POST";
       }else{
         var type = "POST";
       }
       var ajaxurl = $('[name="routAjax_'+ac+'"]').val();
       $.ajax({
           type: type,
           url: ajaxurl,
           data: formData,
           dataType: 'json',
           success: function (data) {
             if(data.exec){
               cancelEdit(id);
							 if(data.mens){
								 lib_formatMensagem('.mens',data.mens,'success');
							 }
						 }else{
							 lib_formatMensagem('.mens',data.mens,'danger');
						 }
             if(data.cartao.totais){
               var array = data.cartao.totais;
               var id_pub = data.cartao.dados.id;
               var eq = 1;
               $.each(array,function(i,k){
                  $('#pub-'+id_pub+' .tf-1 th:eq('+(eq)+')').html(k);
                 eq++;
               });
             }
             if(data.cartao.medias){
               var array = data.cartao.medias;
               var id_pub = data.cartao.dados.id;
               var eq = 1;
               $.each(array,function(i,k){
                  $('#pub-'+id_pub+' .tf-2 th:eq('+(eq)+')').html(k);
                 eq++;
               });
             }
             if(data.salvarRelatorios.obs && data.salvarRelatorios.mes){
               var selector = '#'+id_pub+'_'+data.salvarRelatorios.mes+' td';
               $(selector).last().html(data.salvarRelatorios.obs);
             }

           },
           error: function (data) {
               console.log(data);
           }
       });
  //console.log(arr);
}
*/
function alerta(msg,id,title,tam,fechar,time,fecha){

	if(typeof(fechar) == 'undefined')
        fechar = true;
    if(typeof(title) == 'undefined')
    title = 'Janela modal';
    if(typeof(fecha) != 'undefined')
        fecha = fecha;
    else
        fecha = '';
	if(typeof(id) == 'undefined')
    id = 'meuModal';
	if(typeof(tam) == 'undefined')
    tam = '';
	if(typeof(time) == 'undefined')
        time = 2000;
    if(fechar)
        fechar = '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button></div>';
    var modalHtml = '<div class="modal fade" id="'+id+'" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">'+
            '<div class="modal-dialog '+tam+'" role="document">'+
                '<div class="modal-content">'+
                    '<div class="modal-header">'+
                        '<h5 class="modal-title">'+title+'</h5>'+
                            '<button type="button" class="close" data-dismiss="modal" aria-label="Close">'+
                                '<span aria-hidden="true">&times;</span>'+
                            '</button>'+
                    '</div>'+
                    '<div class="modal-body">'+msg+
                    '</div>'+fechar+
                '</div>'+
            '</div>'+
        '</div>';
        $('#'+id).remove();
	  var bodys = $(document.body).append(modalHtml);

	  $("#"+id).modal({backdrop: 'static'});
	if(fecha == true)
	setTimeout(function(){$("#"+id).modal("hide")}, time);
}
function editarAssistencia(obj){
    var sele = obj.attr('sele');
    var arr = sele.split('_');
    var ac = arr[0];
    var id = 0;
    var s = $('[sele="'+sele+'"] .l1');
    if(arr[0]=='edit'){
      id = arr[1];
      var d = '';
      var valor = s.find('span').html();
      valor = valor.trim();
    }else{
      var d = s.find('[name="dados"]').val();
      valor = 0;
    }
    var tema = '<form id="frm_'+sele+'">'+
                    '<div class="input-group">'+
                      '<input type="number" class="form-control" style="width:56px" name="qtd" value="{value}" placeholder="0">'+
                      '<input type="hidden" class="form-control" name="ac" value="{ac}">'+
                      '<input type="hidden" class="form-control" name="id" value="{id}">'+
                      '<span class="input-group-btn">'+
                        '<button class="btn btn-primary" onclick="salvarAssitencia(\'frm_'+sele+'\',\''+d+'\');" type="button"><i class="fa fa-check"></i></button>'+
                        '<button class="btn btn-secondary" onclick="cancelEditAssistencia(\'frm_'+sele+'\',{valor})" type="button"><i class="fa fa-times"></i></button>'+
                      '</span>'+
                    '</div>'+
                  '</form>';
    var nv = tema.replace('{value}',valor);
    nv = nv.replace('{ac}',ac);
    nv = nv.replace('{id}',id);
    nv = nv.replace('{valor}',valor);
    s.find('span').html(nv);
    s.find('[name="qtd"]').select();
}
function cancelEditAssistencia(frm,qtd){
  var sele = frm.replace('frm_','');
  var s = $('[sele="'+sele+'"] .l1').find('span');
  var tema = '{qtd}';
  var nv = tema.replace('{qtd}',qtd);
  //nv = nv.replace('{ac}',ac);
  //nv = nv.replace('{id}',id);
  s.html(nv);
}
function salvarAssitencia(frm,dados){
  //var var_cartao = atob(arr['var_cartao']);
        $.ajaxSetup({
           headers: {
               'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
           }
       });

       //var state = jQuery('#btn-save').val();
       var f = $('#'+frm);
       var ac = f.find('[name="ac"]').val();
       var RAIZ = $('[name="raiz"]').val();
       if(ac=='cad'){
         var type = "POST";
         var ajaxurl = RAIZ+'/assistencias';
       }else{
         var id = f.find('[name="id"]').val();
         var type = "POST";
         var ajaxurl = RAIZ+"/assistencias/"+id;
       }
       $.ajax({
           type: type,
           url: ajaxurl,
           data: f.serialize()+'&dados='+dados,
           dataType: 'json',
           success: function (data) {
             if(data.exec){
               cancelEditAssistencia(frm,data.data.qtd);
							 if(data.mens){
								 lib_formatMensagem('.mens',data.mens,'success');
							 }
						 }else{
							 lib_formatMensagem('.mens',data.mens,'danger');
						 }
             if(data.data.dados[0].semanas[6].qtd){
               var totalR1 = data.data.dados[0].semanas[6].qtd;
               $('[sele="total_0_6"] span').html(totalR1);
             }
             if(data.data.dados[0].semanas[7].qtd){
               var mediaR1 = data.data.dados[0].semanas[7].qtd;
               $('[sele="media_0_7"] span').html(mediaR1);
             }
             if(data.data.dados[1].semanas[6].qtd){
               var totalR1 = data.data.dados[1].semanas[6].qtd;
               $('[sele="total_1_6"] span').html(totalR1);
             }
             if(data.data.dados[1].semanas[7].qtd){
               var mediaR1 = data.data.dados[1].semanas[7].qtd;
               $('[sele="media_1_7"] span').html(mediaR1);
             }

             /*
             if(data.cartao.medias){
               var array = data.cartao.medias;
               var id_pub = data.cartao.dados.id;
               var eq = 1;
               $.each(array,function(i,k){
                  $('#pub-'+id_pub+' .tf-2 th:eq('+(eq)+')').html(k);
                 eq++;
               });
             }
             if(data.salvarRelatorios.obs && data.salvarRelatorios.mes){
               var selector = '#'+id_pub+'_'+data.salvarRelatorios.mes+' td';
               $(selector).last().html(data.salvarRelatorios.obs);
             }
              */
           },
           error: function (data) {
               console.log(data);
           }
       });
}
function mask(o, f) {
	setTimeout(function() {
		var v = clientes_mascaraTelefone(o.value);
		if (v != o.value && o.value!='') {
		  o.value = v;
		}
	  }, 1);
}
function clientes_mascaraTelefone(v) {
	var r = v.replace(/\D/g, "");
	  r = r.replace(/^0/, "");
	  if (r.length > 10) {
		r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1)$2-$3");
	  } else if (r.length > 5) {
		r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1)$2-$3");
	  } else if (r.length > 2) {
		r = r.replace(/^(\d\d)(\d{0,5})/, "($1)$2");
	  } else {
		r = r.replace(/^(\d*)/, "($1");
	  }
	  return r;
}
function confirmDelete(obj){
    var id = obj.data('id');
    if(window.confirm('DESEJA MESMO EXCLUIR?')){
        // $('#frm-'+id).submit();
        submitFormulario($('#frm-'+id),function(res){
            if(res.mens){
                lib_formatMensagem('.mens',res.mens,res.color);
            }
            if(res.return){
                location.reload();
                //window.location = res.return
            }
            if(res.errors){
                alert('erros');
                console.log(res.errors);
            }
        });
    }

}
function urlAtual(){
    return window.location.href;
}
function isEmpty(str) {
    return (!str || 0 === str.length);
}
function __translate(val,val2){
      return val;
}
function lib_trataAddUrl(campo,valor,urlinic){
	var ret = '';
	if(typeof urlinic == 'undefined')
		var urla = urlAtual();
	else
		var urla = urlinic;
	var urlAtua = urla.split('?');
	if(typeof urlAtua[1]=='undefined'){
		urlAtua[1] = '';
	}
	var urlA1 = urlAtua[1];
	var opc = 1;
	urlA1 = urlA1.replace('&=','',urlAtua[1]);
	if(opc==1){
			ret += urlAtua[0]+'?';
			var arr_url = urlAtua[opc];
			arr_url = arr_url.split('&');
			var mudou = false;
			arr_url.forEach(function (element, index) {
				//console.log("[" + index + "] = " + element);
				var arr_vu = element.split('=');
				if(!isEmpty(arr_vu[0])){
					if(arr_vu[0]==campo){
						mudou = true;
						ret += arr_vu[0] +'='+valor+'&';
					}else{
						ret += arr_vu[0] +'='+arr_vu[1]+'&';
					}
				}

			});
			if(!mudou){
				ret += '&'+campo+'='+valor;
			}
			ret = ret.replace('&&','&');
			ret = ret.replace('?&','?');
			ret = ret.replace('/?','?');
	}
	console.log(ret);
	return ret;
}
function lib_trataRemoveUrl(campo,valor,urlinic){
	var ret = '';
   if(typeof urlinic == 'undefined')
		var urla = urlAtual();
	else
		var urla = urlinic;
	var urlAtua = urla.split('?');
	var urlA1 = urlAtua[1];
	var opc = 1;
	urlA1 = urlA1.replace('&=','',urlAtua[1]);
	if(opc==1){
			ret += urlAtua[0]+'?';
			var arr_url = urlAtua[opc];
			arr_url = arr_url.split('&');
			var mudou = false;
			arr_url.forEach(function (element, index) {
				//console.log("[" + index + "] = " + element);
				var arr_vu = element.split('=');
				if(!isEmpty(arr_vu[0])){
					if(arr_vu[0]==campo){
						mudou = true;
						//ret += arr_vu[0] +'='+valor+'&';
					}else{
						ret += arr_vu[0] +'='+arr_vu[1]+'&';
					}
				}

			});
			if(!mudou){
				ret += '&'+campo+'='+valor;
			}
			ret = ret.replace('&&','&');
			//alert('&&','&');
			//console.log(urlAtua[1]);
	}
	return ret;
}
function visualizaArquivos(token_produto,ajaxurl){

    $.ajax({
        type: 'GET',
        url: ajaxurl,
        data: {
            token_produto:token_produto,
        },
        dataType: 'json',
        success: function (data) {

          if(data.exec && data.arquivos){
            var list = listFiles(data.arquivos,token_produto);
            $('#lista-files').html(list);
            //$(".venobox").venobox();
            if(data.mens){
              lib_formatMensagem('.mens',data.mens,'success');
            }
          }else{
            lib_formatMensagem('.mens',data.mens,'danger');
          }
        },
        error: function (data) {
            console.log(data);
        }
    });
}
function listFiles(arquivos,token_produto){
    if(typeof token_produto == 'undefined'){
        token_produto = '';
    }
    if(arquivos.length>0){
        var tema1 = '<ul class="list-group">{li}</ul>';
        var tm2 = '<li class="list-group-item d-flex justify-content-between align-items-center" id="item-{id}">'+
                        '<div class="row w-100">'+
        '                    <div class="col-3 grade-img text-center">'+
        '                        <a href="{href}" target="_blank" rel=""><i class="fas fa-file-download fa-2x"></i>'+
        '                        </a>'+
        '                    </div>'+
        '                    <div class="col-9">'+
        '                        <a href="{href}" target="_blank" rel="">{nome}'+
        '                        </a>'+
        '                    </div>'+
        '                </div>'+
        '                <button type="button" {event} class="btn btn-default" title="Excluir"><i class="fas fa-trash"></i></button>'+
        '            </li>';
        var tm3 = '<li class="list-group-item d-flex justify-content-between align-items-center" id="item-{id}">'+
                        '<div class="row w-100">'+
        '                    <div class="col-3 grade-img text-center">'+
        '                        <a href="{href}" target="_blank" class="venobox vbox-item" title="{nome}" data-maxwidth="80%" data-max="80%" ><img class="shadow w-100" src="{href}" alt="{nome}">'+
        '                        </a>'+
        '                    </div>'+
        '                    <div class="col-9">'+
        '                        {nome}'+
        '                        '+
        '                    </div>'+
        '                </div>'+
        '                <button type="button" {event} class="btn btn-default" title="Excluir"><i class="fas fa-trash"></i></button>'+
        '            </li>';
        var li = '';
        var temaIcon = '<i class="fas fa-file-{tipo} fa-2x"></i>';
        for (let i = 0; i < arquivos.length; i++) {
            var icon = '';
            let arq = arquivos[i];
            if(conf = arq.config){
                var config = JSON.parse(conf);
                if(config.extenssao == 'jpg' || config.extenssao=='png' || config.extenssao == 'jpeg'){
                    var tipo = 'image';
                }else if(config.extenssao == 'doc' || config.extenssao == 'docx') {
                    var tipo = 'word';
                }else if(config.extenssao == 'xls' || config.extenssao == 'xlsx') {
                    var tipo = 'excel';
                }else{
                    var tipo = 'download';
                }
                icon = temaIcon.replace('{tipo}',tipo);
            }
            if(tipo=='image'){
                var tema2=tm3;
            }else{
                var tema2=tm2;
            }
            var event = 'onclick="excluirArquivo(\''+arq.id+'\',\'/uploads/'+arq.id+'\')"';
            var href = '/storage/'+arq.pasta;
            //var href = 'https://cmd.databrasil.app.br/storage/'+arq.pasta;
            li += tema2.replaceAll('{event}',event);
            li = li.replaceAll('{nome}',arq.nome);
            li = li.replaceAll('{id}',arq.id);
            li = li.replaceAll('{href}',href);
            li = li.replaceAll('{icon}',icon);
        }
        ret = tema1.replace('{li}',li);
        return ret;

    }
}
function excluirArquivo(id,ajaxurl){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
            id:id,
        },
        dataType: 'json',
        success: function (data) {

          if(data.exec){
            //cancelEdit(id,'del');
            $('#item-'+id).remove();
            if(data.dele_file){
              lib_formatMensagem('.mens','Arquivo excluido com sucesso!','success');
            }
          }else{
            lib_formatMensagem('.mens','Erro ao excluir entre em contato com o suporte','danger');
          }


        },
        error: function (data) {
            console.log(data);
        }
    });
}
function carregaDropZone(seletor){
    $(seletor).dropzone({ url: "/file/post" });
}
function submitFormulario(objForm,funCall,funError){
    if(typeof funCall == 'undefined'){
        funCall = function(res){
            console.log(res);
        }
    }
    if(typeof funError == 'undefined'){
        funError = function(res){
            lib_funError(res);
        }
    }
    var route = objForm.attr('action');
    //console.log(route);
    $.ajax({
        type: 'POST',
        url: route,
        data: objForm.serialize()+'&ajax=s',
        dataType: 'json',
        beforeSend: function(){
            $('#preload').fadeIn();
        },
        success: function (data) {
            $('#preload').fadeOut("fast");
            funCall(data);
        },
        error: function (data) {
            $('#preload').fadeOut("fast");
            if(data.responseJSON.errors){
                funError(data.responseJSON.errors);
                console.log(data.responseJSON.errors);
            }else{
                lib_formatMensagem('.mens','Erro','danger');
            }
        }
    });
}
function getAjax(config,funCall,funError){

    if(typeof config.url == 'undefined'){
        alert('informe a Url');
        return false;
    }
    if(typeof config.type == 'undefined'){
        config.type = 'GET';
    }
    if(typeof config.data == 'undefined'){
        config.data = {ajax:'s'};
    }
    if(typeof funCall == 'undefined'){
        funCall = function(res){
            console.log(res);
        }
    }
    if(typeof funError == 'undefined'){
        funError = function(res){
            $('#preload').fadeOut("fast");
            lib_funError(res);
        }
    }
    $.ajax({
        type: config.type,
        url: config.url,
        data: config.data,
        dataType: 'json',
        beforeSend: function(){
            $('#preload').fadeIn();
        },
        success: function (data) {
            funCall(data);
        },
        error: function (data) {
            $('#preload').fadeOut("fast");
            if(data.errors){
                funError(data.errors);
                console.log(data.errors);
            }else{
                lib_formatMensagem('.mens','Erro','danger');
            }
        }
    });
}
function submitFormularioCSRF(objForm,funCall,funError){
    if(typeof funCall == 'undefined'){
        funCall = function(res){
            console.log(res);
        }
    }
    if(typeof funError == 'undefined'){
        funError = function(res){
            lib_funError(res);
        }
    }
    var route = objForm.attr('action');
    console.log(route);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: 'POST',
        url: route,
        data: objForm.serialize()+'&ajax=s',
        dataType: 'json',
        beforeSend: function(){
            $('#preload').fadeIn();
        },
        success: function (data) {
            $('#preload').fadeOut("fast");
            funCall(data);
        },
        error: function (data) {
            $('#preload').fadeOut("fast");
            if(data.responseJSON.errors){
                funError(data.responseJSON.errors);
                console.log(data.responseJSON.errors);
            }else{
                lib_formatMensagem('.mens','Erro','danger');
            }
        }
    });
}
function lib_funError(res){
    var mens = '';
    Object.entries(res).forEach(([key, value]) => {
        //console.log(key + ' ' + value);
        var s = $('[name="'+key+'"]');
        var v = s.val();
        mens += value+'<br>';
        if(key=='cpf'){
           s.addClass('is-invalid');
        }else{
            if(v=='')
                s.addClass('is-invalid');
            else{
                s.removeClass('is-invalid');
            }
        }
    });
    lib_formatMensagem('.mens',mens,'danger');

}
function modalGeral(id,titulo,conteudo){
    var m = $(id);
    m.modal({backdrop:'static'});
    m.find('.modal-title').html(titulo);
    m.find('.conteudo').html(conteudo);

}
function renderForm(config,alvo,funCall){
    if(typeof config=='undefined'){
        return ;
    }
    var d = config;
    if(d.campos){
        var f = qFormCampos(d.campos);
        if(f){
            var tf = '<form id="{id_form}" action="{action}"><div class="row"><div class="col-md-12 mens"></div>{conte}</div></form>';
            var b = '<button type="button" class="btn btn-primary" f-submit>Salvar <i class="fas fa-chevron-circle-right"></i></button>';
            var m = '#modal-geral';
            tf = tf.replace('{id_form}',d.id_form);
            tf = tf.replace('{conte}',f);
            tf = tf.replace('{action}',d.action);
            modalGeral(m,'Cadastrar '+d.label,tf);
            $('[f-submit]').remove();
            $(b).insertAfter(m+' .modal-footer button');
            try {
                $('[mask-cpf]').inputmask('999.999.999-99');
                $('[mask-data]').inputmask('99/99/9999');
                $('[mask-cep]').inputmask('99.999-999');
                if(n=d.value_transport){
                    var vl = $('[name="'+n+'"]').val();
                    if(vl)
                    $('[name="'+n+'"]').find('option[value='+vl+']').attr('selected','selected');
                }
            } catch (error) {
                console.log(error);
            }
            carregaMascaraMoeda(".moeda");
            $('#'+d.id_form+' #inp-nome').focus();
            $('[f-submit]').on('click',function(){
                if(typeof funCall=='undefined'){
                    funCall = function(res){
                        if(res.mens){
                            lib_formatMensagem('.mens',res.mens,res.color);
                        }
                        if(res.exec){
                            $(m).modal('hide');
                            alvo.append($('<option>', {
                                value: res.idCad,
                                text: res.dados[d.campo_bus]
                            }));
                            alvo.find('option[value='+res.idCad+']').attr('selected','selected').addClass('opcs');
                        }
                    }
                }
                submitFormularioCSRF($('#'+d.id_form),funCall);
            });
        }
        //$('.mens').html(campo_nome);
    }
}
function initSelector(alvo,funCall){
    if(alvo.val()=='cad'){
        var d = decodeArray(alvo.data('selector'));
        //console.log(alvo);
        renderForm(d,alvo);
        alvo.find('option[value=\'\']').attr('selected','selected');
    }
    if(alvo.val()=='ger'){
        var d = decodeArray(alvo.data('selector'));
        window.open(d.route_index,'_blank');
        console.log(d);
    }

}
function lib_vinculoCad(obj){
    if(typeof obj == 'undefined'){
        return;
    }
    var d = decodeArray(obj.data('selector')),ac = obj.data('ac');
    if(ac == 'cad' && d.salvar_primeiro){
        var msg = '<div class="row"><div id="exibe_etapas" class="col-md-12 text-center"><h6>Antes de cadastrar um parceiro é necessário salvar este cadastro!</h6></div><div class="col-md-12 mt-3 text-center"></div></div>';
        var btns = '<button type="button" class="btn btn-primary" salvar-agora>Salvar agora</button>';
        alerta(msg,'modal-cad-vinculo','Atenção','',true,9000,true);
        $(btns).insertAfter('#modal-cad-vinculo .modal-footer button');
        $('[salvar-agora]').on('click',function(){
            $('[btn="permanecer"]').click();
            $('#modal-cad-vinculo').modal('hide');
        });
        return;
    }
    if(typeof d.janela=='undefined'){
        d.janela = '';
    }
    if(d.janela.url){
        var url = d.janela.url+'?popup=true';
        try {
            if(pr=d.janela.param){
                for (let i = 0; i < pr.length; i++) {
                    const el = pr[i];
                    url += '&'+el+'='+$('[name="'+el+'"]').val();
                }
            }
        } catch (e) {
            console.log(e);
        }
        var tag_obj = '<obj class="d-none">'+obj.data('selector')+'</obj>';
        $('obj').remove();
        $(tag_obj).insertBefore('body');
        abrirjanelaPadraoConsulta(url,'vinculo');
    }else{
        renderForm(d,obj,function(res){
            if(res.mens){
                lib_formatMensagem('.mens',res.mens,res.color);
            }
            if(res.exec){
                var mod = '#modal-geral';
                $(mod).modal('hide');
                lib_listDadosHtmlVinculo(res,obj.data('selector'),'cad');
            }
        });
    }
}
function qFormCampos(config){
    if(typeof config == 'undefined'){
        return false;
    }
    const tl = '<label for="{campo}">{label}</label>';
    var tema = {
        text : '<div class="form-group col-{col}-{tam} {class_div}" div-id="{campo}" >{label}<input type="{type}" class="form-control {class}" id="inp-{campo}" name="{campo}" aria-describedby="{campo}" placeholder="{placeholder}" value="{value}" {event} /></div>',
        tel : '<div class="form-group col-{col}-{tam} {class_div}" div-id="{campo}" >{label}<input type="{type}" class="form-control {class}" id="inp-{campo}" name="{campo}" aria-describedby="{campo}" placeholder="{placeholder}" value="{value}" {event} /></div>',
        date : '<div class="form-group col-{col}-{tam} {class_div}" div-id="{campo}" >{label}<input type="{type}" class="form-control {class}" id="inp-{campo}" name="{campo}" aria-describedby="{campo}" placeholder="{placeholder}" value="{value}" {event} /></div>',
        number : '<div class="form-group col-{col}-{tam} {class_div}" div-id="{campo}" >{label}<input type="{type}" class="form-control {class}" id="inp-{campo}" name="{campo}" aria-describedby="{campo}" placeholder="{placeholder}" value="{value}" {event} /></div>',
        hidden : '<div class="form-group col-{col}-{tam} {class_div} d-none" div-id="{campo}" >{label}<input type="{type}" class="form-control {class}" id="inp-{campo}" name="{campo}" aria-describedby="{campo}" placeholder="{placeholder}" value="{value}" {event} /></div>',
        textarea : '<div class="form-group col-{col}-{tam} {class_div}" div-id="{campo}" ><textarea name="{campo}" class="form-control {class}" rows="{rows}" cols="{cols}">{value}</textarea></div>',
        chave_checkbox : '<div class="form-group col-{col}-{tam}"><div class="custom-control custom-switch  {class}"><input type="checkbox" class="custom-control-input" {checked} value="{value}"  name="{campo}" id="{campo}"><label class="custom-control-label" for="{campo}">{label}</label></div></div>',
        select : {
            tm1 : '<div class="form-group col-{col}-{tam} {class_div}" div-id="{campo}" >{label}<select name="{campo}" {event} class="form-control custom-select {class}">{op}</select></div>',
            tm2 : '<option value="{k}" class="opcs" {selected}>{v}</option>'
        }
    };
    var r = '';
    var ret = '';
    if(Object.entries(config).length>0){
        Object.entries(config).forEach(([key, v]) => {
            if(v.js || v.active){
                if(v.type == 'selector' || v.type == 'select'){
                    let op='',arr = v.arr_opc,tm1 = tema['select'].tm1,tm2 = tema['select'].tm2;
                    var value = v.value?v.value:'';
                    Object.entries(arr).forEach(([i, el]) => {
                        op += tm2.replace('{k}',i);
                        var selected = '';
                        if(value==i){
                            var selected = 'selected';
                        }
                        op = op.replaceAll('{selected}',selected);
                        op = op.replace('{v}',el);
                    });
                    var type = v.type;
                    r += tm1.replaceAll('{type}',v.type);
                    var label = tl.replaceAll('{campo}',key);
                    label.replaceAll('{label}',);
                    var classe = v.class?v.class:'';
                    var placeholder = v.placeholder?v.placeholder:'';
                    r = r.replaceAll('{campo}',key);
                    r = r.replaceAll('{label}',v.label);
                    r = r.replaceAll('{value}',value);
                    r = r.replaceAll('{tam}',v.tam);
                    r = r.replaceAll('{event}',v.event);
                    r = r.replaceAll('{col}','md');
                    r = r.replaceAll('{class}',classe);
                    r = r.replaceAll('{op}',op);
                    r = r.replaceAll('{placeholder}',placeholder);
                }else{
                    try {

                        var type = v.type;
                        var checked = '';
                        if(type == 'chave_checkbox'){
                            if(v.valor_padrao==v.value){
                                checked = 'checked';
                            }
                        }
                        r += tema[type].replaceAll('{type}',v.type);
                        var label = tl.replaceAll('{campo}',key);
                        label.replaceAll('{label}',);
                        var value = v.value?v.value:'';
                        var classe = v.class?v.class:'';
                        var placeholder = v.placeholder?v.placeholder:'';
                        r = r.replaceAll('{campo}',key);
                        r = r.replaceAll('{label}',v.label);
                        r = r.replaceAll('{value}',value);
                        r = r.replaceAll('{tam}',v.tam);
                        r = r.replaceAll('{event}',v.event);
                        r = r.replaceAll('{col}','md');
                        r = r.replaceAll('{class}',classe);
                        r = r.replaceAll('{checked}',checked);
                        r = r.replaceAll('{placeholder}',placeholder);
                    } catch (e) {
                        console.log(e);
                    }
                }
            }
        });
    }
    ret = r;

    return ret;
}
function color_select1_0(val,val1){
    if(val==true){
		$('#tr_'+val1).addClass('table-info');
	}
	if(val==false){
		$('#tr_'+val1).removeClass('table-info');
	}
}
function gerSelect(obj){
	if(obj.is(':checked')){
        $('.table .checkbox').each(function(){
            this.checked = true;
            color_select1_0(this.checked,this.value);
        });
	}else{
        $('.table .checkbox').each(function(){
            this.checked = false;
            color_select1_0(this.checked,this.value);
        });
	}
}
function coleta_checked(obj){
    let id = '';
    obj.each(function (i) {
        id += this.value+'_';
    });
    return id;
}
function dps_salvarEpatas(res,etapa,m){
    $.each(res,function(v,k) {
        var sl = '#tr_'+v+' .etapa';
        $(sl).html(etapa);
    });
    //if(typeof m!='undefined')
    $(m).modal('hide');
}
function janelaEtapaMass(selecionandos){
    if(typeof selecionandos =='undefined'){
        return ;
    }
    if(selecionandos==''){
        var msg = '<div class="row"><div id="exibe_etapas" class="col-md-12 text-center"><p>Por favor selecione um registro!</p></div></div>';
        alerta(msg,'modal-etapa','Alerta','',true,3000,true)
        return;
    }else{
       var msg = '<form id="frm-etapas" action="/familias/ajax"><div class="row"><div id="exibe_etapas" class="col-md-12">seleEta</div></div></form>',btnsub = '<button type="button" id="submit-frm-etapas" class="btn btn-primary">Salvar</button>',m='modal-etapa';

       alerta(msg,m,'Editar Etapas');
       $.ajax({
            type:"GET",
            url:"/familias/campos",
            dataType:'json',
            success: function(res){
                res.etapa.type = 'select';
                res.etapa.tam = '12';
                res.etapa.option_select = true;
                var conp = {etapa:res.etapa};
                var et = qFormCampos(conp);
                et += '<input type="hidden" name="opc" value="salvar_etapa_massa"/>';
                et += '<input type="hidden" name="ids" value="'+selecionandos+'"/>';
                $('#exibe_etapas').html(et);
                $(btnsub).insertAfter('#'+m+' .modal-footer button');
                $('[mask-cpf]').inputmask('999.999.999-99');
                $('[mask-data]').inputmask('99/99/9999');
                $('[mask-cep]').inputmask('99.999-999');
                carregaMascaraMoeda(".moeda");
                $('#submit-frm-etapas').on('click',function(e){
                    e.preventDefault();
                    submitFormularioCSRF($('#frm-etapas'),function(res){
                        if(res.mens){
                            lib_formatMensagem('.mens',res.mens,res.color);
                        }
                        if(res.exec && (a = res.atualiza)){
                            dps_salvarEpatas(a,res.etapa,'#'+m);
                        }
                    });
                });
            }
       });
    }
}
function carregaMascaraMoeda(s){
    $(s).maskMoney({
        prefix: 'R$ ',
        allowNegative: true,
        thousands: '.',
        decimal: ','
    });
}
function lib_carregaConjuge(frmParce,frmBene){
    var formParce = $(frmParce);
    var formBenef = $(frmBene);
    var idBenef = formBenef.find('[name="id"]').val();
    formParce.find('[name="conjuge"]').val(idBenef);
}
function cursos_carregaUrl(){}
function lib_htmlVinculo(ac,campos,lin){
    var c = decodeArray(campos),idf='#'+c.id_form,arr=c.campos;
    try {
        if(typeof lin == 'undefined'){
            lin = '';
        }
        var tipo=c.tipo;
    } catch (e) {
        var tipo='int';
        console.log(e);
    }
    if(typeof tipo =='undefined'){
        var tipo='int';
    }
    if(ac=='del'){
        if(tipo=='array' && lin){
            var id = c.list[lin].id,trsel = '#tr-'+lin+'-'+id;
        }else{
            var id = c.list.id,trsel = '#tr-'+id;
        }
        if(id){
            var msg = '<div class="row"><div id="mens-id" class="col-md-12 text-center"><h5>Deseja Remover da lista?</h5><p>Para completar é necessário salvar</p><p>Remover da lista não exclui o cadastro</p></div><div class="col-md-12 mt-3 text-center"></div></div>';
            var btnr = '<button type="button" class="btn btn-danger" deletar>Remover Agora!</button>';
            alerta(msg,'modal-del-html_vinculo','Atenção','',true);
            $(btnr).insertAfter('#modal-del-html_vinculo .modal-footer button');
            $('[deletar]').on('click',function(){
                if(tipo=='array' && lin){
                    $('#table-html_vinculo-'+c.campo+' '+trsel).remove();
                }else{
                    $('#table-html_vinculo-'+c.campo+' '+trsel+' td').html('');
                }
                $('#modal-del-html_vinculo').modal('hide');
                $('[name="'+c.campo+'"]').val('');
            });
        }
    }
    if(ac=='alt'){
        if(Object.entries(arr).length>0){
            Object.entries(arr).forEach(([k, v]) => {
                if(tipo=='array'){
                    var l = '';
                    try {
                        var list = $('#tr-'+lin+'-')
                        if(l = c.list[lin][k]){
                            c.campos[k].value = l;
                        }else{
                            if(cp=c.campos[k].cp_busca){
                                let ar = cp.split('][');
                                if(ar[1]){
                                    try {
                                        c.campos[k].value = c.list[lin][ar[0]][ar[1]];
                                    } catch (error) {
                                        console.log(error);
                                    }
                                }
                            }
                        }
                        console.log(c);
                    } catch (error) {
                        console.log(error);
                    }
                }else{
                    if(c.list[k]){
                        c.campos[k].value = c.list[k];
                    }else{
                        if(cp=c.campos[k].cp_busca){
                            let ar = cp.split('][');
                            if(ar[1]){
                                try {
                                    c.campos[k].value = c.list[ar[0]][ar[1]];
                                } catch (error) {
                                    console.log(error);
                                }
                            }
                        }
                    }
                }
            });

            renderForm(c,campos,function(res){
                if(res.mens){
                    lib_formatMensagem('.mens',res.mens,res.color);
                }
                if(res.exec){
                    var mod = '#modal-geral';
                    $(mod).modal('hide');
                    lib_listDadosHtmlVinculo(res,campos,ac,lin);
                }
            });
            if(tipo=='array' && lin){
                try {
                    var tid='';
                    if(c.list[lin].id){
                        tid=c.list[lin].id;
                    }else if(c.list.id){
                        tid=c.list.id;
                    }
                    frm = $(idf)
                    var m = '<input type="hidden" name="_method" value="PUT">';
                    frm.attr('action',c.action+'/'+c.tid);
                    frm.find('[name="_method"]').remove();
                    frm.append(m);

                } catch (error) {
                    console.log(error);
                }
            }else{
                if(c.list.id){
                    frm = $(idf)
                    var m = '<input type="hidden" name="_method" value="PUT">';
                    frm.attr('action',c.action+'/'+c.list.id);
                    frm.find('[name="_method"]').remove();
                    frm.append(m);
                }
            }
        }
    }
}
function lib_htmlVinculo2(ac,campos,id,lin){
    var c = decodeArray(campos),idf='#'+c.id_form,arr=c.campos;
    try {
        if(typeof lin == 'undefined'){
            lin='';
        }
        if(typeof id == 'undefined'){
            return 'dados lista indefinida';
        }
        var seleinp = '#inp-list-'+lin+'-'+id;
        var inpli = $(seleinp).val();
        var dl = decodeArray(inpli);
        var tipo=c.tipo;
    } catch (e) {
        var tipo='int';
        console.log(e);
    }
    if(typeof tipo =='undefined'){
        var tipo='int';
    }
    if(ac=='del'){
        if(tipo=='array' && lin){
            var id = id,trsel = '#tr-'+lin+'-'+id;
        }else{
            var id = c.list.id,trsel = '#tr-'+id;
        }
        if(id){
            var msg = '<div class="row"><div id="mens-id" class="col-md-12 text-center"><h5>Deseja Remover da lista?</h5><p>Para completar é necessário salvar</p><p>Remover da lista não exclui o cadastro</p></div><div class="col-md-12 mt-3 text-center"></div></div>';
            var btnr = '<button type="button" class="btn btn-danger" deletar>Remover Agora!</button>';
            alerta(msg,'modal-del-html_vinculo','Atenção','',true);
            $(btnr).insertAfter('#modal-del-html_vinculo .modal-footer button');
            $('[deletar]').on('click',function(){
                if(tipo=='array' && lin){
                    $('#table-html_vinculo-'+c.campo+' '+trsel).remove();
                }else{
                    $('#table-html_vinculo-'+c.campo+' '+trsel+' td').html('');
                }
                $('#modal-del-html_vinculo').modal('hide');
                $('[name="'+c.campo+'"]').val('');
            });
        }
    }
    if(ac=='alt'){
        if(Object.entries(arr).length>0){
            Object.entries(arr).forEach(([k, v]) => {
                if(tipo=='array'){
                    var l = '';
                    try {
                        if(l = dl[k]){
                            c.campos[k].value = l;
                        }else{
                            if(cp=c.campos[k].cp_busca){
                                let ar = cp.split('][');
                                if(ar[1]){
                                    try {
                                        c.campos[k].value = dl[ar[0]][ar[1]];
                                    } catch (error) {
                                        console.log(error);
                                    }
                                }
                            }
                        }
                    } catch (error) {
                        console.log(error);
                    }
                }else{
                    if(c.list[k]){
                        c.campos[k].value = c.list[k];
                    }else{
                        if(cp=c.campos[k].cp_busca){
                            let ar = cp.split('][');
                            if(ar[1]){
                                try {
                                    c.campos[k].value = c.list[ar[0]][ar[1]];
                                } catch (error) {
                                    console.log(error);
                                }
                            }
                        }
                    }
                }
            });

            renderForm(c,campos,function(res){
                if(res.mens){
                    lib_formatMensagem('.mens',res.mens,res.color);
                }
                if(res.exec){
                    var mod = '#modal-geral';
                    $(mod).modal('hide');
                    lib_listDadosHtmlVinculo(res,campos,ac,lin);
                }
            });
            if(tipo=='array' && dl){
                try {
                    /*
                    var tid='';
                    if(dl[id]){
                        tid=dl[id];
                    }
                    console.log(dl);
                    */
                    frm = $(idf)
                    var m = '<input type="hidden" name="_method" value="PUT">';
                    frm.attr('action',c.action+'/'+dl['id']);
                    frm.find('[name="_method"]').remove();
                    frm.append(m);

                } catch (error) {
                    console.log(error);
                }
            }else{
                if(c.list.id){
                    frm = $(idf)
                    var m = '<input type="hidden" name="_method" value="PUT">';
                    frm.attr('action',c.action+'/'+c.list.id);
                    frm.find('[name="_method"]').remove();
                    frm.append(m);
                }
            }
        }
    }
}
function calculaLinCad(seleTr){
    //calcula numero da ultima linha
    var ret='';
    try {
        var elem = $(seleTr).last().attr('id');
        if(typeof elem=='undefined'){
            return '0';
        }
        var tr=elem.split('-');
        if(tr[1]){
            ret = new Number(tr[1])+1;
        }
    } catch (e) {
        console.log(e);
        return '0';
    }
    return ret;

}
function lib_listDadosHtmlVinculo(res,campos,ac,lin){
    //lin é o numero da linha para o caso do tipo array
    //alert(lin);
    if(typeof ac=='undefined'){
        ac = 'alt';
    }
    if(typeof lin=='undefined'){
        lin = '';
    }
    var dt = decodeArray(campos);
    try {
        var tipo=dt.tipo;
    } catch (e) {
        var tipo='int';
        console.log(e);
    }
    if(typeof tipo =='undefined'){
        var tipo='int';
    }
    if((d=res.dados) && ac =='cad'){
        var table = $('#table-html_vinculo-'+dt.campo);
        lin = calculaLinCad('#table-html_vinculo-'+dt.campo+' tbody tr');
       // alert(lin);
        var tm = $('tm').html();
        var tm0 = '<tr id="tr-{id}">{td}</tr>';
        var tm = '<td id="td-{k}" class="{class}">{v}</td>';
        var data_list = encodeArray(d);
        if(t = dt.table){
            var td = '';
            $.each(t,function(k,v){
                if(v.type=='text'){
                    td += tm.replaceAll('{k}',k);
                    td = td.replaceAll('{v}',d[k]);
                    td = td.replaceAll('{class}','');
                }else if(v.type=='arr_tab'){
                    var kv = k+'_valor';
                    td += tm.replaceAll('{k}',kv);
                    td = td.replaceAll('{v}',d[kv]);
                    td = td.replaceAll('{class}','');
                }
            });
            if (tipo=='array'&&lin){
                if(lin=='0')
                    lin=0;
                /*try {

                    if(typeof dt.list[lin]=='undefined'){
                        dt.list = [d];
                    }else{
                        dt.list[lin] = d;
                    }
                    console.log(dt);
                } catch (e) {
                    dt.list = [d];
                    console.log(e);
                }*/
            }else{
                dt.list = d;
            }
            var e = encodeArray(dt);
            var btnsAc = '<button type="button" btn-alt="" onclick="lib_htmlVinculo2(\'alt\',\''+e+'\',\''+d['id']+'\',\''+lin+'\')" title="Editar" class="btn btn-outline-secondary"><i class="fas fa-pencil-alt"></i> </button> '+
            '<button type="button" onclick="lib_htmlVinculo2(\'del\',\''+e+'\',\''+d['id']+'\',\''+lin+'\')" class="btn btn-outline-danger" title="Remover"> <i class="fa fa-trash" aria-hidden="true"></i> </button>';
            var tdacao = tm.replaceAll('{k}','tr-acao');
            tdacao = tdacao.replaceAll('{v}',btnsAc);
            tdacao = tdacao.replaceAll('{class}','text-right');
            if(tipo=='array'){
                var tr = tm0.replaceAll('{id}',lin+'-'+d.id);
                var inp = '<input type="hidden" name="'+dt.campo+'[]" value="'+d.id+'" />'+
                '<input type="hidden" value="'+data_list+'" id="inp-list-'+lin+'-'+d.id+'">';
                var tr = tr.replaceAll('{td}',td+inp+tdacao);
                //var tr = tr.replaceAll('{data_list}',data_list);
                table.find('tbody').append(tr);
            }else{
                var tr = tm0.replaceAll('{id}',d.id);
                var tr = tr.replaceAll('{td}',td+tdacao);
                var inp = '<input type="hidden" name="'+dt.campo+'" value="'+d.id+'" />';
                var tr = tr.replaceAll('{data_list}',data_list);
                table.find('tbody tr').remove();
                table.find('tbody').html(tr);
                if(dt.campo){
                    $('[name="'+dt.campo+'"]').remove();
                    var selInp = table.find('tbody');
                    $(inp).insertBefore(selInp);
                }
            }
        }
    }
    if((d=res.dados) && ac =='alt'){
        var table = $('#table-html_vinculo-'+dt.campo);
        if(tipo=='array' && lin){
            var seltr = '#tr-'+lin+'-'+d.id;
            //dt.list = d;
            //console.log(dt.list[lin]);
        }else{
            var seltr = '#tr-'+d.id;
            dt.list = d;
        }
        $.each(d,function(k,v){
            table.find(seltr+' #td-'+k).html(v);
        });
        var e = encodeArray(dt);

        table.find(seltr+' [btn-alt]').attr('data_selector',e);
        table.find(seltr+' #inp-list-'+lin+'-'+d.id).val(encodeArray(d));

        //table.find(seltr+' [btn-alt]').attr('onclick','lib_htmlVinculo(\'alt\',"'+e+'","'+lin+'")');
        table.find(seltr+' [btn-alt]').attr('onclick','lib_htmlVinculo2(\'alt\',"'+e+'","'+d.id+'","'+lin+'")');
        //console.log(dt);
        //alert(ac);
    }
}
function lib_listarCadastro(res,obj){
    if(typeof obj == 'undefined')
    {
        return;
    }
    try {
        if(res.id=='cad'){
            var dt = decodeArray(obj.data('selector'));
            $('[div-id="'+dt.campo+'"] [data-ac="cad"]').click();
            //console.log(dt);
            return;
        }
        if(res.dados){
            lib_listDadosHtmlVinculo(res,obj.data('selector'),'cad');
            obj.val('');
        }
    } catch (error) {
        console.log(error);
    }
}
function lib_abrirModalConsultaVinculo(campo,ac){

    var btnAbrir = $('#row-'+campo+' .btn-consulta-vinculo'),btnFechar = $('#row-'+campo+' .btn-voltar-vinculo'),ef='slow';
    if(ac=='abrir'){
        btnAbrir.hide(ef);
        btnFechar.show(ef);
        $('#inp-cad-'+campo).show(ef);
        $('#inp-cad-'+campo+' input').val('');
        $('#inp-cad-'+campo+' input').focus();
        lib_autocomplete($('#inp-auto-'+campo));
    }
    if(ac=='fechar'){
        btnAbrir.show(ef);
        btnFechar.hide(ef);
        $('#inp-cad-'+campo).hide(ef);
        $('#inp-cad-'+campo+' input').val('');
    }
}
function lib_autocomplete(obs){
    var urlAuto = obs.attr('url');
    var data_selector = obs.data('selector'),d=decodeArray(data_selector);
    try {
        if(typeof d.janela != 'undefined'){
            if(pr=d.janela.param){
                //console.log(d.janela.param);
                for (let i = 0; i < pr.length; i++) {
                    const el = pr[i];
                    if(i==0){
                        urlAuto += '?'+el+'='+$('[name="'+el+'"]').val();
                    }else{
                        urlAuto += '&'+el+'='+$('[name="'+el+'"]').val();
                    }
                }
            }
        }
    }
    catch (e) {
        console.log(e);
    }
    //console.log(urlAuto);
     obs.autocomplete({
        source: urlAuto,
        search  : function(){$(this).addClass('ui-autocomplete-loading');},
        open    : function(){$(this).removeClass('ui-autocomplete-loading');},
        select: function (event, ui) {
            lib_listarCadastro(ui.item,$(this));
        },
    });
}
function carregaMatricula(val,local){
    if(typeof local=='undefined'){
        local='';
    }
    if(val==''|| val=='cad'|| val=='ger' || !val)
        return ;
    if(local=='familias'){
        carregaQuadras(val);
        lib_abrirModalConsultaVinculo('loteamento','fechar');
    }
    getAjax({
        url:'/bairros/'+val+'/edit?ajax=s',
    },function(res){
        $('#preload').fadeOut("fast");
        if(m=res.value.matricula){
            $('[name="matricula"]').val(m);
            $('#txt-matricula').html(m);
        }else{
            $('[name="matricula"]').val('');
            $('#txt-matricula').html('');
        }
    });
}
function carregaBairro(val){
    if(val==''|| val=='cad'|| val=='ger' || !val)
        return ;
    getAjax({
        url:'/quadras/'+val+'/edit?ajax=s',
    },function(res){
        $('#preload').fadeOut("fast");
        try {
            if(b=res.value.bairro){

                $('[name="bairro"]').val(b);
                $('#txt-bairro').html(res.value.bairro_nome);
            }else{
                $('[name="bairro"]').val('');
                $('#txt-bairro').html('');
            }
        } catch (error) {
            console.log(error);
        }

    });
}
function carregaQuadras(val,selQuadra){
    if(typeof selQuadra=='undefined'){
        selQuadra='quadra';
    }
    const url = new URL(window.location.href);
    let pagina = url.pathname;
    let pag=pagina.split('/');
    if(pag.length==2){
        selQuadra = 'filter['+selQuadra+']';
    }
    if(val==''){
        $('[div-id="'+selQuadra+'"] option.opcs').each(function(){
            $(this).remove();
        });
        return
    }
    getAjax({
        url:'/quadras?ajax=s&filter[bairro]='+val+'&campo_order=id&order=ASC',
    },function(res){
        $('#preload').fadeOut("fast");
        var option_select = '<option value="{value}" class="opcs">{label}</option>';
        var opc = '';
        $('[div-id="'+selQuadra+'"] option.opcs').each(function(v,k){
            $(this).remove();
        });
        if(d=res.dados.data){
            $.each(d,function(k,v){
                console.log(v);
                opc += option_select.replaceAll('{label}',v.nome);
                opc = opc.replaceAll('{value}',v.id);
            });
            $(opc).insertAfter('[div-id="'+selQuadra+'"] option.option_select');
        }
    });
    $.ajax({
        url:'/quadras?ajax=s&filter[bairro]=1',
        type:'GET',
        success:'GET',
    });
}
function buscaCep1_0(cep_code){
    if( cep_code.length <= 0 ) return;
    cep_code = cep_code.replaceAll('.','').replaceAll('-','');
    $.get("https://viacep.com.br/ws/"+cep_code+"/json/", { code: cep_code },
       function(result){
           console.log(result);
          if( result.cep =='' ){
             alerta(result.message || "Cep nÃ£o encontrado!");
             return;
          }
          if( result.erro){
              $('#Cep,#Cep,[q-inp="Cep"],[name="edit_cliente[Cep]"]').select();
             lib_formatMensagem('.mens,.mensa',"O cep <b>"+cep_code+"</b> nÃ£o foi encontrado! <button type=\"button\" onclick='abrirjanelaPadraoConsulta(\"https://buscacepinter.correios.com.br/app/endereco/index.php?t\");' class='btn btn-primary'>"+__translate('NÃ£o sei o cep')+"</button>",'danger',9000);
              $('input#Cep,[name="cep"],[q-inp="cep"],[name="edit_cliente[Cep]"]').val('');
             return;
          }
          //$("input#Cep,[name=\"cep\"],[q-inp=\"cep\"]").val( result.cep );
          $('input#Estado,[name="uf"],[q-inp="uf"],[uf="cep"],[name="edit_cliente[Uf]"]').val( result.uf );
          $('input#Cidade,[name="cidade"],[q-inp="cidade"],[cidade="cep"],[name="edit_cliente[Cidade]"]').val( result.localidade );
          $('input#Bairro,[name="bairro"],[q-inp="bairro"],[bairro="cep"],[name="edit_cliente[Bairro]"]').val( result.bairro );
          $('input#Endereco,[name="endereco"],[q-inp="endereco"],[endereco="cep"],[name="edit_cliente[Endereco]"]').val( result.logradouro );
          $('#UF,[name="Uf"],[name="config[uf]"]').val( result.uf );
          $("#Uf").val( result.uf );
          $('#codigoCidade,[name="config_notas[endereco][codigoCidade]"],[codigoCidade="cep"],[name="config[codigoCidade]"],[name="edit_cliente[codigoCidade]"]').val(result.ibge);
          $('#numero,#Numero,[q-inp="numero"],[name="numero"],[numero="cep"]').select();
       });
}
function popupCallback_vinculo(res){
    var obj = $('obj').html();
    var d = decodeArray(obj);
    //console.log(d);
    if(res.mens){
        lib_formatMensagem('.mens',res.mens,res.color);
    }
    if(res.exec){
        lib_listDadosHtmlVinculo(res,obj,'cad');
    }
}
function popupCallback_redirect(url){
    window.location=url;
}
function btVoltar(obj){
    var href = obj.attr('href'),redirect = obj.attr('redirect');
    if(redirect){
        if(pop){
            if(redirect){
                popupCallback_redirect(redirect);
                window.close();
            }
        }else{
            window.location = redirect;
        }
    }else{
        if(pop){
            window.close();

        }else{
            window.location = href;
        }
    }
}
function lib_abrirListaOcupantes(){
    var sel = coleta_checked($('.table .checkbox:checked'));
    if(sel==''){
        var msg = '<div class="row"><div id="exibe_etapas" class="col-md-12 text-center"><p>Por favor selecione um registro!</p></div></div>';
        alerta(msg,'modal-etapa','Alerta','',true,3000,true)
        return;
    }else{
        var url = '/lotes/lista-ocupantes/'+sel;
        abrirjanelaPadraoConsulta(url,'lista-ocupantes');
    }
}
function zoom(c) {
    var s = new Number(50);
    let a = 0;
    let box = document.querySelector('#svg-img');
    let width = box.style.width;
    let top = box.style.top;
    let left = box.style.left;
    let height = box.offsetHeight;
    var w = width.replace('%','');
    var l = left.replace('%','');
    var t = top.replace('%','');
    w = new Number(w);
    if(w==0){
        w=100;
    }
    if(c=='p'){
        a=w+s;
        to=t-s;
        le=l-s;
        box.style.width = (a)+'%';
        box.style.top = (to)+'%';
        box.style.left = (le)+'%';
    }
    if(c=='r'){
        //retorna ao inicio
        box.style.width = '100%';
        box.style.left = '0%';
        box.style.top = '0%';
    }
    if(c=='m'){
        a=w-s;
        to=new Number(t)+new Number(s);
        le=new Number(l)+new Number(s);

        box.style.width = (a)+'%';
        box.style.top = (to)+'%';
        box.style.left = (le)+'%';
    }
}
function lib_conteudoMapa(id,tipo,local){
    if(typeof tipo=='undefined'){
        tipo = 'lotes';
    }
    if(typeof local=='undefined'){
        local = 'quadras';
    }
    if(id &&tipo=='lotes' && local=='quadras'){
        let arr_id = id.split('-');
        getAjax({
            url:'/'+tipo+'?ajax=s',
            data:{
                bairro:arr_id[0],
                quadra:arr_id[1],
                term:new Number(arr_id[2]),
                familias:'s',
            }
        },function(res){
            $('#preload').fadeOut("fast");
            lib_infoMaps({
                res:res,
                bairro:arr_id[0],
                quadra:arr_id[1],
                lote:arr_id[2],
                local:local,
                tipo:tipo,
            });
        });
    }
}
function lib_infoMaps(config){
    if(typeof config.res=='undefined' || typeof config.local=='undefined' || typeof config.tipo=='undefined'){
        return;
    }
    if(typeof config.lote=='undefined'){
        config.lote = 0;
    }
    if(typeof config.quadra=='undefined'){
        config.quadra = 0;
    }
    try {
        let mensPainel = '';
        let tm1 = '<div class="card card-secondary shadow card-outline"><div class="card-header"><h3 class="card-title">{title}</h3>{btn_fechar}</div><div class="card-body {px}"><div class="list-group">{cont}</div></div>';
        let tm2 = '<a class="list-group-item  list-group-item-action py-1 px-2" href="{href}">{label} <i class="fa fa-link ml-3"></i></a>';
        let btn_fechar = '<div class="card-tools"><button onclick="lib_fechaCardOc();" type="button" class="btn btn-tool" data-card-widget="close" title="Collapse"><i class="fas fa-times"></i></button></div>';
        let redirect = '?redirect=/mapas/'+config.local+'/'+config.quadra;
        let redirect2 = '&redirect=/mapas/'+config.local+'/'+config.quadra;
        if(dl=config.res[0].dados){
            let link_lote = '/lotes/'+dl.id+'/edit'+redirect;
            if(fam=dl.familias){
                let cont = '';
                if(fam[0]){

                    for (let i = 0; i < fam.length; i++) {
                        const el = fam[i];
                        let href='/familias/'+el.id+'/show'+redirect;
                        cont += tm2.replace('{href}',href);
                        cont = cont.replace('{label}',el.nome);
                    }
                    if(fam.length>1){
                        var title = 'Ocupantes';
                    }else{
                        var title = 'Ocupante';
                    }
                    mensPainel = tm1.replace('{cont}',cont);
                    mensPainel = mensPainel.replace('{px}','p-0');
                    mensPainel = mensPainel.replace('{title}',title+' <a href="'+link_lote+redirect+'" style="text-decoration:underline">lote '+dl.nome+'</a>');
                }else{
                    cont = 'Ocupante não cadastrado';
                    cont += '<a href="/lotes/create?bairro='+config.bairro+'&quadra='+config.quadra+'&nome='+config.lote+redirect2+'" class="btn btn-primary btn-block mt-3">Cadastrar</a>';

                    mensPainel = tm1.replace('{cont}',cont);
                    mensPainel = mensPainel.replace('{title}','Aviso <a href="'+link_lote+redirect+'">lote '+dl.nome+'</a>');
                }
            }else{
                cont = 'Família não localizada';
                mensPainel = tm1.replace('{cont}',cont);
                mensPainel = mensPainel.replace('{title}','Aviso <a href="'+link_lote+redirect+'">lote '+dl.nome+'</a>');
            }
        }else{
            cont = config.res[0].value;
            let btnCadA = '<a href="/familias/create?bairro='+config.bairro+'&quadra='+config.quadra+'&loteamento='+config.lote+redirect2+'" class="btn btn-primary btn-block mt-3">Cadastrar</a>';
            mensPainel = tm1.replace('{cont}',cont);
            mensPainel = mensPainel.replace('Cadastrar agora?',btnCadA);
            mensPainel = mensPainel.replace('Lote','Cadastro do lote '+config.lote);
            mensPainel = mensPainel.replace('{title}','Atenção');
        }
        if(mensPainel){
            mensPainel = mensPainel.replace('{btn_fechar}',btn_fechar);
            $('.mini-card').addClass('active').html(mensPainel);
        }else{
            $('.mini-card').removeClass('active').html('');
        }
    } catch (error) {
        console.log(error);
    }
}
function lib_fechaCardOc(){
    $('.mini-card').removeClass('active').html('');
}
function maskCampos(obj){

}
function currencyFormat(fld, milSep, decSep, tamanho, e) {
    var sep = 0;
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '0123456789';
    var aux = aux2 = '';
    var whichCode = (window.Event) ? e.which : e.keyCode;
    if (whichCode == 13) return true;
    if (whichCode == 8) return true;
    key = String.fromCharCode(whichCode);
    if (strCheck.indexOf(key) == -1) return false;
    len = fld.value.length;
    if (len<=tamanho){
        for(i = 0; i < len; i++)
        if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break;
        aux = '';
        for(; i < len; i++)
        if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i);
        aux += key;
        len = aux.length;
        if (len == 0) fld.value = '';
        if (len == 1) fld.value = '0'+ decSep + '0' + aux;
        if (len == 2) fld.value = '0'+ decSep + aux;
        if (len > 2) {
            aux2 = '';
            for (j = 0, i = len - 3; i >= 0; i--) {
                if (j == 3) {
                    aux2 += milSep;
                    j = 0;
                }
                aux2 += aux.charAt(i);
                j++;
            }
            fld.value = '';
            len2 = aux2.length;
            for (i = len2 - 1; i >= 0; i--)
            fld.value += aux2.charAt(i);
            fld.value += decSep + aux.substr(len - 2, len);
        }
        return false;
    }else{
        return false;
    }
}
function procedimento_alimentador_total(){

    if(document.getElementById('procedimento_alimentador_quantidade').value==''){
        var_quantidade = 0;
    }else{
        var_quantidade = document.getElementById('procedimento_alimentador_quantidade').value;
    }
    if(document.getElementById('procedimento_alimentador_fator').value==''){
        var_fator = 1;
        document.getElementById('procedimento_alimentador_fator').value = '1.00';
    }else{
        var_fator = document.getElementById('procedimento_alimentador_fator').value;
    }
    if(document.getElementById('procedimento_alimentador_valor_unitario').value==''){
        var_valorUnitario = 0;
    }else{
        var_valorUnitario = document.getElementById('procedimento_alimentador_valor_unitario').value;
    }
    if(var_valorUnitario){
        var_valorUnitario = var_valorUnitario.replace('R$','');
        var_valorUnitario = var_valorUnitario.replace('.','');
        var_valorUnitario = var_valorUnitario.replace(',','.');
    }

    //console.log(var_valorUnitario);
    var_valorTotal = var_quantidade * var_fator * var_valorUnitario;
    var_valorTotal = var_valorTotal.toFixed(2);
    document.getElementById('procedimento_alimentador_valor_total').value = var_valorTotal ;
}
function upcase(e,r){
    var1 = e.keyCode;
    var2 = r.value.toUpperCase();
    if (var1 !=37 && var1!=39 && var1!=8 && var1!=46){
        document.querySelector('[name="'+r.name+'"]').value = var2;
    }
}
function alimenta_procedimento(){
    procedimento_alimentador_total();
    erro = '0';
    if (document.getElementById('procedimento_alimentador_data').value==''){
        document.getElementById('procedimento_alimentador_data').style.backgroundColor='#ff5555';
        erro = '1';
    }
    if (document.getElementById('procedimento_alimentador_tabela').value==''){
        document.getElementById('procedimento_alimentador_tabela').style.backgroundColor='#ff5555';
        erro = '1';
    }
    if (document.getElementById('procedimento_alimentador_codigo').value==''){
        document.getElementById('procedimento_alimentador_codigo').style.backgroundColor='#ff5555';
        erro = '1';
    }
    if (document.getElementById('procedimento_alimentador_descricao').value==''){
        document.getElementById('procedimento_alimentador_descricao').style.backgroundColor='#ff5555';
        erro = '1';
    }
    if (document.getElementById('procedimento_alimentador_quantidade').value==''){
        document.getElementById('procedimento_alimentador_quantidade').style.backgroundColor='#ff5555';
        erro = '1';
    }
    if (document.getElementById('procedimento_alimentador_fator').value==''){
        document.getElementById('procedimento_alimentador_fator').style.backgroundColor='#ff5555';
        erro = '1';
    }
    if (document.getElementById('procedimento_alimentador_valor_unitario').value==''){
        document.getElementById('procedimento_alimentador_valor_unitario').style.backgroundColor='#ff5555';
        erro = '1';
    }
    if (document.getElementById('procedimento_alimentador_valor_total').value==''){
        document.getElementById('procedimento_alimentador_valor_total').style.backgroundColor='#ff5555';
        erro = '1';
    }
    if (erro == '0'){
        linha = parseFloat(document.getElementById('qtde_procedimento').value);
        var num_procedimento = $('#demo_procedimentos tr').length;
        if(!linha || typeof linha=='NaN'){
            linha = num_procedimento;
        }
        linha += 1;
        if (document.getElementById('procedimento_alimentador_hora1').value==''){
            hora1 = '&nbsp;'
        }else{
            hora1 = document.getElementById('procedimento_alimentador_hora1').value;
        }
        if (document.getElementById('procedimento_alimentador_hora2').value==''){
            hora2 = '&nbsp;'
        }else{
            hora2 = document.getElementById('procedimento_alimentador_hora2').value;
        }
        if (document.getElementById('procedimento_alimentador_via').value==''){
            via = '&nbsp;'
        }else{
            via = document.getElementById('procedimento_alimentador_via').value;
        }
        if (document.getElementById('procedimento_alimentador_tec').value==''){
            tec = '&nbsp;'
        }else{
            tec = document.getElementById('procedimento_alimentador_tec').value;
        }
        //document.getElementById('qtde_procedimento').value = linha;
        ref_linha = 0;
        if(num_procedimento){
            ref_linha = num_procedimento;
        }
        //nova_linha = '<table id="linha_procedimento_'+linha+'" width="100%" style="display:block"><tr>';
        var inpu = '<input type="hidden" name="config[procedimento]['+linha+'][item]" value="'+linha+'"/>';
        var arr_campos = ['data','hora1','hora2','via','tec','tabela','codigo','descricao','quantidade','fator','valor_unitario','valor_total'];
        if(arr_campos.length>0){
            for (let i = 0; i < arr_campos.length; i++) {
                const element = arr_campos[i];
                inpu += '<input type="hidden" name="config[procedimento]['+linha+']['+element+']" value="'+document.getElementById('procedimento_alimentador_'+element).value+'"/>';

            }
        }
        nova_linha = '<tr id="linha_procedimento_'+linha+'">';
        nova_linha += '<td class="celula_item" align="center" width="20" id="td_contador_linha_'+linha+'">'+ref_linha+'</td>';
        nova_linha += '<td class="celula_item" align="center" width="65">'+inpu+''+document.getElementById('procedimento_alimentador_data').value+'</td>';
        nova_linha += '<td class="celula_item" align="center" width="65">'+hora1+'</td>';
        nova_linha += '<td class="celula_item" align="center" width="60">'+hora2+'</td>';
        nova_linha += '<td class="celula_item" align="center" width="40">'+document.getElementById('procedimento_alimentador_tabela').value+'</td>';
        nova_linha += '<td class="celula_item" align="center" width="70">'+document.getElementById('procedimento_alimentador_codigo').value+'</td>';
        nova_linha += '<td class="celula_item" width="240">'+document.getElementById('procedimento_alimentador_descricao').value+'</td>';
        nova_linha += '<td class="celula_item" align="center" width="40">'+document.getElementById('procedimento_alimentador_quantidade').value+'</td>';
        nova_linha += '<td class="celula_item" align="center" width="35">'+via+'</td>';
        nova_linha += '<td class="celula_item" align="center" width="35">'+tec+'</td>';
        nova_linha += '<td class="celula_item" align="center" width="50">'+document.getElementById('procedimento_alimentador_fator').value+'</td>';
        nova_linha += '<td class="celula_item" align="right" width="80">'+document.getElementById('procedimento_alimentador_valor_unitario').value+'</td>';
        nova_linha += '<td class="celula_item" align="right" width="60">'+document.getElementById('procedimento_alimentador_valor_total').value+'</td>';
        nova_linha += '<td class="celula_item" align="center" width="30"><button type="button" class="btn btn-outline-danger" onclick="procedimento_remove_item(\''+linha+'\')"><i class="fa fa-times"></i></button></td>';
        nova_linha += '</tr>';
        //nova_linha += '</tr></table>';
        document.getElementById('demo_procedimentos').innerHTML+=nova_linha;

            total_procedimento = 0;
            for (var i=1;i<=linha;i++){
                try {
                    var sel = '[name="config[procedimento]['+i+'][valor_total]"]';
                    alert(sel);
                    if (document.querySelector(sel).value!=''){
                        total_procedimento += parseFloat(document.querySelector(sel).value);
                    }
                } catch (e) {
                    console.log(e);
                }
            }
            total_procedimento = total_procedimento.toFixed(2);
            document.getElementById('total_procedimentos').value = total_procedimento;

        //document.getElementById('procedimento_alimentador_item_novo').value='';
        document.getElementById('procedimento_alimentador_data').value='';
        document.getElementById('procedimento_alimentador_hora1').value='';
        document.getElementById('procedimento_alimentador_hora2').value='';
        document.getElementById('procedimento_alimentador_tabela').value='';
        document.getElementById('procedimento_alimentador_codigo').value='';
        document.getElementById('procedimento_alimentador_descricao').value='';
        document.getElementById('procedimento_alimentador_quantidade').value='';
        document.getElementById('procedimento_alimentador_via').value='';
        document.getElementById('procedimento_alimentador_tec').value='';
        document.getElementById('procedimento_alimentador_fator').value='';
        document.getElementById('procedimento_alimentador_valor_unitario').value='';
        document.getElementById('procedimento_alimentador_valor_total').value='';
        document.getElementById('procedimento_alimentador_tabela').value=document.getElementById('tabela_padrao').value;
        calcula_total_geral();
        $('#add-procedimento').modal('hide');
    }
}
function calcula_total_geral(){

    var_total_geral = 0;

    if (document.getElementById('total_procedimentos').value==''){
        document.getElementById('total_procedimentos').value='0.00';
    }else{
        var_total_geral += (document.getElementById('total_procedimentos').value*1);
    }
    if (document.getElementById('total_diarias').value==''){
        document.getElementById('total_diarias').value='0.00';
    }else{
        var_total_geral += (document.getElementById('total_diarias').value*1);
    }
    if (document.getElementById('total_gases').value==''){
        document.getElementById('total_gases').value='0.00';
    }else{
        var_total_geral += (document.getElementById('total_gases').value*1);
    }
    if (document.getElementById('total_medicamentos').value==''){
        document.getElementById('total_medicamentos').value='0.00';
    }else{
        var_total_geral += (document.getElementById('total_medicamentos').value*1);
    }
    if (document.getElementById('total_materiais').value==''){
        document.getElementById('total_materiais').value='0.00';
    }else{
        var_total_geral += (document.getElementById('total_materiais').value*1);
    }
    if (document.getElementById('total_taxas').value==''){
        document.getElementById('total_taxas').value='0.00';
    }else{
        var_total_geral += (document.getElementById('total_taxas').value*1);
    }
    if (document.getElementById('total_opme').value==''){
        document.getElementById('total_opme').value='0.00';
    }else{
        var_total_geral += (document.getElementById('total_opme').value*1);
    }
    document.getElementById('total_geral').value = var_total_geral.toFixed(2);

}
function procedimento_remove_item(linha){
    document.getElementById('tr_contador_linha_'+linha).remove();
    qtde_linhas = document.getElementById('qtde_procedimento').value;
    contador_linha = 0;
    for (var i=1;i<=qtde_linhas;i++){
        contador_linha += 1;
        //if(document.getElementById('linha_procedimento_'+i).style.display=='block'){
            //document.getElementById('td_contador_linha_'+i).innerHTML=contador_linha
        //}
    }
    linha = document.getElementById('qtde_procedimento').value;

        total_procedimento = 0;
        for (var i=1;i<=linha;i++){
            //if (document.getElementById('procedimento_valorTotal_'+i).value!=''){
                //total_procedimento += parseFloat(document.getElementById('procedimento_valorTotal_'+i).value);
            //}
            try {
                var sel = '[name="config[procedimento]['+i+'][valor_total]"]';
                if (document.querySelector(sel).value!=''){
                    total_procedimento += parseFloat(document.querySelector(sel).value);
                }
            } catch (e) {
                console.log(e);
            }
        }
        total_procedimento = total_procedimento.toFixed(2);
        document.getElementById('total_procedimentos').value = total_procedimento;
        calcula_total_geral();

}
function alimenta_executante(){
    erro = '0';
    if (document.getElementById('executante_alimentador_seq').value==''){
        document.getElementById('executante_alimentador_seq').style.backgroundColor='#ff5555';
        erro = '1';
    }
    if (document.getElementById('executante_alimentador_grau_part').value==''){
        document.getElementById('executante_alimentador_grau_part').style.backgroundColor='#ff5555';
        erro = '1';
    }
    if (document.getElementById('executante_alimentador_nome').value==''){
        document.getElementById('executante_alimentador_nome').style.backgroundColor='#ff5555';
        erro = '1';
    }
    if (document.getElementById('executante_alimentador_codigo').value==''){
        document.getElementById('executante_alimentador_codigo').style.backgroundColor='#ff5555';
        erro = '1';
    }
    if (erro == '0'){
        linha = parseFloat(document.getElementById('qtde_executante').value);
        var num_executantes = $('#demo_executantes tr').length;
        if(!linha || typeof linha=='NaN'){
            linha = num_executantes;
        }
        linha += 1;
        info_exec = document.getElementById('executante_alimentador_nome').value.split('|');
        var_exec_id = info_exec[0];
        var_exec_nome = info_exec[1];
        var inpu = '<input type="hidden" name="config[executantes]['+linha+'][item]" value="'+linha+'"/>';
        var arr_campos = ['seq','ex_id','ex_nome','grau_part','codigo','conselho','conselho_numero','conselho_uf','cbo'];
        if(arr_campos.length>0){
            for (let i = 0; i < arr_campos.length; i++) {
                const element = arr_campos[i];
                if(element=='ex_nome'){
                    inpu += '<input type="hidden" name="config[executantes]['+linha+']['+element+']" value="'+var_exec_nome+'"/>';
                }else if(element=='ex_id'){
                    inpu += '<input type="hidden" name="config[executantes]['+linha+']['+element+']" value="'+var_exec_id+'"/>';
                }else{
                    inpu += '<input type="hidden" name="config[executantes]['+linha+']['+element+']" value="'+document.getElementById('executante_alimentador_'+element).value+'"/>';
                }
            }
        }

        nova_linha = '<table id="linha_executante_'+linha+'" width="100%" style="display:block"><tr>';
        nova_linha += '<td class="celula_item" align="center" width="50">'+inpu+document.getElementById('executante_alimentador_seq').value+'</td>';
        nova_linha += '<td class="celula_item" align="center" width="70">'+document.getElementById('executante_alimentador_grau_part').value+'</td>';
        nova_linha += '<td class="celula_item" width="370">'+var_exec_nome+'</td>';
        nova_linha += '<td class="celula_item" align="center" width="140">'+document.getElementById('executante_alimentador_codigo').value+'</td>';
        nova_linha += '<td class="celula_item" align="center" width="60">'+document.getElementById('executante_alimentador_conselho').value+'</td>';
        nova_linha += '<td class="celula_item" align="center" width="95">'+document.getElementById('executante_alimentador_conselho_numero').value+'</td>';
        nova_linha += '<td class="celula_item" align="center" width="30">'+document.getElementById('executante_alimentador_conselho_uf').value+'</td>';
        nova_linha += '<td class="celula_item" align="center" width="75">'+document.getElementById('executante_alimentador_cbo').value+'</td>';
        nova_linha += '<td class="celula_item" align="center" width="30"><button type="button" class="btn btn-outline-danger" onclick="executante_remove_item(\''+linha+'\')"><i class="fa fa-times"></i></button></td>';
        nova_linha += '</tr></table>';
        document.getElementById('qtde_executante').value = linha;
        document.getElementById('demo_executantes').innerHTML+=nova_linha;
        document.getElementById('executante_alimentador_ex_id').value='';
        document.getElementById('executante_alimentador_seq').value='';
        document.getElementById('executante_alimentador_grau_part').value='';
        document.getElementById('executante_alimentador_nome').value='';
        document.getElementById('executante_alimentador_codigo').value='';
        document.getElementById('executante_alimentador_conselho').value='';
        document.getElementById('executante_alimentador_conselho_numero').value='';
        document.getElementById('executante_alimentador_conselho_uf').value='';
        document.getElementById('executante_alimentador_cbo').value='';
    }
}

function executante_remove_item(linha){
    document.getElementById('executor_linha_'+linha).remove();
}
function autocompletarSelect(tipo){
    if(typeof tipo=='undefined'){
        return;
    }
    if (tipo=='executante'){
        valor=document.getElementById('executante_alimentador_nome').value;
        if (valor==''){
            document.getElementById('executante_alimentador_codigo').value = '';
            document.getElementById('executante_alimentador_conselho').value = '';
            document.getElementById('executante_alimentador_conselho_numero').value = '';
            document.getElementById('executante_alimentador_conselho_uf').value = '';
            document.getElementById('executante_alimentador_cbo').value = '';
            document.getElementById('executante_alimentador_ex_id').value = '';
            document.getElementById('executante_alimentador_nome').style.backgroundColor='#ffffff';
            document.getElementById('executante_alimentador_codigo').style.backgroundColor='#ffffff';
        }else{
            valores_exec = valor.split('|');
            var dados = '';
            try {

                if(valores_exec[2]!=''){
                    dados = JSON.parse(valores_exec[2]);
                }
                console.log(dados);
                document.getElementById('executante_alimentador_codigo').value = dados.cpf_profissional;
                document.getElementById('executante_alimentador_conselho').value = dados.conselho;
                document.getElementById('executante_alimentador_conselho_numero').value = dados.numero_conselho;
                document.getElementById('executante_alimentador_conselho_uf').value = dados.uf_conselho;
                document.getElementById('executante_alimentador_cbo').value = dados.cbo;
                document.getElementById('executante_alimentador_ex_id').value = valores_exec[0];
                document.getElementById('executante_alimentador_nome').style.backgroundColor='#ffffff';
                document.getElementById('executante_alimentador_codigo').style.backgroundColor='#ffffff';
            } catch (error) {
                console.log(error);
            }
        }
    }
}
function escolhe_operadora(){
   var op = document.getElementById('select_operadora');
    if(op.valor==''){
        document.getElementById('op_id').value = '';
        document.getElementById('registro_ans').value = '';
        document.getElementById('cont_exec_codigo').value = '';
        document.getElementById('tabela_padrao').value = '';
        document.getElementById('procedimento_alimentador_tabela').value='';
        //document.getElementById('campo_1').style.backgroundColor='#ffffff';
    }else{
        var val=op.options[op.selectedIndex].getAttribute('data-values');
        var arr_val='';
        if(val){
            arr_val = decodeArray(val);
            console.log(arr_val);
            if(config=JSON.parse(arr_val.config)){
                console.log(config);
                document.getElementById('cont_exec_codigo').style.backgroundColor='#ffffff';
                //info_operadora = valor.split("|");
                //document.getElementById('op_id').value = info_operadora[0];
                document.getElementById('op_id').value = arr_val.id;
                document.getElementById('registro_ans').value = arr_val.registro;
                document.getElementById('cont_exec_codigo').value = config.identidade;
                document.getElementById('tabela_padrao').value = config.tabela_cobranca;
                document.getElementById('procedimento_alimentador_tabela').value = config.tabela_cobranca;
                document.getElementById('procedimento_alimentador_tabela').style.backgroundColor='#ffffff';
            }
            //document.getElementById('campo_1').style.backgroundColor='#ffffff';
            //document.getElementById('campo_13').style.backgroundColor='#ffffff';
        }
        autocompletarSelect('beneficiario');
        //verifica_versao_tiss(arr_val.id);
    }
}
