<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class ExampleTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];

    protected $defaultIncludes = [];

    public function transform()
    {
        return [];
    }

}