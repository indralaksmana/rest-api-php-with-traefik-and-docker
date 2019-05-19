<?php

namespace App\Http\REST\v1;

use Core\Http\REST\Controller\ApiBaseController;
use Core\Helpers\Serializer\KeyArraySerializer;

use App\Repositories\CategoryRepository as Category;
use App\Transformers\CategoryTransformer;
use App\Transformers\CategoryMgTransformer;
use App\Repositories\ProductRepository as Product;
use App\Transformers\ProductTransformer;
use App\Transformers\ProductMgTransformer;
use App\Repositories\ViewProductRepository as ViewProduct;
use App\Transformers\ViewProductTransformer;
use App\Transformers\ViewProductMgTransformer;
use App\Repositories\ProductCategoryRepository as ProductCategory;
use App\Transformers\ProductCategoryTransformer;
use App\Transformers\ProductCategoryMgTransformer;
use App\Repositories\ProductImageRepository as ProductImage;
use App\Transformers\ProductImageTransformer;
use App\Transformers\ProductImageMgTransformer;

use Illuminate\Support\Facades\Cache;

use App\Models\ProductEs;

use Gate;
use Illuminate\Http\Request;

/**
 * Product resource representation.
 *
 * @Resource("Product", uri="/product")
 */
class ProductController extends ApiBaseController
{
    /**
     * @var Category
     */
    private $category;

    /**
     * @var Product
     */
    private $product;

    /**
     * @var ProductEs
     */
    private $productEs;

    /**
     * @var ViewProduct
     */
    private $viewProduct;

    /**
     * @var ProductCategory
     */
    private $productCategory;

    /**
     * @var ProductImage
     */
    private $productImage;

    /**
     * UserController constructor.
     *
     * @param Product $product
     */
    public function __construct(Category $category, Product $product, ProductEs $productEs, ViewProduct $viewProduct, ProductCategory $productCategory, ProductImage $productImage)
    {
        parent::__construct();
        $this->category = $category;
        $this->product = $product;
        $this->viewProduct = $viewProduct;
        $this->productCategory = $productCategory;
        $this->productImage = $productImage;
        $this->productEs = $productEs;
        $this->middleware('jwt.verify', ['except' => ['index', 'show', 'search', 'relatedProduct']]);
    }

