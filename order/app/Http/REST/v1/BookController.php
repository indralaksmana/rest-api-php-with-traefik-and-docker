<?php

namespace App\Http\REST\v1;

use Core\Http\REST\Controller\ApiBaseController;
use Core\Helpers\Serializer\KeyArraySerializer;

use App\Repositories\InvoiceRepository as Invoice;
use App\Transformers\InvoiceTransformer;
use App\Transformers\InvoiceMgTransformer;
use App\Repositories\CartRepository as Cart;
use App\Transformers\CartTransformer;
use App\Transformers\CartMgTransformer;
use App\Repositories\UserRepository as User;
use App\Transformers\UserTransformer;
use App\Transformers\UserMgTransformer;
use App\Repositories\PromoRepository as Promo;
use App\Transformers\PromoTransformer;
use App\Transformers\PromoMgTransformer;

use Gate;
use Illuminate\Http\Request;

/**
 * Invoice resource representation.
 *
 * @Resource("Invoice", uri="/invoice")
 */
class BookController extends ApiBaseController
{
    /**
     * @var Invoice
     */
    private $invoice;

    /**
     * @var Cart
     */
    private $cart;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Promo
     */
    private $promo;

    /**
     * UserController constructor.
     *
     * @param Invoice $invoice
     * @param Cart $cart
     * @param User $user
     */
    public function __construct(Invoice $invoice, Cart $cart, User $user, Promo $promo)
    {
        parent::__construct();

        $this->invoice = $invoice;
        $this->cart = $cart;
        $this->user = $user;
        $this->promo = $promo;

        //$this->middleware('jwt.verify', ['except' => ['showInvoice', 'storeInvoice', 'assignCartInvoice', 'getUser']]);
    }

    public function showInvoice($id)
    {
        $model = $this->invoice->find($id);
        if ($model) {
            $data = $this->api->includes(['cart'])
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

    public function storeInvoice(Request $request)
    {
        $data = $request->all();

        if ($data['promo_code'] == "") {
            unset($data['promo_code']);
        }

        $validator = $this->invoice->validateRequest($data);

        if ($validator->status() == "200") {
            $task = $this->invoice->create($data);
            if ($task) {
                return (int) $task->id;
            }

            return $this->response->errorInternal();
        }

        return $validator;
    }

    public function assignCartInvoice($invoice_id, $user_id)
    {
        $model = $this->cart->model->where([
            ['status', '=', 'incomplete'],
            ['user_id', '=', $user_id]
        ])->get();

        if ($numberOfCart = $model->count()) {
            $request_all = array(
                'status' => 'pending',
                'invoice_id' => $invoice_id
            );
            foreach($model as $k => $v) {
                $cart_id = $v->id;

                $validator = $this->cart->validateRequest($request_all, "update");
                if ($validator->status() == "200") {
                    $task = $this->cart->update($request_all, $cart_id);

                    if ($task) {
                        if ($k == ($numberOfCart-1)) {
                            return $this->response->success("Invoice installed to cart");
                        }
                        continue;
                    }
                }

                return $validator;
            }
        }

        return $this->response->errorBadRequest();
    }

    public function getUser($id)
    {
        $model = $this->user->find($id);
        if ($model) {
            $data = $this->api
                ->serializer(new KeyArraySerializer('user'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->item($model, new UserTransformer());
            } else {
                $data = $data->item($model, new UserMgTransformer());
            }

            return $data;
        }

        return array();
    }

    /**
     * Checkout
     *
     * Get a JSON representation of new/updated Cart.
     *
     * @GET("/book/checkout/{user_id}")
     * @Versions({"v1"})
     * @Request({"user_id": "1"})
     * @Response(200, body={"cart":[{"id": 1,"created_at": "2018-12-10 06:08:57","price": 67728,"status": "incomplete","product_id": 1,"user_id": 3,"qty": 1,"invoice_id": null,"product": {"id": 1,"name": "Ergonomic Linen Bottle","description": "Adventures, till she was coming to, but it makes me grow larger, I can say.' This was such a nice.","harga": 67728,"stock": 20}}], "user":{"id": 3,"email": "isai.wiza@example.org","name": "Marisa Gerlach","username": "christ43","phone": "09876543","address": "Street no. 3"}})
     */
    public function checkout(Request $request)
    {
        $models = $this->cart->model->where([
            ['status', '=', 'incomplete'],
            ['user_id', '=', $request->user_id],
        ])->get();

        if ($models->count()) {
            $data = $this->api
                //->includes(['product', 'image', 'category', 'user'])
                ->includes(['product', 'category', 'user'])
                ->serializer(new KeyArraySerializer('cart'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->collection($models, new CartTransformer());
            } else {
                $data = $data->collection($models, new CartMgTransformer());
            }

            $user = array('user' => $data['cart'][0]['user']);

            // retransform output
            $new_data = array();
            foreach($data['cart'] as $k => $v) {
                unset($v['user']);
                $new_data['cart'][$k] = $v;
                //$new_data['cart'][$k]['product'][0]['image'] = $v['image'];
                $new_data['cart'][$k]['product'][0]['category'] = $v['category'];
                unset($new_data['cart'][$k]['image']);
                unset($new_data['cart'][$k]['category']);
            }

            return $this->response->data(array_merge($new_data, $user), 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Commit
     *
     * Set invoice status to lock, also cart status to pending
     *
     * @POST("/book/commit/{user_id}")
     * @Versions({"v1"})
     * @Request({"user_id": "1"})
     * @Response(200, body={"id": 1,"total": 93356,"user_id": 3,"address": "Street no. A","status": "waiting payment","method": "transfer atm","created_at": "2018-12-10 06:37:21","cart": [{"id": 1,"created_at": "2018-12-10 06:08:57","price": 67728,"status": "pending","product_id": 1,"user_id": 3,"qty": 1,"invoice_id": 1}]})
     */
    public function commit(Request $request)
    {
        $cart = $this->cart->model->where([
            ['status', '=', 'incomplete'],
            ['user_id', '=', $request->user_id],
        ])->get();

        $total = 0;
        foreach($cart as $v) {
            $total += (float) $v->price * (int) $v->qty;
        }

        if ($request->promo_code) {
            $current_date = date("Y-m-d");
            $promo = $this->promo->model->where([
                ['code', '=', $request->promo_code],
                ['begin_date', '<=', $current_date],
                ['end_date', '>=', $current_date],
            ])->first();

            if ($promo) {
                $total -= (float) $promo->value;
            } else {
                return $this->response->error("Promo Code not valid");
            }
        }

        $request->request->add([
            'user_id' => $request->user_id,
            'total' => $total,
        ]);

        // create invoice
        $invoice_id = $this->storeInvoice($request);

        if (is_int($invoice_id)) {
            // update cart
            $this->assignCartInvoice($invoice_id, $request->user_id);

            // show invoice
            return $this->showInvoice($invoice_id);
        }

        return $this->response->errorInternal();
    }

}
