<?php

namespace App\Repositories;

class PromoRepository extends BaseRepository
{
    /**
     * @var \App\Models\Promo|\Illuminate\Database\Eloquent\Model
     */
    public $model;

    protected static $rules = [
        'code' => 'required|min:5|max:255',
        'value' => 'required|numeric',
    ];

    protected static $rules_update = [
        'code' => 'min:5|max:255',
        'value' => 'numeric',
    ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) ? 'App\Models\Promo' : 'App\Models\PromoMg';
    }

}