<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//server url will be like this: http://localhost:8000/api/v1/....
Route::group(['prefix' => '/v1'], function () {
    Route::get('/', [HomeController::class, 'index']);

    // POST API, Will always outside of the middleware group
    Route::post("/login", [AuthController::class, 'login']); //login user
    Route::post("/register", [AuthController::class, 'registration']); //register user

    Route::get('/users', [UserController::class, 'usersList']);

    // Only for authenticated users
    Route::group(['middleware' => ['auth:sanctum']], function () {
        // Add General routes for authenticated users

        // Admin-only routes, apply API rate limit throttle:role:admin
        Route::group(['middleware' => 'role:admin', 'prefix' => 'admin'], function () {
            // Admin-specific routes here
        });

        // User-only routes, apply API rate limit throttle:role:user
        Route::group(['middleware' => 'role:user', 'prefix' => 'user'], function () {
            // User-specific routes here
        });
    });
});


// Routes Handler Error
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => '404 Not Found'
    ], 404);
});
