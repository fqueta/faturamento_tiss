<?php

namespace App\Http\Controllers;

use App\Http\Controllers\admin\CobrancaController;
use App\Models\User;
use App\Models\relatorio;
use App\Models\Assistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\Qlib\Qlib;
//use Spatie\Permission\Models\Role;
//use Spatie\Permission\Models\Permission;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $user;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
        $this->routa = 'painel';
    }

    public function teste(){
      //$dados = $request->all();
      //var_dump($dados);
      return view('teste');
    }
    public function upload(Request $request){
      $dados = $request->all();
      var_dump($dados);
    }


    public function index()
    {
        $this->authorize('ler', $this->routa);
        $controlerFamilias = new FamiliaController(Auth::user());
        $controlerMapas = new MapasController(Auth::user());
        //$dadosFamilias = $controlerFamilias->queryFamilias();
        $dadosFamilias = false;
        $id_quadra_home = Qlib::qoption('id_quadra_home')?Qlib::qoption('id_quadra_home'):@$_GET['id_qh'];
        if($id_quadra_home){
            $dadosMp = $controlerMapas->queryQuadras($id_quadra_home);
        }else{
            $dadosMp = false;
        }
        $config = [
            'c_familias'=>$dadosFamilias,
            'mapa'=>$dadosMp,
        ];
        return view('home',[
            'config'=>$config,
        ]);
    }
    public function transparencia()
    {
        $this->authorize('ler', 'transparencia');
        $controlerFamilias = new FamiliaController(Auth::user());
        $controlerMapas = new MapasController(Auth::user());
        $dadosFamilias = $controlerFamilias->queryFamilias();
        $id_quadra_home = Qlib::qoption('id_quadra_home')?Qlib::qoption('id_quadra_home'):@$_GET['id_qh'];
        if($id_quadra_home){
            $dadosMp = $controlerMapas->queryQuadras($id_quadra_home);
        }else{
            $dadosMp = false;
        }
        $config = [
            'c_familias'=>$dadosFamilias,
            'mapa'=>$dadosMp,
        ];
        return view('home',[
            'config'=>$config,
        ]);
    }
    public function resumo(){

    }
}
