<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    protected $defaultIncludes = [];

    /**
     * @Request User
     * @Response array
     */
    public function transform(User $model)
    {
        return [
            'id'                => $model->id,
            'email'             => $model->email,
            //'password'          => $model->password,
            'username'          => $model->username,
            'name'              => $model->name,
            'phone'             => $model->phone,
            'address'           => $model->address,
            //'remember_token'    => $model->remember_token,
            'created_at'        => $model->created_at,
            'updated_at'        => $model->updated_at
        ];
    }
}