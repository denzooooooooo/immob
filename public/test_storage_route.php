<?php
echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Test Route Storage</title>";
echo "<style>body{font-family:Arial;padding:20px;} .ok{color:green;} .error{color:red;}</style></head><body>";
echo "<h1>🔍 Test de la route /storage/</h1>";

// Test 1: Vérifier si le fichier routes/storage.php existe
$routeFile = __DIR__ . '/../routes/storage.php';
echo "<h2>1. Fichier routes/storage.php</h2>";
if (file_exists($routeFile)) {
    echo "<p class='ok'>✅ Le fichier existe</p>";
} else {
    echo "<p class='error'>❌ Le fichier N'EXISTE PAS - Vous devez l'uploader!</p>";
}

// Test 2: Vérifier si bootstrap/app.php charge la route
$bootstrapFile = __DIR__ . '/../bootstrap/app.php';
$bootstrapContent = file_get_contents($bootstrapFile);
echo "<h2>2. Chargement de la route dans bootstrap/app.php</h2>";
if (strpos($bootstrapContent, "routes/storage.php") !== false) {
    echo "<p class='ok'>✅ La route est chargée dans bootstrap/app.php</p>";
} else {
    echo "<p class='error'>❌ La route N'EST PAS chargée - Vous devez modifier bootstrap/app.php!</p>";
}

// Test 3: Tester l'accès à une image via la route
echo "<h2>3. Test d'accès à une image</h2>";
echo "<p>Essayez d'accéder à cette URL:</p>";
echo "<p><a href='/storage/properties/images/n5Arrob86nPUfMTY6PEFQFAr7bdWKXDI7vgXYoa1.jpg' target='_blank'>";
echo "/storage/properties/images/n5Arrob86nPUfMTY6PEFQFAr7bdWKXDI7vgXYoa1.jpg</a></p>";

echo "<h2>4. Image de test</h2>";
echo "<img src='/storage/properties/images/n5Arrob86nPUfMTY6PEFQFAr7bdWKXDI7vgXYoa1.jpg' style='max-width:300px;border:2px solid #ccc;' onerror=\"this.style.display='none'; document.getElementById('img-error').style.display='block';\">";
echo "<p id='img-error' style='display:none;' class='error'>❌ L'image ne s'affiche pas - La route ne fonctionne pas encore</p>";

echo "<h2>📋 Actions à faire:</h2>";
echo "<ol>";
echo "<li>Uploadez <code>routes/storage.php</code></li>";
echo "<li>Uploadez <code>bootstrap/app.php</code> (modifié)</li>";
echo "<li>Exécutez: <code>php artisan route:clear && php artisan cache:clear</code></li>";
echo "<li>Rechargez cette page</li>";
echo "</ol>";

echo "</body></html>";
?>
