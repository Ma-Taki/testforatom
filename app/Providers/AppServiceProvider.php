<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Log;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // query log
        if (config('app.env') !== 'production') {
            DB::listen(function ($query) {
                Log::info("Query Time:".$query->time." Sql:".$query->sql." data:".implode(', ', $query->bindings));
            });
        }

        // custom validation
        // 管理画面：メルマガ配信
        Validator::extend('email_array', function($attribute, $value, $parameters, $validator) {
            $email_array = explode(',', $value);
            foreach ($email_array as $email) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                    return false;
                }
            }
            return true;
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
