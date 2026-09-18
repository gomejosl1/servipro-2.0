<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\FacebookAuthController;
use App\Http\Controllers\Api\V1\GoogleAuthController;
use App\Http\Controllers\Api\V1\OnboardingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->name('v1.')->group(function () {
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/me', [AuthController::class, 'me'])->name('me');
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        });

        Route::prefix('google')->name('google.')->group(function () {
            Route::get('/redirect', [GoogleAuthController::class, 'redirect'])->name('redirect');
            Route::get('/callback', [GoogleAuthController::class, 'callback'])->name('callback');
        });

        Route::prefix('facebook')->name('facebook.')->group(function () {
            Route::get('/redirect', [FacebookAuthController::class, 'redirect'])->name('redirect');
            Route::get('/callback', [FacebookAuthController::class, 'callback'])->name('callback');
        });
    });

    Route::prefix('onboarding')->name('onboarding.')->middleware('auth:sanctum')->group(function () {
        Route::post('/buyer', [OnboardingController::class, 'buyer'])->name('buyer');
        Route::post('/provider', [OnboardingController::class, 'provider'])->name('provider');
    });
});
