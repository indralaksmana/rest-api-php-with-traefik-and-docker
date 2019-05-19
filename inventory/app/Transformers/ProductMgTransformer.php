<?php

namespace App\Transformers;

use App\Models\ProductMg;
use League\Fractal\TransformerAbstract;

class ProductMgTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'image',
        'category',
        'store',
    ];

    protected $defaultIncludes = [];

    /**
     * @Request ProductMg
     * @Response array
     */
    public function transform(ProductMg $model)
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

    public function includeImage(ProductMg $model)
    {
        $images = $model->image()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($images, new ImageMgTransformer(), - 1);
    }

    public function includeCategory(ProductMg $model)
    {
        $categories = $model->category()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($categories, new CategoryMgTransformer(), - 1);
    }

    public function includeStore(ProductMg $model)
    {
        $store = $model->store()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($store, new StoreMgTransformer(), - 1);
    }
}