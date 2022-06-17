<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use App\Http\Requests\StoreFamilyRequest;
use App\Qlib\Qlib;
use Illuminate\Support\Str;
use App\Exports\FamiliasExport;
use App\Exports\FamiliasExportView;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Bairro;
use App\Models\User;
use App\Models\_upload;
use Illuminate\Support\Facades\Auth;

class BairroController extends Controller
{
    protected $user;
    public $routa;
    public $label;
    public $view;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
        $this->routa = 'bairros';
        $this->label = 'Bairro';
        $this->view = $this->routa;
    }
    public function queryBairros($get=false,$config=false)
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

        $bairro =  Bairro::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);
        //$bairro =  DB::table('bairros')->where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);

        $bairro_totais = new stdClass;
        $campos = isset($_SESSION['campos_bairros_exibe']) ? $_SESSION['campos_bairros_exibe'] : $this->campos();
        $tituloTabela = 'Lista de todos cadastros';
        $arr_titulo = false;
        if(isset($get['filter'])){
                $titulo_tab = false;
                $i = 0;
                foreach ($get['filter'] as $key => $value) {
                    if(!empty($value)){
                        if($key=='id'){
                            $bairro->where($key,'LIKE', $value);
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                            $arr_titulo[$campos[$key]['label']] = $value;
                        }else{
                            $bairro->where($key,'LIKE','%'. $value. '%');
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
                $fm = $bairro;
                if($config['limit']=='todos'){
                    $bairro = $bairro->get();
                }else{
                    $bairro = $bairro->paginate($config['limit']);
                }

        }else{
            $fm = $bairro;
            if($config['limit']=='todos'){
                $bairro = $bairro->get();
            }else{
                $bairro = $bairro->paginate($config['limit']);
            }
        }
        $bairro_totais->todos = $fm->count();
        $bairro_totais->esteMes = $fm->whereYear('created_at', '=', $ano)->whereMonth('created_at','=',$mes)->count();
        $bairro_totais->ativos = $fm->where('ativo','=','s')->count();
        $bairro_totais->inativos = $fm->where('ativo','=','n')->count();
        $ret['bairro'] = $bairro;
        $ret['bairro_totais'] = $bairro_totais;
        $ret['arr_titulo'] = $arr_titulo;
        $ret['campos'] = $campos;
        $ret['config'] = $config;
        $ret['tituloTabela'] = $tituloTabela;
        $ret['config']['resumo'] = [
            'todos_registro'=>['label'=>'Todos cadastros','value'=>$bairro_totais->todos,'icon'=>'fas fa-calendar'],
            'todos_mes'=>['label'=>'Cadastros recentes','value'=>$bairro_totais->esteMes,'icon'=>'fas fa-calendar-times'],
            'todos_ativos'=>['label'=>'Cadastros ativos','value'=>$bairro_totais->ativos,'icon'=>'fas fa-check'],
            'todos_inativos'=>['label'=>'Cadastros inativos','value'=>$bairro_totais->inativos,'icon'=>'fas fa-archive'],
        ];
        return $ret;
    }
    public function campos(){
        return [
            'id'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'nome'=>['label'=>'Nome do bairro (ou distrito)*','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'12','placeholder'=>'Informe o nome do bairro ou do loteamento'],
            'cidade'=>['label'=>'Cidade (Opcional)','active'=>true,'type'=>'text','exibe_busca'=>'d-block','placeholder'=>'opcional','event'=>'','tam'=>'12'],
            'matricula'=>['label'=>'Matrícula','active'=>true,'type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'3','placeholder'=>'opcional'],
            'total_quadras'=>['label'=>'Total Quadras','active'=>true,'type'=>'number','placeholder'=>'opcional','exibe_busca'=>'d-block','event'=>'','tam'=>'3'],
            'total_lotes'=>['label'=>'Total de Lote','active'=>true,'type'=>'number','placeholder'=>'opcional','exibe_busca'=>'d-block','event'=>'','tam'=>'3'],
            'ativo'=>['label'=>'Ativado','active'=>true,'type'=>'chave_checkbox','valor_padrao'=>'s','value'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],
            'obs'=>['label'=>'Observação','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
        ];
    }
    public function index(User $user)
    {
        $this->authorize('ler', $this->routa);
        $title = 'Cidades Cadastradas';
        $titulo = $title;
        $ajax = isset($_GET['ajax'])?$_GET['ajax']:false;
        $queryBairros = $this->queryBairros($_GET);
        $queryBairros['config']['exibe'] = 'html';
        $routa = $this->routa;
        $ret = [
            'dados'=>$queryBairros['bairro'],
            'title'=>$title,
            'titulo'=>$titulo,
            'campos_tabela'=>$queryBairros['campos'],
            'bairro_totais'=>$queryBairros['bairro_totais'],
            'titulo_tabela'=>$queryBairros['tituloTabela'],
            'arr_titulo'=>$queryBairros['arr_titulo'],
            'config'=>$queryBairros['config'],
            'routa'=>$routa,
            'view'=>$this->view,
            'i'=>0,
        ];
        if($ajax){
            return response()->json($ret);
        }
        return view($routa.'.index',$ret);
    }
    public function create(User $user)
    {
        $this->authorize('create', $this->routa);
        $title = 'Cadastrar bairro';
        $titulo = $title;
        $config = [
            'ac'=>'cad',
            'frm_id'=>'frm-bairros',
            'route'=>$this->routa,
        ];
        $value = [
            'token'=>uniqid(),
        ];
        $campos = $this->campos();
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
        $this->authorize('create', $this->routa);
        $validatedData = $request->validate([
            'nome' => ['required','string','unique:bairros'],
        ]);
        $dados = $request->all();
        $ajax = isset($dados['ajax'])?$dados['ajax']:'n';
        $dados['ativo'] = isset($dados['ativo'])?$dados['ativo']:'n';

        //dd($dados);
        $salvar = Bairro::create($dados);
        $route = $this->routa.'.index';
        $ret = [
            'mens'=>$this->label.' cadastrado com sucesso!',
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

    public function edit($id,User $user)
    {
        $dados = Bairro::where('id',$id)->get();
        $routa = 'bairros';
        $ajax = isset($_GET['ajax'])?$_GET['ajax']:false;
        $this->authorize('ler', $this->routa);

        if(!empty($dados)){
            $title = 'Editar Cadastro de bairros';
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
                'frm_id'=>'frm-bairros',
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
                'routa'=>$this->routa,
                'exec'=>true,
            ];
            if($ajax=='s'){
                return response()->json($ret);
            }else{
                return view($routa.'.createedit',$ret);
            }
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
            $atualizar=Bairro::where('id',$id)->update($data);
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
        $this->authorize('delete', $this->routa);
        $config = $request->all();
        $ajax =  isset($config['ajax'])?$config['ajax']:'n';
        $routa = 'bairros';
        if (!$post = Bairro::find($id)){
            if($ajax=='s'){
                $ret = response()->json(['mens'=>'Registro não encontrado!','color'=>'danger','return'=>route($this->routa.'.index')]);
            }else{
                $ret = redirect()->route($routa.'.index',['mens'=>'Registro não encontrado!','color'=>'danger']);
            }
            return $ret;
        }

        Bairro::where('id',$id)->delete();
        if($ajax=='s'){
            $ret = response()->json(['mens'=>__('Registro '.$id.' deletado com sucesso!'),'color'=>'success','return'=>route($this->routa.'.index')]);
        }else{
            $ret = redirect()->route($routa.'.index',['mens'=>'Registro deletado com sucesso!','color'=>'success']);
        }
        return $ret;
    }
}
