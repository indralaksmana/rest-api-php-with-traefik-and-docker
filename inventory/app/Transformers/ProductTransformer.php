<?php

namespace App\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
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
    public function transform(Product $model)
    {
        return [
            'id'          => $model->id,
            'name'        => $model->name,
            'description' => $model->description,
            'harga'       => $model->harga,
            'stock'       => $model->stock,
            'store_id'    => $model->store_id,
            'created_at'  => $model->created_at,
            'updated_at'  => $model->updated_at,
        ];
    }

    public function includeImage(Product $model)
    {
        $images = $model->image()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($images, new ImageTransformer(), - 1);
    }

    public function includeCategory(Product $model)
    {
        $categories = $model->category()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($categories, new CategoryTransformer(), - 1);
    }

    public function includeStore(Product $model)
    {
        $store = $model->store()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($store, new StoreTransformer(), - 1);
    }
}