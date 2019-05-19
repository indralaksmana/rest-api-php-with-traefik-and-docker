<?php

namespace App\Http\REST\v1;

use Core\Http\REST\Controller\ApiBaseController;
use Core\Helpers\Serializer\KeyArraySerializer;

use App\Repositories\StoreRepository as Store;
use App\Transformers\StoreTransformer;
use App\Transformers\StoreMgTransformer;
use App\Repositories\ProductRepository as Product;
use App\Transformers\ProductTransformer;
use App\Transformers\ProductMgTransformer;

use Illuminate\Support\Facades\Cache;

use Illuminate\Http\Request;
use Gate;

/**
 * Store resource representation.
 *
 * @Resource("Store", uri="/store")
 */
class StoreController extends ApiBaseController
{
    /**
     * @var Store
     */
    private $store;

    /**
     * @var Product
     */
    private $product;

    /**
     * Initialize @var Store
     *
     * @Request Store
     *
     */
    public function __construct(Store $store, Product $product)
    {
        parent::__construct();
        $this->store = $store;
        $this->product = $product;
        $this->middleware('jwt.verify', ['except' => ['index', 'show', 'search']]);
    }

    /**
     * Display a listing of resource.
     *
     * Get a JSON representation of all store.
     *
     * @Get("/store")
     * @Versions({"v1"})
     * @Response(200, body={"id":1,"name":"Store Name"})
     */
    public function index()
    {

        if ($data = Cache::store(env('CACHE_DRIVER', CONST_REDIS))->get("store.data")) {
            return $this->response->addModelLinks(new $this->store->model())->data(json_decode($data), 200);
        }

        $models = $this->store->all();
        if ($models) {
            $data = $this->api
                ->serializer(new KeyArraySerializer('store'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->collection($models, new StoreTransformer());
            } else {
                $data = $data->collection($models, new StoreMgTransformer());
            }

            // save into cache
            Cache::store(env('CACHE_DRIVER', CONST_REDIS))->put("store.data", json_encode($data), env('CACHE_MINUTES', 60));

            return $this->response->addModelLinks(new $this->store->model())->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Show specific store
     *
     * Get a JSON representation of the store.
     *
     * @Get("/store/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, body={"id":1,"name":"Store Name"})
     */
    public function show($id)
    {
        if ($data = Cache::store(env('CACHE_DRIVER', CONST_REDIS))->get("store.data.{$id}")) {
            return $this->response->data(json_decode($data), 200);
        }

        $model = $this->store->find($id);
        if ($model) {
            $data = $this->api
                ->includes(['user', 'product'])
                ->serializer(new KeyArraySerializer('store'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->item($model, new StoreTransformer());
            } else {
                $data = $data->item($model, new StoreMgTransformer());
            }

            foreach ($data['store']['product'] as $k => $v) {
                $productModel = $this->product->find($v['id']);
                if ($productModel) {
                    $productData = $this->api
                        ->includes(['image', 'category'])
                        ->serializer(new KeyArraySerializer('product'));
                    if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                        $productData = $productData->item($productModel, new ProductTransformer());
                    } else {
                        $productData = $productData->item($productModel, new ProductMgTransformer());
                    }

                    $data['store']['product'][$k] = $productData['product'];
                }
            }

            // save into cache
            Cache::store(env('CACHE_DRIVER', CONST_REDIS))->put("store.data.{$id}", json_encode($data), env('CACHE_MINUTES', 60));

            return $this->response->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Create a new store
     *
     * Get a JSON representation of new store.
     *
     * @Post("/store")
     * @Versions({"v1"})
     * @Request(array -> {"name":"Store Name"})
     * @Response(200, success or error)
     */
    public function store(Request $request)
    {
        $validator = $this->store->validateRequest($request->all());

        if ($validator->status() == "200") {

            $data = $request->all();

            if ($request->hasFile('image')) {
                if ($request->file('image')->isValid()) {
                    $fileName = md5(time()) . "." . $request->file('image')->getClientOriginalExtension();
                    
                    $request->file('image')->move('storage/images', $fileName);

                    $data['image'] = $fileName;
                }
            }

            $task = $this->store->create($data);
            if ($task) {
                return $this->response->success("Store created");
            }

            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Update store
     *
     * Get a JSON representation of update store.
     *
     * @Put("/store/{id}")
     * @Versions({"v1"})
     * @Request(array -> {"name":"Store Name"}, id)
     * @Response(200, success or error)
     */
    public function update(Request $request)
    {
        $request->request->remove('name', 'user_id');

        $failed = false;
        /*if (Gate::denies('store.update', $request)) {
            $failed = true;
        }*/

        $validator = $this->store->validateRequest($request->all(), "update");
        if ( ! $failed && $validator->status() == "200") {
            $task = $this->store->update($request->all(), $request->id);
            if ($task) {
                return $this->response->success("Store updated");
            }

            $failed = true;
        }

        if ($failed) {
            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Delete a specific store
     *
     * Get a JSON representation of get store.
     *
     * @Delete("/store/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, success or error)
     */
    public function delete(Request $request)
    {
        /*if (Gate::denies('store.delete', $request)) {
            return $this->response->errorInternal();
        }*/

        $task = $this->store->delete($request->id);
        if ($task) {
            return $this->response->success("Store deleted");
        }

        return $this->response->errorInternal();
    }

    public function search(Request $request)
    {
        $models = $this->store->model->where([
            ['name', 'like', '%' . urldecode($request->s) . '%'],
        ])->orWhere([
            ['description', 'like', '%' . urldecode($request->s) . '%'],
        ])->get();

        if ($models->count()) {
            $data = $this->api
                ->serializer(new KeyArraySerializer('store'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->collection($models, new StoreTransformer());
            } else {
                $data = $data->collection($models, new StoreMgTransformer());
            }

            return $this->response->addModelLinks(new $this->store->model())->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

}
