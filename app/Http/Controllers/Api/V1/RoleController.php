<?php
// app/Http/Controllers/Api/RoleController.php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Lib\ApiResponse;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Roles",
 *     description="API Endpoints for role management"
 * )
 */
class RoleController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     *     path="/roles",
     *     tags={"Roles"},
     *     summary="Get all roles",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Roles retrieved successfully")
     * )
     */
    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);

        return ApiResponse::success($roles, 'Role list fetch successfully');

    }
    /**
     * @OA\Get(
     *     path="/roles/all",
     *     tags={"Roles"},
     *     summary="Get all roles without pagination",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Roles retrieved successfully")
     * )
     */
    public function allRoles()
    {
        $roles = Role::with('permissions')->get();

        return ApiResponse::success($roles, 'Role list fetch successfully');

    }

    /**
     * @OA\Post(
     *     path="/roles",
     *     tags={"Roles"},
     *     summary="Create new role",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="manager"),
     *             @OA\Property(property="permissions", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(response=201, description="Role created successfully")
     * )
     */
    public function store(StoreRoleRequest $request)
    {
        \Log::info('Store role request', ['input' => $request->all()]);

            $role = Role::create($request->validated());

            if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
    }

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully',
            'data' => $role->load('permissions')
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/roles/{id}",
     *     tags={"Roles"},
     *     summary="Get role by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Role retrieved successfully")
     * )
     */
    public function show(Role $role)
    {
        return response()->json([
            'success' => true,
            'data' => $role->load('permissions')
        ]);
    }

    /**
     * @OA\Put(
     *     path="/roles/{id}",
     *     tags={"Roles"},
     *     summary="Update role",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Role updated successfully")
     * )
     */
    public function update(StoreRoleRequest $request, Role $role)
    {
        $role->update($request->validated());

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully',
            'data' => $role->load('permissions')
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/roles/{id}",
     *     tags={"Roles"},
     *     summary="Delete role",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Role deleted successfully")
     * )
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully'
        ]);
    }

    /**
     * @OA\Post(
     *     path="/roles/{id}/permissions",
     *     tags={"Roles"},
     *     summary="Assign permissions to role",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"permissions"},
     *             @OA\Property(property="permissions", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(response=200, description="Permissions assigned successfully")
     * )
     */
    public function assignPermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role->syncPermissions($request->permissions);

        return response()->json([
            'success' => true,
            'message' => 'Permissions assigned successfully',
            'data' => $role->load('permissions')
        ]);
    }
}
