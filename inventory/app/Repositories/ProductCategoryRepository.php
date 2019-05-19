<?php

namespace App\Repositories;

class ProductCategoryRepository extends BaseRepository
{
    /**
     * @var \App\Models\ProductImage|\Illuminate\Database\Eloquent\Model
     */
    public $model;
    
    protected static $rules = [
        'product_id'  => 'required|exists:product,id',
        'category_id' => 'required|exists:category,id',
    ];

    protected static $rules_update = [
        'product_id'  => 'exists:product,id',
        'category_id' => 'exists:category,id',
    ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) ? 'App\Models\ProductCategory' : 'App\Models\ProductCategoryMg';
    }
    
}