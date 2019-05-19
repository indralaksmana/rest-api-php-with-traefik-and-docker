<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use ResponseHTTP\Response\Traits\ModelREST;

class ViewProductMg extends Model
{
    use ModelREST;

    protected $table = 'view_product';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'harga', 'stock', 'store_id', 'total_sold', 'total_review', 'last_review'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function category()
    {
        return $this->belongsToMany(\App\Models\CategoryMg::class, 'product_category');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function image()
    {
        return $this->hasMany(\App\Models\ProductImageMg::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo(\App\Models\StoreMg::class);
    }
}
