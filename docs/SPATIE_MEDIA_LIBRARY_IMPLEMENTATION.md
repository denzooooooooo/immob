# 🎨 Implémentation de Spatie Media Library

## ✅ Installation Complétée

```bash
composer require "spatie/laravel-medialibrary:^11.0"
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider"
php artisan migrate
```

## 📋 Modifications Effectuées

### 1. **Modèle Property** (`app/Models/Property.php`)

Le modèle `Property` implémente maintenant `HasMedia` et utilise le trait `InteractsWithMedia` :

```php
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Property extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;
    
    // Collections de médias
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useFallbackUrl('/images/placeholder.jpg');

        $this->addMediaCollection('videos')
            ->useFallbackUrl('/images/video-placeholder.jpg');
    }
}
```

### 2. **Relations Renommées**

- ❌ Ancienne : `$property->media()` (conflit avec Spatie)
- ✅ Nouvelle : `$property->propertyMedia()` (pour l'ancienne table property_media)
- ✅ Spatie : `$property->media` (collection Spatie)

## 🚀 Utilisation

### **Ajouter des images :**

```php
// Via fichier uploadé
$property->addMedia($request->file('image'))
    ->toMediaCollection('images');

// Via chemin
$property->addMediaFromDisk('path/to/image.jpg', 'public')
    ->toMediaCollection('images');

// Avec métadonnées
$property->addMedia($request->file('image'))
    ->withCustomProperties(['featured' => true])
    ->toMediaCollection('images');
```

### **Récupérer des images :**

```php
// Toutes les images
$images = $property->getMedia('images');

// Première image
$firstImage = $property->getFirstMedia('images');

// URL de la première image
$url = $property->getFirstMediaUrl('images');

// Avec conversion (thumbnail, etc.)
$thumbUrl = $property->getFirstMediaUrl('images', 'thumb');
```

### **Dans les vues Blade :**

```blade
{{-- Première image --}}
<img src="{{ $property->getFirstMediaUrl('images') }}" alt="{{ $property->title }}">

{{-- Toutes les images --}}
@foreach($property->getMedia('images') as $media)
    <img src="{{ $media->getUrl() }}" alt="{{ $media->name }}">
@endforeach

{{-- Avec attribut personnalisé --}}
@php
    $images = $property->getImagesAttribute(); // Helper method
@endphp
```

## 🔄 Migration des Données Existantes

Pour migrer les images existantes de `property_media` vers Spatie Media Library :

```php
// Script de migration (à créer)
use App\Models\Property;
use App\Models\PropertyMedia;

Property::with('propertyMedia')->chunk(100, function ($properties) {
    foreach ($properties as $property) {
        foreach ($property->propertyMedia as $media) {
            if ($media->type === 'image' && file_exists(storage_path('app/public/' . $media->path))) {
                $property->addMediaFromDisk($media->path, 'public')
                    ->withCustomProperties([
                        'order' => $media->order,
                        'is_featured' => $media->is_featured,
                    ])
                    ->toMediaCollection('images');
            }
        }
    }
});
```

## 📝 Prochaines Étapes

1. ✅ Modèle Property mis à jour
2. ⏳ Mettre à jour les contrôleurs pour utiliser Spatie
3. ⏳ Mettre à jour les vues pour afficher les images via Spatie
4. ⏳ Créer un script de migration des données
5. ⏳ Tester en local
6. ⏳ Déployer en production

## 🎯 Avantages de Spatie Media Library

✅ **Gestion automatique des fichiers** - Plus besoin de gérer manuellement les chemins
✅ **Conversions d'images** - Thumbnails, redimensionnement automatique
✅ **Responsive images** - Génération automatique de plusieurs tailles
✅ **Métadonnées** - Stockage de propriétés personnalisées
✅ **Collections** - Organisation par type (images, videos, documents)
✅ **Optimisation** - Compression automatique des images
✅ **Compatibilité cloud** - S3, DigitalOcean Spaces, etc.
✅ **Pas de symlink requis** - Fonctionne directement !

## 🔗 Documentation Officielle

https://spatie.be/docs/laravel-medialibrary/v11/introduction
