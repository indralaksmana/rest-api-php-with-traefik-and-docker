<?php

namespace App\Transformers;

use App\Models\WishlistMg;
use League\Fractal\TransformerAbstract;

class WishlistMgTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'user',
        'product',
        'category',
        'image'
    ];

    protected $defaultIncludes = [];

    /**
     * @Request WishlistMg
     * @Response array
     */
    public function transform(WishlistMg $model)
    {
        return [
            'id'         => $model->id,
            'user_id'    => $model->user_id,
            'product_id' => $model->product_id,
            'created_at' => $model->created_at,
        ];
    }

    public function includeUser(WishlistMg $model)
    {
        $users = $model->user()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($users, new UserMgTransformer(), - 1);
    }

    public function includeProduct(WishlistMg $model)
    {
        $images = $model->product()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($images, new ProductMgTransformer(), - 1);
    }

    public function includeCategory(WishlistMg $model)
    {
        $categories = $model->category()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($categories, new CategoryMgTransformer(), - 1);
    }

    public function includeImage(WishlistMg $model)
    {
        $images = $model->image()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($images, new ImageMgTransformer(), - 1);
    }
}