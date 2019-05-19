<?php
return [
    /*
     * Load Type graphQL
     */
    'model'     => [
        'User'           => App\Http\GraphQL\Type\User\UserType::class,
        'UserPagination' => App\Http\GraphQL\Type\User\UserPaginationType::class,
    ],

    /*
     * Load contracts type
     */
    'contracts' => [
        'PaginationMeta' => Core\Http\GraphQL\Type\PaginationMetaType::class,
        'Timestamp'      => Core\Http\GraphQL\Type\TimestampType::class,
    ],
];