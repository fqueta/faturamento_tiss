<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use App\Models\Profissional;
use App\Qlib\Qlib;
use App\Models\User;
use App\Models\_upload;
use Illuminate\Support\Facades\Auth;


class ProfissionaisController extends Controller
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

        $this->user = $user;
        $this->routa = $seg2;
        $this->label = 'Profissional';
        $this->view = 'padrao';
        $this->url = $seg2;
    }
    public function queryProfissional($get=false,$config=false)
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

        $profissional =  Profissional::where('excluido','=','n')->where('type','=',$this->url)->where('deletado','=','n')->orderBy('id',$config['order']);
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
    public function campos($sec = false){
        if($this->url=='solicitantes'){
            return [
                'id'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'type'=>['label'=>'type','active'=>false,'type'=>'hidden','value'=>$this->url,'exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'config[operadora]'=>[
                    'label'=>'Operadora',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>Qlib::sql_array("SELECT id,nome FROM operadoras WHERE ativo='s'",'nome','id'),'exibe_busca'=>'d-block',
                    'event'=>'',
                    'tam'=>'12',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][operadora',
                ],
                'nome'=>['label'=>'Nome do Contratado '.$this->label,'active'=>true,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'','tam'=>'8'],
                'config[identificacao]'=>['label'=>'Identificação do Contratado na Operadora','active'=>false,'placeholder'=>'','type'=>'tel','exibe_busca'=>'d-block','event'=>'','tam'=>'4','cp_busca'=>'config][identificacao',],
                'config[nome_profissional]'=>['label'=>'Nome do Profissional Solicitante','active'=>false,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'','tam'=>'12','cp_busca'=>'config][nome_profissional',],
                'config[conselho]'=>[
                    'label'=>'Conselho Profissional',
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
                'config[numero_conselho]'=>['label'=>'N° no Conselho','active'=>true,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'','tam'=>'4'],
                'config[uf_conselho]'=>[
                    'label'=>'UF do Conselho',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>Qlib::sql_array("SELECT letter,letter FROM states ",'letter','letter'),'exibe_busca'=>'d-block',
                    'event'=>'',
                    'tam'=>'2',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][uf_conselho',
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
                'obs'=>['label'=>'Observação','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
                'ativo'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],
            ];
        }
        if($this->url=='executantes'){
            return [
                'id'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'type'=>['label'=>'type','active'=>false,'type'=>'hidden','value'=>$this->url,'exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
                'nome'=>['label'=>'Nome do Profissional Executante','active'=>true,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'','tam'=>'8'],
                'config[cpf_profissional]'=>['label'=>'CPF do Profissional(opcional)','active'=>true,'placeholder'=>'','type'=>'tel','exibe_busca'=>'d-block','event'=>'mask-cpf','tam'=>'4','cp_busca'=>'config][cpf_profissional',],
                'config[conselho]'=>[
                    'label'=>'Conselho Profissional',
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
                'config[numero_conselho]'=>['label'=>'N° no Conselho','active'=>true,'placeholder'=>'','type'=>'text_upcase','exibe_busca'=>'d-block','event'=>'','tam'=>'4','cp_busca'=>'config][numero_conselho'],
                'config[uf_conselho]'=>[
                    'label'=>'UF do Conselho',
                    'active'=>false,
                    'type'=>'select',
                    'arr_opc'=>Qlib::sql_array("SELECT letter,letter FROM states ",'letter','letter'),'exibe_busca'=>'d-block',
                    'event'=>'',
                    'tam'=>'2',
                    'class'=>'',
                    'exibe_busca'=>true,
                    'option_select'=>true,
                    'cp_busca'=>'config][uf_conselho',
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
                'config[telefone]'=>['label'=>'Telefone de Contato(opcional)','active'=>true,'placeholder'=>'','type'=>'tel','exibe_busca'=>'d-block','event'=>'onblur=mask(this,clientes_mascaraTelefone); onkeypress=mask(this,clientes_mascaraTelefone);','tam'=>'4','cp_busca'=>'config][telefone',],
                //'obs'=>['label'=>'Observação','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
                'ativo'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],
            ];
        }
    }
    public function index(User $user)
    {
        $this->authorize('ler', $this->url);
        $title = 'Profissionals Cadastradas';
        $titulo = $title;
        $queryProfissional = $this->queryProfissional($_GET);
        $queryProfissional['config']['exibe'] = 'html';
        $routa = $this->routa;
        return view($this->view.'.index',[
            'dados'=>$queryProfissional['profissional'],
            'title'=>$title,
            'titulo'=>$titulo,
            'campos_tabela'=>$queryProfissional['campos'],
            'profissional_totais'=>$queryProfissional['profissional_totais'],
            'titulo_tabela'=>$queryProfissional['tituloTabela'],
            'arr_titulo'=>$queryProfissional['arr_titulo'],
            'config'=>$queryProfissional['config'],
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
        $ajax = isset($dados['ajax'])?$dados['ajax']:'n';
        $dados['ativo'] = isset($dados['ativo'])?$dados['ativo']:'n';

        //dd($dados);
        $salvar = Profissional::create($dados);
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

    public function edit($profissional,User $user)
    {
        $id = $profissional;
        $dados = Profissional::where('id',$id)->get();
        $routa = 'profissionals';
        $this->authorize('ler', $this->url);

        if(!empty($dados)){
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
            $atualizar=Profissional::where('id',$id)->update($data);
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
        if (!$post = Profissional::find($id)){
            if($ajax=='s'){
                $ret = response()->json(['mens'=>'Registro não encontrado!','color'=>'danger','return'=>route($this->view.'.index')]);
            }else{
                $ret = redirect()->route($this->view.'.index',['mens'=>'Registro não encontrado!','color'=>'danger']);
            }
            return $ret;
        }

        Profissional::where('id',$id)->delete();
        if($ajax=='s'){
            $ret = response()->json(['mens'=>__('Registro '.$id.' deletado com sucesso!'),'color'=>'success','return'=>route($this->routa.'.index')]);
        }else{
            $ret = redirect()->route($routa.'.index',['mens'=>'Registro deletado com sucesso!','color'=>'success']);
        }
        return $ret;
    }
}
