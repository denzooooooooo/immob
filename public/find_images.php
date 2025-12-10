<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Recherche des images</title>";
echo "<style>body{font-family:Arial;padding:20px;background:#f5f5f5;} .box{background:white;padding:20px;margin:10px 0;border-radius:8px;} .ok{color:green;} .error{color:red;}</style>";
echo "</head><body>";
echo "<h1>🔍 Recherche des images manquantes</h1>";

// Chemins à vérifier
$basePaths = [
    '/home/u608034730/domains/immocarrepremium.com/public_html',
    '/home/u608034730/domains/immocarrepremium.com/laravel/public',
    '/home/u608034730/domains/immocarrepremium.com',
];

$imageName = 'gIXjg7NnXBHRT28Q02urTgyh4NTux3G41sYm8YFj.jpg';

echo "<div class='box'><h2>1. Recherche de l'image: $imageName</h2>";

foreach ($basePaths as $basePath) {
    $possiblePaths = [
        $basePath . '/storage/properties/images/' . $imageName,
        $basePath . '/public/storage/properties/images/' . $imageName,
        $basePath . '/laravel/storage/app/public/properties/images/' . $imageName,
    ];
    
    foreach ($possiblePaths as $path) {
        if (file_exists($path)) {
            echo "<p class='ok'>✅ TROUVÉ: $path</p>";
        }
    }
}
echo "</div>";

// Chercher récursivement
echo "<div class='box'><h2>2. Recherche récursive</h2>";
$command = "find /home/u608034730/domains/immocarrepremium.com -name '$imageName' 2>/dev/null";
$output = shell_exec($command);
if ($output) {
    echo "<p class='ok'>✅ Trouvé à:</p><pre>$output</pre>";
} else {
    echo "<p class='error'>❌ Image non trouvée sur le serveur</p>";
}
echo "</div>";

// Lister le contenu de storage
echo "<div class='box'><h2>3. Contenu de public/storage/</h2>";
$storageDir = __DIR__ . '/storage';
if (is_dir($storageDir)) {
    echo "<p class='ok'>✅ Le dossier existe</p>";
    $command2 = "ls -la " . escapeshellarg($storageDir);
    $output2 = shell_exec($command2);
    echo "<pre>$output2</pre>";
    
    if (is_dir($storageDir . '/properties/images')) {
        echo "<h3>Contenu de properties/images/:</h3>";
        $command3 = "ls -la " . escapeshellarg($storageDir . '/properties/images') . " | head -20";
        $output3 = shell_exec($command3);
        echo "<pre>$output3</pre>";
    }
} else {
    echo "<p class='error'>❌ Le dossier public/storage n'existe pas</p>";
}
echo "</div>";

echo "<div class='box'><h2>4. Structure des dossiers</h2>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p><strong>Script actuel:</strong> " . __FILE__ . "</p>";
echo "<p><strong>Dossier public:</strong> " . __DIR__ . "</p>";
echo "</div>";

echo "</body></html>";
?>
