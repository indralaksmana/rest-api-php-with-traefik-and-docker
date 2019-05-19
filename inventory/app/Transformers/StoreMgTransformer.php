<?php

namespace App\Transformers;

use App\Models\StoreMg;
use League\Fractal\TransformerAbstract;

class StoreMgTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'user',
        'product'
    ];

    protected $defaultIncludes = [];

    /**
     * @Request StoreMg
     * @Response array
     */
    public function transform(StoreMg $model)
    {
        return [
            'id'            => $model->id,
            'user_id'       => $model->user_id,
            'name'          => $model->name,
            'description'   => $model->description,
            'image'         => \Storage::url("images/" . $model->image),
            'created_at'    => $model->created_at,
            'updated_at'    => $model->updated_at
        ];
    }

    public function includeUser(StoreMg $model)
    {
        $users = $model->user()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($users, new UserMgTransformer(), - 1);
    }

    public function includeProduct(StoreMg $model)
    {
        $images = $model->product()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($images, new ProductMgTransformer(), - 1);
    }
}