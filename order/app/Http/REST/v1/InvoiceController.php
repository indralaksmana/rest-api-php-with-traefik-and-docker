<?php

namespace App\Http\REST\v1;

use Core\Http\REST\Controller\ApiBaseController;
use Core\Helpers\Serializer\KeyArraySerializer;

use App\Repositories\InvoiceRepository as Invoice;
use App\Transformers\InvoiceTransformer;
use App\Transformers\InvoiceMgTransformer;

use Gate;
use Illuminate\Http\Request;

/**
 * Invoice resource representation.
 *
 * @Resource("Invoice", uri="/invoice")
 */
class InvoiceController extends ApiBaseController
{
    /**
     * @var Invoice
     */
    private $invoice;

    /**
     * UserController constructor.
     *
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        parent::__construct();
        $this->invoice = $invoice;

        //$this->middleware('jwt.verify');
    }

    /**
     * Display a listing of resource.
     *
     * Get a JSON representation of all invoice.
     *
     * @Get("/invoice/history/{user_id}")
     * @Versions({"v1"})
     * @Request({"user_id": "1"})
     * @Response(200, body=[{"id":1,"total":1500000,"user_id":1,"address":"ship address","status":"packaging","method":"transfer atm"}])
     */
    public function index(Request $request)
    {
        $request->page = ($request->page) ? $request->page : 1;

        $models = $this->invoice->model->where([
            ['status', '!=', 'open'],
            ['user_id', '=', $request->user_id],
        ])->paginate(10, ['*'], 'page', $request->page);

        if ($models) {
            $data = $this->api
                ->includes(['cart', 'user', 'promo'])
                ->serializer(new KeyArraySerializer('invoice'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->paginate($models, new InvoiceTransformer());
            } else {
                $data = $data->paginate($models, new InvoiceMgTransformer());
            }

            return $this->response->addModelLinks(new $this->invoice->model())->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Show a specific invoice
     *
     * Get a JSON representation of get invoice.
     *
     * @Get("/invoice/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, body={"id":1,"total":1500000,"user_id":1,"address":"ship address","status":"open","method":"internet banking"})
     */
    public function show($id)
    {
        $model = $this->invoice->find($id);
        if ($model) {
            $data = $this->api
                ->includes(['cart', 'user', 'promo'])
                ->serializer(new KeyArraySerializer('invoice'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->item($model, new InvoiceTransformer());
            } else {
                $data = $data->item($model, new InvoiceMgTransformer());
            }

            return $this->response->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

}
