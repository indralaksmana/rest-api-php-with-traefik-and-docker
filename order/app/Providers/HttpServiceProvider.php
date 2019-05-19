<?php

namespace App\Providers;

use ResponseHTTP\HttpServiceProvider as ServiceProvider;

class HttpServiceProvider extends ServiceProvider
{
    /**
     * Register middleware
     */
    protected function registerMiddleware()
    {
        $this->app->middleware([
            \App\Http\Middleware\CorsMiddleware::class
        ]);
    }
}
