<?php

namespace App\Providers;

use Core\Providers\CoreServiceProvider as ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{

    /**
     * Register providers dependency
     */
    protected function registerProviders()
    {
        $this->app->register(\App\Providers\HttpServiceProvider::class);
        $this->app->register(\CacheSystem\CacheServiceProvider::class);
        $this->app->register(\Spatie\Permission\PermissionServiceProvider::class);
        $this->app->register(\Aws\Laravel\AwsServiceProvider::class);
        $this->app->register(\Folklore\GraphQL\LumenServiceProvider::class);
        $this->app->register(\Illuminate\Filesystem\FilesystemServiceProvider::class);
    }
}
