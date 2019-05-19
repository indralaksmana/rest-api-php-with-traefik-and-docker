<?php

namespace App\Repositories;

class StoreRepository extends BaseRepository
{
    /**
     * @var \App\Models\Store|\Illuminate\Database\Eloquent\Model
     */
    public $model;

    protected static $rules = [
        'user_id'       => 'required|exists:users,id',
        'name'          => 'required|min:5|max:255',
        'description'   => 'min:5',
        CONST_IMAGE     => 'image',
    ];

    protected static $rules_update = [
        'user_id'       => 'exists:users,id',
        'name'          => 'min:5|max:255',
        'description'   => 'min:5',
        CONST_IMAGE     => 'image',
    ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) ? 'App\Models\Store' : 'App\Models\StoreMg';
    }

}