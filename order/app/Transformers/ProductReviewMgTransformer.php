<?php

namespace App\Transformers;

use App\Models\ProductReviewMg;
use League\Fractal\TransformerAbstract;

class ProductReviewMgTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'product',
        'image',
        'category',
        'user'
    ];

    protected $defaultIncludes = [];

    /**
     * @Request ProductReviewMg
     * @Response array
     */
    public function transform(ProductReviewMg $model)
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

    public function includeProduct(ProductReviewMg $model)
    {
        $products = $model->product()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($products, new ProductMgTransformer(), - 1);
    }

    public function includeImage(ProductReviewMg $model)
    {
        $images = $model->image()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($images, new ImageMgTransformer(), - 1);
    }

    public function includeCategory(ProductReviewMg $model)
    {
        $categories = $model->category()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($categories, new CategoryMgTransformer(), - 1);
    }

    public function includeUser(ProductReviewMg $model)
    {
        $users = $model->user()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($users, new UserMgTransformer(), - 1);
    }
}
