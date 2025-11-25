<?php

use App\Http\Controllers\Agent\DashboardController;
use App\Http\Controllers\Agent\PropertyController;
use App\Http\Controllers\Agent\MessageController;
use App\Http\Controllers\Agent\SubscriptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Agent Routes
|--------------------------------------------------------------------------
|
| Routes pour les agents immobiliers
| Accès pour ceux qui déposent des annonces de location/vente
|
*/

Route::middleware(['auth', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {
    
    // Dashboard Agent
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/statistics', [DashboardController::class, 'statistics'])->name('statistics');

    // Gestion des propriétés de l'agent
    Route::resource('properties', PropertyController::class);
    Route::post('properties/{property}/toggle-featured', [PropertyController::class, 'toggleFeatured'])
        ->name('properties.toggle-featured');
    Route::post('properties/{property}/toggle-published', [PropertyController::class, 'togglePublished'])
        ->name('properties.toggle-published');
    Route::post('properties/{property}/duplicate', [PropertyController::class, 'duplicate'])
        ->name('properties.duplicate');
    Route::delete('properties/media/{media}', [PropertyController::class, 'deleteMedia'])
        ->name('properties.media.delete');

    // Gestion des messages
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('index');
        Route::get('{message}', [MessageController::class, 'show'])->name('show');
        Route::post('{message}/reply', [MessageController::class, 'reply'])->name('reply');
        Route::patch('{message}/mark-read', [MessageController::class, 'markAsRead'])->name('mark-read');
    });

    // Gestion de l'abonnement
    Route::prefix('subscription')->name('subscription.')->group(function () {
        Route::get('/', [SubscriptionController::class, 'show'])->name('show');
        Route::post('/upgrade', [SubscriptionController::class, 'upgrade'])->name('upgrade');
        Route::post('/renew', [SubscriptionController::class, 'renew'])->name('renew');
    });

    // Profil agent
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
});
