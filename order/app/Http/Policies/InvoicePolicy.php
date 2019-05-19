<?php

namespace App\Http\Policies;


use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use Core\Http\Policies\AbstractPolicy;

class InvoicePolicy extends AbstractPolicy
{
    /**
     * Determine if the given post can be updated by the user.
     *
     * @param User $user
     * @param Invoice $model
     *
     * @return bool
     */
    public function update(User $user, Invoice $model)
    {
        return $this->checkId($user->id, $model->user_id);
    }

    /**
     * Determine if the given post can be deleted by the user.
     *
     * @param User $user
     * @param Invoice $model
     *
     * @return bool
     */
    public function delete(User $user, Invoice $model)
    {
        return $this->checkId($user->id, $model->user_id);
    }
}