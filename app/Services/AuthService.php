<?php
// app/Services/AuthService.php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Enums\UserTypeEnum;
use App\Lib\ApiResponse;
use App\Models\LoginHistory;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UserRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);

        // Assign default role
        $user->assignRole(UserTypeEnum::USER_TYPE_NAMES);
        $token = $user->createToken('auth_token')->plainTextToken;

        $this->createLoginHistory($user, request());

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function login(array $credentials)
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new HttpResponseException(
                ApiResponse::error("The provided credentials are incorrect.")
            );
        }


        // if ($user->status !== 'active') {
        //     throw ValidationException::withMessages([
        //         'email' => ['Your account is inactive.'],
        //     ]);
        // }
        if ($user->status !== StatusEnum::ACTIVE) {
            return ApiResponse::forbidden('Account is inactive');
        }

        // $this->userRepository->updateLastLogin($user->id);
        $this->createLoginHistory($user, request());
        $token = $user->createToken('auth_token')->plainTextToken;
        $user->load(['roles', 'roles.permissions', 'permissions']);

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout($user)
    {
        $loginHistory = LoginHistory::where(LoginHistory::USER_ID, $user->id)
            ->whereNull(LoginHistory::LOGOUT_AT)
            ->latest()
            ->first();

        if ($loginHistory) {
            $loginHistory->update([LoginHistory::LOGOUT_AT => now()]);
        }
        return $user->currentAccessToken()->delete();
    }

    private function createLoginHistory($user, $request)
    {
        LoginHistory::create([
            LoginHistory::USER_ID => $user->id,
            LoginHistory::IP_ADDRESS => $request->ip(),
            LoginHistory::USER_AGENT => $request->userAgent(),
            LoginHistory::LOGIN_AT => now(),
            LoginHistory::DEVICE_TYPE => $this->getDeviceType($request->userAgent()),
            LoginHistory::LOCATION => $this->getLocationFromIP($request->ip()),
        ]);
    }
    private function getDeviceType($userAgent)
    {
        if (strpos($userAgent, 'Mobile') !== false) {
            return 'mobile';
        } elseif (strpos($userAgent, 'Tablet') !== false) {
            return 'tablet';
        }
        return 'desktop';
    }

    private function getLocationFromIP($ip)
    {
        // Implement IP to location logic here
        return 'Unknown';
    }

}
