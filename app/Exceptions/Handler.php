<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {

            if ($exception instanceof UnauthorizedException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.',
                    'error' => 'You do not have the required permissions.'
                ], 403);
            }

            if ($exception instanceof ValidationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $exception->errors()
                ], 422);
            }

            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found.',
                    'error' => 'The requested resource does not exist.'
                ], 404);
            }

            if ($exception instanceof HttpException) {
                return response()->json([
                    'success' => false,
                    'message' => $exception->getMessage() ?: 'HTTP error occurred.',
                    'error' => $exception->getStatusCode()
                ], $exception->getStatusCode());
            }

            return response()->json([
                'success' => false,
                'message' => 'Internal server error.',
                'error' => config('app.debug') ? $exception->getMessage() : 'Something went wrong.'
            ], 500);
        }

        return parent::render($request, $exception);
    }

    /**
     * Override default unauthenticated behavior to return JSON.
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated.',
            'error' => 'Authentication token is missing or invalid.'
        ], 401);
    }
}
