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
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/', [HomeController::class, 'index']);

// POST API, Will always outside of the middleware group
Route::post("/login", [AuthController::class, 'login']); //login user
Route::post("/register", [AuthController::class, 'registration']); //register user


// Only for authenticate users
Route::group(['middleware' => ['auth:sanctum']], function () {
    // General routes for authenticated users
    Route::get('/users', [UserController::class, 'usersList']);

    // Admin-only routes
    Route::group(['middleware' => 'role:admin', 'prefix' => 'admin'], function () {
        // add routes here
    });

    // user-only routes
    Route::group(['middleware' => 'role:user', 'prefix' => 'user'], function () {
        // add routes here
    });
});



// Routes Handler Error
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => '404 Not Found'
    ], 404);
});
