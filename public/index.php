<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 * Configuration optimisée pour Hostinger
 */

// Activer l'affichage des erreurs en développement
if (isset($_SERVER['APP_DEBUG']) && $_SERVER['APP_DEBUG'] === true) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Définir le début de l'application
define('LARAVEL_START', microtime(true));

// Vérifier le mode maintenance
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Charger l'autoloader de Composer
require __DIR__.'/../vendor/autoload.php';

// Démarrer l'application Laravel
$app = require_once __DIR__.'/../bootstrap/app.php';

// Exécuter la requête
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);
