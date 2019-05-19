<?php

namespace App\Repositories;

use Core\Repository\Eloquent\RepositoryAbstract;
use Illuminate\Support\Facades\Validator;

abstract class BaseRepository extends RepositoryAbstract
{

    protected static $rules = [];

    protected static $rules_update = [];

    /** Validate request api
     *
     * @param array $request
     * @param $type
     * @param array $rules_specific
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateRequest(array $request, $type = '', array $rules_specific = [])
    {
        $modelRules = $this->rules($type, $rules_specific);

        if ( ! isset($request)) {
            return $this->response->errorNotFound();
        }

        $validator = Validator::make($request, $modelRules);
        if ($validator->fails()) {
            return $this->response->addData($validator->errors()->toArray())->errorNotFound();
        }

        return $this->response->success('Rules validate success');
    }

    /** Use rules based on request
     *
     * @param $type
     * @param array $rules_specific
     *
     * @return array
     */
    private function rules($type, array $rules_specific = [])
    {
        if ( ! empty($rules_specific)) {
            return $rules_specific;
        }

        switch ($type) {
            case 'store':
            case 'create':
                $modelRules = self::$rules;
                break;
            case 'update':
                $modelRules = self::$rules_update;
                break;
            default:
                $modelRules = self::$rules;
                break;
        }

        return $modelRules;
    }
}