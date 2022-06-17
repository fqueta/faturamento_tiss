<?php

namespace App\Http\Controllers;

use App\Models\_upload;
use App\Models\Quadra;
use App\Qlib\Qlib;
use App\Models\User;
use Illuminate\Http\Request;

class MapasController extends Controller
{
    protected $user;
    public $routa;
    public $view;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->routa = 'quadras';
        $this->view = $this->routa;
    }
    public function quadras($id = null)
    {
        $this->authorize('ler', $this->routa);
        $ret = $this->queryQuadras($id);
        return view('mapas.quadras',$ret);
    }

    public function queryQuadras($id_quadra = null)
    {
        $ret = false;
        if($id_quadra){
            $dados = Quadra::FindOrFail($id_quadra);
            $config = false;
            $ret['exec'] = false;
            $title = 'Quadra '.$dados['nome'];
            $titulo = $title;
            $ret['mens'] = false;
            $ret['title'] = $title;
            $ret['titulo'] = $titulo;
            if($dados['token']){
                $file = _upload::where('token_produto','=',$dados['token'])->get();
                if(!empty($file) && isset($file[0]['config'])){
                    $arr_confile = Qlib::lib_json_array($file[0]['config']);
                    $dados['lotes'] = Qlib::totalReg('lotes',"WHERE quadra='".$dados['id']."' AND ".Qlib::compleDelete());
                    $dados['familias'] = Qlib::totalReg('familias',"WHERE quadra='".$dados['id']."' AND ".Qlib::compleDelete());
                    $dados['arr_bairros'] = Qlib::sql_array("SELECT id,nome FROM bairros WHERE ativo='s' AND ".Qlib::compleDelete(),'nome','id');
                    $dados['arr_quadras'] = Qlib::sql_array("SELECT id,nome FROM quadras WHERE ativo='s' AND bairro='".$dados['bairro']."' AND ".Qlib::compleDelete(),'nome','id');
                    if(isset($file[0]['pasta']) && $file[0]['pasta'] && $arr_confile['extenssao']=='svg'){
                        $config = [
                            'dados'=>$dados,
                            'local'=>'quadras',
                            'svg_file'=>'/storage/'.$file[0]['pasta'],
                        ];
                    }else{
                        $config['mens'] = Qlib::formatMensagemInfo('Erro Arquivo de mapa inválido!','danger');
                    }
                    if($dados['arr_bairros']){
                        $nb = $dados['arr_bairros'][$dados['bairro']];
                        $title = str_replace('Quadra','Área '.$nb.' - Quadra',$title);
                        $titulo = str_replace('Quadra','Área '.$nb.' - Quadra',$titulo);
                    }
                }else{
                    $config['mens'] = Qlib::formatMensagemInfo('Erro Arquivo não encontrado!','danger');
                }
            }else{
                $config['mens'] = Qlib::formatMensagemInfo('Erro Token inválido!','danger');
            }
            if($config){
                $config['ac']='alt';
                $config['route']=$this->routa;
                $config['id']=$dados['id'];
                $ret = [
                    'config'=>$config,
                    'exec'=>true,
                    'title'=>$title,
                    'titulo'=>$titulo,
                ];
            }
        }
        return $ret;
    }
    public function exibeMapas($config = null)
    {
        //$this->authorize('ler', $this->routa);
        if(isset($config['dados']) && isset($config['svg_file'])){
            return view('mapas.todos',['config'=>$config]);
        }elseif(isset($config['mens'])){
            return $config['mens'];
        }else{
            return Qlib::formatMensagemInfo('Dados insuficientes','danger');
        }
    }
}
