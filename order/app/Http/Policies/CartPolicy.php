<?php

namespace App\Http\Policies;


use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Core\Http\Policies\AbstractPolicy;

class CartPolicy extends AbstractPolicy
{
    /**
     * Determine if the given post can be updated by the user.
     *
     * @param User $user
     * @param Cart $model
     *
     * @return bool
     */
    public function update(User $user, Cart $model)
    {
        return $this->checkId($user->id, $model->user_id);
    }

    /**
     * Determine if the given post can be deleted by the user.
     *
     * @param User $user
     * @param Cart $model
     *
     * @return bool
     */
    public function delete(User $user, Cart $model)
    {
        return $this->checkId($user->id, $model->user_id);
    }
}