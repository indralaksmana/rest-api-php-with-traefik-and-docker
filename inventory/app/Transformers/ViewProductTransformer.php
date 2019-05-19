<?php

namespace App\Transformers;

use App\Models\ViewProduct;
use League\Fractal\TransformerAbstract;

class ViewProductTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'image',
        'category',
        'store',
    ];

    protected $defaultIncludes = [];

    /**
     * @Request Product
     * @Response array
     */
    public function transform(ViewProduct $model)
    {
        return [
            'id'            => $model->id,
            'name'          => $model->name,
            'description'   => $model->description,
            'harga'         => $model->harga,
            'stock'         => $model->stock,
            'store_id'      => $model->store_id,
            'total_sold'    => $model->total_sold,
            'total_review'  => $model->total_review,
            'created_at'    => $model->created_at,
            'updated_at'    => $model->updated_at,
            'last_review'   => $model->last_review,
        ];
    }

    public function includeImage(ViewProduct $model)
    {
        $images = $model->image()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($images, new ImageTransformer(), - 1);
    }

    public function includeCategory(ViewProduct $model)
    {
        $categories = $model->category()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($categories, new CategoryTransformer(), - 1);
    }

    public function includeStore(ViewProduct $model)
    {
        $store = $model->store()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($store, new StoreTransformer(), - 1);
    }
}