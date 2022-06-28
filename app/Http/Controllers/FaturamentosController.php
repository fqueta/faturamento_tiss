<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Qlib\Qlib;
use App\Models\User;
use App\Models\_upload;
use App\Models\Guia;
use App\Models\Lote;
use Illuminate\Support\Facades\Auth;
use stdClass;

class FaturamentosController extends Controller
{
    protected $user;
    public $routa;
    public $view;
    public $tab;
    public $type;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
        $this->routa = 'faturamentos';
        $this->label = 'Faturamento';
        $this->type = isset($_GET['filter']['type'])?$_GET['filter']['type']:false;
        $this->view = $this->routa;
        $this->tab = $this->routa;
        $seg1 = request()->segment(1);
        $seg2 = request()->segment(2);
        $type = false;
        $this->url      = $seg2;
        if($seg1){
            $type = substr($seg1,0,-1);
        }

    }
    public function campos()
    {
        $operadoras = [
            'label'=>'Operadora',
            'active'=>false,
            'type'=>'select',
            'arr_opc'=>Qlib::sql_array("SELECT id,nome,registro,config FROM operadoras WHERE ativo='s'",'registro','id','nome',' | '),'exibe_busca'=>'d-block',
            'event'=>'',
            'tam'=>'4',
            'class'=>'',
            'campo'=>'filter[select_operadora]',
            'exibe_busca'=>true,
            'option_select'=>true,
            'title'=>'',
            'id'=>'select_operadora',
            'cp_busca'=>'',
        ];
        $tipo_guia = [
            'label'=>'Tipo de Guia',
            'active'=>false,
            'type'=>'select',
            'arr_opc'=>Qlib::sql_array("SELECT value,nome FROM tags WHERE ativo='s' AND pai='6'",'nome','value'),
            'exibe_busca'=>'d-block',
            'event'=>'',
            'tam'=>'4',
            'class'=>'',
            'campo'=>'filter[type]',
            'exibe_busca'=>true,
            'option_select'=>true,
            'title'=>'',
            'id'=>'type',
            'cp_busca'=>'',
        ];
        return [
            'op_id'=>$operadoras,
            'type' =>$tipo_guia,
            //'dataI'=>['label'=>'Data inicial','active'=>false,'placeholder'=>'','type'=>'date','id'=>'procedimento_alimentador_data','exibe_busca'=>'d-block','event'=>'onfocus=this.style.backgroundColor=\'#ffffff\'','tam'=>'2','cp_busca'=>'','title'=>''],
        ];
    }

    public function fechar(Request $request)
    {
        $title = __('Fechamentos de guias');
        $titulo = $title;
        //$GuiasController = new GuiasController($this->user);

        $config = [
            'campos_busca'=>$this->campos(),
            'ac' =>'alt',
        ];
        $this->authorize('ler', $this->url);
        $title = 'Guias Cadastradas';
        $titulo = $title;
        $queryGuia = $this->queryGuia($_GET);
        $queryGuia['config']['exibe'] = 'html';
        $routa = $this->routa;
        // return view($this->view.'.index',[
        //     'title'=>$title,
        //     'titulo'=>$titulo,
        //     'campos_tabela'=>$queryGuia['campos'],
        //     'guia_totais'=>$queryGuia['guia_totais'],
        //     'titulo_tabela'=>$queryGuia['tituloTabela'],
        //     'arr_titulo'=>$queryGuia['arr_titulo'],
        //     'config'=>$queryGuia['config'],
        //     'routa'=>$routa,
        //     'view'=>$this->view,
        //     'i'=>0,
        // ]);
        $ret = [
            'title'=>$title,
            'titulo'=>$titulo,
            'config'=>$config,
            'view'=>$this->view,
            'guias'=>$queryGuia['guia'],

        ];
        return view('faturamento.fechar',$ret);
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
        if($this->type){
            $guia =  Guia::where('excluido','=','n')->where('type','=',$this->type)->where('deletado','=','n')->orderBy('id',$config['order']);
        }else{
            $guia =  Guia::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);
        }
        //$guia =  Guia::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);
        //$guia =  DB::table('guias')->where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);
        $guia_totais = new stdClass;
        $campos = $this->campos();
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
                        }elseif($key=='op_id'){

                            $guia->where('config','LIKE', '%"'.$key.'":"'.$value.'"%');
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
    public function gerarLote($ids = null)
    {
        $ret['exec'] = false;
        $ret['mes'] = 'Erro ao Gerar lote';
        if($ids){
            $arr_id = explode('_',$ids);
            if(is_array($arr_id)){
                $ac = new GeradorXmlController();
                $mes = isset($_POST['mes'])?$_POST['mes']:date('m');
                $ano = isset($_POST['ano'])?$_POST['ano']:date('Y');
                $token = isset($_POST['token'])?$_POST['token']:uniqid();
                $type = isset($_POST['type'])?$_POST['type']:'internaçao';
                $nome = isset($_POST['nome'])?$_POST['nome']:'Lote salvo por '.$this->user->nome.' em '.date('d/m/Y');
                $salvarLote = Lote::create([
                    'mes'=>$mes,
                    'ano'=>$ano,
                    'token'=>$token,
                    'nome'=>$nome,
                    'type'=>$type,
                ]);
                if(isset($salvarLote->id)){
                    $geraGuia = $ac->guiaResumoInternacao($arr_id,$salvarLote->id);
                    $ret['geraGuia'] = $geraGuia;
                    $ret['ids'] = $ids;
                    if($geraGuia['exec']){
                        foreach ($arr_id as $kg => $vg) {
                            $ret['upd_gia'][$kg] = Guia::where('id',$vg)->update([
                                'id_lote'=>$vg,
                                'lote'=>'s',
                            ]);
                        }
                        $alte_lote = Lote::where('id',$salvarLote->id)->update([
                            'config'=>Qlib::lib_array_json($ret),
                        ]);
                        $ret['exec'] = true;
                        $ret['mes'] = 'Arquivo de lote gerado com sucesso!!';
                    }
                    $ret['alte_lote'] = $alte_lote;

                }
            }
            return $ret;
        }
        return $ret;
    }
}
