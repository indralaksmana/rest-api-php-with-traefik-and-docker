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
        \Intervention\Image\ImageServiceProvider::class,
        \Maatwebsite\Excel\ExcelServiceProvider::class,
        \Bschmitt\Amqp\AmqpServiceProvider::class,
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
        'Excel'    => Maatwebsite\Excel\Facades\Excel::class,
        'Image'    => Intervention\Image\Facades\Image::class,
        'Eloquent' => (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) ? Illuminate\Database\Eloquent\Model::class : Jenssegers\Mongodb\Eloquent\Model::class,
    ],
];