<?php

namespace App\Http\REST\v1;

use Core\Http\REST\Controller\ApiBaseController;
use Core\Helpers\Serializer\KeyArraySerializer;

use App\Repositories\PromoRepository as Promo;
use App\Transformers\PromoTransformer;
use App\Transformers\PromoMgTransformer;

use Illuminate\Http\Request;
use Gate;

/**
 * Promo resource representation.
 *
 * @Resource("Promo", uri="/promo")
 */
class PromoController extends ApiBaseController
{
    /**
     * @var Promo
     */
    private $promo;

    /**
     * Initialize @var Promo
     *
     * @Request Promo
     *
     */
    public function __construct(Promo $promo)
    {
        parent::__construct();
        $this->promo = $promo;

        //$this->middleware('jwt.verify', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of resource.
     *
     * Get a JSON representation of all promo.
     *
     * @Get("/promo")
     * @Versions({"v1"})
     * @Response(200, body={"id":1,"name":"Promo Name"})
     */
    public function index(Request $request)
    {
        $request->page = ($request->page) ? $request->page : 1;

        $models = $this->promo->paginate(15, ['*'], 'page', $request->page);

        if ($models) {
            $data = $this->api
                //->includes(['invoice'])
                ->serializer(new KeyArraySerializer('promo'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->paginate($models, new PromoTransformer());
            } else {
                $data = $data->paginate($models, new PromoMgTransformer());
            }

            return $this->response->addModelLinks(new $this->promo->model())->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Show specific promo
     *
     * Get a JSON representation of the promo.
     *
     * @Get("/promo/{code}")
     * @Versions({"v1"})
     * @Request({"code": "ANTIQUEWHITE"})
     * @Response(200, body={"code":"ANTIQUEWHITE","value":1234567})
     */
    public function show($code)
    {
        $model = $this->promo->model->where([
            ['code', '=', $code]
        ])->first();
        if ($model) {
            $data = $this->api
                //->includes('invoice')
                ->serializer(new KeyArraySerializer('promo'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->item($model, new PromoTransformer());
            } else {
                $data = $data->item($model, new PromoMgTransformer());
            }

            return $this->response->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Create a new promo
     *
     * Get a JSON representation of new promo.
     *
     * @Post("/promo")
     * @Versions({"v1"})
     * @Request(array -> {"code":"ANTIQUEWHITE","value":1234567,"begin_date":"2018-12-01","end_date":"2019-01-31"})
     * @Response(200, success or error)
     */
    public function store(Request $request)
    {
        $validator = $this->promo->validateRequest($request->all());

        if ($validator->status() == "200") {
            $task = $this->promo->create($request->all());
            if ($task) {
                return $this->response->success("Promo created");
            }

            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Update promo
     *
     * Get a JSON representation of update promo.
     *
     * @Put("/promo/{code}")
     * @Versions({"v1"})
     * @Request(array -> {"value":1234567,"begin_date":"2018-12-01","end_date":"2019-01-31"}, code)
     * @Response(200, success or error)
     */
    public function update(Request $request)
    {
        $failed = false;
        /*if (Gate::denies('promo.update', $request)) {
            $failed = true;
        }*/

        $validator = $this->promo->validateRequest($request->all(), "update");
        if ( ! $failed && $validator->status() == "200") {
            $task = $this->promo->model->where([
                ['code', '=', $request->code]
            ])->update($request->all());
            if ($task) {
                return $this->response->success("Promo updated");
            }

            $failed = true;
        }

        if ($failed) {
            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Delete a specific promo
     *
     * Get a JSON representation of get promo.
     *
     * @Delete("/promo/{code}")
     * @Versions({"v1"})
     * @Request({"code": "1"})
     * @Response(200, success or error)
     */
    public function delete(Request $request)
    {
        /*if (Gate::denies('promo.delete', $request)) {
            return $this->response->errorInternal();
        }*/

        $task = $this->promo->model->where([
                ['code', '=', $request->code]
            ])->delete();
        if ($task) {
            return $this->response->success("Promo deleted");
        }

        return $this->response->errorInternal();
    }

}
