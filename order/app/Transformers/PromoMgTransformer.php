<?php

namespace App\Transformers;

use App\Models\PromoMg;
use League\Fractal\TransformerAbstract;

class PromoMgTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'invoice',
    ];

    protected $defaultIncludes = [];

    /**
     * @Request PromoMg
     * @Response array
     */
    public function transform(PromoMg $model)
    {
        return [
            'code'          => $model->code,
            'value'         => $model->value,
            'begin_date'    => $model->begin_date,
            'end_date'      => $model->end_date,
            'created_at'    => $model->created_at,
            'updated_at'    => $model->updated_at,
        ];
    }

    public function includeInvoice(PromoMg $model)
    {
        $invoice = $model->invoice()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($invoice, new InvoiceMgTransformer(), - 1);
    }
}
