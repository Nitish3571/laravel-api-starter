<?php
// app/Repositories/UserRepository.php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail($email)
    {
        return $this->model->where(User::EMAIL, $email)->first();
    }

    public function updateLastLogin($userId)
    {
        return $this->model->where(User::ID, $userId)->update([
            'last_login_at' => now()
        ]);
    }

    public function assignExtraPermission($userId, $permissionId, $granted = true)
    {
        $user = $this->find($userId);
        return $user->extraPermissions()->syncWithoutDetaching([
            $permissionId => ['granted' => $granted]
        ]);
    }

    public function removeExtraPermission($userId, $permissionId)
    {
        $user = $this->find($userId);
        return $user->extraPermissions()->detach($permissionId);
    }
}
