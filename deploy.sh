#!/bin/bash

# Script de déploiement pour Hostinger
# Usage: ./deploy.sh

set -e

echo "🚀 Début du déploiement Monnkama sur Hostinger..."

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Fonction pour afficher les messages
log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Vérifier si nous sommes dans le bon répertoire
if [ ! -f "artisan" ]; then
    log_error "Ce script doit être exécuté depuis la racine du projet Laravel"
    exit 1
fi

# 1. Mettre l'application en mode maintenance
log_info "Mise en mode maintenance..."
php artisan down --message="Mise à jour en cours..." --retry=60

# 2. Sauvegarder la base de données
log_info "Sauvegarde de la base de données..."
if [ -f ".env" ]; then
    php artisan backup:run --only-db 2>/dev/null || log_warning "Sauvegarde échouée (normal si pas configurée)"
fi

# 3. Mettre à jour le code depuis Git
log_info "Mise à jour du code depuis Git..."
git fetch origin
git reset --hard origin/main

# 4. Installer/Mettre à jour les dépendances Composer
log_info "Installation des dépendances Composer..."
composer install --no-dev --optimize-autoloader --no-interaction

# 5. Mettre à jour les dépendances NPM et compiler les assets
log_info "Compilation des assets..."
if command -v npm &> /dev/null; then
    npm ci --production
    npm run build
else
    log_warning "NPM non trouvé, compilation des assets ignorée"
fi

# 6. Vider les caches
log_info "Nettoyage des caches..."
php artisan optimize:clear

# 7. Exécuter les migrations
log_info "Exécution des migrations..."
php artisan migrate --force

# 8. Créer le lien symbolique pour le stockage
log_info "Création du lien symbolique pour le stockage..."
php artisan storage:link

# 9. Optimiser pour la production
log_info "Optimisation pour la production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 10. Préchauffer le cache
log_info "Préchauffage du cache..."
php artisan cache:warmup 2>/dev/null || log_warning "Préchauffage du cache échoué"

# 11. Nettoyer les fichiers temporaires
log_info "Nettoyage des fichiers temporaires..."
php artisan app:cleanup --type=all --days=7 2>/dev/null || log_warning "Nettoyage échoué"

# 12. Définir les permissions correctes
log_info "Configuration des permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs storage/framework/cache storage/framework/sessions storage/framework/views

# 13. Vérifier l'état de l'application
log_info "Vérification de l'état de l'application..."
php artisan app:health-check --format=summary 2>/dev/null || log_warning "Vérification de santé échouée"

# 14. Remettre l'application en ligne
log_info "Remise en ligne de l'application..."
php artisan up

# 15. Afficher le résumé
echo ""
log_success "🎉 Déploiement terminé avec succès!"
echo ""
echo "📊 Résumé du déploiement:"
echo "  - Code mis à jour depuis Git"
echo "  - Dépendances Composer installées"
echo "  - Assets compilés"
echo "  - Migrations exécutées"
echo "  - Caches optimisés"
echo "  - Application en ligne"
echo ""
echo "🔗 Votre site est maintenant accessible à l'adresse configurée"
echo ""

# 16. Optionnel: Envoyer une notification
if [ ! -z "$SLACK_WEBHOOK_URL" ]; then
    log_info "Envoi de notification Slack..."
    curl -X POST -H 'Content-type: application/json' \
        --data '{"text":"🚀 Déploiement Monnkama terminé avec succès!"}' \
        "$SLACK_WEBHOOK_URL" 2>/dev/null || log_warning "Notification Slack échouée"
fi

log_success "Déploiement terminé!"
