<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->setupConfig();
        $this->registerSystem();
        $this->registerServices();
        $this->registerMiddleware();
        $this->registerProviders();
    }

    /**
     * Load config
     */
    protected function setupConfig()
    {
        $this->app->configure('hashing');
    }

    /**
     * Register system providers Kernel/Console/Filesystem etc..
     */
    protected function registerSystem() { }

    /**
     * Register Services
     */
    protected function registerServices() { }

    /**
     * Register middleware
     */
    protected function registerMiddleware()
    {
        //always when routes are called
        $this->app->middleware([]);

        $this->app->routeMiddleware([
            'jwt.verify' => \App\Http\Middleware\JwtMiddleware::class,
        ]);
    }

    /**
     * Register providers dependency
     */
    protected function registerProviders()
    {
        if ($this->app->environment() !== 'production') {
            /**
             * To generate proper ide-helper uncomment lines below before ide-helper:generate process
             * comment it again after generate process is finished
             */
            if ($this->app->runningInConsole()) {
                $this->app->configure('app');
                $this->app->configure('ide-helper');
            }

            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}