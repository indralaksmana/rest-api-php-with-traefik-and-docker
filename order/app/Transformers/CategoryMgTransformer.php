<?php

namespace App\Transformers;

use App\Models\CategoryMg;
use League\Fractal\TransformerAbstract;

class CategoryMgTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'product',
    ];

    protected $defaultIncludes = [];

    /**
     * @Request CategoryMg
     * @Response array
     */
    public function transform(CategoryMg $model)
    {
        return [
            'id'         => $model->id,
            'name'       => $model->name,
            'created_at' => $model->created_at,
        ];
    }

    public function includeProduct(CategoryMg $model)
    {
        $images = $model->product()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($images, new ProductMgTransformer(), - 1);
    }
}