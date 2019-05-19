<?php

namespace App\Http\REST\v1;

use App\Repositories\UserRepository;
use Core\Services\ACL\ACLService;
use Core\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Core\Http\REST\Controller\ApiBaseController;

class AuthController extends ApiBaseController
{
    /** User Repository
     *
     * @var User
     */
    private $model;


    /**
     * @Var auth instance of Auth service
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = app(UserRepository::class);
        $this->middleware('jwt.verify', ['except' => ['authenticate', 'register', 'refresh']]);
    }

    /**
     * User authentication with JWT.
     * Returns the token or an error response
     *
     * @param Request $request
     *
     * @return mixed (token) or (errors)
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            $token = $this->auth->jwt->attempt($credentials);
            if ( ! $token) {
                return $this->response->errorNotFound();
            }
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return $this->response->errorInternal('Could not create token.');
        }

        return $this->response->data(compact('token'));
    }


    /**
     * Register method.
     * The request is validated by the user repository method, then it is created and then authenticated with JWT which creates the token.
     * If the token is successfully created, the roles and permssi are assigned to the user.
     * Return a reply with the user and the token
     *
     * @param Request $request
     *
     * @return mixed (user + token) or (errors)
     */
    public function register(Request $request)
    {
        $validator = $this->model->validateRequest($request->all(), "store");

        if ($validator->status() != "200") {
            return $validator;
        }

        $user = $this->model->create($request->all());

        $token = $this->auth->jwt->fromUser($user);
        if ( ! $token) {
            return $this->response->errorInternal();
        }

        return $this->response->data(compact('user', 'token'));
    }

    /**
     * Method for check user authenticated.
     * Return user playload or Exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticatedUser()
    {
        $response = $this->auth->getUser()->toArray();

        if (isset($response["_id"])) {
            $response['id'] = $response["_id"];
            unset($response["_id"]);

            return $response;
        }


        return $this->auth->getUser();
    }

    /**
     * @return mixed
     */
    public function invalidate()
    {
        return $this->auth->invalidate(true);
    }

    /**
     * @return mixed
     */
    public function refresh()
    {
        return $this->auth->refresh(true);
    }
}