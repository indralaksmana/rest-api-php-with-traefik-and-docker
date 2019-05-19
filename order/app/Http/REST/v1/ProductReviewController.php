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

        //$this->middleware('jwt.verify');
    }

    /**
     * Create a new productReview
     *
     * Get a JSON representation of new productReview.
     *
     * @Post("/productReview")
     * @Versions({"v1"})
     * @Request(array -> {"product_id":13,"user_id":2,"review":"Lorem ipsum dolor sit amet, consectetuer adipiscing elit.","rating":3})
     * @Response(200, success or error)
     */
    public function store(Request $request)
    {
        $validator = $this->productReview->validateRequest($request->all());

        if ($validator->status() == "200") {
            $task = $this->productReview->create($request->all());
            if ($task) {
                return $this->response->success("Product review created");
            }

            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Update productReview
     *
     * Get a JSON representation of update productReview.
     *
     * @Put("/productReview/{code}")
     * @Versions({"v1"})
     * @Request(array -> {"product_id":13,"user_id":2,"review":"Lorem ipsum dolor sit amet, consectetuer adipiscing elit.","rating":3})
     * @Response(200, success or error)
     */
    public function update(Request $request)
    {
        $failed = false;
        /*if (Gate::denies('productReview.update', $request)) {
            $failed = true;
        }*/

        $data = $request->only('review', 'rating');
        $data['rating'] = ($data['rating'] > 5) ? 5 : $data['rating'];
        $data['rating'] = ($data['rating'] < 1) ? 1 : $data['rating'];

        $validator = $this->productReview->validateRequest($data, "update");
        if ( ! $failed && $validator->status() == "200") {
            $task = $this->productReview->update($data, $request->id);
            if ($task) {
                return $this->response->success("Product review updated");
            }

            $failed = true;
        }

        if ($failed) {
            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Delete a specific productReview
     *
     * Get a JSON representation of get productReview.
     *
     * @Delete("/productReview/{code}")
     * @Versions({"v1"})
     * @Request({"code": "1"})
     * @Response(200, success or error)
     */
    public function delete(Request $request)
    {
        /*if (Gate::denies('productReview.delete', $request)) {
            return $this->response->errorInternal();
        }*/

        $task = $this->productReview->delete($request->id);
        if ($task) {
            return $this->response->success("Product review deleted");
        }

        return $this->response->errorInternal();
    }

}
