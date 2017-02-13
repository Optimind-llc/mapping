<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // app('Dingo\Api\Auth\Auth')->extend('basic', function ($app) {
        //    return new \Dingo\Api\Auth\Provider\Basic($app['auth'], 'email');
        // });

        // app('Dingo\Api\Auth\Auth')->extend('jwt', function ($app) {
        //    return new \Dingo\Api\Auth\Provider\JWT($app['Tymon\JWTAuth\JWTAuth']);
        // });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
        }
    }
}
