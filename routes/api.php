<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthApiController;
use App\Http\Controllers\Api\V1\PropertyApiController;
use App\Http\Controllers\Api\V1\FavoriteApiController;
use App\Http\Controllers\Api\V1\LocationApiController;

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

// API V1 Routes
Route::prefix('v1')->group(function () {
    // Auth Routes
    Route::post('/login', [AuthApiController::class, 'login']);
    Route::post('/register', [AuthApiController::class, 'register']);
    Route::post('/forgot-password', [AuthApiController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthApiController::class, 'resetPassword']);

    // Protected Routes (accepte à la fois Sanctum et les sessions web)
    Route::middleware(['auth:sanctum,web'])->group(function () {
        // User Profile
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::post('/logout', [AuthApiController::class, 'logout']);
        
        // Properties
        Route::get('/properties', [PropertyApiController::class, 'index']);
        Route::get('/properties/{property}', [PropertyApiController::class, 'show']);
        Route::post('/properties/{property}/favorite', [FavoriteApiController::class, 'toggle']);
        
        // Favorites
        Route::get('/favorites', [FavoriteApiController::class, 'index']);
        Route::post('/favorites/bulk', [FavoriteApiController::class, 'bulk']);
        Route::post('/favorites/check', [FavoriteApiController::class, 'check']);
    });

    // Public Property Routes
    Route::get('/properties/featured', [PropertyApiController::class, 'featured']);
    Route::get('/properties/search', [PropertyApiController::class, 'search']);
    Route::get('/properties/cities/{city}', [PropertyApiController::class, 'byCity']);

    // Location Routes
    Route::get('/cities', [LocationApiController::class, 'getCities']);
    Route::get('/cities/{city}/neighborhoods', [LocationApiController::class, 'getNeighborhoodsByCity']);
});

// Routes pour les locations (compatibilité avec l'ancien système)
Route::get('/cities/{city}/neighborhoods', [LocationApiController::class, 'getNeighborhoodsByCity'])->name('api.neighborhoods');

// Fallback for undefined routes
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Route non trouvée.'
    ], 404);
});
