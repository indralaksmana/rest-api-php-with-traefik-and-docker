<?php

namespace App\Http\Policies;


use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Core\Http\Policies\AbstractPolicy;

class StorePolicy extends AbstractPolicy
{
    /**
     * Determine if the given post can be updated by the user.
     *
     * @param User $user
     * @param Store $model
     *
     * @return bool
     */
    public function update(User $user, Store $model)
    {
        return $this->checkId($user->id, $model->user_id);
    }

    /**
     * Determine if the given post can be deleted by the user.
     *
     * @param User $user
     * @param Store $model
     *
     * @return bool
     */
    public function delete(User $user, Store $model)
    {
        return $this->checkId($user->id, $model->user_id);
    }
}