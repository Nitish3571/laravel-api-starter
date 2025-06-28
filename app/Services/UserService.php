<?php
// app/Services/UserService.php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers($perPage = 15)
    {
        return $this->userRepository->paginate($perPage);
    }
    public function getUserById($id)
    {
        return $this->userRepository->find($id);
    }

    public function createUser(array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $userData = [
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => $data['password'] ?? '$2y$12$9LhDzavtcmFGf73zuvFuP.FZ2uLcrBu3Ce62aQrB7lXQwTt9LvaQC', // Ensure password is included
        'user_type' => $data['user_type'] ?? 3, // Default user_type
        'phone' => $data['phone'] ?? null,
        'date_of_birth' => $data['date_of_birth'] ?? null,
        'bio' => $data['bio'] ?? null,
        'status' => $data['status'] ?? 1, // Default status
        'created_by' => $data['created_by'] ?? null,
        'updated_by' => $data['updated_by'] ?? null,
    ];
        $user = $this->userRepository->create($userData);

        if (isset($data['roles'])) {
            $user->assignRole($data['roles']);
        }
        if (isset($data['roles']) && is_array($data['roles'])) {
        $user->assignRole($data['roles']);
    }

    // Assign permissions if provided
    if (isset($data['permissions']) && is_array($data['permissions'])) {
        $user->givePermissionTo($data['permissions']);
    }

        return $user;
    }

    public function updateUser($id, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user = $this->userRepository->update($id, $data);

        // Sync roles if provided
        if (isset($data['roles']) && is_array($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        // Sync permissions if provided
        if (isset($data['permissions']) && is_array($data['permissions'])) {
            $user->syncPermissions($data['permissions']);
        }

        return $user;
    }

    public function deleteUser($id)
    {
        return $this->userRepository->delete($id);
    }

    public function assignExtraPermission($userId, $permissionId, $granted = true)
    {
        return $this->userRepository->assignExtraPermission($userId, $permissionId, $granted);
    }
    public function removeExtraPermission($userId, $permissionId, $granted = true)
    {
        return $this->userRepository->assignExtraPermission($userId, $permissionId);
    }
}
