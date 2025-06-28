<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *    title="Laravel API Documentation",
 *    description="API documentation for Laravel project with authentication, user management, and role-permission",
 *    version="1.0.0",
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Use bearer token to access this API",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth"
 * )
 */

abstract class Controller
{
    //
}
