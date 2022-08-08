<?php
namespace App\Qlib;

use App\Http\Controllers\admin\EventController;
use App\Http\Controllers\UserController;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Models\Permission;
use App\Models\Qoption;

class Qlib
{
    static public function lib_print($data){
      if(is_array($data) || is_object($data)){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
      }else{
        echo $data;
      }
    }
    static public function qoption($valor = false, $type = false){
        //type é o tipo de respsta
		$ret = false;
		if($valor){
			//if($valor=='dominio_site'){
			//	$ret = dominio();
			//}elseif($valor==''){
			//	$ret = dominio().'/admin';
			//}else{
				//$sql = "SELECT valor FROM qoptions WHERE url = '$valor' AND ativo='s' AND excluido='n' AND deletado='n'";

                //$result = Qlib::dados_tab('qoptions',['sql'=>$sql]);
                $result = Qoption::where('url','=',$valor)->
                where('ativo','=','s')->
                where('excluido','=','n')->
                where('deletado','=','n')->
                select('valor')->
                get();

				if(isset($result[0]->valor)) {
						// output data of each row
						$ret = $result[0]->valor;
						if($valor=='urlroot'){
							$ret = str_replace('/home/ctloja/public_html/lojas/','/home/ctdelive/lojas/',$ret);
						}
                        if($type=='array'){
                            $ret = Qlib::lib_json_array($ret);
                        }
                        if($type=='json'){
                            $ret = Qlib::lib_array_json($ret);
                        }
				}
			//}
		}
		return $ret;
	}
  static function dtBanco($data) {
			$data = trim($data);
			if (strlen($data) != 10)
			{
				$rs = false;
			}
			else
			{
				$arr_data = explode("/",$data);
				$data_banco = $arr_data[2]."-".$arr_data[1]."-".$arr_data[0];
				$rs = $data_banco;
			}
			return $rs;
	}
  static function dataExibe($data=false) {
        $rs=false;
        if($data){
           $val = trim(strlen($data));
			$data = trim($data);$rs = false;
			if($val == 10){
					$arr_data = explode("-",$data);
					$data_banco = @$arr_data[2]."/".@$arr_data[1]."/".@$arr_data[0];
					$rs = $data_banco;
			}
			if($val == 19){
					$arr_inic = explode(" ",$data);
					$arr_data = explode("-",$arr_inic[0]);
					$data_banco = $arr_data[2]."/".$arr_data[1]."/".$arr_data[0];
					$rs = $data_banco."-".$arr_inic[1] ;
			}
        }

			return $rs;
	}
  static function lib_json_array($json=''){
		$ret = false;
		if(is_array($json)){
			$ret = $json;
		}elseif(!empty($json) && Qlib::isJson($json)&&!is_array($json)){
			$ret = json_decode($json,true);
		}
		return $ret;
	}
	public static function lib_array_json($json=''){
		$ret = false;
		if(is_array($json)){
			$ret = json_encode($json,JSON_UNESCAPED_UNICODE);
		}
		return $ret;
	}
    static function precoBanco($preco){
            $sp = substr($preco,-3,-2);
            if($sp=='.'){
                $preco_venda1 = $preco;
            }else{
                $preco_venda1 = str_replace(".", "", $preco);
                $preco_venda1 = str_replace(",", ".", $preco_venda1);
            }
            return $preco_venda1;
    }
    static function isJson($string) {
		$ret=false;
		if (is_object(json_decode($string)) || is_array(json_decode($string)))
		{
			$ret=true;
		}
		return $ret;
	}
  static function Meses($val=false){
  		$mese = array('01'=>'JANEIRO','02'=>'FEVEREIRO','03'=>'MARÇO','04'=>'ABRIL','05'=>'MAIO','06'=>'JUNHO','07'=>'JULHO','08'=>'AGOSTO','09'=>'SETEMBRO','10'=>'OUTUBRO','11'=>'NOVEMBRO','12'=>'DEZEMBRO');
  		if($val){
  			return $mese[$val];
  		}else{
  			return $mese;
  		}
	}
  static function totalReg($tabela, $condicao = false,$debug=false){
			//necessario
			$sql = "SELECT COUNT(*) AS totalreg FROM {$tabela} $condicao";
			if($debug)
				 echo $sql.'<br>';
			//return $sql;
			$td_registros = DB::select($sql);
			if(isset($td_registros[0]->totalreg) && $td_registros[0]->totalreg > 0){
				return $td_registros[0]->totalreg;
			}else
				return 0;
	}
  static function zerofill( $number ,$nroDigo=6, $zeros = null ){
		$string = sprintf( '%%0%ds' , is_null( $zeros ) ?  $nroDigo : $zeros );
		return sprintf( $string , $number );
	}
  static function encodeArray($arr){
			$ret = false;
			if(is_array($arr)){
				$ret = base64_encode(json_encode($arr));
			}
			return $ret;
	}
  static function decodeArray($arr){
			$ret = false;
			if($arr){
				//$ret = base64_encode(json_encode($arr));
				$ret = base64_decode($arr);
				$ret = json_decode($ret,true);

			}
			return $ret;
	}
    static function qForm($config=false){
        if(isset($config['type'])){
            $config['campo'] = isset($config['campo'])?$config['campo']:'teste';
            $config['label'] = isset($config['label'])?$config['label']:false;
            $config['placeholder'] = isset($config['placeholder'])?$config['placeholder']:false;
            $config['selected'] = isset($config['selected']) ? $config['selected']:false;
            $config['tam'] = isset($config['tam']) ? $config['tam']:'12';
            $config['col'] = isset($config['col']) ? $config['col']:'md';
            $config['event'] = isset($config['event']) ? $config['event']:false;
            $config['ac'] = isset($config['ac']) ? $config['ac']:'cad';
            $config['option_select'] = isset($config['option_select']) ? $config['option_select']:true;
            $config['label_option_select'] = isset($config['label_option_select']) ? $config['label_option_select']:'Selecione';
            $config['option_gerente'] = isset($config['option_gerente']) ? $config['option_gerente']:false;
            $config['class'] = isset($config['class']) ? $config['class'] : false;
            $config['style'] = isset($config['style']) ? $config['style'] : false;
            $config['class_div'] = isset($config['class_div']) ? $config['class_div'] : false;
            $config['id'] = !empty($config['id']) ? $config['id'] : 'inp-'.$config['campo'];
            if(@$config['type']=='chave_checkbox' && @$config['ac']=='cad'){
                if(@$config['checked'] == null && isset($config['valor_padrao']))
                $config['checked'] = $config['valor_padrao'];
            }
            //if($config['type']=='select_multiple'){
                //dd($config);
            //}
            if(@$config['type']=='html_vinculo' && @$config['ac']=='alt'){
                $tab = $config['data_selector']['tab'];
                $config['data_selector']['placeholder'] = isset($config['data_selector']['placeholder'])?$config['data_selector']['placeholder']:'Digite para iniciar a consulta...';
                $dsel = $config['data_selector'];
                $id = $config['value'];
                if(@$dsel['tipo']=='array'){
                    if(is_array($id)){
                        foreach ($id as $ki => $vi) {
                            $config['data_selector']['list'][$ki] = Qlib::dados_tab($tab,['id'=>$vi]);
                            if($config['data_selector']['list'][$ki] && isset($config['data_selector']['table']) && is_array($config['data_selector']['table'])){
                                foreach ($config['data_selector']['table'] as $key => $v) {
                                    if(isset($v['type']) && $v['type']=='arr_tab' && isset($config['data_selector']['list'][$ki][$key]) && isset($v['conf_sql'])){
                                        $config['data_selector']['list'][$ki][$key.'_valor'] = Qlib::buscaValorDb([
                                            'tab'=>$v['conf_sql']['tab'],
                                            'campo_bus'=>$v['conf_sql']['campo_bus'],
                                            'select'=>$v['conf_sql']['select'],
                                            'valor'=>$config['data_selector']['list'][$ki][$key],
                                        ]);
                                    }
                                }
                            }
                        }
                        //dd($config['data_selector']);
                    }
                }else{
                    $config['data_selector']['list'] = Qlib::dados_tab($tab,['id'=>$id]);
                    if($config['data_selector']['list'] && isset($config['data_selector']['table']) && is_array($config['data_selector']['table'])){
                        foreach ($config['data_selector']['table'] as $key => $v) {
                            if(isset($v['type']) && $v['type']=='arr_tab' && isset($config['data_selector']['list'][$key]) && isset($v['conf_sql'])){
                                $config['data_selector']['list'][$key.'_valor'] = Qlib::buscaValorDb([
                                    'tab'=>$v['conf_sql']['tab'],
                                    'campo_bus'=>$v['conf_sql']['campo_bus'],
                                    'select'=>$v['conf_sql']['select'],
                                    'valor'=>$config['data_selector']['list'][$key],
                                ]);
                            }
                        }
                        //dd($config);
                    }
                }
            }
            return view('qlib.campos_form',['config'=>$config]);
        }else{
            return false;
        }
    }
    static function qShow($config=false){
        if(isset($config['type'])){
            $config['campo'] = isset($config['campo'])?$config['campo']:'teste';
            $config['label'] = isset($config['label'])?$config['label']:false;
            $config['placeholder'] = isset($config['placeholder'])?$config['placeholder']:false;
            $config['selected'] = isset($config['selected']) ? $config['selected']:false;
            $config['tam'] = isset($config['tam']) ? $config['tam']:'12';
            $config['col'] = isset($config['col']) ? $config['col']:'md';
            $config['event'] = isset($config['event']) ? $config['event']:false;
            $config['ac'] = isset($config['ac']) ? $config['ac']:'cad';
            $config['option_select'] = isset($config['option_select']) ? $config['option_select']:true;
            $config['label_option_select'] = isset($config['label_option_select']) ? $config['label_option_select']:'Selecione';
            $config['option_gerente'] = isset($config['option_gerente']) ? $config['option_gerente']:false;
            $config['class'] = isset($config['class']) ? $config['class'] : false;
            $config['style'] = isset($config['style']) ? $config['style'] : false;
            $config['class_div'] = isset($config['class_div']) ? $config['class_div'] : false;
            if(@$config['type']=='chave_checkbox' && @$config['ac']=='cad'){
                if(@$config['checked'] == null && isset($config['valor_padrao']))
                $config['checked'] = $config['valor_padrao'];
            }
            if(@$config['type']=='html_vinculo' && @$config['ac']=='alt'){
                $tab = $config['data_selector']['tab'];
                $config['data_selector']['placeholder'] = isset($config['data_selector']['placeholder'])?$config['data_selector']['placeholder']:'Digite para iniciar a consulta...';
                $dsel = $config['data_selector'];
                $id = $config['value'];
                if(@$dsel['tipo']=='array'){
                    if(is_array($id)){
                        foreach ($id as $ki => $vi) {
                            $config['data_selector']['list'][$ki] = Qlib::dados_tab($tab,['id'=>$vi]);
                            if($config['data_selector']['list'][$ki] && isset($config['data_selector']['table']) && is_array($config['data_selector']['table'])){
                                foreach ($config['data_selector']['table'] as $key => $v) {
                                    if(isset($v['type']) && $v['type']=='arr_tab' && isset($config['data_selector']['list'][$ki][$key]) && isset($v['conf_sql'])){
                                        $value = $config['data_selector']['list'][$ki][$key];
                                        $config['data_selector']['list'][$ki][$key.'_valor'] = Qlib::buscaValorDb([
                                            'tab'=>$v['conf_sql']['tab'],
                                            'campo_bus'=>$v['conf_sql']['campo_bus'],
                                            'select'=>$v['conf_sql']['select'],
                                            'valor'=>$value,
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }else{
                    $config['data_selector']['list'] = Qlib::dados_tab($tab,['id'=>$id]);
                    if($config['data_selector']['list'] && isset($config['data_selector']['table']) && is_array($config['data_selector']['table'])){
                        foreach ($config['data_selector']['table'] as $key => $v) {
                            if(isset($v['type']) && $v['type']=='arr_tab' && isset($config['data_selector']['list'][$key]) && isset($v['conf_sql'])){
                                $config['data_selector']['list'][$key.'_valor'] = Qlib::buscaValorDb([
                                    'tab'=>$v['conf_sql']['tab'],
                                    'campo_bus'=>$v['conf_sql']['campo_bus'],
                                    'select'=>$v['conf_sql']['select'],
                                    'valor'=>$config['data_selector']['list'][$key],
                                ]);
                            }
                        }
                        //dd($config);
                    }
                }
            }
            return view('qlib.campos_show',['config'=>$config]);
        }else{
            return false;
        }
    }
    static function sql_array($sql, $ind, $ind_2, $ind_3 = '', $leg = '',$type=false){
        $table = DB::select($sql);
        $userinfo = array();
        if($table){
            //dd($table);
            for($i = 0;$i < count($table);$i++){
                $table[$i] = (array)$table[$i];
                if($ind_3 == ''){
                    $userinfo[$table[$i][$ind_2]] =  $table[$i][$ind];
                }elseif(is_array($ind_3) && isset($ind_3['tab'])){
                    /*É sinal que o valor vira de banco de dados*/
                    $sql = "SELECT ".$ind_3['campo_enc']." FROM `".$ind_3['tab']."` WHERE ".$ind_3['campo_bus']." = '".$table[$i][$ind_2]."'";
                    $userinfo[$table[$i][$ind_2]] = $sql;
                }else{
                    if($type && $type!='encode'){
                        // se tiver encode deve retornar os dados tambem codificados
                        if($type == 'data'){
                            /*Tipo de campo exibe*/
                            $userinfo[$table[$i][$ind_2]] = $table[$i][$ind] . '' . $leg . '' . Qlib::dataExibe($table[$i][$ind_3]);
                        }
                        if($type == 'dados'){
                            $userinfo[$table[$i][$ind_2]] = $table[$i][$ind] . '' . $leg . '' . $table[$i][$ind_3];
                        }
                    }else{
                        $userinfo[$table[$i][$ind_2]] = $table[$i][$ind] . '' . $leg . '' . $table[$i][$ind_3];
                    }
                }
                if(isset($userinfo[$table[$i][$ind_2]]) && $type=='encode')
                $userinfo[$table[$i][$ind_2]] .= '@#'.Qlib::encodeArray($table[$i]);
            }
        }

        return $userinfo;
    }
    static function sql_array2($sql, $ind, $ind_2, $ind_3 = '', $leg = '',$type=false){
        $table = DB::select($sql);
        $userinfo = array();
        if($table){
            //dd($table);
            for($i = 0;$i < count($table);$i++){
                $table[$i] = (array)$table[$i];
                //$k = $table[$i][$ind_2];
                $k = false;
                foreach ($table[$i] as $key => $value) {
                    $k .= $value.'|';
                }
                if($ind_3 == ''){
                    $userinfo[$k] =  $table[$i][$ind];
                }elseif(is_array($ind_3) && isset($ind_3['tab'])){
                    /*É sinal que o valor vira de banco de dados*/
                    $sql = "SELECT ".$ind_3['campo_enc']." FROM `".$ind_3['tab']."` WHERE ".$ind_3['campo_bus']." = '".$k."'";
                    $userinfo[$k] = $sql;
                }else{
                    if($type){
                        if($type == 'data'){
                            /*Tipo de campo exibe*/
                            $userinfo[$k] = $table[$i][$ind] . '' . $leg . '' . Qlib::dataExibe($table[$i][$ind_3]);
                        }
                    }else{
                        $userinfo[$k] = $table[$i][$ind] . '' . $leg . '' . $table[$i][$ind_3];
                    }
                }
            }
        }

        return $userinfo;
    }
    static function formatMensagem($config=false){
        if($config){
            $config['mens'] = isset($config['mens']) ? $config['mens'] : false;
            $config['color'] = isset($config['color']) ? $config['color'] : false;
            $config['time'] = isset($config['time']) ? $config['time'] : 4000;
            return view('qlib.format_mensagem', ['config'=>$config]);
        }else{
            return false;
        }
	}
    static function formatMensagemInfo($mess='',$cssMes='',$event=false){
		$mensagem = "<div class=\"alert alert-$cssMes alert-dismissable\" role=\"alert\"><button class=\"close\" type=\"button\" data-dismiss=\"alert\" $event aria-hidden=\"true\">×</button><i class=\"fa fa-info-circle\"></i>&nbsp;".__($mess)."</div>";
		return $mensagem;
	}
    static function gerUploadAquivos($config=false){
        if($config){
            $config['parte'] = isset($config['parte']) ? $config['parte'] : 'painel';
            $config['token_produto'] = isset($config['token_produto']) ? $config['token_produto'] : false;
            $config['listFiles'] = isset($config['listFiles']) ? $config['listFiles'] : false; // array com a lista
            $config['time'] = isset($config['time']) ? $config['time'] : 4000;
            $config['arquivos'] = isset($config['arquivos']) ? $config['arquivos'] : false;
            if($config['listFiles']){
                $tipo = false;
                foreach ($config['listFiles'] as $key => $value) {
                    if(isset($value['config'])){
                        $arr_conf = Qlib::lib_json_array($value['config']);
                        if(isset($arr_conf['extenssao']) && !empty($arr_conf['extenssao']))
                        {
                            if($arr_conf['extenssao'] == 'jpg' || $arr_conf['extenssao']=='png' || $arr_conf['extenssao'] == 'jpeg'){
                                $tipo = 'image';
                            }elseif($arr_conf['extenssao'] == 'doc' || $arr_conf['extenssao'] == 'docx') {
                                $tipo = 'word';
                            }elseif($arr_conf['extenssao'] == 'xls' || $arr_conf['extenssao'] == 'xlsx') {
                                $tipo = 'excel';
                            }else{
                                $tipo = 'download';
                            }
                        }
                        $config['listFiles'][$key]['tipo_icon'] = $tipo;
                    }
                }
            }
            if(isset($config['parte'])){
                $view = 'qlib.uploads.painel';
                return view($view, ['config'=>$config]);
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    static function formulario($config=false){
        if($config['campos']){
            $view = 'qlib.formulario';
            return view($view, ['conf'=>$config]);
        }else{
            return false;
        }
    }
    static function show($config=false){
        if($config['campos']){
            $view = 'qlib.show';
            return view($view, ['conf'=>$config]);
        }else{
            return false;
        }
    }
    static function listaTabela($config=false){
        if($config['campos_tabela']){
            $view = 'qlib.listaTabela';
            return view($view, ['conf'=>$config]);
        }else{
            return false;
        }
    }
    static function UrlAtual(){
        return URL::full();
    }
    static function ver_PermAdmin($perm=false,$url=false){
        $ret = false;
        if(!$url){
            $url = URL::current();
            $arr_url = explode('/',$url);
        }
        if($url && $perm){
            $arr_permissions = [];
            $logado = Auth::user();
            $id_permission = $logado->id_permission;
            $dPermission = Permission::findOrFail($id_permission);
            if($dPermission && $dPermission->active=='s'){
                $arr_permissions = Qlib::lib_json_array($dPermission->id_menu);
                if(isset($arr_permissions[$perm][$url])){
                    $ret = true;
                }
            }
        }
        return $ret;
    }
    static public function html_vinculo($config = null)
    {
        /**
        Qlib::html_vinculo([
            'campos'=>'',
            'type'=>'html_vinculo',
            'dados'=>'',
        ]);
         */

        $ret = false;
        $campos = isset($config['campos'])?$config['campos']:false;
        $type = isset($config['type'])?$config['type']:false;
        $dados = isset($config['dados'])?$config['dados']:false;
        if(!$campos)
            return $ret;
        if(is_array($campos) && $dados){
            foreach ($campos as $key => $value) {
                if($value['type']==$type){
                    $id = $dados[$key];
                    $tab = $value['data_selector']['tab'];
                    $d_tab = DB::table($tab)->find($id);
                    if($d_tab){
                        $ret[$key] = (array)$d_tab;
                    }
                }
            }
        }
        return $ret;
    }
    static public function dados_tab($tab = null,$config)
    {
        $ret = false;
        if($tab){
            $id = isset($config['id']) ? $config['id']:false;
            $sql = isset($config['sql']) ? $config['sql']:false;
            if($sql){
                $d = DB::select($sql);
                $arr_list = $d;
                $list = false;
                foreach ($arr_list as $k => $v) {
                    if(is_object($v)){
                        $list[$k] = (array)$v;
                        foreach ($list[$k] as $k1 => $v1) {
                            if(Qlib::isJson($v1)){
                                $list[$k][$k1] = Qlib::lib_json_array($v1);
                            }
                        }
                    }
                }
                $ret = $list;
                return $ret;
            }else{
                $obj_list = DB::table($tab)->find($id);
            }
            if($list=(array)$obj_list){
                //dd($obj_list);
                    if(is_array($list)){
                        foreach ($list as $k => $v) {
                            if(Qlib::isJson($v)){
                                $list[$k] = Qlib::lib_json_array($v);
                            }
                        }
                    }
                    $ret = $list;
            }
        }
        return $ret;
    }
    static public function buscaValorDb($config = false)
    {
        /*Qlib::buscaValorDd([
            'tab'=>'',
            'campo_bus'=>'',
            'valor'=>'',
            'select'=>'',
            'compleSql'=>'',
        ]);
        */
        $ret=false;
        $tab = isset($config['tab'])?$config['tab']:false;
        $campo_bus = isset($config['campo_bus'])?$config['campo_bus']:'id';//campo select
        $valor = isset($config['valor'])?$config['valor']:false;
        $select = isset($config['select'])?$config['select']:false; //
        $compleSql = isset($config['compleSql'])?$config['compleSql']:false; //
        if($tab && $campo_bus && $valor && $select){
            $sql = "SELECT $select FROM $tab WHERE $campo_bus='$valor' $compleSql";
            if(isset($config['debug'])&&$config['debug']){
                echo $sql;
            }
            $d = DB::select($sql);
            if($d)
                $ret = $d[0]->$select;
        }
        return $ret;
    }
    static public function valorTabDb($tab = false,$campo_bus,$valor,$select,$compleSql=false)
    {

        $ret=false;
        /*
        $tab = isset($config['tab'])?$config['tab']:false;
        $campo_bus = isset($config['campo_bus'])?$config['campo_bus']:'id';//campo select
        $valor = isset($config['valor'])?$config['valor']:false;
        $select = isset($config['select'])?$config['select']:false; //
        $compleSql = isset($config['compleSql'])?$config['compleSql']:false; //
        */
        if($tab && $campo_bus && $valor && $select){
            $sql = "SELECT $select FROM $tab WHERE $campo_bus='$valor' $compleSql";
            if(isset($config['debug'])&&$config['debug']){
                echo $sql;
            }
            $d = DB::select($sql);
            if($d)
                $ret = $d[0]->$select;
        }
        return $ret;
    }
    static function lib_valorPorExtenso($valor=0) {
		$singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
		$plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");

		$c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
		$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
		$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
		$u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");

		$z=0;

		$valor = @number_format($valor, 2, ".", ".");
		$inteiro = explode(".", $valor);
		for($i=0;$i<count($inteiro);$i++)
			for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
				$inteiro[$i] = "0".$inteiro[$i];

		// $fim identifica onde que deve se dar junção de centenas por "e" ou por "," 😉
		$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
		$rt=false;
		for ($i=0;$i<count($inteiro);$i++) {
			$valor = $inteiro[$i];
			$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
			$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
			$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
			$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru;
			$t = count($inteiro)-1-$i;
			$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
			if ($valor == "000")$z++; elseif ($z > 0) $z--;
			if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t];
			if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
		}
		return($rt ? $rt : "zero");
	}
	static function convert_number_to_words($number) {

		$hyphen      = '-';
		$conjunction = ' e ';
		$separator   = ', ';
		$negative    = 'menos ';
		$decimal     = ' ponto ';
		$dictionary  = array(
			0                   => 'zero',
			1                   => 'um',
			2                   => 'dois',
			3                   => 'três',
			4                   => 'quatro',
			5                   => 'cinco',
			6                   => 'seis',
			7                   => 'sete',
			8                   => 'oito',
			9                   => 'nove',
			10                  => 'dez',
			11                  => 'onze',
			12                  => 'doze',
			13                  => 'treze',
			14                  => 'quatorze',
			15                  => 'quinze',
			16                  => 'dezesseis',
			17                  => 'dezessete',
			18                  => 'dezoito',
			19                  => 'dezenove',
			20                  => 'vinte',
			30                  => 'trinta',
			40                  => 'quarenta',
			50                  => 'cinquenta',
			60                  => 'sessenta',
			70                  => 'setenta',
			80                  => 'oitenta',
			90                  => 'noventa',
			100                 => 'cento',
			200                 => 'duzentos',
			300                 => 'trezentos',
			400                 => 'quatrocentos',
			500                 => 'quinhentos',
			600                 => 'seiscentos',
			700                 => 'setecentos',
			800                 => 'oitocentos',
			900                 => 'novecentos',
			1000                => 'mil',
			1000000             => array('milhão', 'milhões'),
			1000000000          => array('bilhão', 'bilhões'),
			1000000000000       => array('trilhão', 'trilhões'),
			1000000000000000    => array('quatrilhão', 'quatrilhões'),
			1000000000000000000 => array('quinquilhão', 'quinquilhões')
		);

		if (!is_numeric($number)) {
			return false;
		}

		if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
			// overflow
			trigger_error(
				'convert_number_to_words só aceita números entre ' . PHP_INT_MAX . ' à ' . PHP_INT_MAX,
				E_USER_WARNING
			);
			return false;
		}

		if ($number < 0) {
			return $negative . Qlib::convert_number_to_words(abs($number));
		}

		$string = $fraction = null;

		if (strpos($number, '.') !== false) {
			list($number, $fraction) = explode('.', $number);
		}
        $number = (int)$number;
		switch (true) {
			case $number < 21:
				$string = $dictionary[$number];
				break;
			case $number < 100:
				$tens   = ((int) ($number / 10)) * 10;
				$units  = $number % 10;
				$string = $dictionary[$tens];
				if ($units) {
					$string .= $conjunction . $dictionary[$units];
				}
				break;
			case $number < 1000:
				$hundreds  = floor($number / 100)*100;
				$remainder = $number % 100;
				$string = $dictionary[$hundreds];
				if ($remainder) {
					$string .= $conjunction . Qlib::convert_number_to_words($remainder);
				}
				break;
			default:
				$baseUnit = pow(1000, floor(log($number, 1000)));
				$numBaseUnits = (int) ($number / $baseUnit);
				$remainder = $number % $baseUnit;
				if ($baseUnit == 1000) {
					$string = Qlib::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[1000];
				} elseif ($numBaseUnits == 1) {
					$string = Qlib::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit][0];
				} else {
					$string = Qlib::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit][1];
				}
				if ($remainder) {
					$string .= $remainder < 100 ? $conjunction : $separator;
					$string .= Qlib::convert_number_to_words($remainder);
				}
				break;
		}

		if (null !== $fraction && is_numeric($fraction)) {
			$string .= $decimal;
			$words = array();
			foreach (str_split((string) $fraction) as $number) {
				$words[] = $dictionary[$number];
			}
			$string .= implode(' ', $words);
		}

		return $string;
	}
    static function limpar_texto($str){
        return preg_replace("/[^0-9]/", "", $str);
    }
    static function compleDelete($var = null)
    {
        if($var){
            return "$var.excluido='n' AND $var.deletado='n'";
        }else{
            return "excluido='n' AND deletado='n'";
        }
    }
    static public function show_files(Array $config = null)
    {
        $ret = Qlib::formatMensagemInfo('Nenhum Arquivo','info');

        if($config['token']){
            $files = DB::table('_uploads')->where('token_produto',$config['token'])->get();
            if($files){
                if(isset($files[0]))
                    return view('qlib.show_file',['files'=>$files,'config'=>$config]);
            }
        }
        return $ret;
    }
    /**
     * Registra eventos no sistema
     * @return bool;
     */
    static function regEvent($config=false)
    {
        //return true;
        //$ev = new EventController;
        $user = Auth::user();
        $ret =false;
        if(isset($config['action']) && isset($config['action'])){
            $action = isset($config['action'])?$config['action']:false;
            $tab = isset($config['tab'])?$config['tab']:false;
            $conf = isset($config['config'])?$config['config']:[];
            $ret = Event::create([
                'token'=>uniqid(),
                'user_id'=>$user->id,
                'action'=>$action,
                'tab'=>$tab,
                'config'=>Qlib::lib_array_json($conf),
            ]);
        }
        return $ret;

        //$ret = $ev->store($config);
    }
    /***
     * Busca um tipo de routa padrão do sistema
     * Ex.: routa que será aberta ao logar
     *
     */
    static function redirectLogin($ambiente='back')
    {
        $ret = '/';
        if(!Auth::check()){
            return $ret;
        }
        $id_permission = auth()->user()->id_permission;
        $dPermission = Permission::FindOrFail($id_permission);
        $ret = isset($dPermission['redirect_login']) ? $dPermission['redirect_login']:'/';
        //REGISTRAR EVENTO DE LOGIN
        $regev = Qlib::regEvent(['action'=>'login','tab'=>'user','config'=>[
            'obs'=>'Usuáro logou no sistema',
            ]
        ]);
        return $ret;
    }
    static function tirarAcentos($string){
        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
    }
    static function sanitizeString($str) {
        $str = preg_replace('/[áàãâä]/ui', 'A', $str);
        $str = preg_replace('/[éèêë]/ui', 'E', $str);
        $str = preg_replace('/[íìîï]/ui', 'I', $str);
        $str = preg_replace('/[óòõôö]/ui', 'O', $str);
        $str = preg_replace('/[úùûü]/ui', 'U', $str);
        $str = preg_replace('/[Ç]/ui', 'C', $str);
        $str = preg_replace('/[ç]/ui', 'c', $str);

        // $str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '_', $str);
        //$str = preg_replace('/[^a-z0-9]/i', '_', $str);
        //$str = preg_replace('/_+/', '_', $str); // ideia do Bacco :)
        return $str;
    }
    /**
     * Verifica se o usuario logado tem permissao de admin ou alguma expessífica
     */
    static function isAdmin($perm_admin = 2)
    {
        $user = Auth::user();

        if($user->id_permission<=$perm_admin){
            return true;
        }else{
            return false;
        }
    }
    static function redirect($url,$time=10){
        echo '<meta http-equiv="refresh" content="'.$time.'; url='.$url.'">';
    }
    static function verificaCobranca(){
        //$f = new CobrancaController;
        $user = Auth::user();
        $f = new UserController($user);
        $ret = $f->exec();
        return $ret;
    }
}
