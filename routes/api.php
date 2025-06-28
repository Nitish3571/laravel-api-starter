<?php

use App\Http\Controllers\Api\V1\ModuleController;
use App\Http\Controllers\Api\V1\PermissionController;
use App\Http\Controllers\Api\V1\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;

Route::prefix('v1')->group(function () {
// Authentication routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    // User routes
    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
    Route::post('users/{id}/upload-avatar', [UserController::class, 'uploadAvatar']);


  Route::post('users/{user}/extra-permissions', [UserController::class, 'assignExtraPermission']);
    Route::delete('users/{user}/extra-permissions/{permission}', [UserController::class, 'removeExtraPermission']);

    // Role management
    Route::get('roles/all', [RoleController::class, 'allRoles']);
    Route::apiResource('roles', RoleController::class);
    Route::post('roles/{role}/permissions', [RoleController::class, 'assignPermissions']);

    // Permission management
    Route::apiResource('permissions', PermissionController::class);

    // Module management
    Route::apiResource('modules', ModuleController::class);
});

});

