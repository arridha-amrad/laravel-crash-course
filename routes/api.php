<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;
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

Route::apiResource('users', UserController::class);

Route::post("auth/login", [AuthenticationController::class, 'login']);

Route::middleware("auth:sanctum")->group(function () {
    Route::get("auth/me", [AuthenticationController::class, 'me']);
    Route::get("auth/logout", [AuthenticationController::class, 'logout']);
});
