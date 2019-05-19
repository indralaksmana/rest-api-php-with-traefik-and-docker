<?php

namespace App\Transformers;

use App\Models\CartMg;
use League\Fractal\TransformerAbstract;

class CartMgTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'product',
        'image',
        'category',
        'user'
    ];

    protected $defaultIncludes = [];

    /**
     * @Request CartMg
     * @Response array
     */
    public function transform(CartMg $model)
    {
        return [
            'id'            => $model->id,
            'created_at'    => $model->created_at,
            'price'         => $model->price,
            'status'        => $model->status,
            'product_id'    => $model->product_id,
            'user_id'       => $model->user_id,
            'qty'           => $model->qty,
            'invoice_id'    => $model->invoice_id,
        ];
    }

    public function includeProduct(CartMg $model)
    {
        $products = $model->product()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($products, new ProductMgTransformer(), - 1);
    }

    public function includeImage(CartMg $model)
    {
        $images = $model->image()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($images, new ImageMgTransformer(), - 1);
    }

    public function includeCategory(CartMg $model)
    {
        $categories = $model->category()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($categories, new CategoryMgTransformer(), - 1);
    }

    public function includeUser(CartMg $model)
    {
        $users = $model->user()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($users, new UserMgTransformer(), - 1);
    }
}
