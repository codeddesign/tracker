<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        DB::listen(function ($query) {
            if (!env('QUERY_LOG')) {
                return false;
            }

            Log::info($query->sql.' > '.$query->time);
        });
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }
}
