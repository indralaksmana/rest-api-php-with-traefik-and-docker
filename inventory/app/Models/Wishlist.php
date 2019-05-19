<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use ResponseHTTP\Response\Traits\ModelREST;

class Wishlist extends Model
{
    use ModelREST;

    protected $table = 'wishlist';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'product_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at'];

    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        $this->bootREST();
        parent::__construct($attributes);
    }

    private function bootREST()
    {
        $this->setBasicPath();
        $this->setLinks([
            [
                $this->rel('product'),
                $this->href('product'),
                $this->method('GET')
            ],
            [
                $this->rel('category'),
                $this->href('category'),
                $this->method('GET')
            ],
            [
                'self',
                $this->href(),
                $this->method('GET')
            ]
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function category()
    {
        return $this->BelongsToMany(\App\Models\Category::class, 'product_category', 'product_id', 'category_id', 'product_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function image()
    {
        return $this->hasMany(\App\Models\ProductImage::class, 'product_id', 'product_id');
    }
}
