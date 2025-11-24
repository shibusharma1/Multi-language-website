<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register SetLocale middleware into the 'web' middleware group at runtime
        // This avoids editing Kernel.php
        $router = $this->app->make(Router::class);
        $router->pushMiddlewareToGroup('web', \App\Http\Middleware\SetLocale::class);
    }
}
