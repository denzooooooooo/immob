# 🚀 Guide de Déploiement - Spatie Media Library

## 📋 Checklist Complète

### ✅ Étape 1: Installation Locale (DÉJÀ FAIT)

- [x] Installation de Spatie Media Library via Composer
- [x] Publication des migrations et configurations
- [x] Mise à jour du modèle Property
- [x] Mise à jour des contrôleurs Admin et Agent
- [x] Création de la commande de migration

### ⏳ Étape 2: Tests en Local

```bash
# 1. Tester la création d'une propriété avec images
# Aller sur: http://localhost/admin/properties/create
# Uploader des images et vérifier qu'elles s'affichent

# 2. Tester la commande de migration (mode test)
php artisan media:migrate-to-spatie --dry-run

# 3. Si tout est OK, exécuter la migration réelle
php artisan media:migrate-to-spatie

# 4. Vérifier que les images s'affichent correctement
```

### ⏳ Étape 3: Préparation pour la Production

#### Fichiers à uploader sur le serveur:

```
1. composer.json (mis à jour avec Spatie)
2. composer.lock (mis à jour)
3. app/Models/Property.php
4. app/Http/Controllers/Admin/PropertyController.php
5. app/Http/Controllers/Agent/PropertyController.php
6. app/Console/Commands/MigrateToSpatieMedia.php
7. config/media-library.php (nouveau)
8. database/migrations/2025_12_10_222611_create_media_table.php (nouveau)
```

### ⏳ Étape 4: Déploiement sur le Serveur

```bash
# 1. Se connecter en SSH
ssh u608034730@immocarrepremium.com

# 2. Aller dans le dossier Laravel
cd /home/u608034730/domains/immocarrepremium.com/laravel

# 3. Installer les dépendances Composer
composer install --no-dev --optimize-autoloader

# 4. Exécuter les migrations
php artisan migrate --force

# 5. Nettoyer les caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 6. Optimiser pour la production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### ⏳ Étape 5: Migration des Données Existantes

```bash
# 1. Test de migration (sans modifications)
php artisan media:migrate-to-spatie --dry-run

# 2. Vérifier les résultats du test

# 3. Si tout est OK, migration réelle
php artisan media:migrate-to-spatie

# 4. Vérifier que les images s'affichent sur le site
```

### ⏳ Étape 6: Vérifications Post-Déploiement

- [ ] Les nouvelles propriétés peuvent être créées avec images
- [ ] Les images existantes s'affichent correctement
- [ ] Les propriétés peuvent être modifiées
- [ ] Les images peuvent être supprimées
- [ ] Les images peuvent être ajoutées à une propriété existante
- [ ] La duplication de propriété fonctionne avec les images

## 🔧 Configuration de Spatie Media Library

### Disque de Stockage

Par défaut, Spatie utilise le disque `public`. Configuration dans `config/filesystems.php`:

```php
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
],
```

### Collections de Médias

Dans le modèle `Property`:

```php
public function registerMediaCollections(): void
{
    $this->addMediaCollection('images')
        ->useFallbackUrl('/images/placeholder.jpg');

    $this->addMediaCollection('videos')
        ->useFallbackUrl('/images/video-placeholder.jpg');
}
```

## 📊 Utilisation dans les Vues

### Afficher la première image:

```blade
{{-- Méthode 1: URL directe --}}
<img src="{{ $property->getFirstMediaUrl('images') }}" alt="{{ $property->title }}">

{{-- Méthode 2: Avec fallback --}}
<img src="{{ $property->getFirstMediaUrl('images') ?: '/images/placeholder.jpg' }}" alt="{{ $property->title }}">

{{-- Méthode 3: Via l'attribut helper --}}
<img src="{{ $property->first_image_url }}" alt="{{ $property->title }}">
```

### Afficher toutes les images:

```blade
@foreach($property->getMedia('images') as $media)
    <img src="{{ $media->getUrl() }}" alt="{{ $media->name }}">
@endforeach

{{-- Ou via l'attribut helper --}}
@foreach($property->images as $image)
    <img src="{{ $image->getUrl() }}" alt="{{ $image->name }}">
@endforeach
```

### Vérifier si une propriété a des images:

```blade
@if($property->hasMedia('images'))
    {{-- Afficher les images --}}
@else
    {{-- Afficher placeholder --}}
    <img src="/images/placeholder.jpg" alt="Pas d'image">
@endif
```

## 🎯 Avantages de Spatie Media Library

### ✅ Gestion Automatique

- **Stockage**: Gestion automatique des fichiers
- **Suppression**: Suppression automatique des fichiers lors de la suppression du média
- **Optimisation**: Possibilité d'optimiser automatiquement les images
- **Conversions**: Génération automatique de thumbnails et autres tailles

### ✅ Pas de Symlink Requis

- Fonctionne directement sans `php artisan storage:link`
- Compatible avec les hébergeurs qui désactivent `symlink()`
- Les fichiers sont servis via Laravel

### ✅ Métadonnées Riches

```php
// Ajouter des propriétés personnalisées
$property->addMedia($file)
    ->withCustomProperties([
        'order' => 1,
        'is_featured' => true,
        'photographer' => 'John Doe'
    ])
    ->toMediaCollection('images');

// Récupérer les propriétés
$media->getCustomProperty('is_featured'); // true
```

### ✅ Conversions d'Images

```php
// Dans le modèle Property
public function registerMediaConversions(Media $media = null): void
{
    $this->addMediaConversion('thumb')
        ->width(300)
        ->height(300)
        ->sharpen(10);

    $this->addMediaConversion('large')
        ->width(1200)
        ->height(800)
        ->optimize();
}

// Dans les vues
<img src="{{ $property->getFirstMediaUrl('images', 'thumb') }}">
```

## 🔄 Rollback (Si Nécessaire)

Si vous devez revenir à l'ancien système:

```bash
# 1. Restaurer les anciens contrôleurs depuis Git
git checkout HEAD -- app/Http/Controllers/Admin/PropertyController.php
git checkout HEAD -- app/Http/Controllers/Agent/PropertyController.php

# 2. Restaurer l'ancien modèle Property
git checkout HEAD -- app/Models/Property.php

# 3. Supprimer la table media de Spatie
php artisan migrate:rollback --step=1

# 4. Désinstaller Spatie
composer remove spatie/laravel-medialibrary

# 5. Nettoyer les caches
php artisan config:clear
php artisan cache:clear
```

## 📞 Support

En cas de problème:

1. Vérifier les logs Laravel: `storage/logs/laravel.log`
2. Vérifier les permissions: `chmod -R 775 storage`
3. Consulter la documentation: https://spatie.be/docs/laravel-medialibrary
4. Vérifier que la table `media` existe dans la base de données

## 🎉 Résultat Final

Après le déploiement complet:

- ✅ Upload d'images simplifié
- ✅ Gestion automatique des fichiers
- ✅ Pas de problème de symlink
- ✅ Métadonnées riches
- ✅ Possibilité de conversions d'images
- ✅ Code plus propre et maintenable
- ✅ Compatible avec tous les hébergeurs
