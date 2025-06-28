<?php
// app/Http/Controllers/Api/PermissionController.php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Permission;
use App\Models\Module;

/**
 * @OA\Tag(
 *     name="Permissions",
 *     description="API Endpoints for permission management"
 * )
 */
class PermissionController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     *     path="/permissions",
     *     tags={"Permissions"},
     *     summary="Get all permissions",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Permissions retrieved successfully")
     * )
     */
    public function index()
    {
        $permissions = Permission::with('modules')->get();

        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    }

    /**
     * @OA\Post(
     *     path="/permissions",
     *     tags={"Permissions"},
     *     summary="Create new permission",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","module_id"},
     *             @OA\Property(property="name", type="string", example="view users"),
     *             @OA\Property(property="module_id", type="integer", example=1),
     *             @OA\Property(property="description", type="string", example="View user list")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Permission created successfully")
     * )
     */
    public function store(StorePermissionRequest $request)
    {
        $permission = Permission::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Permission created successfully',
            'data' => $permission->load('module')
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/permissions/{id}",
     *     tags={"Permissions"},
     *     summary="Get permission by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Permission retrieved successfully")
     * )
     */
    public function show(Permission $permission)
    {
        return response()->json([
            'success' => true,
            'data' => $permission->load('module')
        ]);
    }

    /**
     * @OA\Put(
     *     path="/permissions/{id}",
     *     tags={"Permissions"},
     *     summary="Update permission",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Permission updated successfully")
     * )
     */
    public function update(StorePermissionRequest $request, Permission $permission)
    {
        $permission->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Permission updated successfully',
            'data' => $permission->load('module')
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/permissions/{id}",
     *     tags={"Permissions"},
     *     summary="Delete permission",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Permission deleted successfully")
     * )
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->json([
            'success' => true,
            'message' => 'Permission deleted successfully'
        ]);
    }
}
