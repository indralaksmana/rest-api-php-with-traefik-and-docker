<?php

namespace App\Http\Policies;


use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Http\Request;
use Core\Http\Policies\AbstractPolicy;

class ProductReviewPolicy extends AbstractPolicy
{
    /**
     * Determine if the given post can be updated by the user.
     *
     * @param User $user
     * @param ProductReview $model
     *
     * @return bool
     */
    public function update(User $user, ProductReview $model)
    {
        return $this->checkId($user->id, $model->user_id);
    }

    /**
     * Determine if the given post can be deleted by the user.
     *
     * @param User $user
     * @param ProductReview $model
     *
     * @return bool
     */
    public function delete(User $user, ProductReview $model)
    {
        return $this->checkId($user->id, $model->user_id);
    }
}