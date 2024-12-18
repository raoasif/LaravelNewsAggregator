<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\AuthController;

Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login'])->name('login');

Route::middleware('throttle:login')->group(function () {
});

Route::middleware('auth:sanctum')->post('/logout', [ApiAuthController::class, 'logout']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout');
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::get('articles', [ArticleController::class, 'index']);
    Route::get('articles/{article}', [ArticleController::class, 'show']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/password/email', [ApiAuthController::class, 'sendResetLinkEmail']); // Request password reset link
Route::post('/password/reset', [ApiAuthController::class, 'resetPassword']);      // Reset password


Route::get('/test-api', function (Request $request) {
    return response()->json([
        'message' => 'This is a test API route!',
        'status' => 'success'
    ]);
});

