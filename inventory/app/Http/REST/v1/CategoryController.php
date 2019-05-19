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

use Illuminate\Support\Facades\Cache;

use Illuminate\Http\Request;
use Gate;

/**
 * Category resource representation.
 *
 * @Resource("Category", uri="/category")
 */
class CategoryController extends ApiBaseController
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
     * Initialize @var Category
     *
     * @Request Category
     *
     */
    public function __construct(Category $category, Product $product)
    {
        parent::__construct();
        $this->category = $category;
        $this->product = $product;
        $this->middleware('jwt.verify', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of resource.
     *
     * Get a JSON representation of all category.
     *
     * @Get("/category")
     * @Versions({"v1"})
     * @Response(200, body={"id":1,"name":"Category Name"})
     */
    public function index()
    {
        if ($data = Cache::store(env('CACHE_DRIVER', CONST_REDIS))->get("category.data")) {
            return $this->response->addModelLinks(new $this->category->model())->data(json_decode($data), 200);
        }

        $models = $this->category->all();
        if ($models) {
            $data = $this->api
                ->serializer(new KeyArraySerializer('category'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->collection($models, new CategoryTransformer());
            } else {
                $data = $data->collection($models, new CategoryMgTransformer());
            }

            // save into cache
            Cache::store(env('CACHE_DRIVER', CONST_REDIS))->put("category.data", json_encode($data), env('CACHE_MINUTES', 60));

            return $this->response->addModelLinks(new $this->category->model())->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Show specific category
     *
     * Get a JSON representation of the category.
     *
     * @Get("/category/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, body={"id":1,"name":"Category Name"})
     */
    public function show($id)
    {
        if ($data = Cache::store(env('CACHE_DRIVER', CONST_REDIS))->get("category.data.{$id}")) {
            return $this->response->data(json_decode($data), 200);
        }

        $model = $this->category->find($id);
        if ($model) {
            $data = $this->api
                ->includes('product')
                ->serializer(new KeyArraySerializer('category'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->item($model, new CategoryTransformer());
            } else {
                $data = $data->item($model, new CategoryMgTransformer());
            }

            foreach ($data['category']['product'] as $k => $v) {
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

                    $data['category']['product'][$k] = $productData['product'];
                }
            }

            // save into cache
            Cache::store(env('CACHE_DRIVER', CONST_REDIS))->put("category.data.{$id}", json_encode($data), env('CACHE_MINUTES', 60));

            return $this->response->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Create a new category
     *
     * Get a JSON representation of new category.
     *
     * @Post("/category")
     * @Versions({"v1"})
     * @Request(array -> {"name":"Category Name"})
     * @Response(200, success or error)
     */
    public function store(Request $request)
    {
        $validator = $this->category->validateRequest($request->all());

        if ($validator->status() == "200") {
            $task = $this->category->create($request->all());
            if ($task) {
                return $this->response->success("Category created");
            }

            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Update category
     *
     * Get a JSON representation of update category.
     *
     * @Put("/category/{id}")
     * @Versions({"v1"})
     * @Request(array -> {"name":"Category Name"}, id)
     * @Response(200, success or error)
     */
    public function update(Request $request)
    {
        $failed = false;
        if (Gate::denies('category.update', $request)) {
            $failed = true;
        }

        $validator = $this->category->validateRequest($request->all(), "update");
        if ( ! $failed && $validator->status() == "200") {
            $task = $this->category->update($request->all(), $request->id);
            if ($task) {
                return $this->response->success("Category updated");
            }

            $failed = true;
        }

        if ($failed) {
            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Delete a specific category
     *
     * Get a JSON representation of get category.
     *
     * @Delete("/category/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, success or error)
     */
    public function delete(Request $request)
    {
        if (Gate::denies('category.delete', $request)) {
            return $this->response->errorInternal();
        }

        $task = $this->category->delete($request->id);
        if ($task) {
            return $this->response->success("Category deleted");
        }

        return $this->response->errorInternal();
    }

}
