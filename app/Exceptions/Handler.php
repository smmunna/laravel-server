<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'message' => 'Unauthorized',
            'description' => 'Authentication is required to access this resource.',
            'status' => 401,
            'tip' => 'Please provide a valid authentication token in the request header.'
        ], 401);
    }
}
