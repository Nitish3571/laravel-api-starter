<?php

namespace App\Http\Middleware;

use App\Lib\ApiResponse;
use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!$request->user()->can($permission)) {
            return ApiResponse::forbidden('You do not have permission to access this resource');
        }

        return $next($request);
    }
}
