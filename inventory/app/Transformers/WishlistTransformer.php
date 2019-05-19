<?php

namespace App\Transformers;

use App\Models\Wishlist;
use League\Fractal\TransformerAbstract;

class WishlistTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'user',
        'product',
        'category',
        'image'
    ];

    protected $defaultIncludes = [];

    /**
     * @Request Wishlist
     * @Response array
     */
    public function transform(Wishlist $model)
    {
        return [
            'id'         => $model->id,
            'user_id'    => $model->user_id,
            'product_id' => $model->product_id,
            'created_at' => $model->created_at,
        ];
    }

    public function includeUser(Wishlist $model)
    {
        $users = $model->user()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($users, new UserTransformer(), - 1);
    }

    public function includeProduct(Wishlist $model)
    {
        $images = $model->product()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($images, new ProductTransformer(), - 1);
    }

    public function includeCategory(Wishlist $model)
    {
        $categories = $model->category()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($categories, new CategoryTransformer(), - 1);
    }

    public function includeImage(Wishlist $model)
    {
        $images = $model->image()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($images, new ImageTransformer(), - 1);
    }
}