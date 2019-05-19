<?php

namespace App\Http\Policies;


use App\Models\User;
use Illuminate\Http\Request;
use Core\Http\Policies\AbstractPolicy;

class UserPolicy extends AbstractPolicy
{
    /**
     * Determine if the given user can be updated by the user.
     *
     * @param User $user
     * @param Request $request
     *
     * @return bool
     */
    public function update(User $user, Request $request)
    {
        return $this->checkId($user->id, $request->id);
    }

    /**
     * Determine if the given user can be deleted by the user.
     *
     * @param User $user
     * @param Request $request
     *
     * @return bool
     */
    public function delete(User $user, Request $request)
    {
        return $this->checkId($user->id, $request->id);
    }
}