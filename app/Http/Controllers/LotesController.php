<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoteRequest;
use stdClass;
use App\Models\Lote;
use Illuminate\Http\Request;
use App\Qlib\Qlib;
use App\Models\User;
use App\Models\_upload;
use App\Models\Beneficiario;
use App\Models\Documento;
use App\Models\Familia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LotesController extends Controller
{
    protected $user;
    public $routa;
    public $label;
    public $view;
    public $tab;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
        $this->routa = 'lotes';
        $this->label = 'Lote';
        $this->view = 'padrao';
        $this->tab = $this->routa;
    }
    public function queryLote($get=false,$config=false)
    {
        $ret = false;
        $get = isset($_GET) ? $_GET:[];
        $ano = date('Y');
        $mes = date('m');
        //$todasFamilias = Familia::where('excluido','=','n')->where('deletado','=','n');
        $config = [
            'limit'=>isset($get['limit']) ? $get['limit']: 50,
            'order'=>isset($get['order']) ? $get['order']: 'desc',
            'acao_massa'=>[['link'=>'javascript:lib_abrirListaOcupantes();','event'=>false,'icon'=>'fa fa-list','label'=>__('Lista de ocupantes')]],
        ];

        if(isset($get['term'])){
            //Autocomplete
            if(isset($get['bairro']) && !empty($get['bairro']) && isset($get['quadra']) && !empty($get['quadra'])){
               $sql = "SELECT * FROM lotes WHERE (nome LIKE '%".$get['term']."%') AND bairro=".$get['bairro']." AND quadra=".$get['quadra']." AND ".Qlib::compleDelete();
            }elseif(isset($get['bairro']) && !empty($get['bairro'])){
                $sql = "SELECT * FROM lotes WHERE (nome LIKE '%".$get['term']."%') AND bairro=".$get['bairro']." AND ".Qlib::compleDelete();
            }else{
                $sql = "SELECT l.*,q.nome quadra_valor FROM lotes as l
                JOIN quadras as q ON q.id=l.quadra
                WHERE (l.nome LIKE '%".$get['term']."%' OR q.nome LIKE '%".$get['term']."%' ) AND ".Qlib::compleDelete('l');
            }
            $lote = DB::select($sql);
            if(isset($get['familias'])&&$get['familias']=='s' && is_array($lote)){
                foreach ($lote as $k => $v) {
                    $sqlF = "SELECT f.*,b.nome,b.cpf FROM familias As f
                    JOIN beneficiarios As b ON b.id=f.id_beneficiario
                    WHERE f.loteamento LIKE '%\"".$v->id."\"%' AND ".Qlib::compleDelete('f')." AND ".Qlib::compleDelete('b');
                    $lote[$k]->familias = DB::select($sqlF);
                }
            }
            $ret['lote'] = $lote;
            return $ret;
        }else{
            $lote =  Lote::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);
        }

        //$lote =  DB::table('lotes')->where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);

        $lote_totais = new stdClass;
        $campos = isset($_SESSION['campos_lotes_exibe']) ? $_SESSION['campos_lotes_exibe'] : $this->campos();
        $tituloTabela = 'Lista de todos cadastros';
        $arr_titulo = false;

        if(isset($get['filter'])){
                $titulo_tab = false;
                $i = 0;
                foreach ($get['filter'] as $key => $value) {
                    if(!empty($value)){
                        if($key=='id' || $key=='quadra'){
                            $lote->where($key,'LIKE', $value);
                            $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                            $arr_titulo[$campos[$key]['label']] = $value;
                        }else{
                            if(is_array($value)){
                                // dd($value);
                            }else{
                                $lote->where($key,'LIKE','%'. $value. '%');
                                if($campos[$key]['type']=='select'){
                                    $value = $campos[$key]['arr_opc'][$value];
                                }
                                $arr_titulo[$campos[$key]['label']] = $value;
                                $titulo_tab .= 'Todos com *'. $campos[$key]['label'] .'% = '.$value.'& ';
                            }
                        }
                        $i++;
                    }
                }
                if($titulo_tab){
                    $tituloTabela = 'Lista de: &'.$titulo_tab;
                                //$arr_titulo = explode('&',$tituloTabela);
                }
                $fm = $lote;
                if($config['limit']=='todos'){
                    $lote = $lote->get();
                }else{
                    $lote = $lote->paginate($config['limit']);
                }
        }else{
            $fm = $lote;
            if($config['limit']=='todos'){
                $lote = $lote->get();
            }else{
                $lote = $lote->paginate($config['limit']);
            }
        }
        $lote_totais->todos = $fm->count();
        $lote_totais->esteMes = $fm->whereYear('created_at', '=', $ano)->whereMonth('created_at','=',$mes)->get()->count();
        $lote_totais->ativos = $fm->where('ativo','=','s')->get()->count();
        $lote_totais->inativos = $fm->where('ativo','=','n')->get()->count();

        $ret['lote'] = $lote;
        $ret['lote_totais'] = $lote_totais;
        $ret['arr_titulo'] = $arr_titulo;
        $ret['campos'] = $campos;
        $ret['config'] = $config;
        $ret['tituloTabela'] = $tituloTabela;
        $ret['config']['resumo'] = [
            'todos_registro'=>['label'=>'Todos cadastros','value'=>$lote_totais->todos,'icon'=>'fas fa-calendar'],
            'todos_mes'=>['label'=>'Cadastros recentes','value'=>$lote_totais->esteMes,'icon'=>'fas fa-calendar-times'],
            'todos_ativos'=>['label'=>'Cadastros ativos','value'=>$lote_totais->ativos,'icon'=>'fas fa-check'],
            'todos_inativos'=>['label'=>'Cadastros inativos','value'=>$lote_totais->inativos,'icon'=>'fas fa-archive'],
        ];
        return $ret;
    }
    public function campos(){
        $user = Auth::user();
        $quadra = new QuadrasController($user);
        $arr_opc_ocupantes = Qlib::qoption('opc_declara_posse','array');
        $bairro = new BairroController($user);

        return [
            'id'=>['label'=>'Id','active'=>true,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'token'=>['label'=>'token','active'=>false,'type'=>'hidden','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            //'bairro'=>['label'=>'Bairro','active'=>true,'type'=>'hidden_text','exibe_busca'=>'d-block','event'=>'','tam'=>'12','arr_opc'=>Qlib::sql_array("SELECT id,nome FROM bairros WHERE ativo='s'",'nome','id'),'value'=>@$_GET['bairro']],
            'bairro'=>[
                'label'=>'Área',
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
                'event'=>'onchange=carregaMatricula($(this).val(),\'familias\')',
                //'event'=>'onchange=carregaMatricula($(this).val())',
                'tam'=>'12',
                'value'=>@$_GET['bairro'],
            ],
            'quadra'=>[
                'label'=>'Quadra',
                'active'=>true,
                'type'=>'selector',
                'data_selector'=>[
                    'campos'=>$quadra->campos(),
                    'route_index'=>route('quadras.index'),
                    'id_form'=>'frm-quadras',
                    'action'=>route('quadras.store'),
                    'campo_id'=>'id',
                    'campo_bus'=>'nome',
                    'label'=>'Quadra',
                ],
                'arr_opc'=>Qlib::sql_array("SELECT id,nome FROM quadras WHERE ativo='s'",'nome','id'),'exibe_busca'=>'d-block',
                'event'=>'onchange=carregaBairro($(this).val());',
                'tam'=>'5',
                'value'=>@$_GET['quadra'],
                'class'=>'select2'
            ],
            'nome'=>['label'=>'N. do Lote (Somente Números)','active'=>true,'placeholder'=>'Ex.: 14','type'=>'number','exibe_busca'=>'d-block','event'=>'','tam'=>'7','value'=>@$_GET['nome']],
            //'config[complemento]'=>['label'=>'Complemento','active'=>true,'placeholder'=>'Ex.: A','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'3','cp_busca'=>'config][complemento'],
            'cep'=>['label'=>'CEP','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'mask-cep onchange=buscaCep1_0(this.value)','tam'=>'3'],
            'endereco'=>['label'=>'Endereço','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'7'],
            'numero'=>['label'=>'Numero','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'2'],
            'complemento'=>['label'=>'Complemento','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'4'],
            'cidade'=>['label'=>'Cidade','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'6'],
            'config[uf]'=>['label'=>'UF','active'=>false,'js'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-none','event'=>'','tam'=>'2','cp_busca'=>'config][uf'],
            'config[valor_lote]'=>['label'=>'Valor do Lote','active'=>false,'js'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-none','event'=>'','tam'=>'6','class'=>'moeda','cp_busca'=>'config][valor_lote'],
            'config[valor_edificacao]'=>['label'=>'Valor da Edificação','active'=>false,'js'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-none','event'=>'','tam'=>'6','class'=>'moeda','cp_busca'=>'config][valor_edificacao'],
            'config[area_lote]'=>['label'=>'Área quadrada(m²)','active'=>false,'js'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-none','event'=>'','tam'=>'6','class'=>'','cp_busca'=>'config][area_lote'],
            'config[area_construcao]'=>['label'=>'Área construção(m²)','active'=>false,'js'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-none','event'=>'','tam'=>'6','class'=>'','cp_busca'=>'config][area_construcao'],
            //'config[registro]'=>['label'=>'Registro','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'4','cp_busca'=>'config][registro'],
            //'config[livro]'=>['label'=>'Livro','active'=>true,'placeholder'=>'','type'=>'text','exibe_busca'=>'d-block','event'=>'','tam'=>'4','cp_busca'=>'config][livro'],
            'obs'=>['label'=>'Observação','active'=>false,'type'=>'textarea','exibe_busca'=>'d-block','event'=>'','tam'=>'12'],
            'ativo'=>['label'=>'Liberar','active'=>true,'type'=>'chave_checkbox','value'=>'s','valor_padrao'=>'s','exibe_busca'=>'d-block','event'=>'','tam'=>'3','arr_opc'=>['s'=>'Sim','n'=>'Não']],
            'ocupantes'=>['label'=>'lista de Ocupantes','active'=>false,'type'=>'html','exibe_busca'=>'d-none','event'=>'','tam'=>'12','script'=>'lotes.lista_ocupantes','script_show'=>'lotes.show_ocupantes','arr_opc'=>$arr_opc_ocupantes],

        ];
    }
    public function index(User $user)
    {
        $ajax = isset($_GET['ajax'])?$_GET['ajax']:'n';
        $this->authorize('ler', $this->routa);
        $title = 'Lotes Cadastrados';
        $titulo = $title;
        $queryLote = $this->queryLote($_GET);
        $queryLote['config']['exibe'] = 'html';
        $routa = $this->routa;
        if(isset($_GET['term'])){
            $ret = false;
            $ajax = 's';
            $campos = $this->campos();
            if($queryLote['lote']){
               //$ret = $queryLote['lote'];
                if(isset($_GET['bairro']) && empty($_GET['bairro'])){
                    $ret[0]['value'] = 'Por favor selecione a Área! ';
                    $ret[0]['id'] = '';
                }elseif(isset($_GET['quadra']) && empty($_GET['quadra'])){
                    $ret[0]['value'] = 'Por favor selecione a Quadra! ';
                    $ret[0]['id'] = 'cad';
                }else{
                    foreach ($queryLote['lote'] as $key => $v) {
                        $bairro = false;
                        if(isset($v->config)){
                            $v->config = Qlib::lib_json_array($v->config);
                        }
                        if($id_bairro = $v->bairro){
                            $bairro = Qlib::buscaValorDb([
                                'tab'=>'bairros',
                                'campo_bus'=>'id',
                                'valor'=>$id_bairro,
                                'select'=>'nome',
                            ]);
                            $ret[$key]['dados'] = $v;
                        }
                        $nome_quadra = false;
                        if($id_quadra = $v->quadra){

                            $nome_quadra = Qlib::buscaValorDb([
                                'tab'=>'quadras',
                                'campo_bus'=>'id',
                                'valor'=>$id_quadra,
                                'select'=>'nome',
                            ]);
                            $ret[$key]['dados'] = $v;
                            $ret[$key]['dados']->quadra_valor = $nome_quadra;
                        }
                        $ret[$key]['value'] = ' Lote: '.$v->nome.' | quadra: '.$nome_quadra.' | Bairro: '.$bairro;
                    }
                }
            }else{
                $ret[0]['value'] = 'Lote não encontrado. Cadastrar agora?';
                $ret[0]['id'] = 'cad';
            }
        }else{
            $ret = [
                'dados'=>$queryLote['lote'],
                'title'=>$title,
                'titulo'=>$titulo,
                'campos_tabela'=>$queryLote['campos'],
                'lote_totais'=>$queryLote['lote_totais'],
                'titulo_tabela'=>$queryLote['tituloTabela'],
                'arr_titulo'=>$queryLote['arr_titulo'],
                'config'=>$queryLote['config'],
                'routa'=>$routa,
                'view'=>$this->view,
                'i'=>0,
            ];
        }
        if($ajax=='s'){
            return response()->json($ret);
        }else{
            return view($this->view.'.index',$ret);
        }
    }
    public function create(User $user)
    {
        $this->authorize('create', $this->routa);
        $title = 'Cadastrar lote';
        $titulo = $title;
        $config = [
            'ac'=>'cad',
            'frm_id'=>'frm-lotes',
            'route'=>$this->routa,
        ];
        $value = [
            'token'=>uniqid(),
            //'loteamento'=>[123],
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
    public function store(StoreLoteRequest $request)
    {
        $dados = $request->all();
        $ajax = isset($dados['ajax'])?$dados['ajax']:'n';
        $dados['ativo'] = isset($dados['ativo'])?$dados['ativo']:'n';
        $dados['token'] = isset($dados['token'])?$dados['token']:uniqid();
        if (isset($dados['quadra']) && !empty($dados['quadra'])) {
            /**adicionar o bairro ao lote */
            $dados['bairro'] = Qlib::buscaValorDb([
                'tab'=>'quadras',
                'campo_bus'=>'id',
                'valor'=>$dados['quadra'],
                'select'=>'bairro',
            ]);
        }
        $salvar = Lote::create($dados);
        $dados['id'] = $salvar->id;

        $sql = "SELECT l.*,q.nome quadra_valor FROM lotes as l
            JOIN quadras as q ON q.id=l.quadra
            WHERE l.id = '".$dados['id']."' AND l.excluido !='s' AND l.deletado
            ";
        $dadosAtualizados = Qlib::dados_tab($this->tab,[
            'sql'=>$sql,
            'id'=>$dados['id'],
        ]);
        if(!$sql){
            $d = $dadosAtualizados;
        }else{
            $d = $dadosAtualizados[0];
        }
        $route = $this->routa.'.index';
        $ret = [
            'mens'=>$this->label.' cadastrada com sucesso!',
            'color'=>'success',
            'idCad'=>$salvar->id,
            'exec'=>true,
            'dados'=>$d,
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
    public function ocupantes( $id_lote = null,$oc=false)
    {
        /**$oc = o id do ocupamnte */
        $ret = false;

        if($id_lote){
            if($oc){
                $sql = "SELECT f.* FROM familias As f
                WHERE f.loteamento LIKE '%\"$id_lote\"%' AND id_beneficiario='$oc' AND f.excluido='n' AND f.deletado='n' ORDER BY f.complemento_lote ASC";
            }else{
                $sql = "SELECT f.* FROM familias As f
                WHERE f.loteamento LIKE '%\"$id_lote\"%' AND f.excluido='n' AND f.deletado='n' ORDER BY f.complemento_lote ASC";
            }
            $ocupantes = Qlib::dados_tab('familias',['sql'=>$sql]);
            if($ocupantes){
                foreach ($ocupantes as $ko => $vo) {
                    if($vo['id_beneficiario']>0){
                        $ocupantes[$ko]['beneficiario'] = Qlib::buscaValorDb([
                            'tab'=>'beneficiarios',
                            'campo_bus'=>'id',
                            'valor'=>$vo['id_beneficiario'],
                            'select'=>'nome',
                        ]);
                    }
                    $ocupantes[$ko]['id_lote'] = $id_lote;
                    if($vo['id_conjuge']>0){
                        $ocupantes[$ko]['conjuge'] = Qlib::buscaValorDb([
                            'tab'=>'beneficiarios',
                            'campo_bus'=>'id',
                            'valor'=>$vo['id_conjuge'],
                            'select'=>'nome',
                        ]);
                    }
                }
            }
            $ret = $ocupantes;

        }
        return $ret;
    }
    public function edit($lote,User $user)
    {
        $id = $lote;
        $dados = Lote::where('id',$id)->get();
        $routa = 'lotes';
        $ajax = isset($_GET['ajax'])?$_GET['ajax']:false;

        $this->authorize('ler', $this->routa);

        if(!empty($dados)){
            $title = 'Editar Cadastro de lotes';
            $titulo = $title;
            $dados[0]['ac'] = 'alt';
            $dados[0]['ocupantes'] = $this->ocupantes($id);
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
                'frm_id'=>'frm-lotes',
                'route'=>$this->routa,
                'id'=>$id,
            ];
            $_GET['dados'] = $dados[0]; //para ter acesso em todas a views
            $ret = [
                'value'=>$dados[0],
                'config'=>$config,
                'title'=>$title,
                'titulo'=>$titulo,
                'listFiles'=>$listFiles,
                'campos'=>$campos,
                'id'=>$id,
                'exec'=>true,
            ];

            if($ajax=='s'){
                return response()->json($ret);
            }else{
                return view($this->view.'.createedit',$ret);
            }
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
            if (isset($data['quadra']) && !empty($data['quadra'])) {
                /**adicionar o bairro ao lote */
                $data['bairro'] = Qlib::buscaValorDb([
                    'tab'=>'quadras',
                    'campo_bus'=>'id',
                    'valor'=>$data['quadra'],
                    'select'=>'bairro',
                ]);
            }
            $atualizar=Lote::where('id',$id)->update($data);
            $sql = "SELECT l.*,q.nome quadra_valor FROM lotes as l
            JOIN quadras as q ON q.id=l.quadra
            WHERE l.id = '$id' AND l.excluido !='s' AND l.deletado
            ";
            $dadosAtualizados = Qlib::dados_tab($this->tab,[
                'sql'=>$sql,
                'id'=>$id
            ]);
            if(!$sql){
                $d = $dadosAtualizados;
            }else{
                $d = $dadosAtualizados[0];
            }
            $route = $this->routa.'.index';
            $ret = [
                'exec'=>$atualizar,
                'id'=>$id,
                'mens'=>'Salvo com sucesso!',
                'color'=>'success',
                'idCad'=>$id,
                'return'=>$route,
                'dados'=>@$d,
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
        $routa = 'lotes';
        $user = Auth::user();
        $reg_excluido = ['data'=>date('d-m-Y'),'autor'=>$user->id];
        if (!$post = Lote::find($id)){
            if($ajax=='s'){
                $ret = response()->json(['mens'=>'Registro não encontrado!','color'=>'danger','return'=>route($this->view.'.index')]);
            }else{
                $ret = redirect()->route($this->view.'.index',['mens'=>'Registro não encontrado!','color'=>'danger']);
            }
            return $ret;
        }

        Lote::where('id',$id)->update(['excluido'=>'s','reg_excluido'=>Qlib::lib_array_json($reg_excluido)]);
        if($ajax=='s'){
            $ret = response()->json(['mens'=>__('Registro '.$id.' deletado com sucesso!'),'color'=>'success','return'=>route($this->routa.'.index')]);
        }else{
            $ret = redirect()->route($routa.'.index',['mens'=>'Registro deletado com sucesso!','color'=>'success']);
        }
        return $ret;
    }
    public function fichaOcupante($id_lote = false,$id_ocupante=false)
    {
        $retu['familia'] = false;
        $retu['html'] = false;
        $ret = false;
        if($id_lote&&$id_ocupante){
            $oc=$id_ocupante;
            $familia = $this->ocupantes($id_lote,$oc);
            if($familia){
                $retu['familia'] = $familia[0];
                $tema = Documento::where('url','ficha-cadastro-ocupante')->where('excluido','n')->where('deletado','n')->get();
                $parceiro = false;
                //dd($familia);
                if($familia[0]['id_conjuge']>0){
                    $tema2 = Documento::where('url','ocupante-com-parceiro')->where('excluido','n')->where('deletado','n')->get();
                    $parceiro = true;
                }else{
                    $tema2 = Documento::where('url','ocupante-sem-parceiro')->where('excluido','n')->where('deletado','n')->get();
                }
                $dLote = Lote::FindOrFail($id_lote);
                $lote = $dLote['nome'];
                $doc = false;
                $bairro = Qlib::buscaValorDb([
                    'tab'=>'bairros',
                    'campo_bus'=>'id',
                    'valor'=>$dLote['bairro'],
                    'select'=>'nome',
                ]);
                $quadra = Qlib::buscaValorDb([
                    'tab'=>'quadras',
                    'campo_bus'=>'id',
                    'valor'=>$dLote['quadra'],
                    'select'=>'nome',
                ]);
                $arr_sh=[];
                foreach ($familia as $key => $fm) {
                    if($tm1=$tema[0]->conteudo){
                        if($tm2=$tema2[0]->conteudo){

                            $dadosBen = Beneficiario::FindOrFail($fm['id_beneficiario']);
                            $dadosCon = false;
                            if($parceiro>0){
                                $dadosCon = Beneficiario::FindOrFail($fm['id_conjuge']);
                            }
                            if($b = $dadosBen){
                                //Qlib::lib_print($b);
                                //Qlib::lib_print($dLote);
                                $lote = $dLote['nome'];
                                $tipo_beneficiario = 'REURB (S)';
                                $nome_beneficiario = $b['nome'];
                                $filhoa_de = 'filho de';
                                $casados = 'casados';
                                $nascidoa = 'nascido';
                                if($b['sexo']=='f'){
                                    $filhoa_de = 'filha de';
                                    $nascidoa = 'nascida';
                                }
                                if(isset($b['config']['estado_civil']) && $b['config']['estado_civil']!=2){
                                    $casados = 'vivendo';
                                }

                                /*
                                if($tot_familias>1){
                                    if(empty($fm['complemento_lote'])){
                                        $n_benficiario = "<b>".$i.")</b> ";
                                    }else{
                                        $n_benficiario = "<b>".$fm['complemento_lote'].")</b> ";
                                    }
                                }*/
                                $n_benficiario = false;
                                $complemento = '';
                                if(!empty($dLote['complemento'])){
                                    $complemento = ' '.$dLote['complemento'];
                                }
                                $arr_sh = [
                                    'lote'=>['lab'=>'Tipo','v'=>$lote],
                                    'quadra'=>['lab'=>'Tipo','v'=>$quadra],
                                    'matricula'=>['lab'=>'Tipo','v'=>$fm['matricula']],
                                    'lote_extenso'=>['lab'=>'Tipo','v'=>Qlib::convert_number_to_words(Qlib::limpar_texto($lote))],
                                    'quadra_extenso'=>['lab'=>'Tipo','v'=>Qlib::convert_number_to_words($quadra)],
                                    'tipo_beneficiario'=>['lab'=>'Tipo','v'=>$tipo_beneficiario],
                                    'nome_beneficiario'=>['lab'=>'Nome','v'=>$n_benficiario.$nome_beneficiario],
                                    'cpf'=>['lab'=>'CPF','v'=>$b['cpf']],
                                    'endereco'=>['lab'=>'Endereço','v'=>$dLote['endereco']],
                                    'numero'=>['lab'=>'numero','v'=>$dLote['numero'].$complemento],
                                    'complemento'=>['lab'=>'complemento','v'=>$dLote['complemento']],
                                    'cidade'=>['lab'=>'cidade','v'=>$dLote['cidade']],
                                    'cep'=>['lab'=>'cep','v'=>$dLote['cep']],
                                    'bairro'=>['lab'=>'bairro','v'=>$bairro],
                                    'area'=>['lab'=>'bairro','v'=>$bairro],
                                    'casados'=>['lab'=>'','v'=>$casados],
                                    'filha de'=>['lab'=>'','v'=>$filhoa_de],
                                    'filho de'=>['lab'=>'','v'=>$filhoa_de],
                                    'nascida'=>['lab'=>'','v'=>$nascidoa],
                                    'nascido'=>['lab'=>'','v'=>$nascidoa],
                                    //'obs'=>['lab'=>'','v'=>$dLote['obs']],
                                ];
                                if($dadosCon){
                                    $doc .= str_replace('{lote}',$lote,$tm2);
                                    $doc = $this->docConjuge($dadosCon,$doc,$b);
                                }else{
                                    $doc .= str_replace('{lote}',$lote,$tm2);
                                }
                                foreach ($arr_sh as $ks => $vs) {
                                    $doc = str_replace('{'.$ks.'}',$vs['v'],$doc);
                                }
                                //$doc = str_replace('{nome_beneficiario}',$nome_beneficiario,$doc);
                                if(is_array($b['config'])){
                                    foreach ($b['config'] as $kc => $vc) {
                                        if($kc=='escolaridade'||$kc=='estado_civil'){
                                            if($kc=='escolaridade'){
                                                $ta = 'escolaridades';
                                            }
                                            if($kc=='estado_civil'){
                                                $ta = 'estadocivils';
                                            }
                                            $vc=Qlib::buscaValorDb([
                                                'tab'=>$ta,
                                                'campo_bus'=>'id',
                                                'valor'=>$vc,
                                                'select'=>'nome',
                                            ]);
                                        }
                                        if($kc=='data_uniao'){
                                            $vc = Qlib::dataExibe($vc);
                                        }
                                        if($kc=='nascimento')
                                        $vc = Qlib::dataExibe($vc);
                                        $doc = str_replace('{'.$kc.'}',$vc,$doc);
                                    }
                                }
                            }
                        }
                    }
                }
                $ret = str_replace('{ocupantes}',$doc,$tm1);
                if($id_familia = $familia[0]['id']){
                    $meses = Qlib::Meses();
                    $arr_sh['declaracao_posse'] = ['lab'=>'Declaração','v'=>$this->declaracaoPosse($id_lote,$id_familia,$dLote)];
                    $data_posse = isset($dLote['config']['data_posse'][$id_familia])?$dLote['config']['data_posse'][$id_familia]:false;
                    if($data_posse){
                        $arr_sh['data_posse'] = ['lab'=>'Data posse','v'=>Qlib::dataExibe($data_posse)];
                    }else{
                        $arr_sh['data_posse'] = ['lab'=>'Data posse','v'=>'00/00/0000'];
                    }
                    $arr_sh['obs'] = ['lab'=>'Observações','v'=>@$dLote['config']['obs'][$id_familia]];
                    $arr_sh['dia'] = ['lab'=>'Dia','v'=>date('d')];
                    $arr_sh['mes_extenso'] = ['lab'=>'Mês','v'=>$meses[date('m')]];
                    $arr_sh['ano'] = ['lab'=>'Ano','v'=>date('Y')];
                    if(isset($familia[0]['beneficiario'])){
                        $arr_sh['nome_proprietario'] = ['lab'=>'Nome proprietário','v'=>$familia[0]['beneficiario']];
                        $arr_sh['nome_conjuge'] = ['lab'=>'Ano','v'=>''];
                        $arr_sh['espaco_assinatura_conjuge'] = ['lab'=>'espaço','v'=>''];
                    }
                    if(isset($familia[0]['conjuge'])){
                        $arr_sh['nome_conjuge'] = ['lab'=>'Ano','v'=>$familia[0]['conjuge']];
                        $arr_sh['espaco_assinatura_conjuge'] = ['lab'=>'espaço','v'=>'______________________________'];
                    }
                }
                foreach ($arr_sh as $ks => $vs) {
                    $ret = str_replace('{'.$ks.'}',$vs['v'],$ret);
                }
                if(is_array($dLote['config'])){
                    foreach ($dLote['config'] as $kl => $vl) {
                        if(!is_array($vl))
                            $ret = str_replace('{'.$kl.'}',$vl,$ret);
                    }
                }
                if(is_array($b['config'])){
                    foreach ($b['config'] as $kc => $vc) {
                        if($kc=='escolaridade'||$kc=='estado_civil'){
                            if($kc=='escolaridade'){
                                $ta = 'escolaridades';
                            }
                            if($kc=='estado_civil'){
                                $ta = 'estadocivils';
                            }
                            $vc=Qlib::buscaValorDb([
                                'tab'=>$ta,
                                'campo_bus'=>'id',
                                'valor'=>$vc,
                                'select'=>'nome',
                            ]);
                        }
                        if($kc=='data_uniao'){
                            $vc = Qlib::dataExibe($vc);
                        }
                        if($kc=='nascimento')
                            $vc = Qlib::dataExibe($vc);
                        $ret = str_replace('{'.$kc.'}',$vc,$ret);
                    }
                }
            }
        }
        $retu['html'] = $ret;
        return $retu;
    }
    public function declaracaoPosse($id_lote = null,$id_familia,$dLote=false)
    {
        $ret = false;
        if($id_lote&&$id_familia){
            if(!$dLote)
                $dLote = lote::FindOrFail($id_lote);
            if(!isset($dLote['config']['declaracao_posse'][$id_familia])){
                $ret = ' <span class="text-danger">Declarações adicionais sobre a posse não informada! </span><br>';
                return $ret;
            }
            $arr_opc_ocupantes = Qlib::qoption('opc_declara_posse','array');
            $tema1 = '<ul>{li}</ul>';
            $tema2 = '<li>( {mark} ) {label}</li>';
            $li = false;
            if(is_array($arr_opc_ocupantes)){
                foreach ($arr_opc_ocupantes as $k => $v) {
                    $mark = '&nbsp;&nbsp;';
                    if($k==$dLote['config']['declaracao_posse'][$id_familia]){
                        $mark = 'x';
                    }
                    $li .= str_replace('{mark}',$mark,$tema2);
                    $li = str_replace('{label}',$v['label'],$li);
                }
            }
            $ret = str_replace('{li}',$li,$tema1);
        }
        return $ret;
    }
    public function docBeneficiario($id_lote = false,$config=false)
    {
        $ret = false;
        if($id_lote){
            $familia = $this->ocupantes($id_lote);
            $dLote = Lote::FindOrFail($id_lote);
            if($familia && $dLote){
                $tema = Documento::where('url','lista-beneficiario')->where('excluido','n')->where('deletado','n')->get();
                $tema2 = Documento::where('url','lista-beneficiario-2')->where('excluido','n')->where('deletado','n')->get();
                $tema3 = Documento::where('url','lista-beneficiario-3')->where('excluido','n')->where('deletado','n')->get();
                $doc = false;
                $tm1 = $tema[0]->conteudo;
                $tm2 = $tema2[0]->conteudo;
                $tm3 = $tema3[0]->conteudo;
                $bairro = Qlib::buscaValorDb([
                    'tab'=>'bairros',
                    'campo_bus'=>'id',
                    'valor'=>$dLote['bairro'],
                    'select'=>'nome',
                ]);
                $quadra = Qlib::buscaValorDb([
                    'tab'=>'quadras',
                    'campo_bus'=>'id',
                    'valor'=>$dLote['quadra'],
                    'select'=>'nome',
                ]);
                $arr_sh = [];
                $tot_familias = count($familia);
                $n_benficiario = false;
                $i=0;
                foreach ($familia as $key => $fm) {
                    if(isset($tema[0]->conteudo) && $dLote && ($fm['id_beneficiario']>0)){
                        $i++;
                        $dadosBen = Beneficiario::FindOrFail($fm['id_beneficiario']);
                        $dadosCon = false;
                        if($fm['id_conjuge']>0){
                            $dadosCon = Beneficiario::FindOrFail($fm['id_conjuge']);
                        }
                        if($b = $dadosBen){
                            //Qlib::lib_print($b);
                            //Qlib::lib_print($dLote);
                            $lote = $dLote['nome'];
                            $tipo_beneficiario = 'REURB (S)';
                            $nome_beneficiario = $b['nome'];
                            $filhoa_de = 'filho de';
                            $nascidoa = 'nascido';
                            if($b['sexo']=='f'){
                                $filhoa_de = 'filha de';
                                $nascidoa = 'nascida';
                            }
                            if($tot_familias>1){
                                if(empty($fm['complemento_lote'])){
                                    $n_benficiario = "<b>".$i.")</b> ";
                                }else{
                                    $n_benficiario = "<b>".$fm['complemento_lote'].")</b> ";
                                }
                            }
                            $arr_sh = [
                                'lote'=>['lab'=>'Tipo','v'=>$lote],
                                'quadra'=>['lab'=>'Tipo','v'=>$quadra],
                                'lote_extenso'=>['lab'=>'Tipo','v'=>Qlib::convert_number_to_words(Qlib::limpar_texto($lote))],
                                'quadra_extenso'=>['lab'=>'Tipo','v'=>Qlib::convert_number_to_words($quadra)],
                                'tipo_beneficiario'=>['lab'=>'Tipo','v'=>$tipo_beneficiario],
                                'nome_beneficiario'=>['lab'=>'Nome','v'=>$n_benficiario.$nome_beneficiario],
                                'cpf'=>['lab'=>'CPF','v'=>$b['cpf']],
                                'endereco'=>['lab'=>'Endereço','v'=>$dLote['endereco']],
                                'numero'=>['lab'=>'numero','v'=>$dLote['numero']],
                                'cidade'=>['lab'=>'cidade','v'=>$dLote['cidade']],
                                'cep'=>['lab'=>'cep','v'=>$dLote['cep']],
                                'bairro'=>['lab'=>'bairro','v'=>$bairro],
                                'area'=>['lab'=>'bairro','v'=>$bairro],
                                'filha de'=>['lab'=>'','v'=>$filhoa_de],
                                'filho de'=>['lab'=>'','v'=>$filhoa_de],
                                'nascida'=>['lab'=>'','v'=>$nascidoa],
                                'nascido'=>['lab'=>'','v'=>$nascidoa],
                            ];
                            if($dadosCon){
                                $doc .= str_replace('{lote}',$lote,$tm2);
                                $doc = $this->docConjuge($dadosCon,$doc,$b);
                            }else{
                                $doc .= str_replace('{lote}',$lote,$tm3);
                            }
                            foreach ($arr_sh as $ks => $vs) {
                                $doc = str_replace('{'.$ks.'}',$vs['v'],$doc);
                            }
                            //$doc = str_replace('{nome_beneficiario}',$nome_beneficiario,$doc);
                            if(is_array($b['config'])){
                                foreach ($b['config'] as $kc => $vc) {
                                    if($kc=='escolaridade'||$kc=='estado_civil'){
                                        if($kc=='escolaridade'){
                                            $ta = 'escolaridades';
                                        }
                                        if($kc=='estado_civil'){
                                            $ta = 'estadocivils';
                                        }
                                        $vc=Qlib::buscaValorDb([
                                            'tab'=>$ta,
                                            'campo_bus'=>'id',
                                            'valor'=>$vc,
                                            'select'=>'nome',
                                        ]);
                                    }
                                    if($kc=='data_uniao'){
                                        $vc = Qlib::dataExibe($vc);
                                    }
                                    if($kc=='nascimento')
                                        $vc = Qlib::dataExibe($vc);
                                    $doc = str_replace('{'.$kc.'}',$vc,$doc);
                                }
                            }
                        }else{
                            $ret = $this->loteSemBeneficiario($id_lote,$dLote);
                            return $ret;
                        }
                    }else{
                        $ret = $this->loteSemBeneficiario($id_lote,$dLote);
                        return $ret;
                    }
                }
                $ret = str_replace('{dados_beneficiario}',$doc,$tm1);
                foreach ($arr_sh as $ks => $vs) {
                    $ret = str_replace('{'.$ks.'}',$vs['v'],$ret);
                }
                if(is_array($dLote['config'])){
                    foreach ($dLote['config'] as $kl => $vl) {
                        if(!is_array($vl))
                            $ret = str_replace('{'.$kl.'}',$vl,$ret);
                    }
                }
            }elseif($dLote){
                $ret = $this->loteSemBeneficiario($id_lote,$dLote);
            }

        }
        return $ret;
    }
    public function loteSemBeneficiario($id_lote = null,$dLote=false)
    {
        $ret = false;
        if(!$dLote){
            $dLote = Lote::FindOrFail($id_lote);
        }
        $doc = false;
        if($d = $dLote){
            $tema = Documento::where('url','lote-sem-beneficiario')->where('excluido','n')->where('deletado','n')->get();
            $tm = $tema[0]->conteudo;
            $lote = $d['nome'];
            $bairro = Qlib::buscaValorDb([
                'tab'=>'bairros',
                'campo_bus'=>'id',
                'valor'=>$d['bairro'],
                'select'=>'nome',
            ]);
            $quadra = Qlib::buscaValorDb([
                'tab'=>'quadras',
                'campo_bus'=>'id',
                'valor'=>$d['quadra'],
                'select'=>'nome',
            ]);
            $doc = str_replace('{lote}',$lote,$tm);
            if(is_array($d['config'])){
                foreach ($d['config'] as $kl => $vl) {
                    if(is_array($vl)){

                    }else{
                        $doc = str_replace('{'.$kl.'}',$vl,$doc);
                    }
                }
            }
            $arr_sh = [
                'lote'=>['lab'=>'Tipo','v'=>$lote],
                'quadra'=>['lab'=>'Tipo','v'=>$quadra],
                'lote_extenso'=>['lab'=>'Tipo','v'=>Qlib::convert_number_to_words(Qlib::limpar_texto($lote))],
                'quadra_extenso'=>['lab'=>'Tipo','v'=>Qlib::convert_number_to_words($quadra)],
                'valor_lote'=>['lab'=>'Tipo','v'=>'0,00'],
                'valor_edificacao'=>['lab'=>'Tipo','v'=>'0,00'],
            ];
            foreach ($arr_sh as $ks => $vs) {
                $doc = str_replace('{'.$ks.'}',$vs['v'],$doc);
            }
        }
        $ret = $doc;
        return $ret;
    }
    public function docConjuge($dadosDeste = null,$tema=false,$dadosConjuge=false)
    {
        $ret = $tema?$tema:false;
        if($dadosDeste && $dadosConjuge){
            $dadCo = $dadosConjuge;
            $dc = $dadosDeste;
            if($dc['sexo']=='f'){
                if(isset($dadCo['config']['estado_civil']) && $dadCo['config']['estado_civil']==2){
                    $seu_companheiro = 'sua esposa';
                }else{
                    $seu_companheiro = 'sua companheira';
                }
            }else{
                if(isset($dadCo['config']['estado_civil']) && $dadCo['config']['estado_civil']==2){
                    $seu_companheiro = 'seu marido';
                }else{
                    $seu_companheiro = 'seu companheiro';
                }
            }
            $arr_sh = [
                'nome'=>['lab'=>'Nome','v'=>$dc['nome']],
                'cpf'=>['lab'=>'Nome','v'=>$dc['cpf']],
                //'seu companheiro'=>['lab'=>'Nome','v'=>$seu_companheiro],
            ];
            $ret = str_replace('{seu companheiro}',$seu_companheiro,$ret);
            foreach ($arr_sh as $ks => $vs) {
                $ret = str_replace('{'.$ks.'_conjuge}',$vs['v'],$ret);
            }
            if(is_array($dc['config'])){
                /*$data_uniao = @$dc['config']['data_uniao'];
                if(empty($data_uniao)){
                    $data_uniao=@$dadCo['config']['data_uniao'];
                }*/
                foreach ($dc['config'] as $kco => $vco) {
                    if($kco=='escolaridade'||$kco=='estado_civil'){
                        if($kco=='escolaridade'){
                            $ta = 'escolaridades';
                        }
                        if($kco=='estado_civil'){
                            $ta = 'estadocivils';
                        }
                        $vco=Qlib::buscaValorDb([
                            'tab'=>$ta,
                            'campo_bus'=>'id',
                            'valor'=>$vco,
                            'select'=>'nome',
                        ]);
                    }
                    if($kco=='nascimento')
                    $vco = Qlib::dataExibe($vco);
                    $ret = str_replace('{'.$kco.'_conjuge}',$vco,$ret);
                }
            }
        }
        return $ret;
    }
    public function listagemOcupantes($lotes = null)
    {
        $arr=[];
        $title = __('Listagem de ocupantes');
        $titulo = '';
        $tema = Documento::where('url','cabecario-lista-beneficiario')->where('excluido','n')->where('deletado','n')->get();
        $cabecario = $tema[0]->conteudo;
        if($lotes){
            $arr_lotes = explode('_',$lotes);
            if(is_array($arr_lotes)){
                foreach ($arr_lotes as $k => $v) {
                    $arr[$k] = $this->docBeneficiario($v);
                }
            }
        }
        return view('lotes.ocupantes',['cabecario'=>$cabecario,'arr'=>$arr,'titulo'=>$titulo,'title'=>$title]);
    }
    public function FichaOcupantes($lote = null,$familia)
    {

        $dados = $this->fichaOcupante($lote,$familia);
        $ben = isset($dados['familia']['beneficiario'])?$dados['familia']['beneficiario']:__('Ficha de ocupante');
        $title = $lote.'-'.strtoupper($ben);
        $titulo = '';
        return view('lotes.ficha_ocupantes',['dados'=>$dados['html'],'titulo'=>$titulo,'title'=>$title]);
    }
}
