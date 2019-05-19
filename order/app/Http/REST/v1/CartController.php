<?php

namespace App\Http\REST\v1;

use Core\Http\REST\Controller\ApiBaseController;
use Core\Helpers\Serializer\KeyArraySerializer;

use App\Repositories\CartRepository as Cart;
use App\Transformers\CartTransformer;
use App\Transformers\CartMgTransformer;
use App\Repositories\ProductRepository as Product;
use App\Transformers\ProductTransformer;
use App\Transformers\ProductMgTransformer;

use Illuminate\Http\Request;
use Gate;

/**
 * Cart resource representation.
 *
 * @Resource("Cart", uri="/cart")
 */
class CartController extends ApiBaseController
{
    /**
     * @var Cart
     */
    private $cart;

    /**
     * @var Product
     */
    private $product;

    /**
     * Initialize @var Cart
     *
     * @Request Cart
     *
     */
    public function __construct(Cart $cart, Product $product)
    {
        parent::__construct();
        $this->cart = $cart;
        $this->product = $product;

        //$this->middleware('jwt.verify');
    }

    /**
     * Display a listing of resource.
     *
     * Get a JSON representation of all cart.
     *
     * @Get("/trolley/{user_id}")
     * @Versions({"v1"})
     * @Request({"user_id": "1"})
     * @Response(200, body={"id":1,"total":1500000,"status":"status name","product_id":1,"user_id":1,"stock":1,"invoice_id":1})
     */
    public function index(Request $request)
    {
        $models = $this->cart->model->where([
            ['status', '=', 'incomplete'],
            ['user_id', '=', $request->user_id],
        ])->get();

        if ($models->count()) {
            $data = $this->api
                //->includes(['product', 'image', 'category'])
                ->includes(['product', 'category'])
                ->serializer(new KeyArraySerializer('cart'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->collection($models, new CartTransformer());
            } else {
                $data = $data->collection($models, new CartMgTransformer());
            }

            // retransform output
            $new_data = array();
            foreach($data['cart'] as $k => $v) {
                $new_data['cart'][$k] = $v;
                //$new_data['cart'][$k]['product'][0]['image'] = $v['image'];
                $new_data['cart'][$k]['product'][0]['category'] = $v['category'];
                //unset($new_data['cart'][$k]['image']);
                unset($new_data['cart'][$k]['category']);
            }

            return $this->response->addModelLinks(new $this->cart->model())->data($new_data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Show specific cart
     *
     * Get a JSON representation of the cart.
     *
     * @Get("/cart/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, body={"id":1,"total":1500000,"status":"status name","product_id":1,"user_id":1,"stock":1,"invoice_id":1})
     */
    public function show($id)
    {
        $model = $this->cart->find($id);
        if ($model) {
            $data = $this->api
                ->includes(['product', 'image', 'category'])
                ->serializer(new KeyArraySerializer('cart'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->item($model, new CartTransformer());
            } else {
                $data = $data->item($model, new CartMgTransformer());
            }

            // retransform output
            $data['cart']['product'][0]['image'] = $data['cart']['image'];
            $data['cart']['product'][0]['category'] = $data['cart']['category'];
            unset($data['cart']['image']);
            unset($data['cart']['category']);

            return $this->response->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Create a new cart
     *
     * Get a JSON representation of new cart.
     *
     * @Post("/cart")
     * @Versions({"v1"})
     * @Request(array -> {"user_id":1,"product_id":1,"qty":1})
     * @Response(200, success or error)
     */
    public function store(Request $request)
    {
        $product = $this->product->find($request->product_id);

        $model = $this->cart->model->where([
            ['status', '=', 'incomplete'],
            ['user_id', '=', $request->user_id],
            ['product_id', '=', $request->product_id]
        ])->first();

        $request->request->add([
            'price' => $product->harga
        ]);

        // update
        if ($model) {
            $request->qty = ($request->qty) ? $request->qty : 1;
            $task = $this->cart->update([
                'qty' => ((int) $model->qty + (int) $request->qty)
            ], $model->id);
            if ($task) {
                return $this->response->success("Cart updated");
            }
        }

        // create
        $validator = $this->cart->validateRequest($request->all());

        if ($validator->status() == "200") {
            $task = $this->cart->create($request->all());
            if ($task) {
                return $this->response->success("Cart created");
            }

            return $this->response->errorInternal();
        }

        return $validator;
    }

    public function storeBulk(Request $request)
    {
        // delete
        $this->cart->model->where([
            ['status', '=', 'incomplete'],
            ['user_id', '=', $request->user_id]
        ])->delete();

        // create
        $response = array();
        foreach($request->product_id as $k => $v) {
            $product = $this->product->find($v);

            $qty = ($request->qty[$k]) ? $request->qty[$k] : 1;

            $request_all = array(
                'user_id' => $request->user_id,
                'product_id' => $v,
                'qty' => $qty,
                'price' => $product->harga
            );

            $status = array(
                'error' => "Internal Error"
            );

            // create
            $validator = $this->cart->validateRequest($request_all);
            if ($validator->status() == "200") {
                $task = $this->cart->create($request_all);
                if ($task) {
                    $status = array(
                        'success' => "Cart created"
                    );
                }
            } else {
                $status = json_decode(json)($validator);
            }
            $response[] = array(
                'product_id' => $request_all['product_id'],
                'status' => $status
            );
        }

        return $this->response->data($response);
    }

    /**
     * Update cart
     *
     * Get a JSON representation of update cart.
     *
     * @Put("/cart/update/{id}")
     * @Versions({"v1"})
     * @Request(array -> {"total":1200000,"status":"status name","product_id":1,"user_id":1,"stock":1,"invoice_id":1}, id)
     * @Response(200, success or error)
     */
    public function update(Request $request)
    {
        $failed = false;
        /*if (Gate::denies('cart.update', $request)) {
            $failed = true;
        }*/

        $validator = $this->cart->validateRequest($request->all(), "update");
        if ( ! $failed && $validator->status() == "200") {
            $task = $this->cart->update($request->all(), $request->id);
            if ($task) {
                return $this->response->success("Cart updated");
            }

            $failed = true;
        }

        if ($failed) {
            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Delete a specific cart
     *
     * Get a JSON representation of get cart.
     *
     * @Delete("/cart/delete/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, success or error)
     */
    public function delete(Request $request)
    {
        /*if (Gate::denies('cart.delete', $request)) {
            return $this->response->errorInternal();
        }*/

        $task = $this->cart->delete($request->id);
        if ($task) {
            return $this->response->success("Cart deleted");
        }

        return $this->response->errorInternal();
    }

}
