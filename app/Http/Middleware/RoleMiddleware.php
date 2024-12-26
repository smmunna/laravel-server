<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if ($request->user() && $request->user()->role === $role) {
            return $next($request);
        }

        return response()->json([
            'message' => 'Access Denied',
            'description' => 'Your attempt to access this resource has been logged. If this action is unauthorized, please ensure you have the necessary permissions to proceed. For assistance, contact support.',
            'status' => 403,
            'tip' => 'Consider logging in with the appropriate account or reaching out to the administrator for access.'
        ], 403);
    }
}
