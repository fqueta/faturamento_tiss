<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Qlib\Qlib;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\BairroController;

class selectorController extends Controller
{
    protected $user;
    public $routa;
    public function __construct(User $user)
    {
        $this->middleware('auth');
        $this->user = $user;
        $this->routa = 'etapas';
    }

    public function index(Request $request,$campos=false)
    {
        if($request->has('table')){
            $table = $request->get('table');
            $controller = $request->get('controller');
            $dados = DB::table($table)->get();
            //Qlib::lib_print($dados);
            $config = [
                'campos'=>$campos,
            ];
            return view('qlib.selector.index',['config'=>$config]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,$campos=false)
    {
        if($request->has('table') && $campos){
            $table = $request->get('table');
            $controller = $request->get('controller');
            $dados = DB::table($table)->get();
            //Qlib::lib_print($dados);
            $config = [
                'campos'=>$campos,
            ];
            return view('qlib.selector.createedit',['config'=>$config]);
        }
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
