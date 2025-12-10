#!/bin/bash
# Script pour créer le lien symbolique storage sur le serveur

echo "=== Création du lien symbolique storage ==="

# Supprimer l'ancien lien s'il existe
if [ -L "public/storage" ]; then
    echo "Suppression de l'ancien lien symbolique..."
    rm public/storage
fi

# Créer le nouveau lien symbolique
echo "Création du lien symbolique..."
php artisan storage:link

echo "=== Terminé ! ==="
echo ""
echo "Instructions pour le serveur en ligne:"
echo "1. Uploadez ce script sur votre serveur"
echo "2. Donnez-lui les permissions: chmod +x fix_storage_link.sh"
echo "3. Exécutez-le: ./fix_storage_link.sh"
echo ""
echo "OU exécutez directement: php artisan storage:link"
