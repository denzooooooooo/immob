# Guide d'utilisation du script SQL pour Côte d'Ivoire dans phpMyAdmin

## 📋 Étapes à suivre

### 1. Accéder à phpMyAdmin
- Ouvrez votre navigateur
- Allez sur : `http://localhost/phpmyadmin` (ou l'URL de votre phpMyAdmin)
- Connectez-vous avec vos identifiants MySQL

### 2. Sélectionner la base de données
- Dans le panneau de gauche, cliquez sur votre base de données (probablement `nalik` ou similaire)
- La base de données sera mise en surbrillance

### 3. Ouvrir l'onglet SQL
- Cliquez sur l'onglet **SQL** en haut de la page
- Vous verrez une grande zone de texte pour entrer vos requêtes SQL

### 4. Copier et coller le script
- Ouvrez le fichier `database/cleanup_and_insert_cotedivoire.sql`
- Sélectionnez TOUT le contenu du fichier (Ctrl+A ou Cmd+A)
- Copiez le contenu (Ctrl+C ou Cmd+C)
- Collez-le dans la zone de texte SQL de phpMyAdmin (Ctrl+V ou Cmd+V)

### 5. Exécuter le script
- Cliquez sur le bouton **Exécuter** (ou **Go**) en bas à droite
- Le script va s'exécuter (cela peut prendre quelques secondes)

### 6. Vérifier les résultats
Après l'exécution, vous devriez voir :
- ✅ Messages de succès pour chaque opération
- ✅ Les statistiques finales affichées :
  - Nombre total de villes (10 villes ivoiriennes)
  - Nombre total de quartiers (environ 60 quartiers)
  - Nombre total de propriétés (environ 18 propriétés)

## 📊 Données insérées

### Villes de Côte d'Ivoire (10 villes)
1. **Abidjan** - Capitale économique (20 quartiers)
   - Cocody (quartiers huppés : Riviera, II Plateaux, Angré, Ambassades)
   - Plateau (centre d'affaires)
   - Marcory, Treichville, Adjamé, Yopougon, Abobo, etc.

2. **Yamoussoukro** - Capitale politique (6 quartiers)
3. **Bouaké** - 2ème ville (5 quartiers)
4. **San-Pédro** - Ville portuaire (4 quartiers)
5. **Daloa** (3 quartiers)
6. **Korhogo** (3 quartiers)
7. **Man** (3 quartiers)
8. **Gagnoa** (2 quartiers)
9. **Grand-Bassam** - Ville balnéaire (3 quartiers)
10. **Sassandra** - Ville côtière (2 quartiers)

### Types de propriétés insérées (18 propriétés)
- 🏠 **Villas de luxe** à Cocody Riviera, Angré, Grand-Bassam
- 🏢 **Appartements** aux II Plateaux, Marcory
- 🏪 **Locaux commerciaux** au Plateau
- 🏨 **Hôtel boutique** à Cocody
- 🌳 **Terrains** à Cocody, Bingerville
- 🏘️ **Maisons** à Yamoussoukro, Bouaké, San-Pédro

### Prix en Francs CFA (XAF)
- Villas de prestige : 150M - 450M XAF
- Appartements : 180M - 380M XAF
- Locations : 450K - 2.5M XAF/mois
- Terrains : 85M - 120M XAF
- Chambres d'hôtel : 65K XAF/nuit

## ⚠️ Important

### Avant d'exécuter le script
- ✅ **Sauvegardez votre base de données** (Export depuis phpMyAdmin)
- ✅ Assurez-vous d'être sur la bonne base de données
- ✅ Vérifiez que vous avez les droits d'administration

### Ce que fait le script
1. **Désactive** temporairement les contraintes de clés étrangères
2. **Supprime** toutes les données gabonaises :
   - Propriétés et leurs médias
   - Quartiers
   - Villes
3. **Réinitialise** les compteurs auto-increment
4. **Insère** les nouvelles données ivoiriennes :
   - 10 villes
   - ~60 quartiers
   - 18 propriétés
5. **Réactive** les contraintes de clés étrangères
6. **Affiche** les statistiques

## 🔍 Vérification après exécution

### Dans phpMyAdmin
1. Cliquez sur la table `cities` → vous devriez voir 10 villes ivoiriennes
2. Cliquez sur la table `neighborhoods` → vous devriez voir ~60 quartiers
3. Cliquez sur la table `properties` → vous devriez voir 18 propriétés

### Dans votre application Laravel
```bash
# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Redémarrer le serveur
php artisan serve
```

Puis visitez :
- `http://localhost:8000/villes` - Pour voir les villes
- `http://localhost:8000/proprietes` - Pour voir les propriétés

## 🆘 En cas de problème

### Erreur "Foreign key constraint fails"
- Le script désactive automatiquement les contraintes
- Si l'erreur persiste, exécutez d'abord :
```sql
SET FOREIGN_KEY_CHECKS = 0;
```

### Erreur "Table doesn't exist"
- Vérifiez que vous êtes sur la bonne base de données
- Assurez-vous que les migrations Laravel ont été exécutées

### Aucune donnée n'apparaît
- Videz le cache Laravel (commandes ci-dessus)
- Vérifiez dans phpMyAdmin que les données sont bien insérées
- Redémarrez votre serveur web (XAMPP)

## 📝 Notes

- Les coordonnées GPS sont approximatives pour les quartiers
- Les prix sont en Francs CFA (XAF)
- Toutes les propriétés sont publiées (`published = 1`)
- Les propriétés "featured" sont mises en avant sur la page d'accueil
- Les user_id correspondent aux agents existants dans votre base

## ✅ Checklist finale

- [ ] Base de données sauvegardée
- [ ] Script copié dans phpMyAdmin
- [ ] Script exécuté avec succès
- [ ] Statistiques vérifiées
- [ ] Cache Laravel vidé
- [ ] Application testée
- [ ] Villes ivoiriennes visibles
- [ ] Propriétés ivoiriennes visibles

---

**Bon travail ! Votre plateforme immobilière est maintenant configurée pour la Côte d'Ivoire ! 🇨🇮**
