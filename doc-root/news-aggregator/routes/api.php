<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\UserPreferenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test-api', function (Request $request) {
    return response()->json([
        'message' => 'This is a test API route!',
        'status' => 'success'
    ]);
});

Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login'])
    ->middleware('throttle:5,1') // 5 requests per minute
    ->name('login');

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [ApiAuthController::class, 'logout'])->name('logout');
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('articles', [ArticleController::class, 'index']);
    Route::get('articles/{article}', [ArticleController::class, 'show']);
    Route::get('/personalized-feed', [ArticleController::class, 'getPersonalizedFeed']);

    Route::post('/preferences', [UserPreferenceController::class, 'setPreferences']);
    Route::get('/preferences', [UserPreferenceController::class, 'getPreferences']);

});

Route::post('/password/email', [ApiAuthController::class, 'sendResetLinkEmail']); // Request password reset link
Route::post('/password/reset', [ApiAuthController::class, 'resetPassword']);      // Reset password

