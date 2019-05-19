<?php

namespace App\Transformers;

use App\Models\ProductImage;
use League\Fractal\TransformerAbstract;

class ImageTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    protected $defaultIncludes = [];

    /**
     * @Request ProductImage
     * @Response array
     */
    public function transform(ProductImage $model)
    {
        return [
            'id'         => $model->id,
            'image'      => \Storage::url("images/" . $model->image),
            'created_at' => $model->created_at
        ];
    }
}