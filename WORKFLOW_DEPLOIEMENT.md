# 🚀 Workflow de Déploiement - Local vers Hostinger

## ❌ CE QU'IL NE FAUT PAS FAIRE

**JAMAIS pousser le fichier `.env` sur Git !**

Le fichier `.env` contient :
- ❌ Mots de passe de base de données
- ❌ Clés API secrètes
- ❌ Informations sensibles

C'est pourquoi `.env` est dans `.gitignore` ✅

---

## ✅ LE BON WORKFLOW

### 📍 Étape 1 : Développement Local (XAMPP)

**Sur votre machine locale :**

1. **Configurez votre `.env` local :**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nalik
DB_USERNAME=root
DB_PASSWORD=
```

2. **Créez la base de données locale :**
```bash
# Dans phpMyAdmin : http://localhost/phpmyadmin
# Créez une base de données nommée "nalik"
```

3. **Exécutez les migrations :**
```bash
php artisan migrate:fresh --seed
```

4. **Développez votre application :**
```bash
php artisan serve
# Testez sur http://localhost:8000
```

---

### 📤 Étape 2 : Pousser le Code sur Git

**Ce qui est poussé sur Git :**
- ✅ Code source (PHP, Blade, JS, CSS)
- ✅ Migrations (fichiers dans `database/migrations/`)
- ✅ Seeders (fichiers dans `database/seeders/`)
- ✅ Configuration (fichiers dans `config/`)
- ✅ Routes, Controllers, Models
- ❌ **PAS le fichier `.env`**
- ❌ **PAS les données de la base de données**

```bash
# Vérifiez ce qui sera poussé
git status

# Ajoutez vos fichiers
git add .

# Commitez
git commit -m "Ajout de nouvelles fonctionnalités"

# Poussez sur GitHub
git push origin main
```

---

### 🌐 Étape 3 : Déploiement sur Hostinger

#### A. Connectez-vous à Hostinger via SSH ou File Manager

#### B. Tirez le code depuis Git

```bash
cd /home/u608034730/domains/votre-domaine.com/public_html
git pull origin main
```

#### C. Créez/Modifiez le fichier `.env` SUR HOSTINGER

**⚠️ IMPORTANT : Créez un NOUVEAU fichier `.env` directement sur Hostinger**

```bash
# Via SSH
nano .env
```

Ou via le File Manager de Hostinger, créez un fichier `.env` avec ce contenu :

```env
APP_NAME="Monnkama"
APP_ENV=production
APP_KEY=base64:VOTRE_CLE_GENEREE
APP_DEBUG=false
APP_URL=https://votre-domaine.com

# Base de données HOSTINGER
DB_CONNECTION=mysql
DB_HOST=srv1311.hstgr.io
DB_PORT=3306
DB_DATABASE=u608034730_immo
DB_USERNAME=u608034730_immo
DB_PASSWORD=Alan12@12@12

# Autres configurations...
```

#### D. Installez les dépendances

```bash
composer install --no-dev --optimize-autoloader
```

#### E. Générez la clé d'application (si nécessaire)

```bash
php artisan key:generate
```

#### F. Exécutez les migrations sur Hostinger

```bash
php artisan migrate --force
```

#### G. Peuplez la base de données (si nécessaire)

```bash
php artisan db:seed --force
```

#### H. Optimisez pour la production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📊 Schéma du Workflow

```
┌─────────────────────────────────────────────────────────────┐
│                    DÉVELOPPEMENT LOCAL                       │
│                                                              │
│  .env (local)              Base de données locale           │
│  ├─ DB_HOST=127.0.0.1     ├─ nalik (MySQL local)           │
│  ├─ DB_DATABASE=nalik     └─ Données de test               │
│  └─ DB_USERNAME=root                                        │
│                                                              │
│  Développement → Tests → Migrations                         │
└──────────────────────┬───────────────────────────────────────┘
                       │
                       │ git push (SANS .env)
                       ↓
