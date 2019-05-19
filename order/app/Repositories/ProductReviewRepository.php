<?php

namespace App\Repositories;

class ProductReviewRepository extends BaseRepository
{
    protected static $rules = [
        'product_id' => 'required|exists:product,id',
        'user_id' => 'exists:users,id',
        'review' => 'required|min:5',
        'rating' => 'required|numeric'
    ];

    protected static $rules_update = [
        'product_id' => 'exists:product,id',
        'user_id' => 'exists:users,id',
        'review' => 'min:5',
        'rating' => 'numeric'
    ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) ? 'App\Models\ProductReview' : 'App\Models\ProductReviewMg';
    }

}