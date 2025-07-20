<?php

namespace App\Lib;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ApiResponse
{
    public static function res($data = null, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => true,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }
    public static function success($data = null, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => true,
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    public static function error(string $message = 'Error', $data = null, int $statusCode = 400): JsonResponse
    {
        $res = [];
        $res['status'] = false;
        $res['message'] = $message;
        if ($data != null || $data != []) {
            $res['data'] = $data;
        }
        $res['statusCode'] = $statusCode;

        Log::error($message, [
            'data' => $data,
            'statusCode' => $statusCode,
        ]);
        return response()->json($res, $statusCode);
    }

    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::error($message, null, 401);
    }

    public static function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return self::error($message, null, 403);
    }

    public static function notFound(string $message = 'Not Found'): JsonResponse
    {
        return self::error($message, null, 404);
    }

    public static function validationError($errors, string $message = 'Validation Error'): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => $errors,
            'statusCode' => 422
        ], 422);
    }
}
