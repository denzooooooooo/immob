-- Export MySQL pour Monnkama - Hostinger
-- Base de données : u608034730_Yahoo
-- Généré pour la migration depuis SQLite vers MySQL
-- Encodage : UTF-8

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

-- Structure de la table `users`
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `role` enum('user','agent','admin') NOT NULL DEFAULT 'user',
  `phone` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `properties_count` int(11) NOT NULL DEFAULT 0,
  `subscription_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`),
  KEY `users_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Données de la table `users` (Utilisateur admin par défaut)
-- Mot de passe : password (hashé avec bcrypt)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `phone`, `avatar`, `bio`, `company`, `website`, `address`, `city_id`, `is_active`, `is_verified`, `last_login_at`, `properties_count`, `subscription_id`, `created_at`, `updated_at`) VALUES
(1, 'Administrateur', 'admin@monnkama.shop', NOW(), '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin', '+241 01 02 03 04', NULL, 'Administrateur principal de Monnkama', 'Monnkama', 'https://monnkama.shop', 'Libreville, Gabon', 1, 1, 1, NULL, 0, NULL, NOW(), NOW());

-- --------------------------------------------------------

-- AUTO_INCREMENT pour la table users
ALTER TABLE `users` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

-- Instructions d'utilisation :
-- 1. Connectez-vous à votre base de données MySQL sur Hostinger
-- 2. Sélectionnez la base de données u608034730_Yahoo
-- 3. Exécutez ce script SQL
-- 4. Ensuite, exécutez les migrations Laravel : php artisan migrate --force
-- 5. Puis les seeders : php artisan db:seed --force

-- Note : Ce script crée uniquement l'utilisateur admin
-- Les autres tables seront créées par les migrations Laravel
