<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return [
        [
            "success" => true,
            "message" => "Welcome to Server Starter",
            "Developer" => "Minhazul Abedin Munna",
            "Social" => [
                "Facebook" => "https://www.facebook.com/smmunna21",
                "GitHub" => "https://www.github.com/smmunna",
            ],
            "Version" => "1.0.0",
            "Date" => "2024-01-01"
        ]
    ];
});
