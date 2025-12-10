<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Copie des images</title>";
echo "<style>body{font-family:Arial;padding:20px;background:#f5f5f5;} .box{background:white;padding:20px;margin:10px 0;border-radius:8px;} .ok{color:green;} .error{color:red;}</style>";
echo "</head><body>";
echo "<h1>📁 Copie des images vers le bon dossier</h1>";

$sourceDir = '/home/u608034730/domains/immocarrepremium.com/laravel/storage/app/public/properties/images';
$destDir = '/home/u608034730/domains/immocarrepremium.com/laravel/public/storage/properties/images';

echo "<div class='box'><h2>1. Vérification des dossiers</h2>";
echo "<p><strong>Source:</strong> $sourceDir</p>";
echo "<p><strong>Destination:</strong> $destDir</p>";

if (!is_dir($sourceDir)) {
    echo "<p class='error'>❌ Le dossier source n'existe pas!</p>";
    exit;
}
echo "<p class='ok'>✅ Dossier source existe</p>";

// Créer le dossier de destination s'il n'existe pas
if (!is_dir($destDir)) {
    if (mkdir($destDir, 0775, true)) {
        echo "<p class='ok'>✅ Dossier destination créé</p>";
    } else {
        echo "<p class='error'>❌ Impossible de créer le dossier destination</p>";
        exit;
    }
} else {
    echo "<p class='ok'>✅ Dossier destination existe</p>";
}
echo "</div>";

// Copier les fichiers
echo "<div class='box'><h2>2. Copie des fichiers</h2>";
$files = glob($sourceDir . '/*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE);
$copied = 0;
$errors = 0;

if (empty($files)) {
    echo "<p class='error'>❌ Aucun fichier trouvé dans le dossier source</p>";
} else {
    echo "<p>Nombre de fichiers trouvés: " . count($files) . "</p>";
    
    foreach ($files as $file) {
        $filename = basename($file);
        $dest = $destDir . '/' . $filename;
        
        if (copy($file, $dest)) {
            $copied++;
            if ($copied <= 10) { // Afficher seulement les 10 premiers
                echo "<p class='ok'>✅ Copié: $filename</p>";
            }
        } else {
            $errors++;
            echo "<p class='error'>❌ Erreur: $filename</p>";
        }
    }
    
    if ($copied > 10) {
        echo "<p class='ok'>... et " . ($copied - 10) . " autres fichiers</p>";
    }
}
echo "</div>";

// Résumé
echo "<div class='box' style='background:#d4edda;border-left:4px solid #28a745;'>";
echo "<h2>✅ Résumé</h2>";
echo "<p><strong>Fichiers copiés:</strong> $copied</p>";
echo "<p><strong>Erreurs:</strong> $errors</p>";
if ($copied > 0) {
    echo "<p class='ok'>🎉 Les images devraient maintenant s'afficher sur votre site!</p>";
    echo "<p><strong>Testez maintenant:</strong> Visitez une page avec des propriétés</p>";
}
echo "</div>";

echo "<div class='box' style='background:#f8d7da;border-left:4px solid #dc3545;'>";
echo "<p><strong>⚠️ IMPORTANT:</strong> Supprimez ce fichier après utilisation!</p>";
echo "</div>";

echo "</body></html>";
?>
?>
