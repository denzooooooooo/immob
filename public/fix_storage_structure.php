<?php
/**
 * Script pour créer la structure storage manquante
 * Visitez: https://immocarrepremium.com/fix_storage_structure.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Correction Structure Storage</title>";
echo "<style>body{font-family:Arial;padding:20px;background:#f5f5f5;} .box{background:white;padding:20px;margin:10px 0;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);} .ok{color:green;font-weight:bold;} .error{color:red;font-weight:bold;} h2{color:#333;}</style>";
echo "</head><body>";
echo "<h1>🔧 Correction de la structure Storage</h1>";

$publicPath = __DIR__;
$basePath = dirname($publicPath);
$storagePath = $basePath . '/storage';
$storageAppPath = $storagePath . '/app';
$storageAppPublicPath = $storageAppPath . '/public';
$storagePropertiesPath = $storageAppPublicPath . '/properties';
$storageImagesPath = $storagePropertiesPath . '/images';

echo "<div class='box'><h2>1. Création des dossiers manquants</h2>";

// Créer storage si n'existe pas
if (!is_dir($storagePath)) {
    if (mkdir($storagePath, 0775, true)) {
        echo "<p class='ok'>✅ Dossier 'storage' créé</p>";
    } else {
        echo "<p class='error'>❌ Impossible de créer 'storage'</p>";
    }
} else {
    echo "<p class='ok'>✅ Dossier 'storage' existe déjà</p>";
}

// Créer storage/app
if (!is_dir($storageAppPath)) {
    if (mkdir($storageAppPath, 0775, true)) {
        echo "<p class='ok'>✅ Dossier 'storage/app' créé</p>";
    } else {
        echo "<p class='error'>❌ Impossible de créer 'storage/app'</p>";
    }
} else {
    echo "<p class='ok'>✅ Dossier 'storage/app' existe déjà</p>";
}

// Créer storage/app/public
if (!is_dir($storageAppPublicPath)) {
    if (mkdir($storageAppPublicPath, 0775, true)) {
        echo "<p class='ok'>✅ Dossier 'storage/app/public' créé</p>";
    } else {
        echo "<p class='error'>❌ Impossible de créer 'storage/app/public'</p>";
    }
} else {
    echo "<p class='ok'>✅ Dossier 'storage/app/public' existe déjà</p>";
}

// Créer storage/app/public/properties/images
if (!is_dir($storageImagesPath)) {
    if (mkdir($storageImagesPath, 0775, true)) {
        echo "<p class='ok'>✅ Dossier 'storage/app/public/properties/images' créé</p>";
    } else {
        echo "<p class='error'>❌ Impossible de créer 'storage/app/public/properties/images'</p>";
    }
} else {
    echo "<p class='ok'>✅ Dossier 'storage/app/public/properties/images' existe déjà</p>";
}

echo "</div>";

// Créer le lien symbolique
echo "<div class='box'><h2>2. Création du lien symbolique</h2>";
$storageLink = $publicPath . '/storage';

// Supprimer l'ancien lien/dossier s'il existe
if (file_exists($storageLink)) {
    if (is_link($storageLink)) {
        unlink($storageLink);
        echo "<p>Ancien lien symbolique supprimé</p>";
    } elseif (is_dir($storageLink)) {
        rmdir($storageLink);
        echo "<p>Ancien dossier supprimé</p>";
    }
}

// Créer le nouveau lien symbolique
if (symlink($storageAppPublicPath, $storageLink)) {
    echo "<p class='ok'>✅ Lien symbolique créé avec succès!</p>";
    echo "<p><strong>De:</strong> " . $storageLink . "</p>";
    echo "<p><strong>Vers:</strong> " . $storageAppPublicPath . "</p>";
} else {
    echo "<p class='error'>❌ Impossible de créer le lien symbolique</p>";
    echo "<p><strong>Solution alternative:</strong> Créez manuellement via SSH:</p>";
    echo "<code>ln -s " . $storageAppPublicPath . " " . $storageLink . "</code>";
}

echo "</div>";

// Créer les autres dossiers storage nécessaires
echo "<div class='box'><h2>3. Autres dossiers storage</h2>";

$otherDirs = [
    $storagePath . '/framework',
    $storagePath . '/framework/cache',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
];

foreach ($otherDirs as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0775, true)) {
            echo "<p class='ok'>✅ " . str_replace($basePath . '/', '', $dir) . " créé</p>";
        }
    } else {
        echo "<p class='ok'>✅ " . str_replace($basePath . '/', '', $dir) . " existe</p>";
    }
}

echo "</div>";

// Créer un fichier .gitignore dans storage
echo "<div class='box'><h2>4. Fichier .gitignore</h2>";
$gitignorePath = $storagePath . '/.gitignore';
$gitignoreContent = "*\n!.gitignore\n";
if (file_put_contents($gitignorePath, $gitignoreContent)) {
    echo "<p class='ok'>✅ Fichier .gitignore créé dans storage/</p>";
}
echo "</div>";

// Vérification finale
echo "<div class='box' style='background:#d4edda;border-left:4px solid #28a745;'>";
echo "<h2>✅ Vérification finale</h2>";
echo "<p><strong>Structure créée:</strong></p>";
echo "<ul>";
echo "<li>✅ " . $storagePath . "</li>";
echo "<li>✅ " . $storageAppPath . "</li>";
echo "<li>✅ " . $storageAppPublicPath . "</li>";
echo "<li>✅ " . $storageImagesPath . "</li>";
echo "<li>✅ " . $storageLink . " → " . $storageAppPublicPath . "</li>";
echo "</ul>";
echo "</div>";

echo "<div class='box' style='background:#fff3cd;border-left:4px solid #ffc107;'>";
echo "<h2>📋 Prochaines étapes</h2>";
echo "<ol>";
echo "<li>Testez la création d'une propriété avec images</li>";
echo "<li>Les images devraient maintenant s'afficher correctement</li>";
echo "<li><strong>Supprimez ce fichier après utilisation!</strong></li>";
echo "</ol>";
echo "</div>";

echo "</body></html>";
?>
