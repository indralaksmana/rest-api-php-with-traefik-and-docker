<?php

namespace App\Http\Policies;


use App\Models\Wishlist;
use App\Models\User;
use Illuminate\Http\Request;
use Core\Http\Policies\AbstractPolicy;

class WishlistPolicy extends AbstractPolicy
{
    /**
     * Determine if the given post can be deleted by the user.
     *
     * @param User $user
     * @param Wishlist $model
     *
     * @return bool
     */
    public function delete(User $user, Wishlist $model)
    {
        return $this->checkId($user->id, $model->user_id);
    }
}