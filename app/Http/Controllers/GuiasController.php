<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use App\Models\Guia;
use App\Qlib\Qlib;
use App\Models\User;
use App\Models\_upload;
use App\Models\Operadora;
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
                'procedimento[horaInicio]'  =>['label'=>'35-Hora In??cio','id'=>'procedimento_alimentador_hora1','active'=>false,'placeholder'=>'Preenchimento condicionado. Deve ser preenchido quando o procedimento ocorrer em situa????es de urg??ncia e emerg??ncia.','type'=>'time','exibe_busca'=>'d-block','event'=>'onfocus=this.style.backgroundColor=\'#ffffff\'','tam'=>'2','cp_busca'=>'','title'=>''],
                'procedimento[horaFim]'     =>['label'=>'36-Hora Fim','id'=>'procedimento_alimentador_hora2','active'=>false,'placeholder'=>'','type'=>'time','exibe_busca'=>'d-block','event'=>'onfocus=this.style.backgroundColor=\'#ffffff\'','tam'=>'2','cp_busca'=>'','title'=>'Preenchimento condicionado. Deve ser preenchido quando o procedimento ocorrer em situa????es de urg??ncia e emerg??ncia.'],
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
                    'title'=>'C??digo da tabela utilizada para identificar os procedimentos realizados ou itens assistenciais utilizados, conforme tabela de dom??nio n?? 87.',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][tabela_cobranca',
                ],
                'procedimento[codigo]'  =>['label'=>'C??digo do procedimento','id'=>'procedimento_alimentador_codigo','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'onfocus=this.style.backgroundColor=\'#ffffff\'','tam'=>'3','cp_busca'=>'','title'=>''],
                'procedimento[descricao]'  =>['label'=>'Descri????o','id'=>'procedimento_alimentador_descricao','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'onkeyup=upcase(event,this) onfocus=this.style.backgroundColor=\'#ffffff\'','tam'=>'9','cp_busca'=>'','title'=>''],
                'procedimento[qde]'  =>['label'=>'40-Quantidade','id'=>'procedimento_alimentador_quantidade','active'=>false,'placeholder'=>'','type'=>'number','exibe_busca'=>'d-block','event'=>'onfocus=this.style.backgroundColor=\'#ffffff\' onchange=procedimento_alimentador_total()','tam'=>'2','cp_busca'=>'','title'=>'C??digo identificador do procedimento realizado pelo prestador, conforme tabela de dom??nio.'],
                'procedimento[viaAcesso]'=>[
                    'label'=>'41-Via',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>[
                        "1"=>"1 - ??nica","2"=>"2 - Mesma Via","3"=>"3 - Diferentes vias",
                    ],
                    'exibe_busca'=>'d-block',
                    'event'=>'',
                    'tam'=>'2',
                    'class'=>'',
                    'id'=>'procedimento_alimentador_via',
                    'title'=>'Condicionado. Deve ser preenchido em caso de procedimento cir??rgico.',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                ],
                'procedimento[tecnicaUtilizada]'=>[
                    'label'=>'42-T??c',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>[
                        "1"=>"1 - Convencional","2"=>"2 - Videolaparoscopia","3"=>"3 - Rob??tica",
                    ],
                    'exibe_busca'=>'d-block',
                    'event'=>'',
                    'tam'=>'2',
                    'class'=>'',
                    'id'=>'procedimento_alimentador_tec',
                    'title'=>'C??digo da t??cnica utilizada para realiza????o do procedimento, conforme tabela de dom??nio n?? 48.',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                ],
                'procedimento[reducaoAcrescimo]'  =>['label'=>'43-Fator Red/Acr??sc','id'=>'procedimento_alimentador_fator','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'onkeypress=return(currencyFormat(this,\'\',\'.\',4,event)) onkeyup=procedimento_alimentador_total()','tam'=>'2','cp_busca'=>'','title'=>'Obrigat??rio. Quando n??o houver redu????o ou acr??scimo sobre o valor do procedimento, o campo deve ser preenchido com 1,00.'],
                'procedimento[valorUnitario]'  =>['label'=>'44-Valor Unit??rio','id'=>'procedimento_alimentador_valor_unitario','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'onfocus=this.style.backgroundColor=\'#ffffff\' onkeyup=procedimento_alimentador_total()','tam'=>'2','class'=>'moeda','cp_busca'=>'','title'=>'Obrigat??rio. Quando n??o houver redu????o ou acr??scimo sobre o valor do procedimento, o campo deve ser preenchido com 1,00.'],
                'procedimento[valorTotal]'  =>['label'=>'45-Valor Total','id'=>'procedimento_alimentador_valor_total','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','class'=>'','event'=>' onkeyup=procedimento_alimentador_total()','tam'=>'2','cp_busca'=>'','title'=>'Valor total do procedimento realizado, considerando a quantidade de procedimentos realizados, o valor unit??rio e o fator de redu????o ou acr??scimo'],

            ];
            $arrSeq = range(0,100);
            unset($arrSeq[0]);
            $arr_Painelexecutantes = [
                'executante[id_executante]'  =>['label'=>'id do executor','id'=>'executante_alimentador_ex_id','active'=>false,'placeholder'=>'','type'=>'hidden','exibe_busca'=>'d-block','class'=>'','event'=>' ','tam'=>'3','cp_busca'=>'','title'=>'C??digo na Operadora ou CPF do profissional participante da equipe de execu????o do procedimento.'],
                'executante[SeqRef]'=>[
                    'label'=>'46-Seq. Ref.',
                    'id'=>'executante_alimentador_seq',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>$arrSeq,
                    'exibe_busca'=>'d-block',
                    'event'=>'',
                    'tam'=>'2',
                    'class'=>'',
                    'title'=>'Condicionado. Deve ser preenchido na conting??ncia em papel com o n??mero de refer??ncia do procedimento (n??mero da linha) a que se refere a participa????o do profissional integrante da equipe.',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                ],
                'executante[GrauPart]'=>[
                    'label'=>'47-Gru. Part.',
                    'id'=>'executante_alimentador_grau_part',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>[
                        "00"=>"00 - Cirurgi??o","01"=>"01 - Primeiro Auxiliar","02"=>"02 - Segundo Auxiliar","03"=>"03 - Terceiro Auxiliar","04"=>"04 - Quarto Auxiliar","05"=>"05 - Instrumentador","06"=>"06 - Anestesista","07"=>"07 - Auxiliar de Anestesista","08"=>"08 - Consultor","09"=>"09 - Perfusionista","10"=>"10 - Pediatra na sala de parto","11"=>"11 - Auxiliar SADT","12"=>"12 - Cl??nico","13"=>"13 - Intensivista",

                    ],
                    'exibe_busca'=>'d-block',
                    'event'=>'',
                    'tam'=>'2',
                    'class'=>'',
                    'title'=>'Condicionado. Deve ser  preenchido sempre que houver honor??rios profissionais relativos aos procedimentos realizados e tratar-se de procedimento realizado por equipe.',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                ],
                'executante[idProfissional]'=>[
                    'label'=>'49-Nome Profissional',
                    'id'=>'executante_alimentador_nome',
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
                    'arr_opc'=>Qlib::sql_array2("SELECT id,nome,config FROM profissionals WHERE ativo='s' AND type='executantes'",'nome','id'),'exibe_busca'=>'d-block',
                    'event'=>'onchange=autocompletarSelect(\'executante\')',
                    'tam'=>'5',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'title'=>'Nome do profissional participante da equipe de execu????o do procedimento.',
                    'cp_busca'=>'config][idProfissional',
                ],
                'executante[codigoCpfProfissional]'  =>['label'=>'47-C??d. na Operadora / CPF','id'=>'executante_alimentador_codigo','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','class'=>'mask-cpf','event'=>' ','tam'=>'3','cp_busca'=>'','title'=>'C??digo na Operadora ou CPF do profissional participante da equipe de execu????o do procedimento.'],
                'executante[conselho]'=>[
                    'label'=>'Conselho',
                    'id'=>'executante_alimentador_conselho',
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
                'executor[numero_conselho]'=>['label'=>'N?? no Conselho','id'=>'executante_alimentador_conselho_numero','active'=>true,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'disabled','tam'=>'4'],
                'executor[uf_conselho]'=>['label'=>'UF','active'=>true,'placeholder'=>'','id'=>'executante_alimentador_conselho_uf','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'disabled','tam'=>'1'],
                'executor[cbo]'=>['label'=>'C??d. CBO','active'=>true,'placeholder'=>'','id'=>'executante_alimentador_cbo','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'disabled','tam'=>'3'],


            ];
            $arr_Paineldespesas = [
                'despesas[codigoDespesa]'=>[
                    'label'=>'C??digo',
                    'active'=>false,
                    'id'=>'despesa_alimentador_tipo',
                    'type'=>'select',
                    'arr_opc'=>Qlib::sql_array("SELECT value,nome FROM tags WHERE ativo='s' AND pai='7'",'nome','value','','','encode'),
                    'exibe_busca'=>'d-block',
                    'event'=>'onfocus=this.style.backgroundColor=\'#ffffff\'',
                    'tam'=>'3',
                    'class'=>'',
                    'title'=>'C??digo da natureza da despesa, conforme tabela de dom??nio n?? 25.',
                    'exibe_busca'=>false,
                    'option_select'=>true,
                    'cp_busca'=>'config][codigoDespesa',
                ],
                'despesas[data]'        =>['label'=>'Data execu????o','active'=>false,'placeholder'=>'','type'=>'date','id'=>'despesa_alimentador_data','exibe_busca'=>'d-block','event'=>'onfocus=this.style.backgroundColor=\'#ffffff\'','tam'=>'2','cp_busca'=>'','title'=>''],
                'despesas[horaInicio]'  =>['label'=>'Hora In??cio','id'=>'despesa_alimentador_hora1','active'=>false,'placeholder'=>'Preenchimento condicionado. Deve ser preenchido  quando o item de despesa admitir cobran??a mensur??vel em horas.','type'=>'time','exibe_busca'=>'d-block','event'=>'onfocus=this.style.backgroundColor=\'#ffffff\'','tam'=>'2','cp_busca'=>'','title'=>''],
                'despesas[horaFim]'     =>['label'=>'Hora Fim','id'=>'despesa_alimentador_hora2','active'=>false,'placeholder'=>'','type'=>'time','exibe_busca'=>'d-block','event'=>'onfocus=this.style.backgroundColor=\'#ffffff\'','tam'=>'2','cp_busca'=>'','title'=>'Preenchimento condicionado. Deve ser preenchido  quando o item de despesa admitir cobran??a mensur??vel em horas.'],
                'despesas[tabela_cobranca]'=>[
                    'label'=>'Tabela',
                    'active'=>false,
                    'id'=>'despesa_alimentador_tabela',
                    'type'=>'select',
                    'arr_opc'=>Qlib::sql_array("SELECT value,nome FROM tags WHERE ativo='s' AND pai='4'",'nome','value','','','encode'),
                    'exibe_busca'=>'d-block',
                    'event'=>'onfocus=this.style.backgroundColor=\'#ffffff\'',
                    'tam'=>'3',
                    'class'=>'',
                    'title'=>'C??digo da tabela utilizada para identificar os despesas realizados ou itens assistenciais utilizados, conforme tabela de dom??nio n?? 87.',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][tabela_cobranca',
                ],
                'despesas[codigo]'  =>['label'=>'C??digo do despesa','id'=>'despesa_alimentador_codigo','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'onfocus=this.style.backgroundColor=\'#ffffff\'','tam'=>'3','cp_busca'=>'','title'=>'C??digo do item assistencial das despesas realizadas, conforme tabela utilizada'],
                'despesas[descricao]'  =>['label'=>'Descri????o','id'=>'despesa_alimentador_descricao','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'onkeyup=upcase(event,this) onfocus=this.style.backgroundColor=\'#ffffff\'','tam'=>'9','cp_busca'=>'','title'=>''],
                'despesas[unidade_medida]'=>[
                    'label'=>'Unidade de medida',
                    'active'=>false,
                    'id'=>'despesa_alimentador_unidade',
                    'type'=>'select',
                    'arr_opc'=>Qlib::sql_array("SELECT value,nome FROM tags WHERE ativo='s' AND pai='8'",'nome','value'),
                    'exibe_busca'=>'d-block',
                    'event'=>'onfocus=this.style.backgroundColor=\'#ffffff\'',
                    'tam'=>'4',
                    'class'=>'',
                    'title'=>'C??digo da unidade de medida, conforme tabela de dom??nio n?? 60.',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][unidade_medida',
                ],
                'despesas[qde]'  =>['label'=>'Quantidade','id'=>'despesa_alimentador_quantidade','active'=>false,'placeholder'=>'','type'=>'number','exibe_busca'=>'d-block','event'=>'onfocus=this.style.backgroundColor=\'#ffffff\' onchange=despesa_alimentador_total()','tam'=>'2','cp_busca'=>'','title'=>'C??digo identificador do despesa realizado pelo prestador, conforme tabela de dom??nio.'],
                'despesas[reducaoAcrescimo]'  =>['label'=>'Fator Red/Acr??sc','id'=>'despesa_alimentador_fator','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'onkeypress=return(currencyFormat(this,\'\',\'.\',4,event)) onkeyup=despesa_alimentador_total()','tam'=>'2','cp_busca'=>'','title'=>'Obrigat??rio. Quando n??o houver redu????o ou acr??scimo sobre o valor do despesa, o campo deve ser preenchido com 1,00.'],
                'despesas[valorUnitario]'  =>['label'=>'Valor Unit??rio','id'=>'despesa_alimentador_valor_unitario','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'onfocus=this.style.backgroundColor=\'#ffffff\' onkeyup=despesa_alimentador_total()','tam'=>'2','class'=>'moeda','cp_busca'=>'','title'=>'Obrigat??rio. Nos casos em que esse valor n??o possa ser definido previamente por for??a contratual, o campo ser?? preenchido com zero'],
                'despesas[valorTotal]'  =>['label'=>'Valor Total','id'=>'despesa_alimentador_valor_total','active'=>false,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','class'=>'','event'=>' onkeyup=despesa_alimentador_total()','tam'=>'2','cp_busca'=>'','title'=>'Valor total do despesa realizado, considerando a quantidade de despesas realizados, o valor unit??rio e o fator de redu????o ou acr??scimo'],
                //'despesas[valorTotal]'  =>['label'=>'Valor Total','id'=>'despesa_alimentador_valor_total','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','class'=>'','event'=>' onkeyup=despesa_alimentador_total()','tam'=>'2','cp_busca'=>'','title'=>'Valor total do despesa realizado, considerando a quantidade de despesas realizados, o valor unit??rio e o fator de redu????o ou acr??scimo'],
                'despesas[anvisa]'  =>['label'=>'Registro ANVISA do material','id'=>'despesa_alimentador_anvisa','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','class'=>'','event'=>'maxlength=15','tam'=>'4','cp_busca'=>'','title'=>'Condicionado. Deve ser preenchido em caso de cobran??a de ??rteses, pr??teses e materiais especiais, quando for utilizado c??digo de material ainda n??o cadastrado na TUSS.'],
                'despesas[referencia_fabricante]'  =>['label'=>'C??digo de refer??ncia do material no fabricante','id'=>'despesa_alimentador_fabricante','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','class'=>'','event'=>'maxlength=60','tam'=>'4','cp_busca'=>'','title'=>'Condicionado. Deve ser preenchido quando se tratar de ??rteses, pr??teses e materiais especiais, quando for utilizado c??digo de material ainda n??o cadastrado na TUSS.'],
                'despesas[autorizacao]'  =>['label'=>'Autoriza????o de funcionamento','id'=>'despesa_alimentador_autorizacao','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','class'=>'','event'=>'maxlength=30','tam'=>'4','cp_busca'=>'','title'=>'Condicionado. Deve ser preenchido em caso de cobran??a de ??rteses, pr??teses e materiais especiais que foram adquiridos
                pelo prestador solicitante'],

            ];
            return [
                'id'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'type'=>['label'=>'type','active'=>false,'type'=>'hidden','value'=>$this->url,'exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'config[registro_ans]'=>['label'=>'Registro Ans','id'=>'registro_ans','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','cp_busca'=>'config][registro_ans','tam'=>'2'],
                'config[op_id]'=>['label'=>'id operadora','active'=>true,'id'=>'op_id','type'=>'hidden','exibe_busca'=>'d-block','event'=>'','cp_busca'=>'config][op_id','tam'=>'2'],
                'config[select_operadora]'=>[
                    'label'=>'1-Registro ANS',
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
                    'arr_opc'=>Qlib::sql_array("SELECT id,nome,registro,config FROM operadoras WHERE ativo='s'",'registro','id','nome',' | ','encode'),'exibe_busca'=>'d-block',
                    'event'=>'required onchange=escolhe_operadora()',
                    'tam'=>'4',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'title'=>'Registro da operadora de plano privado de assist??ncia ?? sa??dena Ag??ncia Nacional de Sa??deSuplementar (ANS)',
                    'id'=>'select_operadora',
                    'cp_busca'=>'config][select_operadora',
                ],
                'numero_guia'=>['label'=>'2-N?? Guia no Prestador','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'required maxlength=20','tam'=>'4',],
                'config[numeroGuiaSolicitacaoInternacao]'=>['label'=>'3-N?? da Guia de Solicita????o de Interna????o','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'required maxlength=20','tam'=>'4','cp_busca'=>'config][numeroGuiaSolicitacaoInternacao',],
                'config[data_autorizacao]'=>['label'=>'4-Data da Autoriza????o','active'=>false,'placeholder'=>'','type'=>'date','exibe_busca'=>'d-block','event'=>'required','tam'=>'3','cp_busca'=>'config][data_autorizacao','title'=>'Data em que a autoriza????o para realiza????o do atendimento/procedimento foi concedida pela operadora.'],
                'config[senha]'=>['label'=>'5-Senha','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'required maxlength=20','tam'=>'2','cp_busca'=>'config][senha',],
                'config[dataValidadeSenha]'=>['label'=>'6-Data de Validade da Senha','active'=>false,'placeholder'=>'','type'=>'date','exibe_busca'=>'d-block','event'=>'required','tam'=>'3','cp_busca'=>'config][dataValidadeSenha','title'=>'Deve ser preenchido em
                caso de autoriza????o pela operadora com emiss??o de senha com prazo de validade'],
                'config[numeroGuiaOperadora]'=>['label'=>'7-N?? da Guia Atribu??do pela Operadora','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'required maxlength=20','tam'=>'4','cp_busca'=>'config][numeroGuiaOperadora',],
                'sep1'=>['label'=>'Dados do Benefici??rio','active'=>false,'tam'=>'12','script'=>'<h5>Dados do Benefici??rio</h5>','type'=>'html_script','class_div'=>'bg-secondary'],
                'config[numeroCarteira]'=>['label'=>'8-N??mero da Carteira','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'required maxlength=20','tam'=>'3','cp_busca'=>'config][numeroCarteira',],
                'config[validadeCarteira]'=>['label'=>'9-Validade da Carteira','active'=>false,'placeholder'=>'','type'=>'date','exibe_busca'=>'d-block','event'=>'','tam'=>'2','cp_busca'=>'config][validadeCarteira','title'=>'Preenchimento Condicionado. Deve ser preenchido somente na utiliza????o da conting??ncia em papel quando a operadora exigir autoriza????o pr??via para procedimentos ambulatoriais e tal autoriza????o n??o puder ser obtida.'],
                'nome'=>['label'=>'10-Nome','active'=>true,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'required maxlength=70','tam'=>'5'],
                //'config[cartaoSaude]'=>['label'=>'11-Cart??o Nacional de Sa??de','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'','tam'=>'2','cp_busca'=>'config][validadeCarteira','title'=>'Preenchimento Condicionado. Deve ser preenchido somente na utiliza????o da conting??ncia em papel quando a operadora exigir autoriza????o pr??via para procedimentos ambulatoriais e tal autoriza????o n??o puder ser obtida.'],
                'config[atendimentoRN]'=>[
                    'label'=>'12-Atendimento a RN',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>['S'=>'Sim','N'=>'N??o'],'exibe_busca'=>'d-block',
                    'event'=>'required',
                    'tam'=>'2',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][atendimentoRN',
                    'title'=>'Deve ser informado "S" - sim - caso o atendimento seja do rec??m-nato e o benefici??rio seja o respons??vel e "N" - n??o - quando o atendimento for do pr??prio benefici??rio.',
                ],
                'sep2'=>['label'=>'Dados do Contratado Executante','active'=>false,'tam'=>'12','script'=>'<h5>Dados do Contratado Executante</h5>','type'=>'html_script','class_div'=>'bg-secondary'],
                'config[codigoNaOperadora]'=>['label'=>'13-C??digo na Operadora','id'=>'cont_exec_codigo','active'=>false,'placeholder'=>'','type'=>'tel','exibe_busca'=>'d-block','event'=>'required maxlength=14','tam'=>'3','cp_busca'=>'config][codigoNaOperadora','title'=>'C??digo na operadora ou CNPJ do prestador contratado que executou o procedimento.'],
                'config[nomeContratado]'=>['label'=>'14-Nome do Contratado','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'required maxlength=70','tam'=>'7','cp_busca'=>'config][nomeContratado','title'=>'Raz??o Social, nome fantasia ou nome do prestador contratado da operadora que executou oprocedimento.'],
               /*'config[nomeContratado]'=>[
                    'label'=>'14-Nome do Contratado',
                    'id'=>'contratado_solicitante',
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
                    'arr_opc'=>Qlib::sql_array2("SELECT id,nome,config FROM profissionals WHERE ativo='s' AND type='solicitantes'",'nome','id'),'exibe_busca'=>'d-block',
                    'event'=>'onchange=autocompletarSelect(\'executante\')',
                    'tam'=>'3',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'title'=>'Nome do profissional participante da equipe de execu????o do procedimento.',
                    'cp_busca'=>'config][nomeContratado',
                 ],*/
                'config[nomeContratado]'=>['label'=>'14-Nome do Contratado','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'required maxlength=70','tam'=>'7','cp_busca'=>'config][nomeContratado','title'=>''],
                'config[codigoCNES]'=>['label'=>'15-C??digo CNES','active'=>false,'placeholder'=>'','type'=>'tel','exibe_busca'=>'d-block','event'=>'required maxlength=7','tam'=>'2','cp_busca'=>'config][codigoCNES','title'=>'Caso o prestador ainda n??o possua o c??digo do CNES preencher o campo com 9999999.'],
                'sep3'=>['label'=>'Dados da Interna????o','active'=>false,'tam'=>'12','script'=>'<h5>Dados da Interna????o</h5>','type'=>'html_script','class_div'=>'bg-secondary'],
                'config[caraterAtendimento]'=>[
                    'label'=>'16-Car??ter do Atendimento',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>["1"=>"1 - Eletiva","2"=>"2 - Urg??ncia/Emerg??ncia"],
                    'exibe_busca'=>'d-block',
                    'event'=>'required',
                    'tam'=>'3',
                    'title'=>'C??digo do car??ter do atendimento, conforme tabela de dom??nio n?? 23.',
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
                    'title'=>'C??digo do tipo do faturamento apresentado nesta guia, conforme tabela de dom??nio n?? 55.',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][tipoFaturamento',
                ],
                'config[dataInicioFaturamento]'=>['label'=>'18-Data In??cio Faturamento','active'=>false,'placeholder'=>'','type'=>'date','exibe_busca'=>'d-block','event'=>'required','tam'=>'3','cp_busca'=>'config][dataInicioFaturamento','title'=>'Quando o tipo de faturamento for igual a 3-Complementar, preencher o campo com a data do in??cio do faturamento da guia que est?? sendo complementada.'],
                'config[horaInicioFaturamento]'=>['label'=>'19-Hora In??cio Faturamento','active'=>false,'placeholder'=>'','type'=>'time','exibe_busca'=>'d-block','event'=>'required ','tam'=>'3','cp_busca'=>'config][horaInicioFaturamento','title'=>'Quando o tipo de faturamento for igual a 3-Complementar, preencher o campo com a hora do in??cio do faturamento da guia que est?? sendo complementada.'],
                'config[dataFinalFaturamento]'=>['label'=>'20-Data Fim Faturamento','active'=>false,'placeholder'=>'','type'=>'date','exibe_busca'=>'d-block','event'=>'required','tam'=>'3','cp_busca'=>'config][dataFinalFaturamento','title'=>'Quando o tipo de faturamento for igual a 3-Complementar, preencher o campo com a data do fim do faturamento da guia que est?? sendo complementada.'],
                'config[horaFinalFaturamento]'=>['label'=>'21-Hora Fim Faturamento','active'=>false,'placeholder'=>'','type'=>'time','exibe_busca'=>'d-block','event'=>'required ','tam'=>'3','cp_busca'=>'config][horaFinalFaturamento','title'=>'Quando o tipo de faturamento for igual a 3-Complementar, preencher o campo com a hora do fim do faturamento da guia que est?? sendo complementada.'],
                'config[tipoInternacao]'=>[
                    'label'=>'22-Tipo de Interna????o',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>[
                        "1"=>"Cl??nica","2"=>"Cir??rgica","3"=>"Obst??trica","4"=>"Pedi??trica","5"=>"Psiqui??trica",
                    ],
                    'exibe_busca'=>'d-block',
                    'event'=>'required',
                    'tam'=>'3',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'title'=>'C??digo do tipo de interna????o, conforme tabela de dom??nio n?? 57',
                    'cp_busca'=>'config][tipoInternacao',
                ],
                'config[regimeInternacao]'=>[
                    'label'=>'23-Regime de Interna????o',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>[
                        "1"=>"Hospitalar","2"=>"Hospital-Dia","3"=>"Domiciliar",
                    ],
                    'exibe_busca'=>'d-block',
                    'event'=>'required',
                    'tam'=>'3',
                    'class'=>'',
                    'title'=>'Regime da interna????o de acordo com tabela de dom??nio n?? 41.',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][regimeInternacao',
                ],
                'config[CID10Principal]'=>['label'=>'24-CID10 Principal (opcional)','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'maxlength=4','tam'=>'3','cp_busca'=>'config][CID10Principal','title'=>'C??digo do diagn??stico principal de acordo com a Classifica????o Internacional de Doen??as e de Problemas Relacionados a Sa??de - 10?? revis??o'],
                'config[CID10_2]'=>['label'=>'25-CID10 (2)','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'maxlength=4','tam'=>'2','cp_busca'=>'config][CID10_2','title'=>'C??digo do diagn??stico secund??rio de acordo com a Classifica????o Internacional de Doen??as e de Problemas Relacionados a Sa??de - 10?? revis??o'],
                'config[CID10_3]'=>['label'=>'26-CID10 (3)','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'maxlength=4','tam'=>'2','cp_busca'=>'config][CID10_3','title'=>'C??digo do terceiro diagn??stico de acordo com a Classifica????o Internacional de Doen??as e de Problemas Relacionados a Sa??de - 10?? revis??o'],
                'config[CID10_4]'=>['label'=>'27-CID10 (4)','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'maxlength=4','tam'=>'2','cp_busca'=>'config][CID10_4','title'=>'C??digo do quarto diagn??stico de acordo com a Classifica????o Internacional de Doen??as e de Problemas Relacionados a Sa??de - 10?? revis??o'],
                'config[indicadorAcidente]'=>[
                    'label'=>'28-Indica????o de Acidente',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>[
                        "0"=>"Trabalho","1"=>"Tr??nsito","2"=>"Outros Acidentes","9"=>"N??o Acidente",
                    ],
                    'exibe_busca'=>'d-block',
                    'event'=>'required',
                    'tam'=>'3',
                    'title'=>'Indica se o atendimento ?? devido a acidente ocorrido com o benefici??rio ou doen??a relacionada, conforme tabela de dom??nio n?? 36.',
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
                    'title'=>'Indica se o atendimento ?? devido a acidente ocorrido com o benefici??rio ou doen??a relacionada, conforme tabela de dom??nio n?? 36.',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][motivoEncerramento',
                ],
                'config[declaracaoNascidoVivo]'=>['label'=>'30-N?? da decl. de nasc. vivo','active'=>true,'placeholder'=>'','type'=>'tel','exibe_busca'=>'d-block','event'=>'maxlength=11','tam'=>'3','cp_busca'=>'config][declaracaoNascidoVivo','title'=>'Deve ser preenchido em caso de interna????o obst??trica onde tenha havido nascido vivo.'],
                'config[CID10Obito]'=>['label'=>'31-CID 10 ??bito','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'maxlength=4','tam'=>'2','cp_busca'=>'config][CID10Obito','title'=>'C??digo do diagn??stico de ??bito do paciente de acordo com a Classifica????o Internacional de Doen??as e de Problemas Relacionados a Sa??de - 10?? revis??o'],
                'config[nDeclaracaoObito]'=>['label'=>'32-N?? da decl. de ??bito','active'=>true,'placeholder'=>'','type'=>'tel','exibe_busca'=>'d-block','event'=>'maxlength=11','tam'=>'2','cp_busca'=>'config][nDeclaracaoObito','title'=>'Preenchimento Condicionado. Deve ser preenchido quando o motivo de encerramento for igual ao c??digo 41 (??bito com declara????o
                de ??bito fornecida pelo m??dico assistente) ou quando for ??bito do RN na guia de interna????o da m??e.','descricao'=>'N??mero da declara????o de ??bito, que ?? o documento-base do Sistema de Informa????es sobre Mortalidade do Minist??rio da Sa??de (SIM/MS).'],
                'config[indicadorDoRN]'=>[
                    'label'=>'33-Indicador DO de RN',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>['S'=>'Sim','N'=>'N??o'],'exibe_busca'=>'d-block',
                    'event'=>'',
                    'tam'=>'2',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][indicadorDoRN',
                    'title'=>'Preenchimento Condicionado. Deve ser preenchido quando o campo N??mero da Declara????o de ??bito for preenchido. Preencher com S
                    - SIM caso a declara????o de ??bito informada seja do RN e com N - N??o caso a declara????o de ??bito informada seja da m??e.',
                ],
                'sep4'=>['label'=>'Procedimentos e Exames Realizados','active'=>false,'tam'=>'12','script'=>'<h5>Procedimentos e Exames Realizados</h5>','type'=>'html_script','class_div'=>'bg-secondary'],
                'painel1'=>['label'=>'Procedimentos e Exames Realizados','active'=>false,'tam'=>'12','script'=>'guias.procedimentos','type'=>'html','class_div'=>'px-0','dados'=>$arr_PainelProssedimentos],
                'sep5'=>['label'=>'Identifica????o da Equipe','active'=>false,'tam'=>'12','script'=>'<h5>Identifica????o da Equipe</h5>','type'=>'html_script','class_div'=>'bg-secondary'],
                'painel2'=>['label'=>'linsta de executantes','active'=>false,'tam'=>'12','script'=>'guias.executantes','type'=>'html','class_div'=>'','dados'=>$arr_Painelexecutantes],
                'sep6'=>['label'=>'Outras despesas','active'=>false,'tam'=>'12','script'=>'<h5>Anexo de Outras Despesas</h5>','type'=>'html_script','class_div'=>'bg-secondary'],
                'painel3'=>['label'=>'Outras despesas','active'=>false,'tam'=>'12','script'=>'guias.despesas','type'=>'html','class_div'=>'','dados'=>$arr_Paineldespesas],
                'config[qtdProcedimentos]'=>['label'=>'quantidade de procedimentos','id'=>'qtde_procedimento','active'=>false,'placeholder'=>'','type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'3','cp_busca'=>'config][qtdProcedimentos','title'=>''],
                'config[qtdDespesas]'=>['label'=>'quantidade de despesas','id'=>'qtde_despesa','active'=>false,'placeholder'=>'','type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'3','cp_busca'=>'config][qtdDespesas','title'=>''],
                'config[qtdExecutantes]'=>['label'=>'quantidade de executantes','id'=>'qtde_executante','active'=>false,'placeholder'=>'','type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'3','cp_busca'=>'config][qtdExecutantes','title'=>''],
                'config[tabela_padrao]'=>['label'=>'tabela_padrao','id'=>'tabela_padrao','active'=>false,'placeholder'=>'','type'=>'hidden','exibe_busca'=>'d-block','event'=>' ','tam'=>'3','cp_busca'=>'config][tabela_padrao','title'=>'Valor total de todos os procedimentos realizados'],
                'config[valorProcedimentos]'=>['label'=>'54-Total de Procedimentos (R$)','id'=>'total_procedimentos','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'required ','tam'=>'3','cp_busca'=>'config][valorProcedimentos','title'=>'Valor total de todos os procedimentos realizados'],
                'config[valorDiarias]'=>['label'=>'55-Total de Di??rias (R$)','id'=>'total_diarias','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'','tam'=>'3','cp_busca'=>'config][valorDiarias','title'=>'Valor total das di??rias, considerando o valor de cada di??ria e a quantidade de di??rias cobradas'],
                'config[valorTaxasAlugueis]'=>['label'=>'56-Total de Taxas e Alugu??is (R$)','id'=>'total_taxas','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'','tam'=>'3','cp_busca'=>'config][valorTaxasAlugueis','title'=>'Valor total das taxas e alugu??is,  considerando o somat??rio de todas as taxas e alugu??is cobrados'],
                'config[valorMateriais]'=>['label'=>'57-Total de Materiais (R$)','active'=>false,'id'=>'total_materiais','placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'','tam'=>'3','cp_busca'=>'config][valorMateriais','title'=>'Valor total dos materiais, considerando o valor unit??rio de cada material e a quantidade utilizada.'],
                'config[valorOPME]'=>['label'=>'58-Total de OPME (R$)','active'=>false,'placeholder'=>'','id'=>'total_opme','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'','tam'=>'3','cp_busca'=>'config][valorOPME','title'=>'Preenchimento condicionado. Deve ser preenchido caso haja ??rtese, pr??tese ou material especial cobrado, conforme negocia????o entre as partes.'],
                'config[valorMedicamentos]'=>['label'=>'59-Total de Medicamentos (R$)','active'=>false,'id'=>'total_medicamentos','placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'','tam'=>'3','cp_busca'=>'config][valorMedicamentos','title'=>'Valor total de Medicamentos'],
                'config[valorGasesMedicinais]'=>['label'=>'60-Total de Gases Medicinais (R$)','id'=>'total_gases','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'','tam'=>'3','cp_busca'=>'config][valorGasesMedicinais','title'=>'Valor total de todos os procedimentos realizados'],
                'config[valorTotalGeral]'=>['label'=>'61-Total Geral (R$)','active'=>false,'placeholder'=>'','id'=>'total_geral','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'required ','tam'=>'3','cp_busca'=>'config][valorTotalGeral','title'=>'Somat??rio de todos os valores totais de procedimentos realizados e itens assistenciais utilizados'],
                'obs'=>['label'=>'65-Observa????o','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>'maxlenght=500','tam'=>'12'],

                /*
                'ativo'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'N??o']],
                */
            ];
        }
        if($this->url=='executantes'){
            return [
                'id'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'type'=>['label'=>'type','active'=>false,'type'=>'hidden','value'=>$this->url,'exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'nome'=>['label'=>'Nome do Guia Executante','active'=>true,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'','tam'=>'8'],
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
                'config[numero_conselho]'=>['label'=>'N?? no Conselho','active'=>true,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'','tam'=>'4'],
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
                    'label'=>'Especialidade / C??digo CBO',
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
                //'obs'=>['label'=>'Observa????o','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
                'ativo'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'N??o']],
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
            $title = 'GUIA DE RESUMO DE INTERNA????O - INCLUS??O';
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
            'numero_guia' => ['required','string','unique:guias'],
        ],[
            'numero_guia.required' => ['Numero da guia j?? cadastrado']
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
            if($key!='_method'&&$key!='_token'&&$key!='ac'&&$key!='ajax'&&$key!='procedimento'&&$key!='despesas'&&$key!='executante'){
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
                $ret = response()->json(['mens'=>'Registro n??o encontrado!','color'=>'danger','return'=>route($this->view.'.index')]);
            }else{
                $ret = redirect()->route($this->view.'.index',['mens'=>'Registro n??o encontrado!','color'=>'danger']);
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
    public function print($id = null)
    {
        $ret=false;
        if($id){
            $d = Guia::FindOrFail($id);
            if($d['config']){
                $d['config'] = Qlib::lib_json_array($d['config']);
                $ret['dados'] = $d;
                //$ret['arr_peradoras'] = Qlib::sql_array("SELECT id,nome,registro,config FROM operadoras WHERE ativo='s'",'registro','id','nome',' | ','encode');
                $dados_operadora = Operadora::Find(@$d['config']['op_id']);
                $ret['dados_operadora'] = $dados_operadora;
            }
        }
        return view('guias.print', $ret);
    }
    public function printAnexo($id = null)
    {
        $ret=false;
        if($id){
            $d = Guia::FindOrFail($id);
            if($d['config']){
                $d['config'] = Qlib::lib_json_array($d['config']);
                $ret['dados'] = $d;
                //$ret['arr_peradoras'] = Qlib::sql_array("SELECT id,nome,registro,config FROM operadoras WHERE ativo='s'",'registro','id','nome',' | ','encode');
                $dados_operadora = Operadora::Find(@$d['config']['op_id']);
                $ret['dados_operadora'] = $dados_operadora;
            }
        }
        return view('guias.print_despesas', $ret);
    }
}
