<?php

namespace App\Http\GraphQL\v1\Query;

use App\Repositories\UserRepository as User;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\Type;

class UsersQuery extends Query
{
    const CONST_EMAIL = 'email';

    public $model;
    protected $attributes = [
        'name' => 'users',
        'uri'  => 'query=query{users{id,name,email,timestamp{}}}}'
    ];

    public function __construct(User $model, $attributes = [])
    {
        parent::__construct($attributes);
        $this->model = $model;
    }

    public function type()
    {
        return Type::listOf(GraphQL::type('User'));
    }

    public function args()
    {
        return [
            'id'              => ['name' => 'id', 'type' => Type::int()],
            self::CONST_EMAIL => ['name' => self::CONST_EMAIL, 'type' => Type::string()],
            'name'            => ['name' => 'name', 'type' => Type::string()],
        ];
    }

    public function resolve($root, $args)
    {
        if (isset($args['id'])) {
            return array($this->model->find($args['id']));
        } else if (isset($args[ self::CONST_EMAIL ])) {
            return array($this->model->findBy(self::CONST_EMAIL, $args[ self::CONST_EMAIL ]));
        } else {
            return $this->model->all();
        }
    }

}