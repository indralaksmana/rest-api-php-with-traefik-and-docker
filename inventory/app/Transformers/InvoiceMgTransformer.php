<?php

namespace App\Transformers;

use App\Models\InvoiceMg;
use League\Fractal\TransformerAbstract;

class InvoiceMgTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'cart',
        'user',
        'promo'
    ];

    protected $defaultIncludes = [];

    /**
     * @Request InvoiceMg
     * @Response array
     */
    public function transform(InvoiceMg $model)
    {
        return [
            'id'            => $model->id,
            'total'         => $model->total,
            'user_id'       => $model->user_id,
            'address'       => $model->address,
            'status'        => $model->status,
            'method'        => $model->method,
            'promo_code'    => $model->promo_code,
            'created_at'    => $model->created_at,
        ];
    }

    public function includeCart(InvoiceMg $model)
    {
        $carts = $model->cart()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($carts, new CartMgTransformer(), - 1);
    }

    public function includeUser(InvoiceMg $model)
    {
        $users = $model->user()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($users, new UserMgTransformer(), - 1);
    }

    public function includePromo(InvoiceMg $model)
    {
        $promo = $model->promo()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($promo, new PromoMgTransformer(), - 1);
    }
}
