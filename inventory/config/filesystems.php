<?php

if ( ! defined('CONST_PUBLIC')) {
    define('CONST_PUBLIC', 'public');
}
if ( ! defined('CONST_LOCAL')) {
    define('CONST_LOCAL', 'local');
}
if ( ! defined('CONST_DRIVER')) {
    define('CONST_DRIVER', 'driver');
}

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
|
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', CONST_PUBLIC),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */

    'disks' => [
        CONST_LOCAL  => [
            CONST_DRIVER => CONST_LOCAL,
            'root'       => storage_path('app'),
        ],
        CONST_PUBLIC => [
            CONST_DRIVER => CONST_LOCAL,
            'root'       => storage_path('app/public'),
            'url'        => env('APP_URL') . '/storage',
            'visibility' => CONST_PUBLIC,
        ],
        'root'       => [
            CONST_DRIVER => CONST_LOCAL,
            'root'       => '.',
        ],
        'command'    => [
            CONST_DRIVER => CONST_LOCAL,
            'root'       => '.',
        ],
        'logs'       => [
            CONST_DRIVER => CONST_LOCAL,
            'root'       => storage_path('logs'),
        ],
    ]

];