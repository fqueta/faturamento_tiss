<?php

namespace App\Providers;

use App\Qlib\Qlib;
use Illuminate\Support\ServiceProvider;

class QlibServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('Qlib',function(){
          return new Qlib;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