┌─────────────────────────────────────────────────────────────┐
│                         GITHUB                               │
│                                                              │
│  ✅ Code source                                             │
│  ✅ Migrations                                              │
│  ✅ Seeders                                                 │
│  ❌ .env (exclu par .gitignore)                            │
│  ❌ Données de la base                                     │
└──────────────────────┬───────────────────────────────────────┘
                       │
                       │ git pull
                       ↓
┌─────────────────────────────────────────────────────────────┐
│                    HOSTINGER (PRODUCTION)                    │
│                                                              │
│  .env (production)         Base de données Hostinger        │
│  ├─ DB_HOST=srv1311...    ├─ u608034730_immo              │
│  ├─ DB_DATABASE=u608...   └─ Données de production         │
│  └─ DB_USERNAME=u608...                                     │
│                                                              │
│  git pull → composer install → migrate → optimize           │
└─────────────────────────────────────────────────────────────┘
```

---

## 🔄 Workflow Complet Étape par Étape

### 1️⃣ Sur votre machine locale (XAMPP)

```bash
# 1. Développez votre fonctionnalité
# 2. Testez localement
php artisan serve

# 3. Créez une migration si nécessaire
php artisan make:migration create_nouvelle_table

# 4. Testez la migration localement
php artisan migrate

# 5. Commitez et poussez sur Git
git add .
git commit -m "Ajout de nouvelle fonctionnalité"
git push origin main
```

### 2️⃣ Sur Hostinger (via SSH ou File Manager)

```bash
# 1. Allez dans le répertoire du projet
cd /home/u608034730/domains/votre-domaine.com/public_html

# 2. Tirez les dernières modifications
git pull origin main

# 3. Installez les dépendances
composer install --no-dev --optimize-autoloader

# 4. Exécutez les nouvelles migrations
php artisan migrate --force

# 5. Videz les caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 6. Optimisez
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🎯 Points Clés à Retenir

### ✅ À FAIRE

1. **Développer localement** avec votre base de données locale
2. **Pousser le code** sur Git (sans `.env`)
3. **Créer un `.env` séparé** sur Hostinger avec les bonnes informations
4. **Exécuter les migrations** sur Hostinger après le déploiement
5. **Utiliser les seeders** pour peupler les données initiales

### ❌ À NE PAS FAIRE

1. ❌ Pousser le fichier `.env` sur Git
2. ❌ Utiliser les mêmes identifiants de base de données partout
3. ❌ Exporter/Importer manuellement les données entre local et production
4. ❌ Oublier d'exécuter les migrations sur Hostinger

---

## 🔐 Gestion des Données   s

### Données de Structure (Migrations)
✅ **Poussées sur Git** → Exécutées sur Hostinger avec `php artisan migrate`

### Données Initiales (Seeders)
✅ **Poussées sur Git** → Exécutées sur Hostinger avec `php artisan db:seed`

### Données Utilisateurs (Contenu)
❌ **PAS sur Git** → Créées directement en production par les utilisateurs

---

## 📝 Checklist de Déploiement

### Avant de pousser sur Git :
- [ ] Code testé localement
- [ ] Migrations testées localement
- [ ] `.env` n'est PAS dans les fichiers à commiter
- [ ] Pas de données sensibles dans le code

### Après avoir tiré sur Hostinger :
- [ ] `composer install` exécuté
- [ ] `.env` de production configuré correctement
- [ ] `php artisan migrate --force` exécuté
- [ ] Caches vidés et optimisés
- [ ] Site testé en production

---

## 🆘 Résolution de Problèmes

### "Access denied" sur Hostinger après déploiement
➡️ Vérifiez que le `.env` sur Hostinger a les bons identifiants

### Les migrations ne s'exécutent pas
➡️ Vérifiez la connexion à la base de données dans `.env`

### Les changements ne sont pas visibles
➡️ Videz les caches : `php artisan config:clear && php artisan cache:clear`

---

## 📚 Ressources

- [Documentation Laravel - Déploiement](https://laravel.com/docs/deployment)
- [Guide Hostinger - Laravel](https://www.hostinger.com/tutorials/how-to-install-laravel)

---

**Bon déploiement ! 🚀**
