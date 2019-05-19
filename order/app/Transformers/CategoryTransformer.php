<?php

namespace App\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'product',
    ];

    protected $defaultIncludes = [];

    /**
     * @Request Category
     * @Response array
     */
    public function transform(Category $model)
    {
        return [
            'id'         => $model->id,
            'name'       => $model->name,
            'created_at' => $model->created_at,
        ];
    }

    public function includeProduct(Category $model)
    {
        $images = $model->product()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($images, new ProductTransformer(), - 1);
    }
}