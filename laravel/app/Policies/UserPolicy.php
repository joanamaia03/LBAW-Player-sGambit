<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->user_id == $model->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function edit(): bool
    {
        return Auth::check();
    }

    public function delete(): bool
    {
        return Auth::check();
    }
}
