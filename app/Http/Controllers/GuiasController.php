<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use App\Models\Guia;
use App\Qlib\Qlib;
use App\Models\User;
use App\Models\_upload;
use Illuminate\Support\Facades\Auth;


class GuiasController extends Controller
{
    protected $user;
    public $routa;
    public $label;
    public $view;
    public $url;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $seg1 = request()->segment(1);
        $seg2 = request()->segment(2);
        $type = false;
        if($seg1){
            $type = substr($seg1,0,-1);
        }

        $this->user     = $user;
        $this->routa    = $seg2;
        $this->label    = 'Guia';
        $this->view     = 'guias';
        $this->url      = $seg2;
    }
    public function queryGuia($get=false,$config=false)
    {
        $ret = false;
        $get = isset($_GET) ? $_GET:[];
        $ano = date('Y');
        $mes = date('m');
        //$todasFamilias = Familia::where('excluido','=','n')->where('deletado','=','n');
        $config = [
            'limit'=>isset($get['limit']) ? $get['limit']: 50,
            'order'=>isset($get['order']) ? $get['order']: 'desc',
        ];

        $guia =  Guia::where('excluido','=','n')->where('type','=',$this->url)->where('deletado','=','n')->orderBy('id',$config['order']);
        //$guia =  DB::table('guias')->where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);

        $guia_totais = new stdClass;
        $campos = isset($_SESSION['campos_guias_exibe']) ? $_SESSION['campos_guias_exibe'] : $this->campos();
        $tituloTabela = 'Lista de todos cadastros';
        $arr_titulo = false;
        if(isset($get['filter'])){
                $titulo_tab = false;
                $i = 0;
                foreach ($get['filter'] as $key => $value) {
                    if(!empty($value)){
                        if($key=='id'){
                            $guia->where($key,'LIKE', $value);
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                            $arr_titulo[$campos[$key]['label']] = $value;
                        }else{
                            $guia->where($key,'LIKE','%'. $value. '%');
                            if($campos[$key]['type']=='select'){
                                $value = $campos[$key]['arr_opc'][$value];
                            }
                            $arr_titulo[$campos[$key]['label']] = $value;
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                        }
                        $i++;
                    }
                }
                if($titulo_tab){
                    $tituloTabela = 'Lista de: &'.$titulo_tab;
                                //$arr_titulo = explode('&',$tituloTabela);
                }
                $fm = $guia;
                if($config['limit']=='todos'){
                    $guia = $guia->get();
                }else{
                    $guia = $guia->paginate($config['limit']);
                }
        }else{
            $fm = $guia;
            if($config['limit']=='todos'){
                $guia = $guia->get();
            }else{
                $guia = $guia->paginate($config['limit']);
            }
        }
        $guia_totais->todos = $fm->count();
        $guia_totais->esteMes = $fm->whereYear('created_at', '=', $ano)->whereMonth('created_at','=',$mes)->get()->count();
        $guia_totais->ativos = $fm->where('ativo','=','s')->get()->count();
        $guia_totais->inativos = $fm->where('ativo','=','n')->get()->count();

        $ret['guia'] = $guia;
        $ret['guia_totais'] = $guia_totais;
        $ret['arr_titulo'] = $arr_titulo;
        $ret['campos'] = $campos;
        $ret['config'] = $config;
        $ret['tituloTabela'] = $tituloTabela;
        $ret['config']['resumo'] = [
            'todos_registro'=>['label'=>'Todos cadastros','value'=>$guia_totais->todos,'icon'=>'fas fa-calendar'],
            'todos_mes'=>['label'=>'Cadastros recentes','value'=>$guia_totais->esteMes,'icon'=>'fas fa-calendar-times'],
            'todos_ativos'=>['label'=>'Cadastros ativos','value'=>$guia_totais->ativos,'icon'=>'fas fa-check'],
            'todos_inativos'=>['label'=>'Cadastros inativos','value'=>$guia_totais->inativos,'icon'=>'fas fa-archive'],
        ];
        return $ret;
    }
    public function campos($sec = false){
        $camposOper = new PadraoController($this->user);
        if($this->url=='internacao'){
            $qtde_procedimento=0;
            $arr_PainelProssedimentos = [
                'procedimento[data]'        =>['label'=>'34-Data','active'=>false,'placeholder'=>'','type'=>'date','id'=>'procedimento_alimentador_data','exibe_busca'=>'d-block','event'=>'onfocus=this.style.backgroundColor=\'#ffffff\'','tam'=>'2','cp_busca'=>'','title'=>''],
                'procedimento[horaInicio]'  =>['label'=>'35-Hora Início','id'=>'procedimento_alimentador_hora1','active'=>false,'placeholder'=>'Preenchimento condicionado. Deve ser preenchido quando o procedimento ocorrer em situações de urgência e emergência.','type'=>'time','exibe_busca'=>'d-block','event'=>'onfocus=this.style.backgroundColor=\'#ffffff\'','tam'=>'2','cp_busca'=>'','title'=>''],
                'procedimento[horaFim]'     =>['label'=>'36-Hora Fim','id'=>'procedimento_alimentador_hora2','active'=>false,'placeholder'=>'','type'=>'time','exibe_busca'=>'d-block','event'=>'onfocus=this.style.backgroundColor=\'#ffffff\'','tam'=>'2','cp_busca'=>'','title'=>'Preenchimento condicionado. Deve ser preenchido quando o procedimento ocorrer em situações de urgência e emergência.'],
                'procedimento[tabela_cobranca]'=>[
                    'label'=>'37-Tabela',
                    'active'=>false,
                    'id'=>'procedimento_alimentador_tabela',
                    'type'=>'select',
                    'arr_opc'=>Qlib::sql_array("SELECT value,nome FROM tags WHERE ativo='s' AND pai='4'",'nome','value'),
                    'exibe_busca'=>'d-block',
                    'event'=>'onfocus=this.style.backgroundColor=\'#ffffff\'',
                    'tam'=>'6',
                    'class'=>'',
                    'title'=>'Código da tabela utilizada para identificar os procedimentos realizados ou itens assistenciais utilizados, conforme tabela de domínio nº 87.',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][tabela_cobranca',
                ],
                'procedimento[codigo]'  =>['label'=>'Código do procedimento','id'=>'procedimento_alimentador_codigo','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'onfocus=this.style.backgroundColor=\'#ffffff\'','tam'=>'3','cp_busca'=>'','title'=>''],
                'procedimento[descricao]'  =>['label'=>'Descrição','id'=>'procedimento_alimentador_descricao','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'onkeyup=upcase(event,this) onfocus=this.style.backgroundColor=\'#ffffff\'','tam'=>'9','cp_busca'=>'','title'=>''],
                'procedimento[qde]'  =>['label'=>'40-Quantidade','id'=>'procedimento_alimentador_quantidade','active'=>false,'placeholder'=>'','type'=>'number','exibe_busca'=>'d-block','event'=>'onfocus=this.style.backgroundColor=\'#ffffff\' onchange=procedimento_alimentador_total()','tam'=>'2','cp_busca'=>'','title'=>'Código identificador do procedimento realizado pelo prestador, conforme tabela de domínio.'],
                'procedimento[viaAcesso]'=>[
                    'label'=>'41-Via',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>[
                        "1"=>"1 - Única","2"=>"2 - Mesma Via","3"=>"3 - Diferentes vias",
                    ],
                    'exibe_busca'=>'d-block',
                    'event'=>'',
                    'tam'=>'2',
                    'class'=>'',
                    'id'=>'procedimento_alimentador_via',
                    'title'=>'Condicionado. Deve ser preenchido em caso de procedimento cirúrgico.',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                ],
                'procedimento[tecnicaUtilizada]'=>[
                    'label'=>'42-Téc',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>[
                        "1"=>"1 - Convencional","2"=>"2 - Videolaparoscopia","3"=>"3 - Robótica",
                    ],
                    'exibe_busca'=>'d-block',
                    'event'=>'',
                    'tam'=>'2',
                    'class'=>'',
                    'id'=>'procedimento_alimentador_tec',
                    'title'=>'Código da técnica utilizada para realização do procedimento, conforme tabela de domínio nº 48.',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                ],
                'procedimento[reducaoAcrescimo]'  =>['label'=>'43-Fator Red/Acrésc','id'=>'procedimento_alimentador_fator','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'onkeypress=return(currencyFormat(this,\'\',\'.\',4,event)) onkeyup=procedimento_alimentador_total()','tam'=>'2','cp_busca'=>'','title'=>'Obrigatório. Quando não houver redução ou acréscimo sobre o valor do procedimento, o campo deve ser preenchido com 1,00.'],
                'procedimento[valorUnitario]'  =>['label'=>'44-Valor Unitário','id'=>'procedimento_alimentador_valor_unitario','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'onfocus=this.style.backgroundColor=\'#ffffff\' onkeyup=procedimento_alimentador_total()','tam'=>'2','class'=>'moeda','cp_busca'=>'','title'=>'Obrigatório. Quando não houver redução ou acréscimo sobre o valor do procedimento, o campo deve ser preenchido com 1,00.'],
                'procedimento[valorTotal]'  =>['label'=>'45-Valor Total','id'=>'procedimento_alimentador_valor_total','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','class'=>'','event'=>' onkeyup=procedimento_alimentador_total()','tam'=>'2','cp_busca'=>'','title'=>'Valor total do procedimento realizado, considerando a quantidade de procedimentos realizados, o valor unitário e o fator de redução ou acréscimo'],

            ];
            $arrSeq = range(1,100);
            $arr_Painelexecutantes = [
                'executante[DeqRef]'=>[
                    'label'=>'46-Seq. Ref.',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>$arrSeq,
                    'exibe_busca'=>'d-block',
                    'event'=>'',
                    'tam'=>'2',
                    'class'=>'',
                    'title'=>'Condicionado. Deve ser preenchido na contingência em papel com o número de referência do procedimento (número da linha) a que se refere a participação do profissional integrante da equipe.',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                ],
                'executante[GrauPart]'=>[
                    'label'=>'47-Gru. Part.',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>[
                        "00"=>"00 - Cirurgião","01"=>"01 - Primeiro Auxiliar","02"=>"02 - Segundo Auxiliar","03"=>"03 - Terceiro Auxiliar","04"=>"04 - Quarto Auxiliar","05"=>"05 - Instrumentador","06"=>"06 - Anestesista","07"=>"07 - Auxiliar de Anestesista","08"=>"08 - Consultor","09"=>"09 - Perfusionista","10"=>"10 - Pediatra na sala de parto","11"=>"11 - Auxiliar SADT","12"=>"12 - Clínico","13"=>"13 - Intensivista",

                    ],
                    'exibe_busca'=>'d-block',
                    'event'=>'',
                    'tam'=>'2',
                    'class'=>'',
                    'title'=>'Condicionado. Deve ser  preenchido sempre que houver honorários profissionais relativos aos procedimentos realizados e tratar-se de procedimento realizado por equipe.',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                ],
                'executante[idProfissional]'=>[
                    'label'=>'49-Nome Profissional',
                    'active'=>false,
                    'type'=>'select',
                    'data_selector'=>[
                        'campos'=>$camposOper->campos('operadoras'),
                        'route_index'=>route('operadoras.index'),
                        'id_form'=>'frm-operadoras',
                        'action'=>route('operadoras.store'),
                        'campo_id'=>'id',
                        'campo_bus'=>'nome',
                        'label'=>'Operadora',
                    ],
                    'arr_opc'=>Qlib::sql_array("SELECT id,nome FROM operadoras WHERE ativo='s'",'nome','id'),'exibe_busca'=>'d-block',
                    'event'=>'',
                    'tam'=>'5',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'title'=>'Nome do profissional participante da equipe de execução do procedimento.',
                    'cp_busca'=>'config][idProfissional',
                ],
                'executante[codigoCpfProfissional]'  =>['label'=>'47-Cód. na Operadora / CPF','id'=>'codigo_cpf_profissional','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','class'=>'mask-cpf','event'=>' ','tam'=>'3','cp_busca'=>'','title'=>'Código na Operadora ou CPF do profissional participante da equipe de execução do procedimento.'],
                'executante[conselho]'=>[
                    'label'=>'Conselho',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>Qlib::sql_array("SELECT value,nome FROM tags WHERE ativo='s' AND pai='3' ORDER BY id ASC",'nome','value'),'exibe_busca'=>'d-block',
                    'event'=>'',
                    'tam'=>'4',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][conselho',
                ],
                'executor[numero_conselho]'=>['label'=>'N° no Conselho','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'disabled','tam'=>'4'],
                'executor[uf_conselho]'=>['label'=>'UF','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'disabled','tam'=>'1'],
                'executor[cbo]'=>['label'=>'Cód. CBO','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'disabled','tam'=>'3'],


            ];
            return [
                'id'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'type'=>['label'=>'type','active'=>false,'type'=>'hidden','value'=>$this->url,'exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'config[registro_ans]'=>[
                    'label'=>'1-Registro ANS',
                    'active'=>false,
                    'type'=>'selector',
                    'data_selector'=>[
                        'campos'=>$camposOper->campos('operadoras'),
                        'route_index'=>route('operadoras.index'),
                        'id_form'=>'frm-operadoras',
                        'action'=>route('operadoras.store'),
                        'campo_id'=>'id',
                        'campo_bus'=>'nome',
                        'label'=>'Operadora',
                    ],
                    'arr_opc'=>Qlib::sql_array("SELECT id,nome FROM operadoras WHERE ativo='s'",'nome','id'),'exibe_busca'=>'d-block',
                    'event'=>'required',
                    'tam'=>'4',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][registro_ans',
                ],
                'numero_guia'=>['label'=>'2-Nº Guia no Prestador','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'required maxlength=20','tam'=>'4',],
                'config[nome_guia]'=>['label'=>'3-Nº da Guia de Solicitação de Internação','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'required maxlength=20','tam'=>'4','cp_busca'=>'config][nome_guia',],
                'config[data_autorizacao]'=>['label'=>'4-Data da Autorização','active'=>false,'placeholder'=>'','type'=>'date','exibe_busca'=>'d-block','event'=>'required','tam'=>'3','cp_busca'=>'config][data_autorizacao','title'=>'Data em que a autorização para realização do atendimento/procedimento foi concedida pela operadora.'],
                'config[senha]'=>['label'=>'5-Senha','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'required maxlength=20','tam'=>'2','cp_busca'=>'config][senha',],
                'config[dataValidadeSenha]'=>['label'=>'6-Data de Validade da Senha','active'=>false,'placeholder'=>'','type'=>'date','exibe_busca'=>'d-block','event'=>'required','tam'=>'3','cp_busca'=>'config][dataValidadeSenha','title'=>'Deve ser preenchido em
                caso de autorização pela operadora com emissão de senha com prazo de validade'],
                'config[numeroGuiaOperadora]'=>['label'=>'7-Nº da Guia Atribuído pela Operadora','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'required maxlength=20','tam'=>'4','cp_busca'=>'config][numeroGuiaOperadora',],
                'sep1'=>['label'=>'Dados do Beneficiário','active'=>false,'tam'=>'12','script'=>'<h5>Dados do Beneficiário</h5>','type'=>'html_script','class_div'=>'bg-secondary'],
                'config[numeroCarteira]'=>['label'=>'8-Número da Carteira','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'required maxlength=20','tam'=>'3','cp_busca'=>'config][numeroCarteira',],
                'config[validadeCarteira]'=>['label'=>'9-Validade da Carteira','active'=>false,'placeholder'=>'','type'=>'date','exibe_busca'=>'d-block','event'=>'','tam'=>'2','cp_busca'=>'config][validadeCarteira','title'=>'Preenchimento Condicionado. Deve ser preenchido somente na utilização da contingência em papel quando a operadora exigir autorização prévia para procedimentos ambulatoriais e tal autorização não puder ser obtida.'],
                'nome'=>['label'=>'10-Nome','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'required maxlength=70','tam'=>'5'],
                //'config[cartaoSaude]'=>['label'=>'11-Cartão Nacional de Saúde','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'2','cp_busca'=>'config][validadeCarteira','title'=>'Preenchimento Condicionado. Deve ser preenchido somente na utilização da contingência em papel quando a operadora exigir autorização prévia para procedimentos ambulatoriais e tal autorização não puder ser obtida.'],
                'config[atendimentoRN]'=>[
                    'label'=>'12-Atendimento a RN',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>['S'=>'Sim','N'=>'Não'],'exibe_busca'=>'d-block',
                    'event'=>'required',
                    'tam'=>'2',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][atendimentoRN',
                    'title'=>'Deve ser informado "S" - sim - caso o atendimento seja do recém-nato e o beneficiário seja o responsável e "N" - não - quando o atendimento for do próprio beneficiário.',
                ],
                'sep2'=>['label'=>'Dados do Contratado Executante','active'=>false,'tam'=>'12','script'=>'<h5>Dados do Contratado Executante</h5>','type'=>'html_script','class_div'=>'bg-secondary'],
                'config[codigoNaOperadora]'=>['label'=>'13-Código na Operadora','active'=>false,'placeholder'=>'','type'=>'tel','exibe_busca'=>'d-block','event'=>'required maxlength=14','tam'=>'3','cp_busca'=>'config][codigoNaOperadora','title'=>'Código na operadora ou CNPJ do prestador contratado que executou o procedimento.'],
                'config[nomeContratado]'=>['label'=>'14-Nome do Contratado','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'required maxlength=7','tam'=>'7','cp_busca'=>'config][nomeContratado','title'=>''],
                'config[codigoCNES]'=>['label'=>'15-Código CNES','active'=>false,'placeholder'=>'','type'=>'tel','exibe_busca'=>'d-block','event'=>'required maxlength=7','tam'=>'2','cp_busca'=>'config][codigoCNES','title'=>'Caso o prestador ainda não possua o código do CNES preencher o campo com 9999999.'],
                'sep3'=>['label'=>'Dados da Internação','active'=>false,'tam'=>'12','script'=>'<h5>Dados da Internação</h5>','type'=>'html_script','class_div'=>'bg-secondary'],
                'config[caraterAtendimento]'=>[
                    'label'=>'16-Caráter do Atendimento',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>["1"=>"1 - Eletiva","2"=>"2 - Urgência/Emergência"],
                    'exibe_busca'=>'d-block',
                    'event'=>'required',
                    'tam'=>'3',
                    'title'=>'Código do caráter do atendimento, conforme tabela de domínio nº 23.',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][caraterAtendimento',
                ],
                'config[tipoFaturamento]'=>[
                    'label'=>'17-Tipo de Faturamento',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>["1"=>"1 - Parcial","2"=>"2 - Final","3"=>"3 - Complementar","4"=>"4 - Total",],
                    'exibe_busca'=>'d-block',
                    'event'=>'required',
                    'tam'=>'3',
                    'title'=>'Código do tipo do faturamento apresentado nesta guia, conforme tabela de domínio nº 55.',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][tipoFaturamento',
                ],
                'config[dataInicioFaturamento]'=>['label'=>'18-Data Início Faturamento','active'=>false,'placeholder'=>'','type'=>'date','exibe_busca'=>'d-block','event'=>'required','tam'=>'3','cp_busca'=>'config][dataInicioFaturamento','title'=>'Quando o tipo de faturamento for igual a 3-Complementar, preencher o campo com a data do início do faturamento da guia que está sendo complementada.'],
                'config[horaInicioFaturamento]'=>['label'=>'19-Hora Início Faturamento','active'=>false,'placeholder'=>'','type'=>'time','exibe_busca'=>'d-block','event'=>'required ','tam'=>'3','cp_busca'=>'config][horaInicioFaturamento','title'=>'Quando o tipo de faturamento for igual a 3-Complementar, preencher o campo com a hora do início do faturamento da guia que está sendo complementada.'],
                'config[dataFinalFaturamento]'=>['label'=>'20-Data Fim Faturamento','active'=>false,'placeholder'=>'','type'=>'date','exibe_busca'=>'d-block','event'=>'required','tam'=>'3','cp_busca'=>'config][dataFinalFaturamento','title'=>'Quando o tipo de faturamento for igual a 3-Complementar, preencher o campo com a data do fim do faturamento da guia que está sendo complementada.'],
                'config[horaFinalFaturamento]'=>['label'=>'21-Hora Fim Faturamento','active'=>false,'placeholder'=>'','type'=>'time','exibe_busca'=>'d-block','event'=>'required ','tam'=>'3','cp_busca'=>'config][horaFinalFaturamento','title'=>'Quando o tipo de faturamento for igual a 3-Complementar, preencher o campo com a hora do fim do faturamento da guia que está sendo complementada.'],
                'config[tipoInternacao]'=>[
                    'label'=>'22-Tipo de Internação',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>[
                        "1"=>"Clínica","2"=>"Cirúrgica","3"=>"Obstétrica","4"=>"Pediátrica","5"=>"Psiquiátrica",
                    ],
                    'exibe_busca'=>'d-block',
                    'event'=>'required',
                    'tam'=>'3',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'title'=>'Código do tipo de internação, conforme tabela de domínio nº 57',
                    'cp_busca'=>'config][tipoInternacao',
                ],
                'config[regimeInternacao]'=>[
                    'label'=>'23-Regime de Internação',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>[
                        "1"=>"Hospitalar","2"=>"Hospital-Dia","3"=>"Domiciliar",
                    ],
                    'exibe_busca'=>'d-block',
                    'event'=>'required',
                    'tam'=>'3',
                    'class'=>'',
                    'title'=>'Regime da internação de acordo com tabela de domínio nº 41.',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][regimeInternacao',
                ],
                'config[CID10Principal]'=>['label'=>'24-CID10 Principal (opcional)','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'maxlength=4','tam'=>'3','cp_busca'=>'config][CID10Principal','title'=>'Código do diagnóstico principal de acordo com a Classificação Internacional de Doenças e de Problemas Relacionados a Saúde - 10ª revisão'],
                'config[CID10_2]'=>['label'=>'25-CID10 (2)','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'maxlength=4','tam'=>'2','cp_busca'=>'config][CID10_2','title'=>'Código do diagnóstico secundário de acordo com a Classificação Internacional de Doenças e de Problemas Relacionados a Saúde - 10ª revisão'],
                'config[CID10_3]'=>['label'=>'26-CID10 (3)','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'maxlength=4','tam'=>'2','cp_busca'=>'config][CID10_3','title'=>'Código do terceiro diagnóstico de acordo com a Classificação Internacional de Doenças e de Problemas Relacionados a Saúde - 10ª revisão'],
                'config[CID10_4]'=>['label'=>'27-CID10 (4)','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'maxlength=4','tam'=>'2','cp_busca'=>'config][CID10_4','title'=>'Código do quarto diagnóstico de acordo com a Classificação Internacional de Doenças e de Problemas Relacionados a Saúde - 10ª revisão'],
                'config[indicadorAcidente]'=>[
                    'label'=>'28-Indicação de Acidente',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>[
                        "0"=>"Trabalho","1"=>"Trânsito","2"=>"Outros Acidentes","9"=>"Não Acidente",
                    ],
                    'exibe_busca'=>'d-block',
                    'event'=>'required',
                    'tam'=>'3',
                    'title'=>'Indica se o atendimento é devido a acidente ocorrido com o beneficiário ou doença relacionada, conforme tabela de domínio nº 36.',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][indicadorAcidente',
                ],
                'config[motivoEncerramento]'=>[
                    'label'=>'29-Motivo do Encerramento',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>Qlib::sql_array("SELECT value,nome FROM tags WHERE ativo='s' AND pai='5'",'nome','value'),
                    'exibe_busca'=>'d-block',
                    'event'=>'required',
                    'tam'=>'3',
                    'title'=>'Indica se o atendimento é devido a acidente ocorrido com o beneficiário ou doença relacionada, conforme tabela de domínio nº 36.',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][motivoEncerramento',
                ],
                'config[declaracaoNascidoVivo]'=>['label'=>'30-N° da decl. de nasc. vivo','active'=>true,'placeholder'=>'','type'=>'tel','exibe_busca'=>'d-block','event'=>'maxlength=11','tam'=>'3','cp_busca'=>'config][declaracaoNascidoVivo','title'=>'Deve ser preenchido em caso de internação obstétrica onde tenha havido nascido vivo.'],
                'config[CID10Obito]'=>['label'=>'31-CID 10 Óbito','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'maxlength=4','tam'=>'2','cp_busca'=>'config][CID10Obito','title'=>'Código do diagnóstico de óbito do paciente de acordo com a Classificação Internacional de Doenças e de Problemas Relacionados a Saúde - 10ª revisão'],
                'config[nDeclaracaoObito]'=>['label'=>'32-N° da decl. de óbito','active'=>true,'placeholder'=>'','type'=>'tel','exibe_busca'=>'d-block','event'=>'maxlength=11','tam'=>'2','cp_busca'=>'config][nDeclaracaoObito','title'=>'Preenchimento Condicionado. Deve ser preenchido quando o motivo de encerramento for igual ao código 41 (Óbito com declaração
                de óbito fornecida pelo médico assistente) ou quando for óbito do RN na guia de internação da mãe.','descricao'=>'Número da declaração de óbito, que é o documento-base do Sistema de Informações sobre Mortalidade do Ministério da Saúde (SIM/MS).'],
                'config[indicadorDoRN]'=>[
                    'label'=>'33-Indicador DO de RN',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>['S'=>'Sim','N'=>'Não'],'exibe_busca'=>'d-block',
                    'event'=>'',
                    'tam'=>'2',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][indicadorDoRN',
                    'title'=>'Preenchimento Condicionado. Deve ser preenchido quando o campo Número da Declaração de Óbito for preenchido. Preencher com S
                    - SIM caso a declaração de óbito informada seja do RN e com N - Não caso a declaração de óbito informada seja da mãe.',
                ],
                'sep4'=>['label'=>'Procedimentos e Exames Realizados','active'=>false,'tam'=>'12','script'=>'<h5>Procedimentos e Exames Realizados</h5>','type'=>'html_script','class_div'=>'bg-secondary'],
                'painel1'=>['label'=>'Procedimentos e Exames Realizados','active'=>false,'tam'=>'12','script'=>'guias.procedimentos','type'=>'html','class_div'=>'px-0','dados'=>$arr_PainelProssedimentos],
                'sep5'=>['label'=>'Identificação do(s) Profissional(is) Executante(s)','active'=>false,'tam'=>'12','script'=>'<h5>Identificação do(s) Profissional(is) Executante(s)</h5>','type'=>'html_script','class_div'=>'bg-secondary'],
                'painel2'=>['label'=>'linsta de executantes','active'=>false,'tam'=>'12','script'=>'guias.executantes','type'=>'html','class_div'=>'','dados'=>$arr_Painelexecutantes],
                'config[qtdProcedimentos]'=>['label'=>'quantidade de procedimentos','id'=>'qtde_procedimento','active'=>false,'placeholder'=>'','type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'3','cp_busca'=>'config][qtdProcedimentos','title'=>'Valor total de todos os procedimentos realizados'],
                'config[tabela_padrao]'=>['label'=>'tabela_padrao','id'=>'tabela_padrao','active'=>false,'placeholder'=>'','type'=>'hidden','exibe_busca'=>'d-block','event'=>' ','tam'=>'3','cp_busca'=>'config][tabela_padrao','title'=>'Valor total de todos os procedimentos realizados'],
                'config[valorProcedimentos]'=>['label'=>'54-Total de Procedimentos (R$)','id'=>'total_procedimentos','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'required ','tam'=>'3','cp_busca'=>'config][valorProcedimentos','title'=>'Valor total de todos os procedimentos realizados'],
                'config[valorDiarias]'=>['label'=>'55-Total de Diárias (R$)','id'=>'total_diarias','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'3','cp_busca'=>'config][valorDiarias','title'=>'Valor total das diárias, considerando o valor de cada diária e a quantidade de diárias cobradas'],
                'config[valorTaxasAlugueis]'=>['label'=>'56-Total de Taxas e Aluguéis (R$)','id'=>'total_taxas','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'3','cp_busca'=>'config][valorTaxasAlugueis','title'=>'Valor total das taxas e aluguéis,  considerando o somatório de todas as taxas e aluguéis cobrados'],
                'config[valorMateriais]'=>['label'=>'57-Total de Materiais (R$)','active'=>false,'id'=>'total_materiais','placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'3','cp_busca'=>'config][valorMateriais','title'=>'Valor total dos materiais, considerando o valor unitário de cada material e a quantidade utilizada.'],
                'config[valorOPME]'=>['label'=>'58-Total de OPME (R$)','active'=>false,'placeholder'=>'','id'=>'total_opme','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'3','cp_busca'=>'config][valorOPME','title'=>'Preenchimento condicionado. Deve ser preenchido caso haja órtese, prótese ou material especial cobrado, conforme negociação entre as partes.'],
                'config[valorMedicamentos]'=>['label'=>'59-Total de Medicamentos (R$)','active'=>false,'id'=>'total_medicamentos','placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'3','cp_busca'=>'config][valorMedicamentos','title'=>'Valor total de Medicamentos'],
                'config[valorGasesMedicinais]'=>['label'=>'60-Total de Gases Medicinais (R$)','id'=>'total_gases','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'3','cp_busca'=>'config][valorGasesMedicinais','title'=>'Valor total de todos os procedimentos realizados'],
                'config[valorTotalGeral]'=>['label'=>'61-Total Geral (R$)','active'=>false,'placeholder'=>'','id'=>'total_geral','type'=>'text','exibe_busca'=>'d-block','event'=>'required ','tam'=>'3','cp_busca'=>'config][valorTotalGeral','title'=>'Somatório de todos os valores totais de procedimentos realizados e itens assistenciais utilizados'],
                'obs'=>['label'=>'65-Observação','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>'maxlenght=500','tam'=>'12'],

                /*
                'ativo'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],
                */
            ];
        }
        if($this->url=='executantes'){
            return [
                'id'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'type'=>['label'=>'type','active'=>false,'type'=>'hidden','value'=>$this->url,'exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'nome'=>['label'=>'Nome do Guia Executante','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'8'],
                'config[cpf_guia]'=>['label'=>'CPF do Guia(opcional)','active'=>true,'placeholder'=>'','type'=>'tel','exibe_busca'=>'d-block','event'=>'mask-cpf','tam'=>'4','cp_busca'=>'config][identificacao',],
                'config[conselho]'=>[
                    'label'=>'Conselho Guia',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>Qlib::sql_array("SELECT value,nome FROM tags WHERE ativo='s' AND pai='3' ORDER BY id ASC",'nome','value'),'exibe_busca'=>'d-block',
                    'event'=>'',
                    'tam'=>'6',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][conselho',
                ],
                'config[numero_conselho]'=>['label'=>'N° no Conselho','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'4'],
                'config[uf_concelhor]'=>[
                    'label'=>'UF do Conselho',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>Qlib::sql_array("SELECT letter,letter FROM states ",'letter','letter'),'exibe_busca'=>'d-block',
                    'event'=>'',
                    'tam'=>'2',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][uf_concelhor',
                ],
                'config[cbo]'=>[
                    'label'=>'Especialidade / Código CBO',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>Qlib::sql_array("SELECT value,nome FROM tags WHERE ativo='s' AND pai='2' ORDER BY id ASC",'nome','value'),
                    'exibe_busca'=>'d-block',
                    'event'=>'',
                    'tam'=>'8',
                    'class'=>'select2',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][cbo',
                ],
                'config[telefone]'=>['label'=>'Telefone de Contato(opcional)','active'=>true,'placeholder'=>'','type'=>'tel','exibe_busca'=>'d-block','event'=>'onblur=mask(this,clientes_mascaraTelefone); onkeypress=mask(this,clientes_mascaraTelefone);','tam'=>'4'],
                //'obs'=>['label'=>'Observação','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
                'ativo'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],
            ];
        }
    }
    public function index(User $user)
    {
        $this->authorize('ler', $this->url);
        $title = 'Guias Cadastradas';
        $titulo = $title;
        $queryGuia = $this->queryGuia($_GET);
        $queryGuia['config']['exibe'] = 'html';
        $routa = $this->routa;
        return view($this->view.'.index',[
            'dados'=>$queryGuia['guia'],
            'title'=>$title,
            'titulo'=>$titulo,
            'campos_tabela'=>$queryGuia['campos'],
            'guia_totais'=>$queryGuia['guia_totais'],
            'titulo_tabela'=>$queryGuia['tituloTabela'],
            'arr_titulo'=>$queryGuia['arr_titulo'],
            'config'=>$queryGuia['config'],
            'routa'=>$routa,
            'view'=>$this->view,
            'i'=>0,
        ]);
    }
    public function create(User $user)
    {
        $this->authorize('create', $this->url);
        if($this->url=='internacao'){
            $title = 'GUIA DE RESUMO DE INTERNAÇÃO - INCLUSÃO';
        }else{
            $title = 'Cadastrar guia';
        }
        $titulo = $title;
        $config = [
            'ac'=>'cad',
            'frm_id'=>'frm-guias',
            'route'=>$this->routa,
        ];
        $value = [
            'token'=>uniqid(),
        ];
        $campos = $this->campos();
        return view($this->view.'.createedit',[
            'config'=>$config,
            'title'=>$title,
            'titulo'=>$titulo,
            'campos'=>$campos,
            'value'=>$value,
        ]);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => ['required','string','unique:guias'],
        ]);
        $dados = $request->all();
        $ajax = isset($dados['ajax'])?$dados['ajax']:'n';
        $dados['ativo'] = isset($dados['ativo'])?$dados['ativo']:'n';

        //dd($dados);
        $salvar = Guia::create($dados);
        $route = $this->routa.'.index';
        $ret = [
            'mens'=>$this->label.' cadastrada com sucesso!',
            'color'=>'success',
            'idCad'=>$salvar->id,
            'exec'=>true,
            'dados'=>$dados
        ];

        if($ajax=='s'){
            $ret['return'] = route($route).'?idCad='.$salvar->id;
            $ret['redirect'] = route($this->routa.'.edit',['id'=>$salvar->id]);
            return response()->json($ret);
        }else{
            return redirect()->route($route,$ret);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($guia,User $user)
    {
        $id = $guia;
        $dados = Guia::where('id',$id)->get();
        $routa = 'guias';
        $this->authorize('ler', $this->url);

        if(!empty($dados)){
            $title = 'Editar Cadastro de guias';
            $titulo = $title;
            $dados[0]['ac'] = 'alt';
            if(isset($dados[0]['config'])){
                $dados[0]['config'] = Qlib::lib_json_array($dados[0]['config']);
            }
            $listFiles = false;
            $campos = $this->campos();
            if(isset($dados[0]['token'])){
                $listFiles = _upload::where('token_produto','=',$dados[0]['token'])->get();
            }
            $config = [
                'ac'=>'alt',
                'frm_id'=>'frm-guias',
                'route'=>$this->routa,
                'id'=>$id,
            ];
            //dd($dados[0]);
            $_REQUEST['dados'] = $dados[0];
            $ret = [
                'value'=>$dados[0],
                'config'=>$config,
                'title'=>$title,
                'titulo'=>$titulo,
                'listFiles'=>$listFiles,
                'campos'=>$campos,
                'exec'=>true,
            ];
            return view($this->view.'.createedit',$ret);
        }else{
            $ret = [
                'exec'=>false,
            ];
            return redirect()->route($this->view.'.index',$ret);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nome' => ['required'],
        ]);
        $data = [];
        $dados = $request->all();
        $ajax = isset($dados['ajax'])?$dados['ajax']:'n';
        foreach ($dados as $key => $value) {
            if($key!='_method'&&$key!='_token'&&$key!='ac'&&$key!='ajax'&&$key!='procedimento'&&$key!='executante'){
                if($key=='data_batismo' || $key=='data_nasci'){
                    if($value=='0000-00-00' || $value=='00/00/0000'){
                    }else{
                        $data[$key] = Qlib::dtBanco($value);
                    }
                }elseif($key == 'renda_familiar') {
                    $value = str_replace('R$','',$value);
                    $data[$key] = Qlib::precoBanco($value);
                }else{
                    $data[$key] = $value;
                }
            }
        }
        $userLogadon = Auth::id();
        $data['ativo'] = isset($data['ativo'])?$data['ativo']:'n';
        $data['autor'] = $userLogadon;
        if(isset($dados['config'])){
            $dados['config'] = Qlib::lib_array_json($dados['config']);
        }
        $atualizar=false;
        if(!empty($data)){
            $atualizar=Guia::where('id',$id)->update($data);
            $route = $this->routa.'.index';
            $ret = [
                'exec'=>$atualizar,
                'id'=>$id,
                'mens'=>'Salvo com sucesso!',
                'color'=>'success',
                'idCad'=>$id,
                'return'=>$route,
            ];
        }else{
            $route = $this->routa.'.edit';
            $ret = [
                'exec'=>false,
                'id'=>$id,
                'mens'=>'Erro ao receber dados',
                'color'=>'danger',
            ];
        }
        if($ajax=='s'){
            $ret['return'] = route($route).'?idCad='.$id;
            return response()->json($ret);
        }else{
            return redirect()->route($route,$ret);
        }
    }

    public function destroy($id,Request $request)
    {
        $this->authorize('delete', $this->url);
        $config = $request->all();
        $ajax =  isset($config['ajax'])?$config['ajax']:'n';
        $routa = 'guias';
        if (!$post = Guia::find($id)){
            if($ajax=='s'){
                $ret = response()->json(['mens'=>'Registro não encontrado!','color'=>'danger','return'=>route($this->view.'.index')]);
            }else{
                $ret = redirect()->route($this->view.'.index',['mens'=>'Registro não encontrado!','color'=>'danger']);
            }
            return $ret;
        }

        Guia::where('id',$id)->delete();
        if($ajax=='s'){
            $ret = response()->json(['mens'=>__('Registro '.$id.' deletado com sucesso!'),'color'=>'success','return'=>route($this->routa.'.index')]);
        }else{
            $ret = redirect()->route($routa.'.index',['mens'=>'Registro deletado com sucesso!','color'=>'success']);
        }
        return $ret;
    }
}
