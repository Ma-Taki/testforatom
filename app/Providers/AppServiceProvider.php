<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Log;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        app("db")->listen(function($e) {
            Log::debug($e->sql);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
