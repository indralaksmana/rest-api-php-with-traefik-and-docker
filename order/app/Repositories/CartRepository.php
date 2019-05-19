<?php

namespace App\Repositories;

class CartRepository extends BaseRepository
{
    protected static $rules = [
        'price' => 'required|numeric',
        'status' => 'min:3|max:255',
        'product_id' => 'required|exists:product,id',
        'user_id' => 'exists:users,id',
        'qty' => 'required|numeric',
        'invoice_id' => 'exists:invoice,id'
    ];

    protected static $rules_update = [
        'price' => 'numeric',
        'status' => 'min:3|max:255',
        'product_id' => 'exists:product,id',
        'user_id' => 'exists:users,id',
        'qty' => 'numeric',
        'invoice_id' => 'exists:invoice,id'
    ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) ? 'App\Models\Cart' : 'App\Models\CartMg';
    }

}