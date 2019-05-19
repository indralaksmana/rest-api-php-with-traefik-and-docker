<?php

namespace App\Repositories;

class InvoiceRepository extends BaseRepository
{
    protected static $rules = [
        'total' => 'required|numeric',
        'user_id' => 'required|exists:users,id',
        'address' => 'min:5',
        'status' => 'min:3|max:255',
        'method' => 'min:5|max:255',
        'promo_code' => 'exists:promo,code',
    ];

    protected static $rules_update = [
        'total' => 'numeric',
        'user_id' => 'exists:users,id',
        'address' => 'min:5',
        'status' => 'min:3|max:255',
        'method' => 'min:5|max:255',
        'promo_code' => 'exists:promo,code',
    ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) ? 'App\Models\Invoice' : 'App\Models\InvoiceMg';
    }

}