<?php
// Script de test pour l'API des quartiers
// À exécuter depuis le navigateur: http://localhost:8000/test_api_neighborhoods.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\City;
use App\Models\Neighborhood;

echo "<h1>Test de l'API des quartiers</h1>";

// Test 1: Vérifier les villes
echo "<h2>1. Villes dans la base de données:</h2>";
$cities = City::all();
echo "<ul>";
foreach ($cities as $city) {
    echo "<li><strong>{$city->name}</strong> (slug: {$city->slug}, id: {$city->id})</li>";
}
echo "</ul>";

// Test 2: Vérifier les quartiers d'Abidjan
echo "<h2>2. Quartiers d'Abidjan:</h2>";
$abidjan = City::where('slug', 'abidjan')->first();
if ($abidjan) {
    echo "<p>Ville trouvée: {$abidjan->name} (ID: {$abidjan->id})</p>";
    $neighborhoods = Neighborhood::where('city_id', $abidjan->id)->get();
    echo "<p>Nombre de quartiers: " . $neighborhoods->count() . "</p>";
    echo "<ul>";
    foreach ($neighborhoods as $neighborhood) {
        echo "<li>{$neighborhood->name} (slug: {$neighborhood->slug})</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: red;'>❌ Ville 'abidjan' non trouvée!</p>";
}

// Test 3: Simuler l'appel API
echo "<h2>3. Simulation de l'appel API:</h2>";
$citySlug = 'abidjan';
$city = City::where('slug', $citySlug)->first();

if (!$city) {
    echo "<p style='color: red;'>❌ Ville non trouvée</p>";
} else {
    $neighborhoods = Neighborhood::where('city_id', $city->id)
        ->where('is_active', true)
        ->select('slug', 'name')
        ->orderBy('name')
        ->get();
    
    echo "<p style='color: green;'>✅ Ville trouvée: {$city->name}</p>";
    echo "<p>Nombre de quartiers actifs: " . $neighborhoods->count() . "</p>";
    echo "<pre>";
    echo json_encode($neighborhoods, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo "</pre>";
}

// Test 4: Tester l'URL de l'API
echo "<h2>4. Test de l'URL API:</h2>";
echo "<p>URL à tester: <a href='/api/cities/abidjan/neighborhoods' target='_blank'>/api/cities/abidjan/neighborhoods</a></p>";
echo "<p>Cliquez sur le lien ci-dessus pour voir la réponse de l'API</p>";
