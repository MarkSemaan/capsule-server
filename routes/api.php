<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CapsuleController;
use App\Http\Controllers\CapsuleMediaController;
use App\Http\Middleware\CheckTokenVersion;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
        Route::get('me', 'me');
    });
});

Route::middleware([CheckTokenVersion::class, 'auth:api'])->group(function () {
    Route::controller(CapsuleController::class)->group(function () {
        Route::post('/capsules', 'store');
        Route::delete('/capsules/{id}', 'destroy');
        Route::get('/my-capsules', 'myCapsules');
        Route::get('/upcoming-capsules', 'upcomingCapsules');
    });

    Route::controller(CapsuleMediaController::class)->group(function () {
        Route::post('/capsule-media', 'store');
        Route::get('/capsule-media/{id}', 'show');
        Route::get('/capsules/{capsuleId}/media', 'getCapsuleMedia');
        Route::delete('/capsule-media/{id}', 'destroy');
    });
});

Route::group(['prefix' => 'public'], function () {
    Route::controller(CapsuleController::class)->group(function () {
        Route::get('/capsules/{id}', 'show');
        Route::get('/revealed-capsules', 'revealedCapsules');
        Route::get('/public-capsules', 'publicCapsules');
    });
});
