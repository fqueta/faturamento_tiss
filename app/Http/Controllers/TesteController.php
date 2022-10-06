<?php

namespace App\Http\Controllers;

use App\Models\Familia;
use App\Models\User;
use App\Qlib\Qlib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TesteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $user = Auth::user();
        //$doc = new LotesController($user);
        //$this->escutaSessao($request);
        //$request->session()->put('verifica_faturas.acao','liberar');
        //$request->session()->put('nome','Patricia');
        $ac = new GeradorXmlController();
        $ret = $ac->guiaResumoInternacao(['8'],8);
        dd($ret);
        // $ret = (new FaturamentosController())->guiasLote(8);
        // dd($ret);
        //return view('teste',$config);
    }
    public function testeF($var = null)
    {
        return 'ola t';
    }
    private function escutaSessao(Request $request)
    {
        Qlib::lib_print($request->session()->all());
    }
    public function ajax(){
        $limit = isset($_GET['limit']) ?$_GET['limit'] : 50;
        $page = isset($_GET['page']) ?$_GET['page'] : 1;
        $site=false;

        $urlApi = $site?$site: 'https://po.presidenteolegario.mg.gov.br';
        $link = $urlApi.'/api/diaries?page='.$page.'&limit='.$limit;
        $link_html = dirname(__FILE__).'/html/front.html';
        $dir_img = $urlApi.'/uploads/posts/image_previews/{id}/thumbnail/{image_preview_file_name}';
        $dir_file = $urlApi.'/uploads/diaries/files/{id}/original/{file_file_name}';

        //$arquivo = $this->carregaArquivo($link_html);
        //$temaHTML = explode('<!--separa--->',$arquivo);
        $api = file_get_contents($link);
        $arr_api = Qlib::lib_json_array($api);
        /*
        $tema1 = '<ul id="conteudo" class="list-group">{tr}</ul>';
        $tema2 = '<li class="list-group-item" itemprop="headline"><a href="{link_file}" target="_blank">{file_file_name} – {date}</a></li>';
        $tr=false;
        if(isset($arr_api['data']) && !empty($arr_api['data'])){
          foreach ($arr_api['data'] as $key => $value) {
              $link = false;
              $link_file = str_replace('{id}',$value['id'],$dir_file);
              $link_file = str_replace('{file_file_name}',$value['file_file_name'],$link_file);


              $conteudoPost = isset($value['content'])?:false;
              $date = false;
              $time = false;
              $datetime = str_replace(' ','T',$value['date']);
              $d = explode(' ',$value['date']);

              if(isset($d[0])){
                $date = Qlib::dataExibe($d[0]);
              }
              if(isset($d[1])){
                $time = $d[1];
              }
              $file_name = str_replace('.pdf','',$value['file_file_name']);
              $file_name = str_replace('.PDF','',$file_name);
              $tr .= str_replace('{file_file_name}',$file_name,$tema2);
              $tr = str_replace('{link}',$link,$tr);
              $tr = str_replace('{link_file}',$link_file,$tr);
              $tr = str_replace('{time}',$time,$tr);
              $tr = str_replace('{date}',$date,$tr);
              $tr = str_replace('{description}',$value['description'],$tr);
              $tr = str_replace('{datetime}',$datetime,$tr);
          }
        }
        $link_veja_mais = '/diario-oficial';
        $ret = str_replace('{tr}',$tr,$tema1);
        //$ret = str_replace('{id_sec}',$id_sec,$ret);
        $ret = str_replace('{link_veja_mais}',$link_veja_mais,$ret);
        */
        return response()->json($arr_api);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
