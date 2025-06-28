<?php
// app/Http/Controllers/Api/UserController.php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Lib\ApiResponse;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints for user management"
 * )
 */
class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        // $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     *     path="/users",
     *     tags={"Users"},
     *     summary="Get all users",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", default=15)),
     *     @OA\Response(response=200, description="Users retrieved successfully")
     * )
     */
    public function index(Request $request)
    {
        // $this->authorize('viewAny', User::class);

        $users = $this->userService->getAllUsers($request->get('per_page', 15));
        $users->load(['roles', 'permissions', 'extraPermissions']);

        return ApiResponse::success($users, 'User list fetch successfully');
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     tags={"Users"},
     *     summary="Create new user",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="address", type="string"),
     *             @OA\Property(property="roles", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(response=201, description="User created successfully")
     * )
     */
    public function store(StoreUserRequest $request)
    {
        // Log::info($request->all());
        // $this->authorize('create', User::class);

        $user = $this->userService->createUser($request->validated());

        return ApiResponse::success($user, 'User created successfully', 201);

    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Get user by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="User retrieved successfully")
     * )
     */
    public function show($id)
    {
        $user = $this->userService->getUserById($id);
        // $this->authorize('view', $user);

        return response()->json([
            'success' => true,
            'data' => $user->load(['roles', 'permissions', 'extraPermissions'])
        ]);
    }

    /**
     * @OA\Put(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Update user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="User updated successfully")
     * )
     */
    public function update(StoreUserRequest $request, $id)
    {
        Log::info($request->all());
        $user = $this->userService->getUserById($id);
        if (!$user) {
            return ApiResponse::error('User not found', 404);
        }
        // $this->authorize('update', $user);
        $updatedUser = $this->userService->updateUser($id, $request->validated());
        return ApiResponse::success($updatedUser, 'User updated successfully');

    }

    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Delete user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="User deleted successfully")
     * )
     */
    public function destroy($id)
    {
        $user = $this->userService->getUserById($id);
        // $this->authorize('delete', $user);

        $this->userService->deleteUser($id);
        return ApiResponse::success(null, 'User deleted successfully');
    }

    /**
     * @OA\Post(
     *     path="/users/{id}/extra-permissions",
     *     tags={"Users"},
     *     summary="Assign extra permission to user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"permission_id"},
     *             @OA\Property(property="permission_id", type="integer"),
     *             @OA\Property(property="granted", type="boolean", default=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Extra permission assigned")
     * )
     */
    public function assignExtraPermission(Request $request, $id)
    {
        $request->validate([
            'permission_id' => 'required|exists:permissions,id',
            'granted' => 'boolean'
        ]);

        $this->userService->assignExtraPermission(
            $id,
            $request->permission_id,
            $request->get('granted', true)
        );

        return ApiResponse::success(null, 'Extra permission assigned successfully');

    }
    public function removeExtraPermission(Request $request, $id)
    {
        $request->validate([
            'permission_id' => 'required|exists:permissions,id',
        ]);

        $this->userService->removeExtraPermission(
            $id,
            $request->permission_id,
        );

        return ApiResponse::success(null, 'Extra permission removed successfully');

    }

    /**
     * @OA\Post(
     *     path="/users/{id}/upload-avatar",
     *     tags={"Users"},
     *     summary="Upload user avatar",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="avatar", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Avatar uploaded successfully")
     * )
     */
    public function uploadAvatar(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return ApiResponse::notFound('User not found');
        }

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user->clearMediaCollection('avatars');
        $media = $user->addMediaFromRequest('avatar')
            ->toMediaCollection('avatars');

        return ApiResponse::success([
            'avatar_url' => $media->getUrl()
        ], 'Avatar uploaded successfully');
    }
}
