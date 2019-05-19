<?php

namespace App\Http\REST\v1;

use Core\Http\REST\Controller\ApiBaseController;
use Core\Helpers\Serializer\KeyArraySerializer;

use App\Repositories\ProductReviewRepository as ProductReview;
use App\Transformers\ProductReviewTransformer;
use App\Transformers\ProductReviewMgTransformer;

use Illuminate\Http\Request;
use Gate;

/**
 * ProductReview resource representation.
 *
 * @Resource("ProductReview", uri="/productReview")
 */
class ProductReviewController extends ApiBaseController
{
    /**
     * @var ProductReview
     */
    private $productReview;

    /**
     * Initialize @var ProductReview
     *
     * @Request ProductReview
     *
     */
    public function __construct(ProductReview $productReview)
    {
        parent::__construct();
        $this->productReview = $productReview;
    }

    /**
     * Display a listing of resource.
     *
     * Get a JSON representation of all wishlist.
     *
     * @Get("/review/{product_id}")
     * @Versions({"v1"})
     * @Response(200, body={"id":1,"product_id":111,"review":"Lorem ipsum dolor sit amet, consectetuer adipiscing elit.","rating":3})
     */
    public function index(Request $request)
    {
        $request->page = ($request->page) ? $request->page : 1;

        $models = $this->productReview->model->where([
            ['product_id', '=', $request->product_id],
        ])->paginate(3, ['*'], 'page', $request->page);

        if ($models) {
            $data = $this->api
                ->includes(['user'])
                ->serializer(new KeyArraySerializer('productReview'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->paginate($models, new ProductReviewTransformer());
            } else {
                $data = $data->paginate($models, new ProductReviewMgTransformer());
            }

            return $this->response->addModelLinks(new $this->productReview->model())->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

}
