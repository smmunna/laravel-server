<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomCors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Specify allowed origins here
        $allowedOrigins = [
            'http://localhost:5173', // Example domain 1
            'http://example.com',    // Example domain 2
            // Add more domains as needed
        ];

        $origin = $request->headers->get('Origin');

        // Check if the origin is in the allowed list
        if (in_array($origin, $allowedOrigins)) {
            // If the origin is allowed, continue the request
            return $next($request)
                ->header('Access-Control-Allow-Origin', $origin) // Allow the specific origin
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization')
                ->header('Access-Control-Allow-Credentials', 'true'); // Allow credentials (cookies)
        }

        // If the origin is not allowed, return a JSON response with a message
        return response()->json([
            'message' => 'CORS Policy Error: Access from this origin is not allowed.',
            'origin' => $origin,
            'allowed_origins' => $allowedOrigins,
        ], 403);
    }
}
