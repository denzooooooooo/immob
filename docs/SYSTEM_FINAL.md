# Monnkama - Système Immobilier Complet

## Vue d'ensemble

Monnkama est une plateforme immobilière complète développée avec Laravel, conçue spécifiquement pour le marché gabonais. Le système comprend trois interfaces principales : publique, agent et administration.

## Architecture du Système

### Structure des Rôles
- **Utilisateurs publics** : Consultation et recherche de propriétés
- **Agents immobiliers** : Gestion de leurs propriétés avec système d'abonnement
- **Administrateurs** : Gestion complète de la plateforme

### Base de Données
Le système utilise une base de données relationnelle avec les tables principales :
- `users` : Utilisateurs avec rôles (admin, agent, user)
- `properties` : Propriétés immobilières
- `property_details` : Détails techniques des propriétés
- `property_media` : Images et médias
- `cities` et `neighborhoods` : Localisation
- `subscriptions` : Abonnements des agents
- `messages` : Communication entre utilisateurs et agents
- `favorites` et `property_views` : Interactions utilisateurs

## Fonctionnalités Principales

### Interface Publique
- **Page d'accueil** avec propriétés en vedette
- **Recherche avancée** par ville, type, prix
- **Détails des propriétés** avec galerie d'images
- **Système de favoris** pour les utilisateurs connectés
- **Messagerie** pour contacter les agents
- **Pages statiques** (À propos, Contact)

### Interface Agent
- **Dashboard** avec statistiques personnalisées
- **Gestion des propriétés** (CRUD complet)
- **Système d'abonnement** avec limites par plan
- **Messagerie** pour gérer les demandes
- **Statistiques** de vues et performances

### Interface Administration
- **Dashboard global** avec métriques complètes
- **Gestion des utilisateurs** avec actions groupées
- **Modération des propriétés**
- **Gestion des abonnements** et revenus
- **Supervision des messages**
- **Gestion des villes** et quartiers

## Système d'Abonnement

### Plans Disponibles
1. **Basique** (5,000 XAF/mois) : 3 propriétés
2. **Standard** (15,000 XAF/mois) : 10 propriétés
3. **Premium** (30,000 XAF/mois) : 25 propriétés
4. **Entreprise** (50,000 XAF/mois) : 100 propriétés

### Fonctionnalités par Plan
- Limitation du nombre de propriétés
- Suivi de l'utilisation en temps réel
- Renouvellement automatique
- Historique des abonnements

## Technologies Utilisées

### Backend
- **Laravel 11** : Framework PHP
- **MySQL** : Base de données
- **Intervention Image** : Traitement d'images
- **Laravel Sanctum** : Authentification API

### Frontend
- **Blade Templates** : Moteur de templates
- **Tailwind CSS** : Framework CSS
- **Alpine.js** : Interactivité JavaScript
- **Chart.js** : Graphiques et statistiques
- **Font Awesome** : Icônes

## Structure des Fichiers

### Contrôleurs
```
app/Http/Controllers/
├── HomeController.php
├── PropertyController.php
├── CityController.php
├── PageController.php
├── Admin/
│   ├── DashboardController.php
│   ├── PropertyController.php
│   ├── UserController.php
│   ├── SubscriptionController.php
│   ├── MessageController.php
│   └── LocationController.php
└── Agent/
    ├── DashboardController.php
    ├── PropertyController.php
    ├── MessageController.php
    └── SubscriptionController.php
```

### Modèles
```
app/Models/
├── User.php
├── Property.php
├── PropertyDetail.php
├── PropertyMedia.php
├── City.php
├── Neighborhood.php
├── Subscription.php
├── Message.php
├── Favorite.php
└── PropertyView.php
```

### Vues
```
resources/views/
├── layouts/
│   ├── app.blade.php
│   ├── admin.blade.php
│   └── agent.blade.php
├── home.blade.php
├── properties/
├── cities/
├── pages/
├── admin/
│   ├── dashboard.blade.php
│   ├── properties/
│   ├── users/
│   ├── subscriptions/
│   └── messages/
└── agent/
    ├── dashboard.blade.php
    ├── properties/
    └── subscription/
```

## Configuration et Déploiement

### Prérequis
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js (pour les assets)

### Installation
```bash
# Cloner le projet
git clone [repository-url]
cd monnkama

# Installer les dépendances
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Base de données
php artisan migrate
php artisan db:seed

# Assets
npm run build
```

### Variables d'Environnement
```env
APP_NAME=Monnkama
APP_ENV=production
APP_URL=https://monnkama.ga

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=monnkama
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-password
```

## Sécurité

### Mesures Implémentées
- **Authentification** avec Laravel Sanctum
- **Autorisation** basée sur les rôles
- **Validation** des données d'entrée
- **Protection CSRF** sur tous les formulaires
- **Middleware** de vérification des rôles
- **Hashage** des mots de passe avec bcrypt

### Middleware Personnalisés
- `CheckRole` : Vérification des rôles utilisateur
- `CheckSubscription` : Validation des abonnements agents

## Performance

### Optimisations
- **Eager Loading** pour éviter le problème N+1
- **Pagination** sur toutes les listes
- **Cache** des requêtes fréquentes
- **Indexation** des colonnes de recherche
- **Compression** des images uploadées

## Maintenance

### Tâches Régulières
- Nettoyage des sessions expirées
- Sauvegarde de la base de données
- Mise à jour des dépendances
- Monitoring des performances
- Vérification des abonnements expirés

### Logs et Monitoring
- Logs Laravel dans `storage/logs/`
- Monitoring des erreurs
- Statistiques d'utilisation
- Alertes pour les abonnements expirés

## Support et Contact

### Documentation
- Architecture détaillée dans `docs/ARCHITECTURE.md`
- Guide d'accès dans `docs/GUIDE_ACCES.md`
- Configuration admin dans `docs/ADMIN_SETUP_COMPLETE.md`

### Développement Futur
- API mobile
- Système de paiement en ligne
- Notifications push
- Chat en temps réel
- Application mobile

## Conclusion

Monnkama est un système immobilier complet et moderne, adapté aux besoins du marché gabonais. Il offre une expérience utilisateur optimale avec des fonctionnalités avancées pour tous les types d'utilisateurs.

Le système est prêt pour la production et peut être facilement étendu avec de nouvelles fonctionnalités selon les besoins du marché.
