<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class ImageService
{
    /**
     * Tailles des images pour différents usages
     */
    const IMAGE_SIZES = [
        'thumbnail' => [150, 150],
        'small' => [300, 300],
        'medium' => [600, 600],
        'large' => [1200, 1200],
    ];

    /**
     * Types d'images autorisés
     */
    const ALLOWED_MIMES = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    /**
     * Taille maximale en octets (5MB)
     */
    const MAX_FILE_SIZE = 5242880;

    /**
     * Stocker une image avec ses différentes versions
     */
    public function store(UploadedFile $file, string $path, array $options = []): array
    {
        // Valider le fichier
        $this->validateImage($file);

        // Générer un nom unique
        $filename = $this->generateFilename($file);
        $fullPath = trim($path, '/') . '/' . $filename;

        // Créer les différentes versions
        $versions = [];
        foreach (self::IMAGE_SIZES as $size => [$width, $height]) {
            if (!isset($options['sizes']) || in_array($size, $options['sizes'])) {
                $resizedImage = $this->resize($file, $width, $height, $options);
                $sizePath = $this->getVersionPath($fullPath, $size);
                
                $imageData = $this->encodeImage($resizedImage, $file->getMimeType());
                Storage::put("public/{$sizePath}", $imageData);
                $versions[$size] = $sizePath;
            }
        }

        // Stocker l'original si demandé
        if (!isset($options['skip_original']) || !$options['skip_original']) {
            Storage::putFileAs("public/{$path}", $file, $filename);
            $versions['original'] = $fullPath;
        }

        return $versions;
    }

    /**
     * Supprimer une image et ses versions
     */
    public function delete(string $path): bool
    {
        $success = true;
        $directory = dirname($path);
        $filename = basename($path);

        // Supprimer l'original
        if (!Storage::delete("public/{$path}")) {
            $success = false;
        }

        // Supprimer les versions
        foreach (self::IMAGE_SIZES as $size => $dimensions) {
            $versionPath = $this->getVersionPath($path, $size);
            if (!Storage::delete("public/{$versionPath}")) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Redimensionner une image
     */
    protected function resize(UploadedFile $file, int $width, int $height, array $options = [])
    {
        $sourceImage = $this->createImageFromFile($file);
        if (!$sourceImage) {
            throw new \InvalidArgumentException('Impossible de créer l\'image à partir du fichier');
        }

        $originalWidth = imagesx($sourceImage);
        $originalHeight = imagesy($sourceImage);

        // Calculer les nouvelles dimensions en conservant le ratio
        if (isset($options['fit']) && $options['fit']) {
            $newWidth = $width;
            $newHeight = $height;
        } else {
            $ratio = min($width / $originalWidth, $height / $originalHeight);
            $newWidth = (int)($originalWidth * $ratio);
            $newHeight = (int)($originalHeight * $ratio);
        }

        // Créer la nouvelle image
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Préserver la transparence pour PNG
        if ($file->getMimeType() === 'image/png') {
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
            imagefill($resizedImage, 0, 0, $transparent);
        }

        // Redimensionner
        imagecopyresampled(
            $resizedImage, $sourceImage,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $originalWidth, $originalHeight
        );

        // Nettoyer
        imagedestroy($sourceImage);

        return $resizedImage;
    }

    /**
     * Créer une ressource image à partir d'un fichier
     */
    protected function createImageFromFile(UploadedFile $file)
    {
        $mimeType = $file->getMimeType();
        
        switch ($mimeType) {
            case 'image/jpeg':
                return imagecreatefromjpeg($file->getPathname());
            case 'image/png':
                return imagecreatefrompng($file->getPathname());
            case 'image/webp':
                return imagecreatefromwebp($file->getPathname());
            default:
                return false;
        }
    }

    /**
     * Encoder une image en string
     */
    protected function encodeImage($image, string $mimeType): string
    {
        ob_start();
        
        switch ($mimeType) {
            case 'image/jpeg':
                imagejpeg($image, null, 85); // Qualité 85%
                break;
            case 'image/png':
                imagepng($image);
                break;
            case 'image/webp':
                imagewebp($image, null, 85);
                break;
        }
        
        $imageData = ob_get_contents();
        ob_end_clean();
        imagedestroy($image);
        
        return $imageData;
    }

    /**
     * Ajouter un filigrane à l'image
     */
    protected function addWatermark($image)
    {
        $watermarkPath = public_path('images/watermark.png');
        
        if (!file_exists($watermarkPath)) {
            return $image; // Pas de filigrane disponible
        }

        $watermark = imagecreatefrompng($watermarkPath);
        if (!$watermark) {
            return $image;
        }

        $imageWidth = imagesx($image);
        $imageHeight = imagesy($image);
        $watermarkWidth = imagesx($watermark);
        $watermarkHeight = imagesy($watermark);

        // Redimensionner le filigrane à 30% de la largeur de l'image
        $newWatermarkWidth = (int)($imageWidth * 0.3);
        $newWatermarkHeight = (int)($watermarkHeight * ($newWatermarkWidth / $watermarkWidth));

        $resizedWatermark = imagecreatetruecolor($newWatermarkWidth, $newWatermarkHeight);
        imagealphablending($resizedWatermark, false);
        imagesavealpha($resizedWatermark, true);

        imagecopyresampled(
            $resizedWatermark, $watermark,
            0, 0, 0, 0,
            $newWatermarkWidth, $newWatermarkHeight,
            $watermarkWidth, $watermarkHeight
        );

        // Positionner en bas à droite avec une marge de 10px
        $x = $imageWidth - $newWatermarkWidth - 10;
        $y = $imageHeight - $newWatermarkHeight - 10;

        imagecopy($image, $resizedWatermark, $x, $y, 0, 0, $newWatermarkWidth, $newWatermarkHeight);

        imagedestroy($watermark);
        imagedestroy($resizedWatermark);

        return $image;
    }

    /**
     * Valider une image
     */
    protected function validateImage(UploadedFile $file): void
    {
        if (!in_array($file->getMimeType(), self::ALLOWED_MIMES)) {
            throw new \InvalidArgumentException(
                'Type de fichier non autorisé. Types acceptés : ' . implode(', ', self::ALLOWED_MIMES)
            );
        }

        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new \InvalidArgumentException(
                'Fichier trop volumineux. Taille maximale : ' . self::MAX_FILE_SIZE / 1024 / 1024 . 'MB'
            );
        }
    }

    /**
     * Générer un nom de fichier unique
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        return $name . '_' . uniqid() . '.' . $extension;
    }

    /**
     * Obtenir le chemin d'une version d'image
     */
    protected function getVersionPath(string $path, string $version): string
    {
        $directory = dirname($path);
        $filename = basename($path);
        return "{$directory}/{$version}_{$filename}";
    }

    /**
     * Obtenir l'URL d'une image
     */
    public function url(string $path, string $version = 'original'): string
    {
        if ($version === 'original') {
            return Storage::url($path);
        }

        return Storage::url($this->getVersionPath($path, $version));
    }

    /**
     * Vérifier si une image existe
     */
    public function exists(string $path, string $version = 'original'): bool
    {
        if ($version === 'original') {
            return Storage::exists("public/{$path}");
        }

        return Storage::exists("public/" . $this->getVersionPath($path, $version));
    }

    /**
     * Obtenir les dimensions d'une image
     */
    public function getDimensions(string $path): array
    {
        $fullPath = Storage::path("public/{$path}");
        
        if (!file_exists($fullPath)) {
            return ['width' => 0, 'height' => 0];
        }

        $imageInfo = getimagesize($fullPath);
        
        if ($imageInfo === false) {
            return ['width' => 0, 'height' => 0];
        }

        return [
            'width' => $imageInfo[0],
            'height' => $imageInfo[1],
        ];
    }

    /**
     * Obtenir les métadonnées d'une image
     */
    public function getMetadata(string $path): array
    {
        $fullPath = Storage::path("public/{$path}");
        
        return [
            'size' => Storage::size("public/{$path}"),
            'mime_type' => Storage::mimeType("public/{$path}"),
            'dimensions' => $this->getDimensions($path),
            'created_at' => Storage::lastModified("public/{$path}"),
        ];
    }

    /**
     * Déplacer une image
     */
    public function move(string $oldPath, string $newPath): bool
    {
        $success = true;

        // Déplacer l'original
        if (!Storage::move("public/{$oldPath}", "public/{$newPath}")) {
            $success = false;
        }

        // Déplacer les versions
        foreach (self::IMAGE_SIZES as $size => $dimensions) {
            $oldVersionPath = $this->getVersionPath($oldPath, $size);
            $newVersionPath = $this->getVersionPath($newPath, $size);
            
            if (Storage::exists("public/{$oldVersionPath}")) {
                if (!Storage::move("public/{$oldVersionPath}", "public/{$newVersionPath}")) {
                    $success = false;
                }
            }
        }

        return $success;
    }

    /**
     * Copier une image
     */
    public function copy(string $sourcePath, string $destinationPath): bool
    {
        $success = true;

        // Copier l'original
        if (!Storage::copy("public/{$sourcePath}", "public/{$destinationPath}")) {
            $success = false;
        }

        // Copier les versions
        foreach (self::IMAGE_SIZES as $size => $dimensions) {
            $sourceVersionPath = $this->getVersionPath($sourcePath, $size);
            $destinationVersionPath = $this->getVersionPath($destinationPath, $size);
            
            if (Storage::exists("public/{$sourceVersionPath}")) {
                if (!Storage::copy("public/{$sourceVersionPath}", "public/{$destinationVersionPath}")) {
                    $success = false;
                }
            }
        }

        return $success;
    }

    /**
     * Nettoyer les images orphelines
     */
    public function cleanupOrphans(): array
    {
        $cleaned = [];
        $files = Storage::allFiles('public/properties/images');

        foreach ($files as $file) {
            // Vérifier si l'image est référencée dans la base de données
            $path = str_replace('public/', '', $file);
            $isReferenced = \App\Models\PropertyMedia::where('path', $path)->exists();

            if (!$isReferenced) {
                Storage::delete($file);
                $cleaned[] = $path;
            }
        }

        return $cleaned;
    }
}
