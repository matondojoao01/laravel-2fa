<?php

namespace Matondo;

use Illuminate\Support\ServiceProvider;

class TwoFactorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load views
        // This method loads the views from the specified directory, 
        // allowing them to be used in the package with the name 'twofactorauth'
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'twofactorauth');

        if ($this->app->runningInConsole()) {
            // Publish migrations
            // This allows the package's migrations to be copied to the Laravel application's migrations directory
            $this->loadMigrationsFrom(__DIR__ . '/resources/migrations');

            // Publish views
            // This allows the package's views to be copied to the application's views directory, 
            // enabling customization by the user
            $this->publishes([
                __DIR__ . '/resources/views' => resource_path('views/vendor/twofactorauth'),
            ], 'twofactorauth-views');

            // Publish migrations
            // Publishes the package's migrations to the Laravel migrations directory
            $this->publishes([
                __DIR__ . '/resources/migrations' => database_path('migrations'),
            ], 'twofactorauth-migrations');

            // Publish middlewares
            // Publishes the package's middleware to the application's middleware directory
            $this->publishes([
                __DIR__ . '/App/Http/Middleware' => app_path('Http/Middleware'),
            ], 'twofactorauth-middleware');

            // Publish controllers
            // Publishes the package's controllers to the application's controllers directory
            $this->publishes([
                __DIR__ . '/App/Http/Controllers' => app_path('Http/Controllers'),
            ], 'twofactorauth-controllers');

            // Publish models
            // Publishes the package's models to the application's models directory
            $this->publishes([
                __DIR__ . '/App/Models' => app_path('Models'), 
            ], 'twofactorauth-models');

            // Publish routes
            // Publishes the package's routes to the application's routes directory
            $this->publishes([
                __DIR__ . '/../routes/web.php' => base_path('routes/twofactorauth.php'),
            ], 'twofactorauth-routes');

            // Publish language files in the root /lang
            // Publishes the package's language files to the application's root lang directory
            $this->publishes([
                __DIR__ . '/../lang' => base_path('lang'),
            ], 'twofactorauth-lang');
        }
    }

    public function register()
    {
        
    }
}
