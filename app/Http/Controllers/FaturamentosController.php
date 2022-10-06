<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Qlib\Qlib;
use App\Models\User;
use App\Models\_upload;
use App\Models\Faturamento;
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
    public function __construct()
    {
        $this->middleware('auth');
        $this->user = Auth::user();
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

    public function camposData(){
        $meses = Qlib::Meses();
        $anos = [];
        foreach (range((date('Y')-4),(date('Y')+4)) as $key => $v) {
            $anos[$v] = $v;
        }
        return [
            'mes'=>[
                'label'=>'Mês referência',
                'active'=>false,
                'type'=>'select',
                'arr_opc'=>$meses,
                'exibe_busca'=>'d-block',
                'event'=>'',
                'tam'=>'4',
                'class'=>'',
                'campo'=>'mes',
                'exibe_busca'=>true,
                'selected'=>date('m'),
                'option_select'=>true,
                'title'=>'',
                'id'=>'type',
                'cp_busca'=>'',
            ],
            'ano'=>[
                'label'=>'Ano referência',
                'active'=>false,
                'type'=>'select',
                'arr_opc'=>$anos,
                'exibe_busca'=>'d-block',
                'selected'=>date('Y'),
                'event'=>'',
                'tam'=>'4',
                'class'=>'',
                'campo'=>'ano',
                'exibe_busca'=>true,
                'option_select'=>true,
                'title'=>'',
                'id'=>'type',
                'cp_busca'=>'',
            ],
        ];
    }

    public function fechar(Request $request)
    {
        $title = __('Fechamentos de guias');
        $titulo = $title;
        //$GuiasController = new GuiasController($this->user);

        $config = [
            'campos_busca'=>$this->campos(),
            'campos_data'=>$this->camposData(),
            'ac' =>'alt',
        ];
        $this->authorize('ler', $this->url);
        $title = 'Guias Cadastradas';
        $titulo = $title;
        if(isset($get['filter'])){
            $siga = true;
        }else{
            $siga = false;
        }
        $queryGuia = $this->queryGuia($_GET,['siga'=>$siga,'lote'=>'n']);
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
        $lote = isset($config['lote'])?$config['lote']:false;
        $siga = isset($config['siga'])?$config['siga']:false;
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
        if($lote){
            $guia->where('lote','=',$lote);
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
    public function queryFaturas($get=false,$config=false)
    {
        $ret = false;
        $get = isset($_GET) ? $_GET:[];
        $ano = date('Y');
        $mes = date('m');
        //$lote = isset($config['lote'])?$config['lote']:false;
        $siga = isset($config['siga'])?$config['siga']:true;
        //$todasFamilias = Familia::where('excluido','=','n')->where('deletado','=','n');
        $config = [
            'limit'=>isset($get['limit']) ? $get['limit']: 50,
            'order'=>isset($get['order']) ? $get['order']: 'desc',
        ];
        if($this->type){
            $guia =  Faturamento::where('excluido','=','n')->where('type','=',$this->type)->where('deletado','=','n')->orderBy('id',$config['order']);
        }else{
            $guia =  Faturamento::where('excluido','=','n')->where('deletado','=','n')->orderBy('id',$config['order']);
        }
        /*if($lote){
            $guia->where('lote','=',$lote);
        }*/

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
            $user = Auth::user();
            $arr_id = explode('_',$ids);
            if(is_array($arr_id)){
                $ac = new GeradorXmlController();
                $mes = isset($_POST['mes'])?$_POST['mes']:date('m');
                $ano = isset($_POST['ano'])?$_POST['ano']:date('Y');
                $token = isset($_POST['token'])?$_POST['token']:uniqid();
                $acao = isset($_POST['acao'])?$_POST['acao']:'cad';
                $type = isset($_POST['type'])?$_POST['type']:'internaçao';
                $id_lote = isset($_POST['id_lote'])?$_POST['id_lote']:false;
                $nome = isset($_POST['nome'])?$_POST['nome']:'Lote salvo por '.$user->name.' em '.date('d/m/Y');
                if($acao=='alt'){
                    if($id_lote){
                        //$dadosFatura = Faturamento::Find($id_lote);
                        //if(!$dadosFatura);
                    }else{
                        return $ret;
                    }
                }elseif($acao=='cad'){
                    $salvarLote = Faturamento::create([
                        'mes'=>$mes,
                        'ano'=>$ano,
                        'token'=>$token,
                        'nome'=>$nome,
                        'type'=>$type,
                    ]);
                    $id_lote = isset($salvarLote['id'])?$salvarLote['id']:$salvarLote->id;
                }
                if(isset($id_lote)){
                    $geraGuia = $ac->guiaResumoInternacao($arr_id,$id_lote);
                    $ret['geraGuia'] = $geraGuia;
                    $ret['ids'] = $ids;
                    if($geraGuia['exec']){
                        foreach ($arr_id as $kg => $vg) {
                            $ret['upd_gia'][$kg] = Guia::where('id',$vg)->update([
                                'id_lote'=>$id_lote,
                                'lote'=>'s',
                            ]);
                        }
                        $alte_lote = Faturamento::where('id',$id_lote)->update([
                            'config'=>Qlib::lib_array_json($geraGuia),
                            'id_operadora'=>@$geraGuia['id_operadora'],
                            'guias'=>@$geraGuia['guias'],
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
    public function gerenciarLote(){
        $title = __('Fechamentos de guias');
        $titulo = $title;
        //$GuiasController = new GuiasController($this->user);

        $config = [
            'campos_busca'=>$this->campos(),
            'campos_data'=>$this->camposData(),
            'ac' =>'alt',
        ];
        $this->authorize('ler', $this->url);
        $title = 'Guias Cadastradas';
        $titulo = $title;
        $siga = false;
        if(isset($get['filter'])){
            $siga = true;
        }
        $queryGuia = $this->queryFaturas($_GET,['siga'=>$siga]);
        $queryGuia['config']['exibe'] = 'html';
        $routa = $this->routa;
        $ret = [
            'title'=>$title,
            'titulo'=>$titulo,
            'config'=>$config,
            'view'=>$this->view,
            'guias'=>$queryGuia['guia'],

        ];
        return view('faturamento.gerenciar',$ret);
    }
    public function listarLote(Request $request,$id){
        $arr_id = false;
        if($id){
            $arr_id = explode('_',$id);
        }
        $get = $request->all();
        $ret['exec'] = false;
        $ret['lista'] = false;
        $ret['get'] = $get;
        if(is_array($arr_id) && ($id_lote=$get['id_lote'])){
            $guias = $this->guiasLote($id_lote);
            if($guias['exec'] && ($g = $guias['guias'])){
                foreach ($g as $k => $guia) {
                        $ret['exec'] = true;
                        //$guia = Guia::Find($v);
                        if(isset($guia['config'])){
                            $guia['config'] = Qlib::lib_json_array($guia['config']);
                        }
                        $ret['lista'][$k] = $guia;
                }
            }
        }
        return $ret;
    }
    public function removerGuiasLote(Request $request,$id){
        $get = $request->all();
        $arr_id = false;
        $ret['exec']=false;
        if($id){
            $arr_id = explode('_',$id);
        }
        $ret['arr_id'] = $arr_id;
        if(isset($get['id_lote']) && ($id_lote=$get['id_lote'])){
            // $dF = Faturamento::Find($id_lote);
            //$guias_fatura = Qlib::lib_json_array($dF['guias']);
            $guias_fatura = $this->guiasLote($id_lote);
            if(is_array($arr_id)){
                foreach ($arr_id as $k => $v) {
                    $ret['atualiza'][$k] = Guia::where('id',$v)->update(['lote'=>'n','id_lote'=>0]);
                }
            }
            $ret['arr_id_pos'] = $arr_id;
            $guias_rest = [];
            $i=0;
            foreach ($guias_fatura as $key => $value) {
                $guias_rest[$i] = $value;
                $i++;
            }

            $data = ['guias'=>$guias_rest];
            $guias = $this->guiasLote($id_lote);
            $ret['excluir_lote'] = false;
            if(!$guias['exec']){
                $data['excluido'] ='s';
                $data['reg_excluido'] =['motivo'=>'Excluido por remoção de guias'];
                $ret['atualiza_lote'] = Faturamento::where('id',$id_lote)->update($data);
                $ret['excluir_lote'] = true;
            }
            //if($ret['atualiza_lote']){
                $ret['exec'] = true;
                $ret['mes'] = 'Removido com sucesso';
            //}
            // $ret['data'] = $data;
        }
        //dd($ret);
        return $ret;
    }
    public function guiasLote($id_lote = null)
    {
        $ret['guias'] = false;
        $ret['exec'] = false;
        if($id_lote){
            $guias = Guia::where('id_lote','=',$id_lote)->where('lote','=','s')->get();
            if($guias->count()>0){
                $ret['exec'] = true;
                //foreach ($guias as $k => $v) {
                    //$guias[$k]['config'] = @$v['config'];
                    //dd($v['config']);
                //}
            }
            $ret['guias'] = $guias;


        }
        return $ret;
    }
}
