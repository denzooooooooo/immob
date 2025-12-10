<?php
/**
 * Script de diagnostic pour les images
 * Visitez: https://votresite.com/test_images.php
 */

echo "<h1>🔍 Diagnostic des Images</h1>";
echo "<style>body{font-family:Arial;padding:20px;} .ok{color:green;} .error{color:red;} .warning{color:orange;}</style>";

// 1. Vérifier la configuration Laravel
echo "<h2>1. Configuration Laravel</h2>";
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$config = config('filesystems.disks.public');
echo "<p><strong>Disque public configuré:</strong> " . ($config ? "✅ Oui" : "❌ Non") . "</p>";
echo "<p><strong>Root:</strong> " . ($config['root'] ?? 'Non défini') . "</p>";
echo "<p><strong>URL:</strong> " . ($config['url'] ?? 'Non défini') . "</p>";

// 2. Vérifier le lien symbolique
echo "<h2>2. Lien Symbolique</h2>";
$storageLink = __DIR__ . '/storage';
if (is_link($storageLink)) {
    echo "<p class='ok'>✅ Le lien symbolique existe</p>";
    echo "<p><strong>Pointe vers:</strong> " . readlink($storageLink) . "</p>";
} else if (is_dir($storageLink)) {
    echo "<p class='warning'>⚠️ 'storage' est un dossier, pas un lien symbolique</p>";
} else {
    echo "<p class='error'>❌ Le lien symbolique n'existe pas</p>";
    echo "<p><strong>Solution:</strong> Exécutez <code>php artisan storage:link</code></p>";
}

// 3. Vérifier les permissions
echo "<h2>3. Permissions des Dossiers</h2>";
$storagePath = __DIR__ . '/../storage/app/public';
if (is_dir($storagePath)) {
    echo "<p class='ok'>✅ Le dossier storage/app/public existe</p>";
    $perms = substr(sprintf('%o', fileperms($storagePath)), -4);
    echo "<p><strong>Permissions:</strong> " . $perms . "</p>";
    if (is_writable($storagePath)) {
        echo "<p class='ok'>✅ Le dossier est accessible en écriture</p>";
    } else {
        echo "<p class='error'>❌ Le dossier n'est pas accessible en écriture</p>";
        echo "<p><strong>Solution:</strong> Exécutez <code>chmod -R 775 storage/</code></p>";
    }
} else {
    echo "<p class='error'>❌ Le dossier storage/app/public n'existe pas</p>";
}

// 4. Tester les images de la base de données
echo "<h2>4. Test des Images en Base de Données</h2>";
try {
    $media = \App\Models\PropertyMedia::first();
    if ($media) {
        echo "<p class='ok'>✅ Image trouvée en base de données</p>";
        echo "<p><strong>Chemin stocké:</strong> " . $media->path . "</p>";
        echo "<p><strong>URL générée:</strong> " . $media->url . "</p>";
        
        // Vérifier si le fichier existe physiquement
        $fullPath = storage_path('app/public/' . str_replace('storage/', '', $media->path));
        if (file_exists($fullPath)) {
            echo "<p class='ok'>✅ Le fichier existe physiquement</p>";
            echo "<p><strong>Chemin complet:</strong> " . $fullPath . "</p>";
        } else {
            echo "<p class='error'>❌ Le fichier n'existe pas physiquement</p>";
            echo "<p><strong>Chemin recherché:</strong> " . $fullPath . "</p>";
        }
        
        // Afficher l'image
        echo "<h3>Test d'affichage:</h3>";
        echo "<img src='" . $media->url . "' style='max-width:300px;border:2px solid #ccc;' onerror=\"this.style.border='2px solid red'; this.alt='❌ Image non chargée';\">";
        
    } else {
        echo "<p class='warning'>⚠️ Aucune image en base de données</p>";
    }
} catch (\Exception $e) {
    echo "<p class='error'>❌ Erreur: " . $e->getMessage() . "</p>";
}

// 5. Vérifier l'URL de l'application
echo "<h2>5. Configuration URL</h2>";
echo "<p><strong>APP_URL:</strong> " . config('app.url') . "</p>";
echo "<p><strong>URL actuelle:</strong> " . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "</p>";

// 6. Test direct d'une image
echo "<h2>6. Tests Directs</h2>";
echo "<p>Testez ces URLs directement dans votre navigateur:</p>";
echo "<ul>";
echo "<li><a href='/storage/properties/images/test.jpg' target='_blank'>/storage/properties/images/test.jpg</a></li>";
echo "<li><a href='/storage/app/public/properties/images/test.jpg' target='_blank'>/storage/app/public/properties/images/test.jpg</a></li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANT:</strong> Supprimez ce fichier après le diagnostic pour des raisons de sécurité!</p>";
?>
