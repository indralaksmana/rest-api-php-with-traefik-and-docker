<?php

namespace App\Transformers;

use App\Models\ProductReview;
use League\Fractal\TransformerAbstract;

class ProductReviewTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'product',
        'image',
        'category',
        'user'
    ];

    protected $defaultIncludes = [];

    /**
     * @Request ProductReview
     * @Response array
     */
    public function transform(ProductReview $model)
    {
        return [
            'id'            => $model->id,
            'product_id'    => $model->product_id,
            'user_id'       => $model->user_id,
            'review'        => $model->review,
            'rating'        => $model->rating,
            'created_at'    => $model->created_at,
            'updated_at'    => $model->updated_at,
        ];
    }

    public function includeProduct(ProductReview $model)
    {
        $products = $model->product()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($products, new ProductTransformer(), - 1);
    }

    public function includeImage(ProductReview $model)
    {
        $images = $model->image()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($images, new ImageTransformer(), - 1);
    }

    public function includeCategory(ProductReview $model)
    {
        $categories = $model->category()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($categories, new CategoryTransformer(), - 1);
    }

    public function includeUser(ProductReview $model)
    {
        $users = $model->user()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($users, new UserTransformer(), - 1);
    }
}
