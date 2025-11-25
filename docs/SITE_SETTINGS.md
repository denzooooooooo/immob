# Système de Paramètres du Site - Monnkama

## Vue d'ensemble

Le système de paramètres du site permet aux administrateurs de configurer facilement les éléments dynamiques du site web sans avoir besoin de modifier le code. Les paramètres sont stockés en base de données et mis en cache pour des performances optimales.

## Fonctionnalités

### 1. Types de Paramètres Supportés

- **Text** : Champs de texte simple (titres, descriptions, URLs, etc.)
- **Image** : Upload et gestion d'images avec prévisualisation
- **Boolean** : Interrupteurs on/off pour activer/désactiver des fonctionnalités

### 2. Groupes de Paramètres

Les paramètres sont organisés en groupes logiques :

- **General** : Paramètres généraux du site (nom, description)
- **Hero** : Section hero de la page d'accueil (titre, sous-titre, image)
- **Contact** : Informations de contact (email, téléphone, adresse)
- **Social** : Liens vers les réseaux sociaux
- **Features** : Activation/désactivation de fonctionnalités

## Utilisation

### Interface d'Administration

1. **Accès** : Admin → Paramètres → Paramètres du Site
2. **Navigation** : Interface organisée par groupes avec icônes distinctives
3. **Modification** : Formulaire intuitif avec prévisualisation pour les images
4. **Sauvegarde** : Bouton de sauvegarde global pour tous les paramètres

### Dans le Code

#### Accès aux Paramètres dans les Vues

```blade
<!-- Utilisation de la variable globale $siteSettings -->
<h1>{{ $siteSettings['site_name'] ?? 'Monnkama' }}</h1>

<!-- Utilisation de la fonction helper -->
<p>{{ site_setting('hero_title', 'Titre par défaut') }}</p>

<!-- Pour les images -->
@if($siteSettings['hero_image'])
    <img src="{{ asset('storage/' . $siteSettings['hero_image']) }}" alt="Hero">
@endif
```

#### Accès aux Paramètres dans les Contrôleurs

```php
use App\Models\SiteSetting;

// Récupérer un paramètre spécifique
$siteName = SiteSetting::getValue('site_name', 'Monnkama');

// Récupérer tous les paramètres
$settings = SiteSetting::getAllSettings();

// Récupérer les paramètres d'un groupe
$heroSettings = SiteSetting::getGroupSettings('hero');
```

## Structure de la Base de Données

### Table `site_settings`

```sql
- id (bigint, primary key)
- key (string, unique) - Clé unique du paramètre
- value (text, nullable) - Valeur du paramètre
- type (string) - Type de paramètre (text, image, boolean)
- group (string) - Groupe d'appartenance
- label (string) - Libellé affiché dans l'interface
- description (text, nullable) - Description du paramètre
- created_at (timestamp)
- updated_at (timestamp)
```

## Gestion des Images

### Upload et Stockage

- **Dossier** : `storage/app/public/site-settings/`
- **Validation** : Types MIME autorisés (jpeg, png, jpg, gif, webp)
- **Taille** : Maximum 2MB par défaut
- **Nommage** : UUID pour éviter les conflits

### Suppression

- Interface de suppression avec confirmation
- Suppression physique du fichier sur le serveur
- Mise à jour automatique de la base de données

## Cache et Performance

### Stratégie de Cache

- **Durée** : 1 heure (3600 secondes)
- **Clé** : `site_settings`
- **Invalidation** : Automatique lors de la mise à jour des paramètres

### Optimisations

- Chargement unique des paramètres par requête
- Partage global avec toutes les vues via View Composer
- Fonction helper statique pour éviter les requêtes multiples

## Sécurité

### Contrôle d'Accès

- **Middleware** : `auth` + `role:admin`
- **Routes** : Protégées dans le groupe admin
- **Validation** : Validation stricte des uploads d'images

### Protection CSRF

- Tokens CSRF sur tous les formulaires
- Validation côté serveur pour toutes les modifications

## Extensibilité

### Ajouter de Nouveaux Paramètres

1. **Via Seeder** :
```php
SiteSetting::create([
    'key' => 'nouveau_parametre',
    'value' => 'valeur_par_defaut',
    'type' => 'text',
    'group' => 'general',
    'label' => 'Nouveau Paramètre',
    'description' => 'Description du paramètre'
]);
```

2. **Via Interface Admin** : Modification directe du seeder et re-exécution

### Ajouter de Nouveaux Types

1. Modifier le contrôleur `SiteSettingController`
2. Ajouter la logique de traitement dans `update()`
3. Mettre à jour la vue `site.blade.php` avec le nouveau type

### Ajouter de Nouveaux Groupes

1. Ajouter les paramètres avec le nouveau groupe
2. Mettre à jour la vue pour l'affichage du groupe
3. Ajouter l'icône et le style correspondants

## Maintenance

### Commandes Utiles

```bash
# Vider le cache des paramètres
php artisan cache:forget site_settings

# Re-seeder les paramètres
php artisan db:seed --class=SiteSettingSeeder

# Optimiser le cache
php artisan config:cache
php artisan view:cache
```

### Sauvegarde

- Les paramètres sont sauvegardés avec la base de données
- Les images doivent être incluses dans la sauvegarde du dossier `storage/`

## Dépannage

### Problèmes Courants

1. **Paramètres non affichés** : Vérifier le cache, vider si nécessaire
2. **Images non uploadées** : Vérifier les permissions du dossier storage
3. **Erreurs de validation** : Vérifier les types MIME et tailles des fichiers

### Logs

Les erreurs sont loggées dans `storage/logs/laravel.log` avec le contexte approprié.

## Roadmap

### Améliorations Futures

- [ ] Support des paramètres de type textarea
- [ ] Support des paramètres de type select/dropdown
- [ ] Interface de gestion des groupes
- [ ] Historique des modifications
- [ ] Import/Export des paramètres
- [ ] API REST pour les paramètres
- [ ] Validation avancée par type de paramètre
- [ ] Support multilingue des paramètres
