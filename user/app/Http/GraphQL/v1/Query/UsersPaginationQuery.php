<?php

namespace App\Http\GraphQL\v1\Query;

use ApiService;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use App\Repositories\UserRepository as User;

class UsersPaginationQuery extends Query
{

    public $model;

    protected $attributes = [
        'name' => 'usersPaginate',
        'uri'  => 'query=query{usersPagination(perPage:15,page:1){user{id},meta{total}}}'
    ];

    public function __construct(User $model, $attributes = [])
    {
        parent::__construct($attributes);
        $this->model = $model;
    }

    public function type()
    {
        return GraphQL::pagination(GraphQL::type('User'));
    }

    public function args()
    {
        return [
            'page'    => [
                'name'        => 'page',
                'description' => 'The page',
                'type'        => Type::int()
            ],
            'perPage' => [
                'name'        => 'perPage',
                'description' => 'The count',
                'type'        => Type::int()
            ]
        ];
    }

    /**
     * note: to get page argument:
     * $page    = array_get($args, 'page', 1);
     * 
     * @param $root
     * @param $args
     *
     * @return mixed
     */
    public function resolve($root, $args)
    {
        $perPage = array_get($args, 'perPage', 15);

        return $this->model->paginate($perPage, ['*']);
    }

}