<?php

namespace App\Providers;

use App\Models\Menu;
use App\Qlib\Qlib;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        Paginator::useBootstrap();
        /*
        $events->listen(BuildingMenu::class, function (BuildingMenu $event ) {

            $items['menus'] = app(Menu::class)::where('actived',true)->
            where('pai','')->
            get()->map(function(Menu $menu){
                $ret = [
                    'key'    =>  $menu['url'].'-'.$menu['id'],
                    'text'   =>  $menu['description'],
                    'route'  =>  [$menu['url'].'.index',['id'=>$menu['id']]],
                    'active' =>  [$menu['url'].'/'.$menu['id'].'/*'],
                    'icon'   =>  $menu['icon'],
                ];

                return $ret;
            });
            //dd($items);
            $event->menu->addAfter('painel',
                ...$items['menus']
            );
        });
        */

        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
           $ret = $this->exibeMenu($event);
            /*

            */
        });
    }
    /*
    private function exibeMenu($event,$sub=false,$key=false){
        if($sub){
            $event->menu->add($sub);
        }else{
            $event->menu->add('MENU DE NAVEGAÇÃO');
            $menu = new Menu;
            $menus = $menu->where('actived',true)->where('pai','')->get();
            foreach($menus as $menu){
                if(!$sub){
                    $submm = Menu::where('actived',true)->where('pai',$menu['url'])->get();
                    if(count($submm) != NULL){
                        $arrayMenu = [];
                        foreach($submm as $submenu){
                            $arrayMenu[] = array(
                                'key' => $submenu->key,
                                'text' => $submenu->description,
                                'url' => $submenu->url,
                                'icon' => $submenu->icon
                            );
                        }
                        $arr = [
                            'key' => $menu['url'],
                            'text' => $menu['description'],
                            'url' => $menu['url'],
                            'icon' => $menu['icon'],
                            'submenu' => $arrayMenu,
                        ];
                        $ret = $this->exibeMenu($event,$arr);
                    }else{

                        $arr_menu = [
                            'key' => $menu->url,
                            'text' => $menu->description,
                            'url' => $menu->url,
                            'icon' => $menu->icon,
                        ];
                        $event->menu->add($arr_menu);
                    }
                }else{

                }

            }
        }
    }*/
    private function exibeMenu($event,$sub=false,$key=false){
        if($sub){
            $event->menu->add($sub);
        }else{
            $menu = new Menu;
            $menus = $menu->where('actived',true)->where('pai','')->get();
            foreach($menus as $menu){
                if(!$sub){
                    $submm = Menu::where('actived',true)->where('pai',$menu['url'])->get();
                    if(count($submm) != NULL){
                        $arrayMenu = [];
                        foreach($submm as $submenu){
                            if(Qlib::ver_PermAdmin('ler',$submenu->url)){
                                $arrayMenu[] = array(
                                    'key' => $submenu->url,
                                    'text' => $submenu->description,
                                    'icon' => $submenu->icon,
                                    'route' => $submenu->route,
                                );
                            }
                        }
                        $arr = [
                            'key' => $menu['url'],
                            'text' => $menu['description'],
                            'icon' => $menu['icon'],
                            'submenu' => $arrayMenu,
                        ];

                        if($menu['route']){
                            $arr['route'] = $menu['route'];
                        }else{
                            $arr['url'] = '#';
                        }
                        if(Qlib::ver_PermAdmin('ler',$menu['url'])){
                            if($menu['categoria']){
                                $event->menu->add($menu['categoria']);
                            }
                            $ret = $this->exibeMenu($event,$arr);
                        }
                    }else{
                        if($menu->categoria){
                           $event->menu->add($menu->categoria);
                        }
                        $arr_menu = [
                            'key' => $menu->url,
                            'text' => $menu->description,
                            'icon' => $menu->icon,
                        ];
                        if($menu->route){
                            $arr_menu['route'] = $menu->route;
                        }else{
                            $arr_menu['url'] = '#';
                        }
                        if(Qlib::ver_PermAdmin('ler',$menu->url))
                            $event->menu->add($arr_menu);
                    }
                }
            }
        }
    }
}
