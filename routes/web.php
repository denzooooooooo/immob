<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');

// Propriétés
Route::get('/proprietes', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/proprietes/{property:slug}', [PropertyController::class, 'show'])->name('properties.show');
Route::get('/recherche', [SearchController::class, 'index'])->name('search.index');

// Villes
Route::get('/villes', [CityController::class, 'index'])->name('cities.index');
Route::get('/ville/{city:slug}', [CityController::class, 'show'])->name('city');

// Pages statiques
Route::get('/a-propos', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/politique-de-confidentialite', [PageController::class, 'privacy'])->name('privacy');
Route::get('/conditions-utilisation', [PageController::class, 'terms'])->name('terms');

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [App\Http\Controllers\Auth\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/connexion', [App\Http\Controllers\Auth\AuthController::class, 'login']);
    Route::get('/inscription', [App\Http\Controllers\Auth\AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/inscription', [App\Http\Controllers\Auth\AuthController::class, 'register']);
    
    // Routes de réinitialisation de mot de passe
    Route::get('/mot-de-passe/reinitialiser', [App\Http\Controllers\Auth\AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/mot-de-passe/email', [App\Http\Controllers\Auth\AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/mot-de-passe/reinitialiser/{token}', [App\Http\Controllers\Auth\AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/mot-de-passe/reinitialiser', [App\Http\Controllers\Auth\AuthController::class, 'resetPassword'])->name('password.update');
    
    // Routes d'authentification sociale
    Route::get('auth/google', [App\Http\Controllers\Auth\AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [App\Http\Controllers\Auth\AuthController::class, 'handleGoogleCallback']);
    Route::get('auth/facebook', [App\Http\Controllers\Auth\AuthController::class, 'redirectToFacebook'])->name('auth.facebook');
    Route::get('auth/facebook/callback', [App\Http\Controllers\Auth\AuthController::class, 'handleFacebookCallback']);
});

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    Route::post('/deconnexion', [App\Http\Controllers\Auth\AuthController::class, 'logout'])->name('logout');
    Route::get('/profil', [App\Http\Controllers\Auth\AuthController::class, 'profile'])->name('profile');
    Route::put('/profil', [App\Http\Controllers\Auth\AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profil/mot-de-passe', [App\Http\Controllers\Auth\AuthController::class, 'updatePassword'])->name('profile.password');
    
    // Routes des favoris
    Route::get('/favoris', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/proprietes/{property}/favori', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::delete('/favoris/{property}', [FavoriteController::class, 'remove'])->name('favorites.remove');
    Route::delete('/favoris', [FavoriteController::class, 'clear'])->name('favorites.clear');
    Route::get('/api/favoris', [FavoriteController::class, 'getFavorites'])->name('favorites.api');
});

// Route pour contacter à propos d'une propriété
Route::post('/proprietes/{property}/contact', function () {
    return redirect()->back()->with('success', 'Votre message a été envoyé avec succès !');
})->name('contact.property');

// Routes de paiement
Route::prefix('payment')->name('payment.')->group(function () {
    // Callbacks Orange Money
    Route::get('/callback/orange', [PaymentController::class, 'orangeCallback'])->name('callback.orange');
    Route::get('/callback/orange/cancel', [PaymentController::class, 'orangeCancelCallback'])->name('callback.orange.cancel');
    
    // Webhooks
    Route::post('/webhook/orange', [PaymentController::class, 'orangeWebhook'])->name('webhook.orange');
    Route::post('/webhook/mtn', [PaymentController::class, 'mtnWebhook'])->name('webhook.mtn');
    Route::post('/webhook/airtel', [PaymentController::class, 'airtelWebhook'])->name('webhook.airtel');
    Route::post('/webhook/stripe', [PaymentController::class, 'stripeWebhook'])->name('webhook.stripe');
    
    // Vérification de statut
    Route::get('/status/mtn', [PaymentController::class, 'checkMTNStatus'])->name('status.mtn');
    Route::get('/status/airtel', [PaymentController::class, 'checkAirtelStatus'])->name('status.airtel');
    
    // PayPal
    Route::get('/callback/paypal', [PaymentController::class, 'paypalCallback'])->name('callback.paypal');
    Route::get('/callback/paypal/cancel', [PaymentController::class, 'paypalCancelCallback'])->name('callback.paypal.cancel');
    
    // Stripe
    Route::get('/callback/stripe', [PaymentController::class, 'stripeCallback'])->name('callback.stripe');
    Route::get('/callback/stripe/cancel', [PaymentController::class, 'stripeCancelCallback'])->name('callback.stripe.cancel');
});

// Inclusion des routes admin
require __DIR__.'/admin.php';

// Inclusion des routes agent
require __DIR__.'/agent.php';
