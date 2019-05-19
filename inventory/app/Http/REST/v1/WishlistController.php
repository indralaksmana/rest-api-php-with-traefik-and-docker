<?php

namespace App\Http\REST\v1;

use Core\Http\REST\Controller\ApiBaseController;
use Core\Helpers\Serializer\KeyArraySerializer;

use App\Repositories\WishlistRepository as Wishlist;
use App\Transformers\WishlistTransformer;
use App\Transformers\WishlistMgTransformer;

use Illuminate\Support\Facades\Cache;

use Illuminate\Http\Request;
use Gate;

/**
 * Wishlist resource representation.
 *
 * @Resource("Wishlist", uri="/wishlist")
 */
class WishlistController extends ApiBaseController
{
    /**
     * @var Wishlist
     */
    private $wishlist;

    /**
     * Initialize @var Wishlist
     *
     * @Request Wishlist
     *
     */
    public function __construct(Wishlist $wishlist)
    {
        parent::__construct();
        $this->wishlist = $wishlist;
        $this->middleware('jwt.verify');
    }

    /**
     * Display a listing of resource.
     *
     * Get a JSON representation of all wishlist.
     *
     * @Get("/wishlist/{user_id}")
     * @Versions({"v1"})
     * @Response(200, body={"id":1,"name":"Wishlist Name"})
     */
    public function index(Request $request)
    {
        if ($new_data = Cache::store(env('CACHE_DRIVER', CONST_REDIS))->get("wishlist.new_data.user.{$request->user_id}")) {
            return $this->response->addModelLinks(new $this->wishlist->model())->data(json_decode($new_data), 200);
        }

        $models = $this->wishlist->model->where([
            ['user_id', '=', $request->user_id],
        ])->get();

        if ($models->count()) {
            $data = $this->api
                ->includes(['user', 'product', 'category', 'image'])
                ->serializer(new KeyArraySerializer('wishlist'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->collection($models, new WishlistTransformer());
            } else {
                $data = $data->collection($models, new WishlistMgTransformer());
            }

            // retransform output
            $new_data = array();
            foreach($data['wishlist'] as $k => $v) {
                $new_data['wishlist'][$k] = $v;
                $new_data['wishlist'][$k]['product'][0]['image'] = $v['image'];
                $new_data['wishlist'][$k]['product'][0]['category'] = $v['category'];
                $new_data['wishlist'][$k]['product'] = $new_data['wishlist'][$k]['product'][0];
                $new_data['wishlist'][$k]['user'] = $new_data['wishlist'][$k]['user'][0];
                unset($new_data['wishlist'][$k]['image']);
                unset($new_data['wishlist'][$k]['category']);
            }

            // save into cache
            Cache::store(env('CACHE_DRIVER', CONST_REDIS))->put("wishlist.new_data.user.{$request->user_id}", json_encode($new_data), env('CACHE_MINUTES', 60));

            return $this->response->addModelLinks(new $this->wishlist->model())->data($new_data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Show specific wishlist
     *
     * Get a JSON representation of the wishlist.
     *
     * @Get("/wishlist/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, body={"id":1,"name":"Wishlist Name"})
     */
    public function show($id)
    {
        if ($data = Cache::store(env('CACHE_DRIVER', CONST_REDIS))->get("wishlist.data.{$id}")) {
            return $this->response->data(json_decode($data), 200);
        }

        $model = $this->wishlist->find($id);
        if ($model) {
            $data = $this->api
                ->includes(['user', 'product', 'category' ,'image'])
                ->serializer(new KeyArraySerializer('wishlist'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->item($model, new WishlistTransformer());
            } else {
                $data = $data->item($model, new WishlistMgTransformer());
            }

            // retransform output
            $data['wishlist']['product'][0]['image'] = $data['wishlist']['image'];
            $data['wishlist']['product'][0]['category'] = $data['wishlist']['category'];
            $data['wishlist']['user'] = $data['wishlist']['user'][0];
            $data['wishlist']['product'] = $data['wishlist']['product'][0];
            unset($data['wishlist']['image']);
            unset($data['wishlist']['category']);

            // save into cache
            Cache::store(env('CACHE_DRIVER', CONST_REDIS))->put("wishlist.data.{$id}", json_encode($data), env('CACHE_MINUTES', 60));

            return $this->response->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Create a new wishlist
     *
     * Get a JSON representation of new wishlist.
     *
     * @Post("/wishlist")
     * @Versions({"v1"})
     * @Request(array -> {"name":"Wishlist Name"})
     * @Response(200, success or error)
     */
    public function store(Request $request)
    {
        $validator = $this->wishlist->validateRequest($request->all());

        if ($validator->status() == "200") {
            $task = $this->wishlist->create($request->all());
            if ($task) {
                return $this->response->success("Wishlist created");
            }

            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Delete a specific wishlist
     *
     * Get a JSON representation of get wishlist.
     *
     * @Delete("/wishlist/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, success or error)
     */
    public function delete(Request $request)
    {
        /*if (Gate::denies('wishlist.delete', $request)) {
            return $this->response->errorInternal();
        }*/

        $task = $this->wishlist->delete($request->id);
        if ($task) {
            return $this->response->success("Wishlist deleted");
        }

        return $this->response->errorInternal();
    }

}
