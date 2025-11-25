<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 * Configuration spéciale pour Hostinger avec dossier "nalik"
 */

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Définir le début de l'application
define('LARAVEL_START', microtime(true));

// Vérifier le mode maintenance
if (file_exists($maintenance = __DIR__.'/../nalik/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Charger l'autoloader de Composer
require __DIR__.'/../nalik/vendor/autoload.php';

// Démarrer l'application Laravel
$app = require_once __DIR__.'/../nalik/bootstrap/app.php';

// Capturer et traiter la requête
use Illuminate\Http\Request;

$request = Request::capture();
$response = $app->handle($request);
$response->send();

// Terminer l'application
$app->terminate($request, $response);
