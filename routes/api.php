<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CapsuleController;
use App\Http\Controllers\CapsuleMediaController;
use App\Http\Middleware\CheckTokenVersion;
use App\Http\Controllers\TagController;

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

    Route::controller(TagController::class)->group(function () {
        Route::post('/tags', 'store');                           // Create tag
        Route::get('/tags', 'index');                            // Get all tags
        Route::get('/tags/{id}', 'show');                        // Get specific tag
        Route::delete('/tags/{id}', 'destroy');                  // Delete tag

        Route::post('/capsules/{capsuleId}/tags', 'attachToCapsule');
        Route::delete('/capsules/{capsuleId}/tags/{tagId}', 'detachFromCapsule');
        Route::get('/capsules/{capsuleId}/tags', 'getCapsuleTags');
    });
});

Route::group(['prefix' => 'public'], function () {
    Route::controller(CapsuleController::class)->group(function () {
        Route::get('/capsules/{id}', 'show');
        Route::get('/revealed-capsules', 'revealedCapsules');
        Route::get('/public-capsules', 'publicCapsules');
    });
});
