<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Diagnostic Images</title>";
echo "<style>body{font-family:Arial;padding:20px;background:#f5f5f5;} .box{background:white;padding:20px;margin:10px 0;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);} .ok{color:green;font-weight:bold;} .error{color:red;font-weight:bold;} .warning{color:orange;font-weight:bold;} h2{color:#333;border-bottom:2px solid #667eea;padding-bottom:10px;} code{background:#f0f0f0;padding:2px 6px;border-radius:3px;}</style>";
echo "</head><body>";
echo "<h1>🔍 Diagnostic des Images - Version Simple</h1>";

// Test 1: Vérifier que PHP fonctionne
echo "<div class='box'><h2>1. PHP fonctionne</h2>";
echo "<p class='ok'>✅ PHP Version: " . phpversion() . "</p></div>";

// Test 2: Vérifier les chemins
echo "<div class='box'><h2>2. Chemins du serveur</h2>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p><strong>Script actuel:</strong> " . __FILE__ . "</p>";
$publicPath = __DIR__;
$storagePath = dirname(__DIR__) . '/storage/app/public';
echo "<p><strong>Dossier public:</strong> " . $publicPath . "</p>";
echo "<p><strong>Dossier storage:</strong> " . $storagePath . "</p>";
echo "</div>";

// Test 3: Vérifier le lien symbolique
echo "<div class='box'><h2>3. Lien Symbolique 'storage'</h2>";
$storageLink = $publicPath . '/storage';
if (is_link($storageLink)) {
    echo "<p class='ok'>✅ Le lien symbolique existe</p>";
    echo "<p><strong>Pointe vers:</strong> " . readlink($storageLink) . "</p>";
} elseif (is_dir($storageLink)) {
    echo "<p class='warning'>⚠️ 'storage' est un DOSSIER, pas un lien symbolique</p>";
    echo "<p><strong>Solution:</strong> Supprimez le dossier et exécutez <code>php artisan storage:link</code></p>";
} else {
    echo "<p class='error'>❌ Le lien symbolique n'existe PAS</p>";
    echo "<p><strong>Solution:</strong> Exécutez <code>php artisan storage:link</code></p>";
}
echo "</div>";

// Test 4: Vérifier si storage/app/public existe
echo "<div class='box'><h2>4. Dossier storage/app/public</h2>";
if (is_dir($storagePath)) {
    echo "<p class='ok'>✅ Le dossier existe</p>";
    $perms = substr(sprintf('%o', fileperms($storagePath)), -4);
    echo "<p><strong>Permissions:</strong> " . $perms . "</p>";
    
    // Lister les sous-dossiers
    $subdirs = glob($storagePath . '/*', GLOB_ONLYDIR);
    if ($subdirs) {
        echo "<p><strong>Sous-dossiers:</strong></p><ul>";
        foreach ($subdirs as $dir) {
            echo "<li>" . basename($dir) . "</li>";
        }
        echo "</ul>";
    }
} else {
    echo "<p class='error'>❌ Le dossier n'existe PAS</p>";
}
echo "</div>";

// Test 5: Chercher des images
echo "<div class='box'><h2>5. Recherche d'images</h2>";
$imagePatterns = [
    $storagePath . '/properties/images/*.jpg',
    $storagePath . '/properties/images/*.png',
    $storagePath . '/properties/images/*.jpeg',
];

$foundImages = [];
foreach ($imagePatterns as $pattern) {
    $images = glob($pattern);
    if ($images) {
        $foundImages = array_merge($foundImages, $images);
    }
}

if ($foundImages) {
    echo "<p class='ok'>✅ " . count($foundImages) . " image(s) trouvée(s)</p>";
    echo "<p><strong>Première image:</strong> " . basename($foundImages[0]) . "</p>";
    
    // Tester l'affichage
    $relativePath = str_replace($storagePath, '', $foundImages[0]);
    $url1 = '/storage' . $relativePath;
    $url2 = '/storage/app/public' . $relativePath;
    
    echo "<h3>Test d'affichage:</h3>";
    echo "<p>URL 1: <code>" . $url1 . "</code></p>";
    echo "<img src='" . $url1 . "' style='max-width:200px;border:2px solid green;' onerror=\"this.style.border='2px solid red';this.alt='❌ Échec';\">";
    
    echo "<p>URL 2: <code>" . $url2 . "</code></p>";
    echo "<img src='" . $url2 . "' style='max-width:200px;border:2px solid green;' onerror=\"this.style.border='2px solid red';this.alt='❌ Échec';\">";
} else {
    echo "<p class='warning'>⚠️ Aucune image trouvée dans storage/app/public/properties/images/</p>";
}
echo "</div>";

// Test 6: Vérifier la base de données
echo "<div class='box'><h2>6. Base de données</h2>";
try {
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    $kernel = $app->make('Illuminate\Contracts\Console\Kernel');
    $kernel->bootstrap();
    
    $mediaCount = \App\Models\PropertyMedia::count();
    echo "<p class='ok'>✅ Connexion réussie</p>";
    echo "<p><strong>Nombre d'images en BDD:</strong> " . $mediaCount . "</p>";
    
    if ($mediaCount > 0) {
        $media = \App\Models\PropertyMedia::first();
        echo "<p><strong>Exemple de chemin stocké:</strong> <code>" . $media->path . "</code></p>";
        echo "<p><strong>URL générée par le modèle:</strong> <code>" . $media->url . "</code></p>";
        
        echo "<h3>Test avec l'URL du modèle:</h3>";
        echo "<img src='" . $media->url . "' style='max-width:200px;border:2px solid green;' onerror=\"this.style.border='2px solid red';this.alt='❌ Échec';\">";
    }
} catch (\Exception $e) {
    echo "<p class='error'>❌ Erreur: " . htmlspecialchars($e->getMessage()) . "</p>";
}
echo "</div>";

// Résumé et solutions
echo "<div class='box' style='background:#fff3cd;border-left:4px solid #ffc107;'>";
echo "<h2>📋 Résumé et Solutions</h2>";
echo "<ol>";
echo "<li><strong>Si le lien symbolique n'existe pas:</strong><br>Exécutez <code>php artisan storage:link</code> sur votre serveur</li>";
echo "<li><strong>Si 'storage' est un dossier:</strong><br>Supprimez-le puis exécutez <code>php artisan storage:link</code></li>";
echo "<li><strong>Si les permissions sont incorrectes:</strong><br>Exécutez <code>chmod -R 775 storage/</code></li>";
echo "<li><strong>Si les images ne s'affichent toujours pas:</strong><br>Vérifiez le fichier <code>.htaccess</code> dans public/</li>";
echo "</ol>";
echo "</div>";

echo "<div class='box' style='background:#f8d7da;border-left:4px solid #dc3545;'>";
echo "<p><strong>⚠️ IMPORTANT:</strong> Supprimez ce fichier après le diagnostic!</p>";
echo "</div>";

echo "</body></html>";
?>
