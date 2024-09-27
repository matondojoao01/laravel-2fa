<?php

namespace Matondo;

use Illuminate\Support\ServiceProvider;

class TwoFactorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'twofactorauth');

        if ($this->app->runningInConsole()) {

            $this->loadMigrationsFrom(__DIR__ . '/resources/migrations');

            $this->publishes([
                __DIR__ . '/resources/views' => resource_path('views/vendor/twofactorauth'),
            ], 'twofactorauth-views');

            $this->publishes([
                __DIR__ . '/resources/migrations' => database_path('migrations'),
            ], 'twofactorauth-migrations');

            $this->publishes([
                __DIR__ . '/Http/Middleware' => app_path('Http/Middleware'),
            ], 'twofactorauth-middleware');
        }

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    
    public function register()
    {
        
    }
}