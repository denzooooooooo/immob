# Système Monnkama - Plateforme Immobilière Complète

## Vue d'ensemble

Monnkama est une plateforme immobilière complète développée avec Laravel 11, conçue spécifiquement pour le marché gabonais. Le système offre une solution complète pour la gestion des propriétés immobilières avec trois interfaces distinctes : publique, agent et administrateur.

## Architecture du Système

### Structure des Rôles
- **Utilisateurs (users)** : Peuvent rechercher et consulter les propriétés
- **Agents immobiliers (agents)** : Peuvent publier et gérer leurs propriétés via abonnement
- **Administrateurs (admin)** : Gestion complète de la plateforme

### Base de Données
Le système utilise une base de données relationnelle avec les tables principales :
- `users` : Gestion des utilisateurs avec rôles
- `properties` : Propriétés immobilières avec soft delete
- `property_details` : Détails techniques des propriétés
- `property_media` : Images et médias des propriétés
- `subscriptions` : Abonnements des agents
- `messages` : Système de messagerie interne
- `cities` et `neighborhoods` : Localisation géographique
- `favorites` et `property_views` : Interactions utilisateurs

## Fonctionnalités Principales

### Interface Publique
- **Page d'accueil** avec recherche avancée
- **Catalogue de propriétés** avec filtres multiples
- **Détails des propriétés** avec galerie d'images
- **Système de favoris** pour les utilisateurs connectés
- **Messagerie** pour contacter les agents
- **Pages statiques** (À propos, Contact)

### Interface Agent
- **Dashboard** avec statistiques personnalisées
- **Gestion des propriétés** (CRUD complet)
- **Système d'abonnements** avec limites par plan
- **Messagerie** pour communiquer avec les clients
- **Gestion du profil** professionnel

### Interface Administrateur
- **Dashboard** avec vue d'ensemble complète
- **Gestion des utilisateurs** et modération
- **Gestion des propriétés** avec validation
- **Gestion des abonnements** et facturation
- **Système de messagerie** et support
- **Gestion des localisations** (villes, quartiers)

## Système d'Authentification

### Pages d'Authentification
- **Connexion** (`/login`) avec options sociales
- **Inscription** (`/register`) avec sélection de rôle
- **Récupération de mot de passe** (`/password/reset`)
- **Réinitialisation** avec validation sécurisée

### Sécurité
- **Middleware de rôles** (`CheckRole`)
- **Policies d'autorisation** (Property, Message, Subscription)
- **Validation des abonnements** (`CheckSubscription`)
- **Protection CSRF** sur tous les formulaires

## Système d'Abonnements

### Plans Disponibles
- **Basic** : 3 propriétés, 30 jours - 25,000 FCFA
- **Standard** : 10 propriétés, 30 jours - 50,000 FCFA
- **Premium** : 25 propriétés + mise en avant, 30 jours - 100,000 FCFA
- **Enterprise** : 100 propriétés + priorité, 30 jours - 200,000 FCFA

### Fonctionnalités
- **Gestion automatique** des limites de publication
- **Renouvellement** et mise à niveau
- **Historique des paiements**
- **Notifications d'expiration**

## Technologies Utilisées

### Backend
- **Laravel 11** - Framework PHP
- **MySQL** - Base de données
- **Eloquent ORM** - Gestion des données
- **Laravel Sanctum** - Authentification API
- **Laravel Mail** - Système d'emails

### Frontend
- **Blade Templates** - Moteur de templates
- **Tailwind CSS** - Framework CSS
- **Alpine.js** - Interactivité JavaScript
- **Font Awesome** - Icônes

### Packages Additionnels
- **Intervention Image** - Traitement d'images
- **Laravel Socialite** - Authentification sociale
- **Spatie Permission** - Gestion des permissions

## Structure des Fichiers

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/AuthController.php
│   │   ├── Admin/
│   │   ├── Agent/
│   │   └── Public Controllers
│   ├── Middleware/
│   │   ├── CheckRole.php
│   │   └── CheckSubscription.php
│   └── Policies/
├── Models/
├── Mail/
└── Providers/

resources/
├── views/
│   ├── auth/
│   ├── admin/
│   ├── agent/
│   ├── properties/
│   ├── layouts/
│   └── emails/
└── css/

database/
├── migrations/
└── seeders/

routes/
├── web.php
├── admin.php
└── agent.php
```

## Configuration et Déploiement

### Variables d'Environnement
```env
APP_NAME=Monnkama
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
MAIL_USERNAME=
MAIL_PASSWORD=

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
FACEBOOK_CLIENT_ID=
FACEBOOK_CLIENT_SECRET=
```

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

# Serveur de développement
php artisan serve
```

## Comptes de Test

### Administrateur
- **Email** : admin@monnkama.ga
- **Mot de passe** : admin123
- **Accès** : `/admin`

### Agent
- **Email** : agent@monnkama.ga
- **Mot de passe** : agent123
- **Accès** : `/agent`

### Utilisateur
- **Email** : user@monnkama.ga
- **Mot de passe** : user123
- **Accès** : Interface publique

## API et Intégrations

### Endpoints Principaux
- `GET /api/properties` - Liste des propriétés
- `GET /api/properties/{id}` - Détails d'une propriété
- `GET /api/cities` - Liste des villes
- `POST /api/messages` - Envoi de message

### Intégrations Sociales
- **Google OAuth** pour l'authentification
- **Facebook OAuth** pour l'authentification
- **WhatsApp** pour contact rapide

## Maintenance et Support

### Logs et Monitoring
- **Laravel Log** pour le debugging
- **Monitoring des erreurs** avec stack traces
- **Métriques de performance** des requêtes

### Sauvegarde
- **Base de données** : Sauvegarde quotidienne
- **Fichiers média** : Synchronisation cloud
- **Configuration** : Versioning Git

## Évolutions Futures

### Fonctionnalités Prévues
- **Application mobile** (React Native)
- **Système de géolocalisation** avancé
- **Notifications push** en temps réel
- **Système de reviews** et évaluations
- **Intégration paiement mobile** (Mobile Money)

### Optimisations
- **Cache Redis** pour les performances
- **CDN** pour les images
- **Optimisation SEO** avancée
- **Progressive Web App** (PWA)

## Contact et Support

- **Email** : support@monnkama.ga
- **Téléphone** : +241 XX XX XX XX
- **Documentation** : `/docs`
- **Status** : Système opérationnel

---

**Monnkama** - La plateforme immobilière de référence au Gabon
Version 1.0 - Développé avec Laravel 11
