<?php

namespace App\Transformers;

use App\Models\ProductImageMg;
use League\Fractal\TransformerAbstract;

class ImageMgTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    protected $defaultIncludes = [];

    /**
     * @Request ProductImageMg
     * @Response array
     */
    public function transform(ProductImageMg $model)
    {
        return [
            'id'         => $model->id,
            'image'      => \Storage::url("images/" . $model->image),
            'created_at' => $model->created_at
        ];
    }
}