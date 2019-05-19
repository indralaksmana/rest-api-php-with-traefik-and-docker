<?php

namespace App\Repositories;

class ProductRepository extends BaseRepository
{

    /**
     * @var \App\Models\Product|\Illuminate\Database\Eloquent\Model
     */
    public $model;
    
    protected static $rules = [
        'user_id'     => 'required|exists:users,id',
        'name'        => 'required|min:5|max:255',
        'description' => 'required|min:5',
        'harga'       => 'required|numeric',
        'stock'       => 'required|numeric',
        'store_id'    => 'required|exists:store,id',
    ];

    protected static $rules_update = [
        'user_id'     => 'exists:users,id',
        'name'        => 'min:5|max:255',
        'description' => 'min:5',
        'harga'       => 'numeric',
        'stock'       => 'numeric',
        'store_id'    => 'exists:store,id',
    ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) ? 'App\Models\Product' : 'App\Models\ProductMg';
    }

}