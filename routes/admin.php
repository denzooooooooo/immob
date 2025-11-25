<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes pour l'administration du site immobilier Monnkama
| Toutes ces routes nécessitent une authentification et le rôle admin
|
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');
    Route::get('/system-status', [DashboardController::class, 'systemStatus'])->name('system-status');

    // Gestion des propriétés
    Route::resource('properties', PropertyController::class);
    Route::patch('properties/{property}/toggle-featured', [PropertyController::class, 'toggleFeatured'])
        ->name('properties.toggle-featured');
    Route::patch('properties/{property}/toggle-published', [PropertyController::class, 'togglePublished'])
        ->name('properties.toggle-published');
    Route::post('properties/bulk-action', [PropertyController::class, 'bulkAction'])
        ->name('properties.bulk-action');

    // Gestion des utilisateurs
    Route::resource('users', UserController::class);
    Route::post('users/{user}/impersonate', [UserController::class, 'impersonate'])
        ->name('users.impersonate');
    Route::post('users/stop-impersonating', [UserController::class, 'stopImpersonating'])
        ->name('users.stop-impersonating');
    Route::post('users/bulk-action', [UserController::class, 'bulkAction'])
        ->name('users.bulk-action');
    Route::patch('users/{user}/activate', [UserController::class, 'activate'])
        ->name('users.activate');
    Route::patch('users/{user}/deactivate', [UserController::class, 'deactivate'])
        ->name('users.deactivate');

    // Gestion des abonnements
    Route::resource('subscriptions', SubscriptionController::class);
    Route::patch('subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])
        ->name('subscriptions.cancel');
    Route::post('subscriptions/{subscription}/renew', [SubscriptionController::class, 'renew'])
        ->name('subscriptions.renew');
    Route::patch('subscriptions/{subscription}/extend', [SubscriptionController::class, 'extend'])
        ->name('subscriptions.extend');
    Route::get('subscriptions-analytics', [SubscriptionController::class, 'analytics'])
        ->name('subscriptions.analytics');
    Route::post('subscriptions/bulk-action', [SubscriptionController::class, 'bulkAction'])
        ->name('subscriptions.bulk-action');

    // Gestion des villes et quartiers
    Route::prefix('locations')->name('locations.')->group(function () {
        // Cities
        Route::get('cities', [LocationController::class, 'cities'])->name('cities.index');
        Route::post('cities', [LocationController::class, 'storeCity'])->name('cities.store');
        Route::put('cities/{city}', [LocationController::class, 'updateCity'])->name('cities.update');
        Route::delete('cities/{city}', [LocationController::class, 'destroyCity'])->name('cities.destroy');
        
        // Neighborhoods
        Route::get('neighborhoods', [LocationController::class, 'neighborhoods'])->name('neighborhoods.index');
        Route::get('neighborhoods/{citySlug}', [LocationController::class, 'getNeighborhoodsByCity'])->name('neighborhoods.by-city');
        Route::post('neighborhoods', [LocationController::class, 'storeNeighborhood'])->name('neighborhoods.store');
        Route::put('neighborhoods/{neighborhood}', [LocationController::class, 'updateNeighborhood'])->name('neighborhoods.update');
        Route::delete('neighborhoods/{neighborhood}', [LocationController::class, 'destroyNeighborhood'])->name('neighborhoods.destroy');
    });

    // Gestion des messages
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('index');
        Route::get('{message}', [MessageController::class, 'show'])->name('show');
        Route::patch('{message}/mark-as-read', [MessageController::class, 'markAsRead'])->name('mark-as-read');
        Route::patch('{message}/report', [MessageController::class, 'report'])->name('report');
        Route::delete('{message}', [MessageController::class, 'destroy'])->name('destroy');
        Route::post('bulk-action', [MessageController::class, 'bulkAction'])->name('bulk-action');
    });

    // Gestion des paramètres
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::put('/', [SettingsController::class, 'update'])->name('update');
        
        // Site Settings
        Route::get('/site', [SiteSettingController::class, 'index'])->name('site');
        Route::put('/site', [SiteSettingController::class, 'update'])->name('site.update');
        Route::delete('/site/delete-image/{key}', [SiteSettingController::class, 'deleteImage'])->name('site.delete-image');
    });
});
