#!/bin/bash

# Script de déploiement final pour Monnkama sur Hostinger
# Auteur: Assistant IA
# Date: $(date)

echo "🚀 Déploiement de Monnkama sur Hostinger"
echo "========================================"

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Fonction pour afficher les messages
log_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

log_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

log_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

log_error() {
    echo -e "${RED}❌ $1${NC}"
}

# Vérification des prérequis
log_info "Vérification des prérequis..."

if ! command -v php &> /dev/null; then
    log_error "PHP n'est pas installé"
    exit 1
fi

if ! command -v composer &> /dev/null; then
    log_error "Composer n'est pas installé"
    exit 1
fi

if ! command -v npm &> /dev/null; then
    log_error "NPM n'est pas installé"
    exit 1
fi

log_success "Tous les prérequis sont installés"

# 1. Installation des dépendances
log_info "Installation des dépendances Composer..."
composer install --no-dev --optimize-autoloader --no-interaction
if [ $? -eq 0 ]; then
    log_success "Dépendances Composer installées"
else
    log_error "Erreur lors de l'installation des dépendances Composer"
    exit 1
fi

log_info "Installation des dépendances NPM..."
npm ci --only=production
if [ $? -eq 0 ]; then
    log_success "Dépendances NPM installées"
else
    log_error "Erreur lors de l'installation des dépendances NPM"
    exit 1
fi

# 2. Compilation des assets
log_info "Compilation des assets..."
npx vite build
if [ $? -eq 0 ]; then
    log_success "Assets compilés"
else
    log_error "Erreur lors de la compilation des assets"
    exit 1
fi

# 3. Configuration Laravel
log_info "Configuration de Laravel..."

# Génération de la clé d'application
if [ ! -f .env ]; then
    log_warning "Fichier .env non trouvé, copie depuis .env.production"
    cp .env.production .env
fi

php artisan key:generate --force
log_success "Clé d'application générée"

# 4. Optimisation pour la production
log_info "Optimisation pour la production..."

# Nettoyage du cache
php artisan optimize:clear
log_success "Cache nettoyé"

# Mise en cache des configurations
php artisan config:cache
php artisan route:cache
php artisan event:cache
log_success "Configurations mises en cache"

# 5. Création du lien symbolique pour le stockage
log_info "Création du lien symbolique pour le stockage..."
php artisan storage:link
log_success "Lien symbolique créé"

# 6. Permissions des fichiers
log_info "Configuration des permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 755 public
log_success "Permissions configurées"

# 7. Vérification de la santé de l'application
log_info "Vérification de la santé de l'application..."
php artisan about --only=environment
log_success "Application prête"

# 8. Instructions finales
echo ""
echo "🎉 Déploiement terminé avec succès !"
echo "=================================="
echo ""
log_info "Instructions pour Hostinger :"
echo "1. Uploadez tous les fichiers dans le dossier 'nalik' sur votre hébergement"
echo "2. Copiez le contenu de 'public' dans 'public_html'"
echo "3. Copiez 'index_hostinger.php' vers 'public_html/index.php'"
echo "4. Configurez votre base de données MySQL dans .env"
echo "5. Exécutez les migrations : php artisan migrate --force"
echo ""
log_success "Votre site sera accessible sur https://monnkama.shop"
echo ""
log_warning "N'oubliez pas de :"
echo "- Configurer les DNS de votre domaine"
echo "- Activer SSL/HTTPS"
echo "- Configurer les tâches cron si nécessaire"
echo ""
echo "📚 Consultez GUIDE_DEPLOIEMENT_FINAL.md pour plus de détails"
