<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use ResponseHTTP\Response\Traits\ModelREST;

class PromoMg extends Model
{
    use ModelREST;

    protected $table = 'promo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'value',
        'begin_date',
        'end_date',
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
                'self',
                $this->href(),
                $this->method('GET')
            ]
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoice()
    {
        return $this->hasMany(\App\Models\InvoiceMg::class, 'promo_code', 'code');
    }
}