    /**
     * Display a listing of resource.
     *
     * Get a JSON representation of all product.
     *
     * @Get("/product")
     * @Versions({"v1"})
     * @Response(200, body={"id":1,"name":"Product Name","description":"Product Description","harga":100000,"stock":5})
     */
    public function index(Request $request)
    {
        $request->page = ($request->page) ? $request->page : 1;

        if ($data = Cache::store(env('CACHE_DRIVER', CONST_REDIS))->get("product.data.page{$request->page}")) {
            return $this->response->addModelLinks(new $this->product->model())->data(json_decode($data), 200);
        }

        $models = $this->product->paginate(20, ['*'], 'page', $request->page);

        if ($models) {
            $data = $this->api
                ->includes(['image', 'category', 'store'])
                ->serializer(new KeyArraySerializer('product'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->paginate($models, new ProductTransformer());
            } else {
                $data = $data->paginate($models, new ProductMgTransformer());
            }

            // save into cache
            Cache::store(env('CACHE_DRIVER', CONST_REDIS))->put("product.data.page{$request->page}", json_encode($data), env('CACHE_MINUTES', 60));

            return $this->response->addModelLinks(new $this->product->model())->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Show a specific product
     *
     * Get a JSON representation of get product.
     *
     * @Get("/product/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, body={"id":1,"name":"Product Name","description":"Product Description","harga":100000,"stock":5})
     */
    public function show($id)
    {
        if ($data = Cache::store(env('CACHE_DRIVER', CONST_REDIS))->get("product.data.{$id}")) {
            return $this->response->data(json_decode($data), 200);
        }

        $model = $this->product->find($id);
        if ($model) {
            $data = $this->api
                ->includes(['image', 'category', 'store'])
                ->serializer(new KeyArraySerializer('product'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->item($model, new ProductTransformer());
            } else {
                $data = $data->item($model, new ProductMgTransformer());
            }

            $data['product']['store'] = $data['product']['store'][0];

            $categories = end($data['product']['category']);
            $data['product']['related_product'] = $this->relatedProduct($categories['id']);

            // save into cache
            Cache::store(env('CACHE_DRIVER', CONST_REDIS))->put("product.data.{$id}", json_encode($data), env('CACHE_MINUTES', 60));

            return $this->response->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Create a new product
     *
     * Get a JSON representation of new product.
     *
     * @Post("/product")
     * @Versions({"v1"})
     * @Request(array -> {"id":1,"name":"Product Name","description":"Product Description","harga":100000,"stock":5})
     * @Response(200, success or error)
     */
    public function store(Request $request)
    {
        $validator = $this->product->validateRequest($request->all());

        if ($validator->status() == "200") {
            $task = $this->product->create($request->all());
            if ($task) {

                foreach($request->category_id as $v) {
                    $this->productCategory->create([
                        'product_id' => $task->id,
                        'category_id' => $v,
                    ]);
                }

                if ($request->hasFile('image')) {
                    $i = 0;
                    foreach($request->file('image') as $v) {
                        if (!$v->isValid()) {
                            continue;
                        }
                        if ($i == 5) {
                            break;
                        }

                        $fileName = md5(time() . $i) . "." . $v->getClientOriginalExtension();
                
                        $v->move('storage/images', $fileName);

                        $this->productImage->create([
                            'product_id' => $task->id,
                            'image' => $fileName
                        ]);

                        $i++;
                    }
                }

                $this->storeEs($task->id);

                return $this->response->success("Product created");
            }

            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Update product
     *
     * Get a JSON representation of update product.
     *
     * @Put("/product/{id}")
     * @Versions({"v1"})
     * @Request(array -> {"id":1,"name":"Product Name","description":"Product Description","harga":100000,"stock":5}, id)
     * @Response(200, success or error)
     */
    public function update(Request $request)
    {
        $failed = false;
        if (Gate::denies('product.update', $request)) {
            $failed = true;
        }

        $validator = $this->product->validateRequest($request->all(), "update");
        if ( ! $failed && $validator->status() == "200") {
            $task = $this->product->update($request->all(), $request->id);
            if ($task) {
                return $this->response->success("Product updated");
            }

            $failed = true;
        }

        if ($failed) {
            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Delete a specific product
     *
     * Get a JSON representation of get product.
     *
     * @Delete("/product/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, success or error)
     */
    public function delete(Request $request)
    {
        if (Gate::denies('product.delete', $request)) {
            return $this->response->errorInternal();
        }

        $task = $this->product->delete($request->id);
        if ($task) {
            return $this->response->success("Product deleted");
        }

        return $this->response->errorInternal();
    }

    public function search(Request $request)
    {
        $request->page = ($request->page) ? $request->page : 1;

        $productIds = array();
        if ($request->category_id) {
            $productIds = $this->productCategory->model->where([
                ['category_id', '=', $request->category_id],
            ])->pluck('product_id')->toArray();
        }

        $models = $this->viewProduct->model;

        if ($productIds) {
            $models = $models->whereIn('id', $productIds);
        }

        if ($request->s) {
            $models = $models->where(
                'name', 'like', '%' . urldecode($request->s) . '%'
            )->orWhere(
                'description', 'like', '%' . urldecode($request->s) . '%'
            );
        }

        $sort = ($request->sort) ? $request->sort : "asc";
        if ($request->order_by) {
            $models = $models->orderBy("{$request->order_by}", "{$sort}");
        } else {
            $models = $models->orderBy("name", "{$sort}");
        }

        $models = $models->paginate(20, ['*'], 'page', $request->page);

        if ($models->count()) {
            $data = $this->api
                ->includes(['store', 'category', 'image'])
                ->serializer(new KeyArraySerializer('product'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->paginate($models, new ViewProductTransformer());
            } else {
                $data = $data->paginate($models, new ViewProductMgTransformer());
            }

            return $this->response->addModelLinks(new $this->product->model())->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    public function relatedProduct($category_id) {
        $output = array();
        $model = $this->category->find($category_id);
        if ($model) {
            $data = $this->api
                ->includes('relatedProduct')
                ->serializer(new KeyArraySerializer('category'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->item($model, new CategoryTransformer());
            } else {
                $data = $data->item($model, new CategoryMgTransformer());
            }

            foreach ($data['category']['relatedProduct'] as $k => $v) {
                $productModel = $this->product->find($v['id']);
                if ($productModel) {
                    $productData = $this->api
                        ->includes(['image', 'category', 'store'])
                        ->serializer(new KeyArraySerializer('product'));
                    if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                        $productData = $productData->item($productModel, new ProductTransformer());
                    } else {
                        $productData = $productData->item($productModel, new ProductMgTransformer());
                    }

                    $productData['product']['store'] = $productData['product']['store'][0];

                    $data['category']['relatedProduct'][$k] = $productData['product'];
                }
            }

            $output = $data['category']['relatedProduct'];
        }

        return $output;
    }

    public function storeEs($id)
    {
        $model = $this->product->find($id);
        if ($model) {
            $data = $this->api
                ->includes(['image', 'category', 'store'])
                ->serializer(new KeyArraySerializer('product'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->item($model, new ProductTransformer());
            } else {
                $data = $data->item($model, new ProductMgTransformer());
            }

            $data['product']['store'] = $data['product']['store'][0];

            $this->productEs->id = $data['product']['id'];
            $this->productEs->name = $data['product']['name'];
            $this->productEs->description = $data['product']['description'];
            $this->productEs->harga = $data['product']['harga'];
            $this->productEs->stock = $data['product']['stock'];
            $this->productEs->store_id = $data['product']['store_id'];
            $this->productEs->created_at = date_timestamp_get($data['product']['created_at']);
            $this->productEs->updated_at = date_timestamp_get($data['product']['updated_at']);

            $images = array();
            foreach($data['product']['image'] as $v) {
                $images[] = array(
                    "id" => $v["id"],
                    "image" => $v["image"],
                    "created_at" => date_timestamp_get($v["created_at"])
                );
            }
            $this->productEs->image = $images;

            $categories = array();
            foreach($data['product']['category'] as $v) {
                $categories[] = array(
                    "id" => $v["id"],
                    "name" => $v["name"],
                    "created_at" => date_timestamp_get($v["created_at"])
                );
            }
            $this->productEs->category = $categories;

            $data['product']['store']['created_at'] = date_timestamp_get($data['product']['store']['created_at']);
            $data['product']['store']['updated_at'] = date_timestamp_get($data['product']['store']['updated_at']);
            $this->productEs->store_detail = $data['product']['store'];

            $this->productEs->save();
        }
    }
}
