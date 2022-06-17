<?php

namespace App\Http\Controllers;

use App\Models\Familia;
use App\Models\User;
use Illuminate\Http\Request;

use stdClass;
use App\Http\Requests\StoreFamilyRequest;
use App\Qlib\Qlib;
use Illuminate\Support\Str;
use App\Exports\FamiliasExport;
use App\Exports\SocialExportView;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Exports\UsersExport;
use App\Models\Bairro;
use App\Models\Etapa;
use App\Models\Tag;
use DataTables;
use Illuminate\Support\Facades\Auth;

class RelatoriosController extends Controller
{
    protected $user;
    public $routa;
    public $view;
    public $tab;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
        $this->routa = 'familias';
        $this->label = 'Familia';
        $this->view = 'relatorios';
        $this->tab = $this->routa;
    }
    public function realidadeSocial($config = null)
    {

        $this->authorize('ler', $this->routa);
        $title = 'Relatório de realidade social';
        $titulo = $title;
        $queryFamilias = $this->queryFamilias($_GET);
        $queryFamilias['config']['exibe'] = 'html';
        $routa = $this->routa;
        $view = 'familias.export.tabela';
        $view = $this->view;
        $ret = [
            'dados'=>$queryFamilias['familia'],
            'familias'=>$queryFamilias['familia'],
            'title'=>$title,
            'titulo'=>$titulo,
            'campos_tabela'=>$queryFamilias['campos'],
            'familia_totais'=>$queryFamilias['familia_totais'],
            'titulo_tabela'=>$queryFamilias['tituloTabela'],
            'arr_titulo'=>$queryFamilias['arr_titulo'],
            'config'=>$queryFamilias['config'],
            'routa'=>$routa,
            'redirect'=>'relatorios',
            'view'=>$this->view,
            'i'=>0,
        ];
        return view($view.'.social',$ret);
    }
    public function queryFamilias($get=false,$config=false)
    {
        $ret = false;
        $get = isset($_GET) ? $_GET:[];
        $ano = date('Y');
        $mes = date('m');
        $idUltimaEtapa = Etapa::where('ativo','=','s')->where('excluido','=','n')->where('deletado','=','n')->max('id');
        $tags = Tag::where('ativo','=','s')->where('pai','=','1')->where('excluido','=','n')->where('deletado','=','n')->get();
        $id_pendencia = 3;
        $id_imComRegistro = 4;
        $id_recusas = 5;
        $id_nLocalizado = 6;
        $completos = 0;
        $pendentes = 0;
        $etapas = Etapa::where('ativo','=','s')->where('excluido','=','n')->OrderBy('id','asc')->get();
        //$todasFamilias = Familia::where('excluido','=','n')->where('deletado','=','n');
        $config = [
            'limit'=>isset($get['limit']) ? $get['limit']: 50,
            'order'=>isset($get['order']) ? $get['order']: 'desc',
            'quadra'=>isset($get['filter']['quadra']) ? $get['filter']['quadra']: 'desc',
        ];

        DB::enableQueryLog();
        $familia =  Familia::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);
        $countFam =  Familia::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);

        //$familia =  DB::table('familias')->where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);

        $familia_totais = new stdClass;
        $campos = isset($_SESSION['campos_familias_exibe']) ? $_SESSION['campos_familias_exibe'] : $this->campos();
        $tituloTabela = 'Lista de todos cadastros';
        $arr_titulo = false;

        if(isset($get['filter'])){
                $titulo_tab = false;
                $i = 0;
                foreach ($get['filter'] as $key => $value) {
                    if(!empty($value)){
                        if($key=='id'){
                            $familia->where($key,'LIKE', $value);
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                            $arr_titulo[$campos[$key]['label']] = $value;
                        }elseif(is_array($value)){
                            foreach ($value as $kb => $vb) {
                                if(!empty($vb)){
                                    if($key=='tags'){
                                        $familia->where($key,'LIKE', '%"'.$vb.'"%' );
                                    }else{
                                        $familia->where($key,'LIKE', '%"'.$kb.'":"'.$vb.'"%' );
                                    }
                                }
                            }
                        }else{
                            $familia->where($key,'LIKE','%'. $value. '%');
                            if(isset($campos[$key]['type']) && $campos[$key]['type']=='select'){
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
                $fm = $familia;
                if($config['limit']=='todos'){
                    $familia = $familia->get();
                }else{
                    $familia = $familia->paginate($config['limit']);
                }
                //$query = DB::getQueryLog();
                //$query = end($query);
                //dd($query);

                if($idUltimaEtapa)
                $completos = $familia->where('etapa','=',$idUltimaEtapa)->count();
                $pendentes = $familia->where('tags','LIKE','%"'.$id_pendencia.'"')->count();
                $familia_totais->todos = $fm->count();
                $familia_totais->esteMes = $fm->whereYear('created_at', '=', $ano)->whereMonth('created_at','=',$mes)->count();
                $familia_totais->idoso = $fm->where('idoso','=','s')->count();
                $familia_totais->criancas = $fm->where('crianca_adolescente','=','s')->count();
        }else{
            $fm = $familia;
            if($idUltimaEtapa){
                $completos = $countFam->where('etapa','=',$idUltimaEtapa)->count();
            }

            if($config['limit']=='todos'){
                $familia = $familia->get();
            }else{
                $familia = $familia->paginate($config['limit']);
            }
            $familia_totais->todos = $fm->count();
            $familia_totais->esteMes = $fm->whereYear('created_at', '=', $ano)->whereMonth('created_at','=',$mes)->count();
            $familia_totais->idoso = $fm->where('idoso','=','s')->count();
            $familia_totais->criancas = $fm->where('crianca_adolescente','=','s')->count();
        }
        $progresso = [];
        if($etapas){
            foreach ($etapas as $key => $value) {
                $progresso[$key]['label'] = $value['nome'];
                $progresso[$key]['total'] = Familia::where('etapa','=',$value['id'])->where('excluido','=','n')->where('deletado','=','n')->count();
                $progresso[$key]['geral'] = $familia_totais->todos;
                if($progresso[$key]['total']>0 && $progresso[$key]['geral'] >0){
                    $porceto = round($progresso[$key]['total']*100/$progresso[$key]['geral'],2);
                }else{
                    $porceto = 0;
                }
                $progresso[$key]['porcento'] = $porceto;
                $progresso[$key]['color'] = $this->colorPorcento($porceto);
            }
        }
        $familia_totais->completos = $completos;
        $colTabela = $this->colTabela($familia);
        $ret['familia'] = $colTabela;
        $ret['familia_totais'] = $familia_totais;
        $ret['arr_titulo'] = $arr_titulo;
        $ret['campos'] = $campos;
        $ret['config'] = $config;
        $ret['tituloTabela'] = $tituloTabela;
        $ret['progresso'] = $progresso;
        $ret['link_completos'] = route('familias.index').'?filter[etapa]='.$idUltimaEtapa;
        $ret['link_idosos'] = route('familias.index').'?filter[idoso]=s';
        $cardTags = [];
        $ret['cards_home'] = [
            [
                'label'=>'Lotes cadastrados',
                'valor'=>$familia_totais->todos,
                'href'=>route('familias.index'),
                'icon'=>'fa fa-map-marked-alt',
                'lg'=>'2',
                'xs'=>'6',
                'color'=>'info',
            ],
            [
                'label'=>'Cadastros completos',
                'valor'=>$familia_totais->completos,
                'href'=>$ret['link_completos'],
                'icon'=>'fa fa-check',
                'lg'=>'2',
                'xs'=>'6',
                'color'=>'success',
            ],
        ];
        if(!empty($tags)){
            foreach ($tags as $kt => $vt) {
                $countFamTag =  Familia::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order'])->where('tags','LIKE','%"'.$vt['id'].'"%')->count();
                $cardTags[$vt['id']] =
                [
                    'label'=>$vt['nome'],
                    'valor'=>$countFamTag,
                    'href'=>route('familias.index').'?filter[tags][]='.$vt['id'],
                    'icon'=>$vt['config']['icon'],
                    'lg'=>'2',
                    'xs'=>'6',
                    'color'=>$vt['config']['color'],
                ];
                array_push($ret['cards_home'],$cardTags[$vt['id']]);
            }
        }

        $ret['config']['acao_massa'] = [
            ['link'=>'#edit_etapa','event'=>'edit_etapa','icon'=>'fa fa-pencil','label'=>'Editar etapa'],
        ];
        return $ret;
    }
    public function colTabela($familia = null,$campos=false)
    {
        $ret = false;
        if($familia){
            if(!$campos){
                $campos = $this->campos();
            }
            foreach ($familia as $k1 => $v1) {
                foreach ($campos as $k2 => $v2) {
                    if($v2['type']=='text'){
                        if($k2=='lote' && isset($v1['loteamento'])){
                            if(is_array($v1['loteamento'])){
                                foreach ($v1['loteamento'] as $kl => $lote) {
                                    $loteN = Qlib::buscaValorDb([
                                        'tab'=>'lotes',
                                        'campos_bus'=>'id',
                                        'valor'=>$lote,
                                        'select'=>'nome',
                                    ]);

                                    $loteN = $loteN.$v1['complemento_lote'].',';
                                    $familia[$k1][$k2] .= $loteN;
                                }
                                $familia[$k1][$k2] = substr($familia[$k1][$k2], 0, -1);
                            }
                        }
                        if(($k2=='nome' || $k2=='cpf'  || $k2=='cpf_conjuge' || $k2=='escolaridade') && (isset($v1[$v2['valor']]))){
                            $familia[$k1][$k2] = Qlib::buscaValorDb([
                                'tab'=>$v2['tab'],
                                'campos_bus'=>'id',
                                'valor'=>$v1[$v2['valor']],
                                'select'=>$v2['select'],
                            ]);
                        }
                    }elseif($v2['type']=='array' && isset($v1[$v2['valor']])){
                        $familia[$k1][$k2] = Qlib::buscaValorDb([
                            'tab'=>$v2['tab'],
                            'campos_bus'=>'id',
                            'valor'=>$v1[$v2['valor']],
                            'select'=>$v2['select'],
                        ]);
                    }elseif($v2['type']=='chave_checkbox' && isset($v2['arr_opc'])){
                        $familia[$k1][$k2] = $v2['arr_opc'][$v1[$k2]];
                    }elseif($v2['type']=='json'){
                        if((isset($v2['cp_b']))){
                            $ab = explode('][',$v2['cp_b']);
                            if($ab[1]){
                                $valor = Qlib::buscaValorDb([
                                    'tab'=>$v2['tab'],
                                    'campos_bus'=>'id',
                                    'valor'=>$v1[$v2['valor']],
                                    'select'=>$v2['select'],
                                ]);

                                if(Qlib::isJson($valor)){
                                    $valor = Qlib::lib_json_array($valor);
                                }
                                $value = @$valor[$ab[1]];
                                if($ab[1]=='telefone' && !empty(@$valor['telefone2'])){
                                    $value .= ', '.$valor['telefone2'];
                                }
                                if($k2=='escolaridades' || $k2=='estadocivils'){
                                    $value = Qlib::buscaValorDb([
                                        'tab'=>$k2,
                                        'campos_bus'=>'id',
                                        'valor'=>$value,
                                        'select'=>'nome',
                                    ]);
                                }
                                $familia[$k1][$k2] = $value;
                            }
                        }
                    }
                }
            }
            $ret = $familia;
        }
        return $ret;
    }
    public function colorPorcento($val=0){
        $ret = 'bg-danger';
        if($val<=25){
            $ret = 'bg-danger';
        }elseif($val > 25 && $val <= 50){
            $ret = 'bg-warning';
        }elseif($val > 50 && $val <= 85){
            $ret = 'bg-primary';
        }elseif($val > 85){
            $ret = 'bg-success';
        }
        return $ret;
    }
    public function index(User $user)
    {
        //$this->authorize('is_admin', $user);
        $this->authorize('ler', $this->routa);
        $title = 'Relatório de realidade social';
        $titulo = $title;
        $queryFamilias = $this->queryFamilias($_GET);
        $queryFamilias['config']['exibe'] = 'html';
        $routa = $this->routa;
        $ret = [
            'dados'=>$queryFamilias['familia'],
            'familias'=>$queryFamilias['familia'],
            'title'=>$title,
            'titulo'=>$titulo,
            'campos_tabela'=>$queryFamilias['campos'],
            'familia_totais'=>$queryFamilias['familia_totais'],
            'titulo_tabela'=>$queryFamilias['tituloTabela'],
            'arr_titulo'=>$queryFamilias['arr_titulo'],
            'config'=>$queryFamilias['config'],
            'routa'=>$routa,
            'view'=>$this->view,
            'i'=>0,
        ];
        return view($routa.'.index',$ret);
    }
    public function exportAll(User $user)
    {
        $this->authorize('is_admin', $user);
        return Excel::download(new FamiliasExport, 'Familias_'.date('d_m_Y').'.xlsx');
    }
    public function exportFilter(User $user)
    {
        $this->authorize('is_admin', $user);
        $dados = new SocialExportView;
        //return $dados->view();
        return Excel::download(new SocialExportView, 'Realidade_social_'.date('d_m_Y').'.xlsx');
    }
    public function campos(){
        $user = Auth::user();
        $etapa = new EtapaController($user);
        $bairro = new BairroController($user);
        $beneficiario = new BeneficiariosController($user);
        $escolaridade = new EscolaridadeController($user);
        $estadocivil = new EstadocivilController($user);
        $lote = new LotesController($user);
        return [
            //'id'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'3','placeholder'=>''],
            'bairro'=>[
                'label'=>'ÁREA',
                'active'=>true,
                'type'=>'select',
                'data_selector'=>[
                    'campos'=>$bairro->campos(),
                    'route_index'=>route('bairros.index'),
                    'id_form'=>'frm-bairros',
                    'action'=>route('bairros.store'),
                    'campo_id'=>'id',
                    'campo_bus'=>'nome',
                    'label'=>'Etapa',
                ],'arr_opc'=>Qlib::sql_array("SELECT id,nome FROM bairros WHERE ativo='s'",'nome','id'),'exibe_busca'=>'d-block',
                //'event'=>'onchange=lib_abrirModalConsultaVinculo(\'loteamento\',\'fechar\');carregaMatricula($(this))',
                'event'=>'',
                'tam'=>'6',
                'class'=>'select2'
            ],
            'quadra'=>['label'=>'QUADRA','active'=>true,'type'=>'array','exibe_busca'=>'d-block','tam'=>'4','tab'=>'quadras','valor'=>'quadra','select'=>'nome'],
            'matricula'=>['label'=>'MATRÍCULA','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'4','placeholder'=>''],
            'etapa'=>[
                'label'=>'Etapa',
                'active'=>true,
                'type'=>'select',
                'data_selector'=>[
                    'campos'=>$etapa->campos(),
                    'route_index'=>route('etapas.index'),
                    'id_form'=>'frm-etapas',
                    'action'=>route('etapas.store'),
                    'campo_id'=>'id',
                    'campo_bus'=>'nome',
                    'label'=>'Etapa',
                ],'arr_opc'=>Qlib::sql_array("SELECT id,nome FROM etapas WHERE ativo='s'",'nome','id'),'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'6',
                'value'=>@$_GET['etapa'],
            ],
            'tags[]'=>[
                'label'=>'SITUAÇÃO',
                'active'=>true,
                'type'=>'select_multiple',
                'arr_opc'=>Qlib::sql_array("SELECT id,nome FROM tags WHERE ativo='s' AND pai='1'",'nome','id'),'exibe_busca'=>'d-block',
                'event'=>'',
                'class'=>'',
                'option_select'=>false,
                'tam'=>'12',
                'cp_busca'=>'tags]['
            ],
            'lote'=>['label'=>'LOTE','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'4','placeholder'=>''],
            'nome'=>['label'=>'NOME COMPLETO','active'=>true,'type'=>'text','exibe_busca'=>'d-block','tam'=>'4','tab'=>'beneficiarios','valor'=>'id_beneficiario','select'=>'nome'],
            'id_beneficiario'=>['label'=>'','active'=>false,'type'=>'hidden','exibe_busca'=>'d-none','tam'=>'4','tab'=>'beneficiarios','valor'=>'id_beneficiario','select'=>'nome'],
            'cpf'=>['label'=>'CPF','active'=>true,'type'=>'text','exibe_busca'=>'d-block','tam'=>'4','tab'=>'beneficiarios','valor'=>'id_beneficiario','select'=>'cpf'],
            'telefone'=>['label'=>'TELEFONE','active'=>true,'type'=>'json','exibe_busca'=>'d-block','tam'=>'4','tab'=>'beneficiarios','valor'=>'id_beneficiario','select'=>'config','cp_b'=>'config][telefone'],
            'escolaridades'=>['label'=>'ESCOLARDADE','active'=>true,'type'=>'json','exibe_busca'=>'d-block','tam'=>'4','tab'=>'beneficiarios','valor'=>'id_beneficiario','select'=>'config','cp_b'=>'config][escolaridade'],
            'estadocivils'=>['label'=>'ESTADO CIVIL','active'=>true,'type'=>'json','exibe_busca'=>'d-block','tam'=>'4','tab'=>'beneficiarios','valor'=>'id_beneficiario','select'=>'config','cp_b'=>'config][estado_civil'],
            'profissao'=>['label'=>'SITUAÇÃO PROFISSIONAL','active'=>true,'type'=>'json','exibe_busca'=>'d-block','tam'=>'4','tab'=>'beneficiarios','valor'=>'id_beneficiario','select'=>'config','cp_b'=>'config][profissao'],
            'config[registro]'=>['label'=>'REGISTRO','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'3','placeholder'=>'','cp_busca'=>'config][registro'],
            'config[livro]'=>['label'=>'Livro','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'3','placeholder'=>'','cp_busca'=>'config][livro'],
            //'situacao_profissional'=>['label'=>'Situação Profissional','type'=>'text','active'=>true,'exibe_busca'=>'d-block','event'=>'','tam'=>'4'],
            'bcp_bolsa_familia'=>['label'=>'BPC OU BOLSA FAMÍLIA','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'3'],
            'renda_familiar'=>['label'=>'Renda Fam.','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'3','class'=>''],
            'doc_imovel'=>['label'=>'DOC IMÓVEL','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'6'],
            'qtd_membros'=>['label'=>'MEMBROS','active'=>true,'type'=>'number','exibe_busca'=>'d-block','event'=>'','tam'=>'6'],
            'idoso'=>['label'=>'IDOSO','active'=>true,'type'=>'chave_checkbox','value'=>'s','exibe_busca'=>'d-none','event'=>'','tam'=>'6','arr_opc'=>['s'=>'Sim','n'=>'Não']],
            'crianca_adolescente'=>['label'=>'CRIANÇA E ADOLESCENTE','active'=>true,'exibe_busca'=>'d-none','event'=>'','type'=>'chave_checkbox','value'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'6','arr_opc'=>['s'=>'Sim','n'=>'Não']],
            'nome_conjuge'=>['label'=>'NOME DO CÔNJUGE','active'=>true,'type'=>'array','exibe_busca'=>'d-block','tam'=>'4','tab'=>'beneficiarios','valor'=>'id_conjuge','select'=>'nome'],
            'cpf_conjuge'=>['label'=>'CPF','active'=>true,'type'=>'text','exibe_busca'=>'d-block','tam'=>'4','tab'=>'beneficiarios','valor'=>'id_conjuge','select'=>'cpf'],

            'obs'=>['label'=>'OBSERVAÇÕES','active'=>true,'type'=>'textarea','exibe_busca'=>'d-block','event'=>'','rows'=>'4','cols'=>'80','tam'=>'12'],
        ];
    }
}
