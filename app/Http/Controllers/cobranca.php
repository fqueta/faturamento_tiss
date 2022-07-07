<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Qlib\Qlib;
class cobranca extends Controller
{
    public $access_token;
	public $url;
	public $url_plataforma;
	public $tk_conta;
	public $seg1;
	function __construct(){
		$this->credenciais();
		$this->seg1 = request()->segment(1);
        $seg2 = request()->segment(2);

	}
	public function credenciais(){
		$this->access_token = 'NWM5OGMyZGRiOTAzMS41ZmQwZGQyNTUzZGI0LjQx';
		$this->url 		 	= 'https://api.ctloja.com.br/v1';
		$this->tk_conta	 	= '62c4423f4a61f';
		//$this->tk_conta	 	= '60b77bc73e7c0';
	}
    public function exec($token_conta = null)
    {
        $cont = false;
        //if($token_conta){
            $verifica_fatura = $this->verifica_faturas(array('token_conta'=>$token_conta));
            if(isset($_GET['teste'])){
                Qlib::lib_print($verifica_fatura);
            }
            if($verifica_fatura['acao']=='alertar'){
                if(Qlib::isAdmin()){
                    $cont = @$verifica_fatura['mens'];
                    //echo $cont;
                }
            }elseif($verifica_fatura['acao']=='suspender' || $verifica_fatura['acao']=='desativar'){
                //Não terá acesso ao admin somente ao boleto e as faturas e o site estará desativado tbem
                if(Qlib::isAdmin(3)){
                    $cont = @$verifica_fatura['mens'];
                }else{
                    $cont = Qlib::formatMensagemInfo('Sistema temporariamente suspenso entre em contato com o administrador','danger');
                }
                $pagSusped = 'suspenso';
                if($this->seg1!=$pagSusped){
                    Qlib::redirect('/'.$pagSusped,0);
                    die();
                }
                //echo $cont;

            }
        //}
        return $cont;
    }
	public function clientes($config=false){
		$ret = false;
		if($config){
			if(isset($config['campo_bus'])&&isset($config['valor'])&&!empty($config['campo_bus'])&&!empty($config['valor'])){
				$url = $this->url.'/clientes/'.$config['campo_bus'].'/'.$config['valor'];
			}else{
				$url = $this->url.'/clientes';
			}
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => $url,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'GET',
			  CURLOPT_HTTPHEADER => array(
				'Access-Token: '.$this->access_token
			  ),
			));
			$response = curl_exec($curl);
			curl_close($curl);
			$ret['response'] = json_decode($response,true);

		}
		return $ret;
	}
	public function verifica_faturas($config=false,$cache=true){
		$ret['exec'] = false;
		$ret['cache'] = false;
		//exemplo de uso
		/*
		$this = new apictloja;
		$ret = $this->verifica_faturas(array('token_conta'=>''));
		Qlib::lib_print($ret);
		*/
		$token = isset($config['token_conta'])?$config['token_conta']:$this->tk_conta;

		if($token){
            $ver_sess = session('verifica_faturas');
            //Qlib::lib_print($ver_sess);

			if(isset($ver_sess['exec'])&&$ver_sess['exec'] && $cache){
				$arr_response = $ver_sess;
				$ret['cache'] = true;
			}else{

				$curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => $this->url.'/verifica_faturas/'.$token,
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'GET',
				  CURLOPT_HTTPHEADER => array(
					'Access-Token: '.$this->access_token
				  ),
				));
				$response = curl_exec($curl);
				curl_close($curl);
				$arr_response = json_decode($response,true);
				//$ret['arr_response'] = $arr_response;
			}
			if(isset($arr_response['exec'])){
				$ret['exec'] = $arr_response['exec'];
				$ret['acao'] = $arr_response['acao'];
                session(['verifica_faturas'=>$arr_response]);
				//$ver_sess=$arr_response;
			}
			if(isset($arr_response['mens'])){
				$ret['mens'] = $arr_response['mens'];
			}
			$ret['token'] = $token;
		}else{
			$ret['mens'] = Qlib::formatMensagemInfo('Configuração ou token inválido','danger');
		}
        return $ret;
	}
	public function minhasFaturas($config=false){
		$ret['exec'] = false;
		$ret['cache'] = false;
		//exemplo de uso
		/*
		$this = new apictloja;
		$ret = $this->minhasFaturas(array('token_conta'=>''));
		Qlib::lib_print($ret);
		*/
		$token = isset($config['token_conta'])?$config['token_conta']:$this->tk_conta;
		if($token){
			$curl = curl_init();

			curl_setopt_array($curl, array(
				  CURLOPT_URL => $this->url.'/lcf_entradas?filter%5Bref_compra%5D='.$token,
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => '',
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 0,
				  CURLOPT_FOLLOWLOCATION => true,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => 'GET',
				  CURLOPT_HTTPHEADER => array(
					'Access-Token: '.$this->access_token,
					),
			));
			$response = curl_exec($curl);
			curl_close($curl);
			$ret['response'] = Qlib::lib_json_array($response);

			if(isset($arr_response['mens'])){
				$ret['mens'] = $arr_response['mens'];
			}
			$ret['token'] = $token;
		}else{
			$ret['mens'] = Qlib::formatMensagem('Configuração ou token inválido','danger');
		}
		return $ret;
	}
	public function pararAlertaFaturaVencida(Request $request){
        $request->session()->put('verifica_faturas.acao','liberar');
        $ret['exec']=true;
		return $ret;
	}
	// public function geraDadosEmpresa($config=false){
	// 	$ret=false;
	// 	if($this->tk_conta){
	// 		if(isset($_SESSION[$this->tk_conta]['dadosConta'.$this->tk_conta])){
	// 			$dadosConta[0] = $_SESSION[$this->tk_conta]['dadosConta'.$this->tk_conta];
	// 		}else{
	// 			$dadosConta = dados_tab_SERVER($GLOBALS['contas_usuarios'],'*',"WHERE token='".$this->tk_conta."'");
	// 		}
	// 		if(isset($dadosConta[0])&&is_array($dadosConta[0])){
	// 			$arr = array(
	// 				'nome'		=>'Nome',
	// 				'sobrenome'	=>'sobrenome',
	// 				'token'		=>'token',
	// 				'ativo'		=>'ativo',
	// 				'pessoa'	=>'EscolhaDoc',
	// 				'cep'		=>'Cep',
	// 				'cnpj'		=>'cnpj',
	// 				'razao'		=>'Fantasia',
	// 				'telefone'	=>'Tel',
	// 				'celular'	=>'Celular',
	// 				'endereco'	=>'Endereco',
	// 				'numero'	=>'Numero',
	// 				'complemento'=>'Compl',
	// 				'bairro'	=>'Bairro',
	// 				'cidade'	=>'Cidade',
	// 				'uf'		=>'Uf',
	// 				'uf'		=>'Uf',
	// 			);
	// 			foreach($arr As $k=>$vl){
	// 				if($k=='pessoa'){
	// 					if($dadosConta[0][$k]=='juridica'){
	// 						$ret[$vl] = 'CGC';
	// 					}else{
	// 						$ret[$vl] = 'CPF';
	// 					}
	// 				}else{
	// 					$ret[$vl] = $dadosConta[0][$k];
	// 				}
	// 			}
	// 			$ret['campo_bus'] = 'token';
	// 		}
	// 	}
	// 	return $ret;
	// }

    // public function cad_clientes($config=false){
	// 	$ret = false;
	// 	if($config){
	// 		$curl = curl_init();
	// 		$url = $this->url.'/clientes';
	// 		/*
	// 		$json = '{
	// 		  "id": "1",
	// 		  "token": "5fd3e6d3323ui",
	// 		  "ativo": "s",
	// 		  "cnpj": "06.323.579/0001-00",
	// 		  "Cpf": "",
	// 		  "Ident": "",
	// 		  "Endereco": "Rua Padre Serafim",
	// 		  "Numero": "243",
	// 		  "Compl": "Apt 802",
	// 		  "Bairro": "Centro",
	// 		  "Cidade": "Juiz de fora",
	// 		  "Uf": "MG",
	// 		  "Cep": "36.570-093",
	// 		  "Tel": "",
	// 		  "Celular": "(32)99164-8202",
	// 		  "Email": "teste.api@ctloja.com.br",
	// 		  "Nome": "Cliente ",
	// 		  "sobrenome": "teste da API",
	// 		  "DtNasc2": "26/02/1981",
	// 		  "Tel2": null,
	// 		  "Contato2": null,
	// 		  "CodCurso": null,
	// 		  "genero": null,
	// 		  "pais": null,
	// 		  "nacionalidade": null,
	// 		  "orgao_emissor": null,
	// 		  "Fantasia": "Cliente de teste da API LTDA",
	// 		  "ip": null,
	// 		  "Indicar": null,
	// 		  "Indicado": null,
	// 		  "TelIndicado": null,
	// 		  "TempCont": null,
	// 		  "CodTipo": null,
	// 		  "CodOperador": null,
	// 		  "DtAgenda": null,
	// 		  "Obs": "",
	// 		  "EmissContrato": null,
	// 		  "EmissContratoH": null,
	// 		  "HorariosCurso": null,
	// 		  "ValorCurso": null,
	// 		  "Matricula": null,
	// 		  "grupos": "[\\"1\\",\\"2\\",\\"7\\"]",
	// 		  "Escolaridade": null,
	// 		  "NumCarta": null,
	// 		  "Numdecursos": null,
	// 		  "NumCobranca": null,
	// 		  "NumContatos": null,
	// 		  "Pai": null,
	// 		  "Mae": null,
	// 		  "Naturalidade": null,
	// 		  "BairroP": null,
	// 		  "UltimoContato": null,
	// 		  "OperadorTel": null,
	// 		  "DataRetorno": null,
	// 		  "Indicacao": null,
	// 		  "cpfaluno": null,
	// 		  "SPC": "N",
	// 		  "DtUltimaCobranca": null,
	// 		  "UltStatusTele": "0",
	// 		  "senha": "123456",
	// 		  "HoraUltStatusTele": "00:00:00",
	// 		  "id_asaas": "",
	// 		  "Dt_Email": null,
	// 		  "HoraRetorno": null,
	// 		  "emissaoCertif": null,
	// 		  "memoria": null,
	// 		  "config": "{\\"inscricao\\":\\"\\",\\"inscricaoMunicipal\\":\\"\\",\\"codigoCidade\\":\\"3171303\\"}",
	// 		  "Origem": null,
	// 		  "Operador_Incluiu": null,
	// 		  "OperadorEmissaoContrato": null,
	// 		  "Operador_Matriculou": null,
	// 		  "OperadorEmissaoCertif": null,
	// 		  "DataiInformatica": null,
	// 		  "DatafInformatica": null,
	// 		  "HoraInformatica": null,
	// 		  "DiasInformatica": null,
	// 		  "permissao": null,
	// 		  "verificado": "n",
	// 		  "usuario": "",
	// 		  "sessao": "",
	// 		  "logado": "n",
	// 		  "huggy_id": "0",
	// 		  "huggy_plataforma_criacao": null,
	// 		  "EscolhaDoc": "CGC",
	// 		  "excluido": "n",
	// 		  "reg_excluido": "",
	// 		  "deletado": "n",
	// 		  "reg_deletado": "",
	// 		  "campo_bus": "token"
	// 		}';*/
	// 		$arr = $this->geraDadosEmpresa($config);
	// 		if(is_array($arr)){
	// 			$json = json_encode($arr);
	// 			curl_setopt_array($curl, array(
	// 			  CURLOPT_URL => $url,
	// 			  CURLOPT_RETURNTRANSFER => true,
	// 			  CURLOPT_ENCODING => '',
	// 			  CURLOPT_MAXREDIRS => 10,
	// 			  CURLOPT_TIMEOUT => 0,
	// 			  CURLOPT_FOLLOWLOCATION => true,
	// 			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	// 			  CURLOPT_CUSTOMREQUEST => 'POST',
	// 			  CURLOPT_POSTFIELDS =>$json,
	// 			  CURLOPT_HTTPHEADER => array(
	// 				'Access-Token: '.$this->access_token
	// 			  ),
	// 			));
	// 			$response = curl_exec($curl);
	// 			curl_close($curl);
	// 			$ret['response'] = json_decode($response,true);
	// 		}else{
	// 			$ret['response']=false;
	// 		}

	// 	}
	// 	return $ret;
	// }
    public function suspenso()
    {
        return view('admin.suspenso');
    }
}
