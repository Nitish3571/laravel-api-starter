<?php
// app/Policies/UserPolicy.php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('users_view');
    }

    public function view(User $user, User $model)
    {
        return $user->can('users_view') || $user->id === $model->id;
    }

    public function create(User $user)
    {
        return $user->can('users_create');
    }

    public function update(User $user, User $model)
    {
        return $user->can('users_edit') || $user->id === $model->id;
    }

    public function delete(User $user, User $model)
    {
        return $user->can('users_delete') && $user->id !== $model->id;
    }
}
