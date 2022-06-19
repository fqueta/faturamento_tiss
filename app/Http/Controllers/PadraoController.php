<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use App\Models\Padrao;
use App\Qlib\Qlib;
use App\Models\User;
use App\Models\_upload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PadraoController extends Controller
{
    protected $user;
    public $routa;
    public $label;
    public $view;
    public $url;
    public $sec;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $seg1 = request()->segment(1);
        $seg2 = request()->segment(2);
        $type = false;
        if($seg1){
            $type = substr($seg1,0,-1);
        }
        $this->user = $user;
        $this->routa = $seg1;
        $this->sec = $seg1;
        $this->tab = $seg1;
        $this->label = ucfirst($this->sec);
        //$dadosMenu = DB::table('menus')->where('url','=',$this->sec)->get();
        //dd($dadosMenu);
        $this->view = 'padrao';
        $this->url = $seg1;
    }
    public function queryPadrao($get=false,$config=false)
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

        $profissional =  DB::table($this->tab)->where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);
        //$profissional =  DB::table('profissionals')->where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);

        $profissional_totais = new stdClass;
        $campos = isset($_SESSION['campos_profissionals_exibe']) ? $_SESSION['campos_profissionals_exibe'] : $this->campos();
        $tituloTabela = 'Lista de todos cadastros';
        $arr_titulo = false;
        if(isset($get['filter'])){
                $titulo_tab = false;
                $i = 0;
                foreach ($get['filter'] as $key => $value) {
                    if(!empty($value)){
                        if($key=='id'){
                            $profissional->where($key,'LIKE', $value);
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                            $arr_titulo[$campos[$key]['label']] = $value;
                        }else{
                            $profissional->where($key,'LIKE','%'. $value. '%');
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
                $fm = $profissional;
                if($config['limit']=='todos'){
                    $profissional = $profissional->get();
                }else{
                    $profissional = $profissional->paginate($config['limit']);
                }
        }else{
            $fm = $profissional;
            if($config['limit']=='todos'){
                $profissional = $profissional->get();
            }else{
                $profissional = $profissional->paginate($config['limit']);
            }
        }
        $profissional_totais->todos = $fm->count();
        $profissional_totais->esteMes = $fm->whereYear('created_at', '=', $ano)->whereMonth('created_at','=',$mes)->get()->count();
        $profissional_totais->ativos = $fm->where('ativo','=','s')->get()->count();
        $profissional_totais->inativos = $fm->where('ativo','=','n')->get()->count();

        $ret['profissional'] = $profissional;
        $ret['profissional_totais'] = $profissional_totais;
        $ret['arr_titulo'] = $arr_titulo;
        $ret['campos'] = $campos;
        $ret['config'] = $config;
        $ret['tituloTabela'] = $tituloTabela;
        $ret['config']['resumo'] = [
            'todos_registro'=>['label'=>'Todos cadastros','value'=>$profissional_totais->todos,'icon'=>'fas fa-calendar'],
            'todos_mes'=>['label'=>'Cadastros recentes','value'=>$profissional_totais->esteMes,'icon'=>'fas fa-calendar-times'],
            'todos_ativos'=>['label'=>'Cadastros ativos','value'=>$profissional_totais->ativos,'icon'=>'fas fa-check'],
            'todos_inativos'=>['label'=>'Cadastros inativos','value'=>$profissional_totais->inativos,'icon'=>'fas fa-archive'],
        ];
        return $ret;
    }
    public function campos($sec=false){
        $sec = $sec?$sec:trim($this->sec);

        if($sec=='operadoras'){
            return [
                'id'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'registro'=>['label'=>'Nº do Registro ANS','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'4'],
                'nome'=>['label'=>'Nome da Operadora de Saúde','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'8'],
                'config[identidade]'=>['label'=>'Sua Identificação nesta Operadora de Saúde','active'=>false,'type'=>'text','tam'=>'6','exibe_busca'=>'d-block','event'=>'','cp_busca'=>'config][identidade'],
                'config[versao_tiss]'=>[
                    'label'=>'Versão do Padrão TISS',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>Qlib::sql_array("SELECT value,nome FROM tags WHERE ativo='s' AND pai='1'",'nome','value'),'exibe_busca'=>'d-block',
                    'event'=>'',
                    'tam'=>'6',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>false,
                    'cp_busca'=>'config][versao_tiss',
                ],
                'config[tabela_cobranca]'=>[
                    'label'=>'Tabela Padrão de Cobrança(opcional)',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>Qlib::sql_array("SELECT value,nome FROM tags WHERE ativo='s' AND pai='4'",'nome','value'),'exibe_busca'=>'d-block',
                    'event'=>'',
                    'tam'=>'12',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][tabela_cobranca',
                ],
                //'obs'=>['label'=>'Observação','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
                'ativo'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],
            ];
        }else{
            return [
                'id'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'nome'=>['label'=>'Nome','active'=>true,'placeholder'=>'Ex.: Cadastrado','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
                'ordem'=>['label'=>'Ordenar','active'=>true,'type'=>'number','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'obs'=>['label'=>'Observação','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
                'ativo'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],
            ];
        }
    }
    public function index(User $user)
    {
        $this->authorize('ler', $this->url);
        $title = $this->label;
        $titulo = $title;
        $queryPadrao = $this->queryPadrao($_GET);
        $queryPadrao['config']['exibe'] = 'html';
        $routa = $this->routa;
        return view($this->view.'.index',[
            'dados'=>$queryPadrao['profissional'],
            'title'=>$title,
            'titulo'=>$titulo,
            'campos_tabela'=>$queryPadrao['campos'],
            'profissional_totais'=>$queryPadrao['profissional_totais'],
            'titulo_tabela'=>$queryPadrao['tituloTabela'],
            'arr_titulo'=>$queryPadrao['arr_titulo'],
            'config'=>$queryPadrao['config'],
            'routa'=>$routa,
            'view'=>$this->view,
            'i'=>0,
        ]);
    }
    public function create(User $user)
    {
        $this->authorize('create', $this->url);
        $title = 'Cadastrar profissional';
        $titulo = $title;
        $config = [
            'ac'=>'cad',
            'frm_id'=>'frm-profissionals',
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
            'nome' => ['required','string','unique:profissionals'],
        ]);
        $dados = $request->all();
        $data = $this->sanitize($dados);
        $ajax = isset($dados['ajax'])?$dados['ajax']:'n';
        $dados['ativo'] = isset($dados['ativo'])?$dados['ativo']:'n';
        $salvar = DB::table($this->tab)->insertGetId($data);
        $id = $salvar;
        $route = $this->routa.'.index';
        $ret = [
            'mens'=>$this->label.' cadastrada com sucesso!',
            'color'=>'success',
            'idCad'=>$id,
            'exec'=>true,
            'dados'=>$dados
        ];

        if($ajax=='s'){
            $ret['return'] = route($route).'?idCad='.$id;
            $ret['redirect'] = route($this->routa.'.edit',['id'=>$id]);
            return response()->json($ret);
        }else{
            return redirect()->route($route,$ret);
        }
    }
    public function sanitize($dados = null)
    {
        $dt=[];
        if(is_array($dados)){
            foreach($dados As $k0=>$v0){
                if($k0!='_token'&&$k0!='ajax'){
                    if(is_array($v0)){
                        $dt[$k0]=Qlib::lib_array_json($v0);
                    }else{
                        $dt[$k0]=$v0;
                    }
                }
            }
        }
        return $dt;
    }
    public function show($id)
    {
        //
    }

    public function edit($profissional,User $user)
    {
        $id = $profissional;
        $dt = DB::table($this->tab)->where('id','=',$id)->get();
        $routa = 'profissionals';
        $this->authorize('ler', $this->url);
        if(!empty($dt)){
            $dad = $dt->toArray();
            $dados[0] = (array)$dad[0];
            $title = 'Editar Cadastro de profissionals';
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
                'frm_id'=>'frm-profissionals',
                'route'=>$this->routa,
                'id'=>$id,
            ];
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
            return redirect()->route($this->routa.'.index',$ret);
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
            if($key!='_method'&&$key!='_token'&&$key!='ac'&&$key!='ajax'){
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
            $atualizar=DB::table($this->tab)->where('id',$id)->update($data);
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
        $routa = 'profissionals';
        if (!$post = DB::table($this->tab)->find($id)){
            if($ajax=='s'){
                $ret = response()->json(['mens'=>'Registro não encontrado!','color'=>'danger','return'=>route($this->view.'.index')]);
            }else{
                $ret = redirect()->route($this->view.'.index',['mens'=>'Registro não encontrado!','color'=>'danger']);
            }
            return $ret;
        }

        DB::table($this->tab)->where('id',$id)->delete();
        if($ajax=='s'){
            $ret = response()->json(['mens'=>__('Registro '.$id.' deletado com sucesso!'),'color'=>'success','return'=>route($this->routa.'.index')]);
        }else{
            $ret = redirect()->route($routa.'.index',['mens'=>'Registro deletado com sucesso!','color'=>'success']);
        }
        return $ret;
    }
}
