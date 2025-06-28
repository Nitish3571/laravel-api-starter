<?php
// app/Http/Controllers/Api/ModuleController.php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreModuleRequest;
use App\Http\Requests\UpdateModuleRequest;
use App\Models\Module;

/**
 * @OA\Tag(
 *     name="Modules",
 *     description="API Endpoints for module management"
 * )
 */
class ModuleController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     *     path="/modules",
     *     tags={"Modules"},
     *     summary="Get all modules",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Modules retrieved successfully")
     * )
     */
    public function index()
    {
        $modules = Module::with('permissions')->get();

        return response()->json([
            'success' => true,
            'data' => $modules
        ]);
    }

    /**
     * @OA\Post(
     *     path="/modules",
     *     tags={"Modules"},
     *     summary="Create new module",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","slug"},
     *             @OA\Property(property="name", type="string", example="User Management"),
     *             @OA\Property(property="slug", type="string", example="users"),
     *             @OA\Property(property="description", type="string", example="Module for managing users"),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Module created successfully")
     * )
     */
    public function store(StoreModuleRequest $request)
    {
        $module = Module::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Module created successfully',
            'data' => $module
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/modules/{id}",
     *     tags={"Modules"},
     *     summary="Get module by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Module retrieved successfully")
     * )
     */
    public function show(Module $module)
    {
        return response()->json([
            'success' => true,
            'data' => $module->load('permissions')
        ]);
    }

    /**
     * @OA\Put(
     *     path="/modules/{id}",
     *     tags={"Modules"},
     *     summary="Update module",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Module updated successfully")
     * )
     */
    public function update(StoreModuleRequest $request, Module $module)
    {
        $module->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Module updated successfully',
            'data' => $module
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/modules/{id}",
     *     tags={"Modules"},
     *     summary="Delete module",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Module deleted successfully")
     * )
     */
    public function destroy(Module $module)
    {
        $module->delete();

        return response()->json([
            'success' => true,
            'message' => 'Module deleted successfully'
        ]);
    }
}
