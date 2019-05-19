<?php

namespace App\Transformers;

use App\Models\Cart;
use League\Fractal\TransformerAbstract;

class CartTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'product',
        'image',
        'category',
        'user'
    ];

    protected $defaultIncludes = [];

    /**
     * @Request Cart
     * @Response array
     */
    public function transform(Cart $model)
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

    public function includeProduct(Cart $model)
    {
        $products = $model->product()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($products, new ProductTransformer(), - 1);
    }

    public function includeImage(Cart $model)
    {
        $images = $model->image()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($images, new ImageTransformer(), - 1);
    }

    public function includeCategory(Cart $model)
    {
        $categories = $model->category()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($categories, new CategoryTransformer(), - 1);
    }

    public function includeUser(Cart $model)
    {
        $users = $model->user()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($users, new UserTransformer(), - 1);
    }
}
