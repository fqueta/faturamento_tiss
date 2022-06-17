<?php

namespace App\Http\Controllers\admin;

use stdClass;
use App\Http\Controllers\Controller;
use App\Models\_upload;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Qlib\Qlib;
use App\Models\User;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class UserPermissions extends Controller
{
    protected $user;
    public $routa;
    public $label;
    public $view;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
        $this->routa = 'permissions';
        $this->label = 'Permissões';
        $this->view = 'padrao';
    }
    public function queryPermissions($get=false,$config=false)
    {
        $ret = false;
        $get = isset($_GET) ? $_GET:[];
        $ano = date('Y');
        $mes = date('m');
        $config = [
            'limit'=>isset($get['limit']) ? $get['limit']: 50,
            'order'=>isset($get['order']) ? $get['order']: 'desc',
        ];

        $logado = Auth::user();
        $permission =  Permission::where('id','>=',$logado->id_permission)->orderBy('id',$config['order']);
        //$permission =  DB::table('permissions')->where('active','s')->orderBy('id',$config['order']);

        $permissions = new stdClass;
        $campos = isset($_SESSION['campos_permissions_exibe']) ? $_SESSION['campos_permissions_exibe'] : $this->campos();
        $tituloTabela = 'Lista de todos cadastros';
        $arr_titulo = false;
        if(isset($get['filter'])){
                $titulo_tab = false;
                $i = 0;
                foreach ($get['filter'] as $key => $value) {
                    if(!empty($value)){
                        if($key=='id'){
                            $permission->where($key,'LIKE', $value);
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                            $arr_titulo[$campos[$key]['label']] = $value;
                        }else{
                            $permission->where($key,'LIKE','%'. $value. '%');
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
                }
                $fm = $permission;
                if($config['limit']=='todos'){
                    $permission = $permission->get();
                }else{
                    $permission = $permission->paginate($config['limit']);
                }
        }else{
            $fm = $permission;
            if($config['limit']=='todos'){
                $permission = $permission->get();
            }else{
                $permission = $permission->paginate($config['limit']);
            }
        }
        $permissions->todos = $fm->count();
        $permissions->esteMes = $fm->whereYear('created_at', '=', $ano)->whereMonth('created_at','=',$mes)->get()->count();
        $permissions->ativos = $fm->where('active','=','s')->get()->count();
        $permissions->inativos = $fm->where('active','=','n')->get()->count();

        $ret['permission'] = $permission;
        $ret['etapa_totais'] = $permissions;
        $ret['arr_titulo'] = $arr_titulo;
        $ret['campos'] = $campos;
        $ret['config'] = $config;
        $ret['tituloTabela'] = $tituloTabela;
        $ret['config']['resumo'] = [
            'todos_registro'=>['label'=>'Todos cadastros','value'=>$permissions->todos,'icon'=>'fas fa-calendar'],
            'todos_mes'=>['label'=>'Cadastros recentes','value'=>$permissions->esteMes,'icon'=>'fas fa-calendar-times'],
            'todos_ativos'=>['label'=>'Cadastros ativos','value'=>$permissions->ativos,'icon'=>'fas fa-check'],
            'todos_inativos'=>['label'=>'Cadastros inativos','value'=>$permissions->inativos,'icon'=>'fas fa-archive'],
        ];
        return $ret;
    }
    public function campos($dados=false){
        return [
            'id'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'name'=>['label'=>'Nome da permissão','active'=>true,'placeholder'=>'Ex.: Cadastrados','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
            'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'active'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','checked'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],
            'redirect_login'=>['label'=>'link da pagina','active'=>false,'type'=>'text','exibe_busca'=>'d-none','event'=>'','tam'=>'12'],
            'description'=>['label'=>'Observação','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
            'id_menu'=>['label'=>'lista de Permissões','active'=>false,'type'=>'html','exibe_busca'=>'d-none','event'=>'','tam'=>'12','script'=>'permissions.check_permissao','dados'=>@$dados['id_menu']],

        ];
    }
    public function index(User $user)
    {
        $this->authorize('ler', $this->routa);
        $title = 'Permissões Cadastradas';
        $titulo = $title;
        $queryPermissions = $this->queryPermissions($_GET);
        $queryPermissions['config']['exibe'] = 'html';
        $routa = $this->routa;
        $view = $this->view;

        return view($routa.'.index',[
            'dados'=>$queryPermissions['permission'],
            'title'=>$title,
            'titulo'=>$titulo,
            'campos_tabela'=>$queryPermissions['campos'],
            'etapa_totais'=>$queryPermissions['etapa_totais'],
            'titulo_tabela'=>$queryPermissions['tituloTabela'],
            'arr_titulo'=>$queryPermissions['arr_titulo'],
            'config'=>$queryPermissions['config'],
            'routa'=>$routa,
            'view'=>$view,
            'i'=>0,
        ]);
    }
    public function create(User $user)
    {
        $this->authorize('is_admin', $user);
        $title = __('Cadastrar permissão');
        $titulo = $title;
        $config = [
            'ac'=>'cad',
            'frm_id'=>'frm-permissions',
            'route'=>$this->routa,
        ];
        $value = [
            'token'=>uniqid(),
        ];

        $arrMenus = $this->listMenusPermisson();

        $campos = $this->campos([
            'id_menu'=>@$arrMenus,
        ]);
        return view($this->routa.'.createedit',[
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
            'name' => ['required','string','unique:permissions'],
        ]);
        $dados = $request->all();
        $ajax = isset($dados['ajax'])?$dados['ajax']:'n';
        $dados['active'] = isset($dados['active'])?$dados['active']:'n';
        $dados['id_menu'] = isset($dados['id_menu'])?$dados['id_menu']:[];

        //dd($dados);
        $salvar = Permission::create($dados);
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
            return response()->json($ret);
        }else{
            return redirect()->route($route,$ret);
        }
    }

    public function show($id)
    {
        //
    }
    public function listMenusPermisson($var = null)
    {
        $arrMenus = [];
        $dadosMenus = Menu::where('actived',true)->where('pai','')->get();
        $roles = ['create'=>'Cadastrar','update'=>'Editar','delete'=>'Excluir','ler_arquivos'=>'Ver Arquivos',];
        if(count($dadosMenus)){
            foreach($dadosMenus as $k=>$v){
                $arrMenus[$k] = $v;
                $submen = Menu::where('actived',true)->where('pai',$v->url)->getQuery()->get();
                if(count($dadosMenus)){
                    $arrMenus[$k]['submenu'] = $submen->all();
                    $arrMenus[$k]['roles'] = $roles;
                }
            }
        }
        return $arrMenus;
    }
    public function edit($permission,User $user)
    {
        $this->authorize('is_admin');
        $this->authorize('update', $this->routa);
        $id = $permission;
        $dados = Permission::where('id',$id)->get();
        $routa = 'permissions';

        if(!empty($dados)){
            $title = 'Editar Cadastro de permissions';
            $titulo = $title;
            $dados[0]['ac'] = 'alt';
            if(isset($dados[0]['config'])){
                $dados[0]['config'] = Qlib::lib_json_array($dados[0]['config']);
            }
            $listFiles = false;
            if(isset($dados[0]['token'])){
                $listFiles = _upload::where('token_produto','=',$dados[0]['token'])->get();
            }
            $config = [
                'ac'=>'alt',
                'frm_id'=>'frm-permissions',
                'route'=>$this->routa,
                'id'=>$id,
            ];
            $arrMenus = $this->listMenusPermisson();

            $campos = $this->campos([
                'id_menu'=>@$arrMenus,
            ]);
            $ret = [
                'value'=>$dados[0],
                'config'=>$config,
                'title'=>$title,
                'titulo'=>$titulo,
                'listFiles'=>$listFiles,
                'campos'=>$campos,
                'arrMenus'=>$arrMenus,
                'exec'=>true,
            ];

            return view($routa.'.createedit',$ret);
        }else{
            $ret = [
                'exec'=>false,
            ];
            return redirect()->route($routa.'.index',$ret);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => ['required'],
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
        $data['active'] = isset($data['active'])?$data['active']:'n';
        $data['id_menu'] = isset($data['id_menu'])?$data['id_menu']:[];
        $data['autor'] = $userLogadon;
        if(isset($dados['config'])){
            $dados['config'] = Qlib::lib_array_json($dados['config']);
        }
        $atualizar=false;
        if(!empty($data)){
            $atualizar=Permission::where('id',$id)->update($data);
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
        $config = $request->all();
        $ajax =  isset($config['ajax'])?$config['ajax']:'n';
        $routa = 'permissions';
        if (!$post = Permission::find($id)){
            if($ajax=='s'){
                $ret = response()->json(['mens'=>'Registro não encontrado!','color'=>'danger','return'=>route($this->routa.'.index')]);
            }else{
                $ret = redirect()->route($routa.'.index',['mens'=>'Registro não encontrado!','color'=>'danger']);
            }
            return $ret;
        }

        Permission::where('id',$id)->delete();
        if($ajax=='s'){
            $ret = response()->json(['mens'=>__('Registro '.$id.' deletado com sucesso!'),'color'=>'success','return'=>route($this->routa.'.index')]);
        }else{
            $ret = redirect()->route($routa.'.index',['mens'=>'Registro deletado com sucesso!','color'=>'success']);
        }
        return $ret;
    }
}
