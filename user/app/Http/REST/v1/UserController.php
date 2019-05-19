<?php

namespace App\Http\REST\v1;

use App\Repositories\UserRepository as User;
use App\Transformers\UserTransformer;
use App\Transformers\UserMgTransformer;
use Core\Helpers\Serializer\KeyArraySerializer;
use Core\Http\REST\Controller\ApiBaseController;
use Gate;
use Illuminate\Http\Request;

/**
 * User resource representation.
 *
 * @Resource("Users", uri="/users")
 */
class UserController extends ApiBaseController
{
    const CONST_WORD = 'password';

    /**
     * @var User
     */
    private $user;

    /**
     * UserController constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct();
        $this->user = $user;
    }

    /**
     * Display a listing of resource.
     *
     * Get a JSON representation of all users.
     *
     * @Get("/users")
     * @Versions({"v1"})
     * @Response(200, body={"id":1,"email":"lavonne.cole@hermann.com","name":"Amelie Trantow","surname":"Kayley Klocko Sr."})
     */
    public function index()
    {
        $models = $this->user->paginate();

        if ($models) {
            $data = $this->api
                ->serializer(new KeyArraySerializer('users'));
            if (env('DB_CONNECTION', CONST_MYSQL) == CONST_MYSQL) {
                $data = $data->paginate($models, new UserTransformer());
            } else {
                $data = $data->paginate($models, new UserMgTransformer());
            }

            return $this->response->addModelLinks(new $this->user->model())->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Show a specific user
     *
     * Get a JSON representation of get user.
     *
     * @Get("/users/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, body={"id":1,"email":"lavonne.cole@hermann.com","name":"Amelie Trantow","surname":"Kayley Klocko Sr."})
     */
    public function show($id)
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

            return $this->response->data($data, 200);
        }

        return $this->response->errorNotFound();
    }

    /**
     * Update user
     *
     * Get a JSON representation of update user.
     *
     * @Put("/users/{id}")
     * @Versions({"v1"})
     * @Request(array -> {"email":"lavonne.cole@hermann.com","name":"Amelie Trantow","surname":"Kayley Klocko Sr."}, id)
     * @Response(200, success or error)
     */
    public function update(Request $request)
    {
        $failed = false;
        if (Gate::denies('users.update', $request)) {
            $failed = true;
        }

        $validator = $this->user->validateRequest($request->all(), 'update');
        if ($validator->status() == '200') {
            $task = $this->user->updateUser($request->all(), $request->id);
            if ($task) {
                return $this->response->success('User updated');
            }

            $failed = true;
        }

        if ($failed) {
            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Update user password
     *
     * Get a JSON representation of update user.
     *
     * @Put("/users/{id}/password")
     * @Versions({"v1"})
     * @Request(array -> {"password":"xAdsavad$"}, id)
     * @Response(200, success or error)
     */
    public function updatePassword(Request $request)
    {
        $failed = false;
        if (Gate::denies('users.update', $request)) {
            $failed = true;
        }

        $validator = $this->user->validateRequest($request->only([self::CONST_WORD, 'confirm_password']), self::CONST_WORD);

        if ($validator->status() == '200') {
            $task = $this->user->updateUser($request->only(self::CONST_WORD), $request->id);
            if ($task) {
                return $this->response->success('User updated');
            }

            $failed = false;
        }

        if ($failed) {
            return $this->response->errorInternal();
        }

        return $validator;
    }

    /**
     * Delete a specific user
     *
     * Get a JSON representation of get user.
     *
     * @Delete("/users/{id}")
     * @Versions({"v1"})
     * @Request({"id": "1"})
     * @Response(200, success or error)
     */
    public function delete(Request $request)
    {
        if (Gate::denies('users.delete', $request)) {
            return $this->response->errorInternal();
        }

        $task = $this->user->delete($request->id);
        if ($task) {
            return $this->response->success('User deleted');
        }

        return $this->response->errorInternal();
    }
}
