<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Qlib\Qlib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public $user;
    function __construct()
    {
        $user = Auth::user();
        $this->user = $user;
    }
    function store($config=false){
        $user = $this->user;
        $ret =false;
        if(isset($config['action']) && isset($config['action'])){
            $action = isset($config['action'])?$config['action']:false;
            $tab = isset($config['tab'])?$config['tab']:false;
            $conf = isset($config['config'])?$config['config']:[];
            $ret = Event::create([
                'token'=>uniqid(),
                'user_id'=>$user->id,
                'action'=>$action,
                'tab'=>$tab,
                'config'=>Qlib::lib_array_json($conf),
            ]);
        }
        return $ret;
    }
}
