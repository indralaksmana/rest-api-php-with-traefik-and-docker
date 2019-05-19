<?php

if ( ! defined('CONST_DEFAULT')) {
    define('CONST_DEFAULT', 'default');
}

return [
    'prefix' => 'api/graph',

    'domain' => null,

    'routes' => '{graphql_schema?}',

    'controllers' => \Folklore\GraphQL\GraphQLController::class . '@query',

    'variables_input_name' => 'variables',

    'middleware' => [],

    'middleware_schema' => [
        CONST_DEFAULT => [],
        'v1'          => [],
    ],

    'headers' => [],

    'json_encoding_options' => 0,

    'schema' => env('API_STABLE_VERSION', CONST_DEFAULT),

    'schemas' => [
        CONST_DEFAULT => [
            'query'    => [],
            'mutation' => []
        ],
        //Version Graph API
        'v1'          => [
            'query'    => [
                //
            ],
            'mutation' => [
                //
            ]
        ]
    ],

    'resolvers' => [
        CONST_DEFAULT => [
        ],
    ],

    'defaultFieldResolver' => null,

    'error_formatter' => [\Folklore\GraphQL\GraphQL::class, 'formatError'],

    'security' => [
        'query_max_complexity'  => null,
        'query_max_depth'       => null,
        'disable_introspection' => false
    ]
];
