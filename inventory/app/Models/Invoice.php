<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use ResponseHTTP\Response\Traits\ModelREST;

class Invoice extends Model
{
    use ModelREST;

    protected $table = 'invoice';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'total', 'user_id', 'address', 'status', 'method', 'promo_code'
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
                $this->rel('cart'),
                $this->href('cart'),
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cart()
    {
        return $this->hasMany(\App\Models\Cart::class);
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
    public function promo()
    {
        return $this->belongsTo(\App\Models\Promo::class, 'promo_code', 'code');
    }
}
