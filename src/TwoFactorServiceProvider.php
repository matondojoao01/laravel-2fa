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
                __DIR__ . '/App/Http/Middleware' => app_path('Http/Middleware'),
            ], 'twofactorauth-middleware');

            $this->publishes([
                __DIR__ . '/App/Http/Controllers' => app_path('Http/Controllers/Auth'),
            ], 'twofactorauth-controllers');

            $this->publishes([
                __DIR__ . '/Models' => app_path('Models'), 
            ], 'twofactorauth-models');

            $this->publishes([
                __DIR__ . '/../routes/web.php' => base_path('routes/twofactorauth.php'),
            ], 'twofactorauth-routes');
        }
    }

    public function register()
    {

    }
}

