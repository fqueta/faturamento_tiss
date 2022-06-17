<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use App\Qlib\Qlib;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('is_admin', function ($user) {
            if(($user->id_permission==1 || $user->id_permission==2) && $user->ativo=='s'){
                return Response::allow();
            }else{
                return Response::deny('Você deve ser um administrador.');
            }
        });

        Gate::define('ler', function($user,$pagina=false){
            $ret = false;
            if($user->ativo=='s'){
                if($pagina){
                    $qlib = Qlib::ver_permAdmin('ler',$pagina);
                    if($qlib){
                        $ret = Response::allow();
                    }else{
                        $ret = Response::deny('Usuário sem autorização');
                    }
                }
            }
            return $ret;
        });

        Gate::define('ler_arquivos', function($user,$pagina=false){
            $ret = false;
            if($user->ativo=='s'){
                if($pagina){
                    $qlib = Qlib::ver_permAdmin('ler_arquivos',$pagina);
                    if($qlib){
                        $ret = Response::allow();
                    }else{
                        $ret = Response::deny('Usuário sem autorização');
                    }
                }
            }
            return $ret;
        });

        Gate::define('create', function($user,$pagina=false){
            $ret = false;
            if($pagina){
                $qlib = Qlib::ver_permAdmin('create',$pagina);
                if($qlib){
                    $ret = Response::allow();
                }else{
                    $ret = Response::deny('Usuário sem autorização para cadastro');
                }
            }
            return $ret;
        });
        Gate::define('update', function($user,$pagina=false){
            $ret = false;
            if($pagina){
                $qlib = Qlib::ver_permAdmin('update',$pagina);
                if($qlib){
                    $ret = Response::allow();
                }else{
                    $ret = Response::deny('Usuário sem autorização de atualização');
                }
            }
            return $ret;
        });

        Gate::define('delete', function($user,$pagina=false){
            $ret = false;
            if($pagina){
                $qlib = Qlib::ver_permAdmin('delete',$pagina);
                if($qlib){
                    $ret = Response::allow();
                }else{
                    $ret = Response::deny('Usuário sem autorização de deletar');
                }
            }
            return $ret;
        });
    }
}
