<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDetailsController;
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

Route::prefix("user-details")->group(function () {
    Route::get("/", [UserDetailsController::class, 'index']);
    Route::get("/{id}", [UserDetailsController::class, 'show']);
});


Route::apiResource("books", BookController::class);

Route::middleware("auth:sanctum")->group(function () {
    Route::get("auth/me", [AuthenticationController::class, 'me']);
    Route::get("auth/logout", [AuthenticationController::class, 'logout']);
    Route::prefix("user-details")->group(function () {
        Route::post("/", [UserDetailsController::class, 'store']);
        Route::put("/{id}", [UserDetailsController::class, 'update']);
        Route::delete("/{id}", [UserDetailsController::class, 'destroy']);
    });
});
