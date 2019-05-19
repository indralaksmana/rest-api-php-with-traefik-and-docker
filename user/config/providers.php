<?php
return [
    /*
     * Providers always load in Core
     */
    'global'     => [
        'core'    => \App\Providers\CoreServiceProvider::class,
        'auth'    => \Core\Providers\AuthServiceProvider::class,
        'graphql' => \Core\Providers\GraphQLServiceProvider::class,
        'app'     => App\Providers\AppServiceProvider::class,
    ],

    /*
     * Providers load when env is production
     */
    'production' => [
        //
    ],

    /*
     * Providers load when env is local
     */
    'local'      => [
        //
    ],

    'alias' => [
        'Eloquent' => (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) ? Illuminate\Database\Eloquent\Model::class : Jenssegers\Mongodb\Eloquent\Model::class,
    ],
];