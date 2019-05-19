<?php

namespace App\Transformers;

use App\Models\Promo;
use League\Fractal\TransformerAbstract;

class PromoTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'invoice',
    ];

    protected $defaultIncludes = [];

    /**
     * @Request Promo
     * @Response array
     */
    public function transform(Promo $model)
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

    public function includeInvoice(Promo $model)
    {
        $invoice = $model->invoice()->get();

        //resourceKey -1 if you want to exclude arrayKey from the data included by the transformer when use KeyArraySerializer
        return $this->collection($invoice, new InvoiceTransformer(), - 1);
    }
}
