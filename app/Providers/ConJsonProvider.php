<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Lib\Tools\ConJson;

class ConJsonProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton('ConJson',function (){
               return new ConJson();
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
