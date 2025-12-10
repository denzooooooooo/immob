# 🔧 Guide de Configuration .env - Monnkama

## 📋 Problème Rencontré

Vous avez reçu cette erreur :
```
SQLSTATE[HY000] [1045] Access denied for user 'u608034730_immo'@'localhost'
```

**Cause** : Votre fichier `.env` est configuré avec les identifiants Hostinger (production), mais vous essayez de vous connecter à `localhost` (127.0.0.1) au lieu du serveur distant Hostinger.

---

## 🎯 Solutions

### Option 1 : Développement Local avec XAMPP (RECOMMANDÉ)

#### Étape 1 : Créer une base de données locale

1. Ouvrez phpMyAdmin : http://localhost/phpmyadmin
2. Cliquez sur "Nouvelle base de données"
3. Nom : `nalik`
4. Interclassement : `utf8mb4_unicode_ci`
5. Cliquez sur "Créer"

#### Étape 2 : Configurer votre .env

Copiez le contenu de `.env.local` vers `.env` :

```bash
cp .env.local .env
```

Ou manuellement, modifiez votre `.env` avec ces paramètres :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nalik
DB_USERNAME=root
DB_PASSWORD=
```

#### Étape 3 : Générer la clé d'application

```bash
php artisan key:generate
```

#### Étape 4 : Exécuter les migrations

```bash
php artisan migrate:fresh --seed
```

#### Étape 5 : Vider les caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

#### Étape 6 : Démarrer le serveur

```bash
php artisan serve
```

Visitez : http://localhost:8000

---

### Option 2 : Se Connecter à Hostinger (Production)

⚠️ **ATTENTION** : Cette option se connecte directement à votre base de données de production !

#### Étape 1 : Trouver l'hôte de votre base de données Hostinger

1. Connectez-vous à votre panneau Hostinger
2. Allez dans "Bases de données MySQL"
3. Trouvez l'hôte de votre base de données (généralement : `srv1311.hstgr.io` ou similaire)

#### Étape 2 : Modifier votre .env

```env
DB_CONNECTION=mysql
DB_HOST=srv1311.hstgr.io  # ⚠️ Remplacez par votre vrai hôte Hostinger
DB_PORT=3306
DB_DATABASE=u608034730_immo
DB_USERNAME=u608034730_immo
DB_PASSWORD=Alan12@12@12
```

#### Étape 3 : Autoriser l'accès distant

Dans Hostinger :
