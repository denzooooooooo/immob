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

-- Structure de la table `cache`
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Structure de la table `cache_locks`
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Structure de la table `cities`
DROP TABLE IF EXISTS `cities`;
CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `properties_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cities_slug_unique` (`slug`),
  KEY `cities_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Données de la table `cities`
INSERT INTO `cities` (`id`, `name`, `slug`, `description`, `image`, `is_active`, `properties_count`, `created_at`, `updated_at`) VALUES
(1, 'Libreville', 'libreville', 'Capitale économique du Gabon', NULL, 1, 0, NOW(), NOW()),
(2, 'Port-Gentil', 'port-gentil', 'Capitale pétrolière du Gabon', NULL, 1, 0, NOW(), NOW()),
(3, 'Franceville', 'franceville', 'Ville universitaire du Haut-Ogooué', NULL, 1, 0, NOW(), NOW()),
(4, 'Oyem', 'oyem', 'Chef-lieu de la province du Woleu-Ntem', NULL, 1, 0, NOW(), NOW()),
(5, 'Moanda', 'moanda', 'Ville minière du Haut-Ogooué', NULL, 1, 0, NOW(), NOW());

-- --------------------------------------------------------

-- Structure de la table `failed_jobs`
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Structure de la table `favorites`
DROP TABLE IF EXISTS `favorites`;
CREATE TABLE `favorites` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `favorites_user_id_property_id_unique` (`user_id`, `property_id`),
  KEY `favorites_property_id_foreign` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Structure de la table `jobs`
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Structure de la table `job_batches`
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(10) UNSIGNED DEFAULT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  `finished_at` int(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Structure de la table `messages`
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `property_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `replied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_property_id_foreign` (`property_id`),
  KEY `messages_user_id_foreign` (`user_id`),
  KEY `messages_is_read_index` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Structure de la table `migrations`
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Données de la table `migrations`
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_20_000000_create_properties_table', 1),
(5, '2024_01_20_000001_create_property_details_table', 1),
(6, '2024_01_20_000002_create_property_media_table', 1),
(7, '2024_01_20_000003_create_subscriptions_table', 1),
(8, '2024_01_20_000004_create_messages_table', 1),
(9, '2024_01_20_000005_create_cities_table', 1),
(10, '2024_01_20_000006_create_neighborhoods_table', 1),
(11, '2024_01_20_000007_create_favorites_and_views_tables', 1),
(12, '2024_01_20_000008_update_users_table', 1),
(13, '2024_01_20_000009_add_deleted_at_to_properties_table', 1),
(14, '2024_01_21_000001_create_site_settings_table', 1),
(15, '2025_06_20_184636_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

-- Structure de la table `neighborhoods`
DROP TABLE IF EXISTS `neighborhoods`;
CREATE TABLE `neighborhoods` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `city_id` bigint(20) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `properties_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `neighborhoods_slug_unique` (`slug`),
  KEY `neighborhoods_city_id_foreign` (`city_id`),
  KEY `neighborhoods_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Données de la table `neighborhoods`
INSERT INTO `neighborhoods` (`id`, `name`, `slug`, `city_id`, `description`, `is_active`, `properties_count`, `created_at`, `updated_at`) VALUES
(1, 'Akanda', 'akanda', 1, 'Quartier résidentiel de Libreville', 1, 0, NOW(), NOW()),
(2, 'Batterie IV', 'batterie-iv', 1, 'Quartier populaire de Libreville', 1, 0, NOW(), NOW()),
(3, 'Cocotiers', 'cocotiers', 1, 'Quartier moderne de Libreville', 1, 0, NOW(), NOW()),
(4, 'Glass', 'glass', 1, 'Centre-ville de Libreville', 1, 0, NOW(), NOW()),
(5, 'Nombakélé', 'nombakele', 1, 'Quartier résidentiel de Libreville', 1, 0, NOW(), NOW()),
(6, 'Cité Damas', 'cite-damas', 2, 'Quartier résidentiel de Port-Gentil', 1, 0, NOW(), NOW()),
(7, 'Bord de Mer', 'bord-de-mer', 2, 'Front de mer de Port-Gentil', 1, 0, NOW(), NOW());

-- --------------------------------------------------------

-- Structure de la table `password_reset_tokens`
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Structure de la table `personal_access_tokens`
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`, `tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Structure de la table `properties`
DROP TABLE IF EXISTS `properties`;
CREATE TABLE `properties` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('sale','rent') NOT NULL,
  `category` enum('apartment','house','villa','studio','office','land','commercial') NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'XAF',
  `address` varchar(255) NOT NULL,
  `city_id` bigint(20) UNSIGNED NOT NULL,
  `neighborhood_id` bigint(20) UNSIGNED DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('available','sold','rented','pending') NOT NULL DEFAULT 'available',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `views_count` int(11) NOT NULL DEFAULT 0,
  `favorites_count` int(11) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `properties_slug_unique` (`slug`),
  KEY `properties_city_id_foreign` (`city_id`),
  KEY `properties_neighborhood_id_foreign` (`neighborhood_id`),
  KEY `properties_user_id_foreign` (`user_id`),
  KEY `properties_type_index` (`type`),
  KEY `properties_category_index` (`category`),
  KEY `properties_status_index` (`status`),
  KEY `properties_is_featured_index` (`is_featured`),
  KEY `properties_is_published_index` (`is_published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Structure de la table `property_details`
DROP TABLE IF EXISTS `property_details`;
CREATE TABLE `property_details` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `bedrooms` int(11) DEFAULT NULL,
  `bathrooms` int(11) DEFAULT NULL,
  `surface_area` decimal(8,2) DEFAULT NULL,
  `land_area` decimal(8,2) DEFAULT NULL,
  `floor` int(11) DEFAULT NULL,
  `total_floors` int(11) DEFAULT NULL,
  `year_built` int(11) DEFAULT NULL,
  `parking_spaces` int(11) DEFAULT NULL,
  `furnished` tinyint(1) NOT NULL DEFAULT 0,
  `balcony` tinyint(1) NOT NULL DEFAULT 0,
  `terrace` tinyint(1) NOT NULL DEFAULT 0,
  `garden` tinyint(1) NOT NULL DEFAULT 0,
  `pool` tinyint(1) NOT NULL DEFAULT 0,
  `garage` tinyint(1) NOT NULL DEFAULT 0,
  `security` tinyint(1) NOT NULL DEFAULT 0,
  `air_conditioning` tinyint(1) NOT NULL DEFAULT 0,
  `heating` tinyint(1) NOT NULL DEFAULT 0,
  `internet` tinyint(1) NOT NULL DEFAULT 0,
  `elevator` tinyint(1) NOT NULL DEFAULT 0,
  `accessibility` tinyint(1) NOT NULL DEFAULT 0,
  `energy_class` enum('A','B','C','D','E','F','G') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `property_details_property_id_foreign` (`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Structure de la table `property_media`
DROP TABLE IF EXISTS `property_media`;
CREATE TABLE `property_media` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('image','video','document') NOT NULL DEFAULT 'image',
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `mime_type` varchar(255) NOT NULL,
  `size` bigint(20) UNSIGNED NOT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `property_media_property_id_foreign` (`property_id`),
  KEY `property_media_type_index` (`type`),
  KEY `property_media_is_primary_index` (`is_primary`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Structure de la table `property_views`
DROP TABLE IF EXISTS `property_views`;
CREATE TABLE `property_views` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `property_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `property_views_property_id_foreign` (`property_id`),
  KEY `property_views_user_id_foreign` (`user_id`),
  KEY `property_views_viewed_at_index` (`viewed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Structure de la table `sessions`
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Structure de la table `site_settings`
DROP TABLE IF EXISTS `site_settings`;
CREATE TABLE `site_settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` longtext DEFAULT NULL,
  `type` enum('string','text','number','boolean','json','file') NOT NULL DEFAULT 'string',
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `description` text DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_settings_key_unique` (`key`),
  KEY `site_settings_group_index` (`group`),
  KEY `site_settings_is_public_index` (`is_public`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Données de la table `site_settings`
INSERT INTO `site_settings` (`id`, `key`, `value`, `type`, `group`, `description`, `is_public`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Monnkama', 'string', 'general', 'Nom du site', 1, NOW(), NOW()),
(2, 'site_description', 'Plateforme immobilière au Gabon', 'text', 'general', 'Description du site', 1, NOW(), NOW()),
(3, 'site_keywords', 'immobilier, gabon, propriété, location, vente', 'text', 'seo', 'Mots-clés SEO', 1, NOW(), NOW()),
(4, 'contact_email', 'contact@monnkama.shop', 'string', 'contact', 'Email de contact', 1, NOW(), NOW()),
(5, 'contact_phone', '+241 01 02 03 04', 'string', 'contact', 'Téléphone de contact', 1, NOW(), NOW()),
(6, 'contact_address', 'Libreville, Gabon', 'text', 'contact', 'Adresse de contact', 1, NOW(), NOW()),
(7, 'social_facebook', 'https://facebook.com/monnkama', 'string', 'social', 'Page Facebook', 1, NOW(), NOW()),
(8, 'social_twitter', 'https://twitter.com/monnkama', 'string', 'social', 'Compte Twitter', 1, NOW(), NOW()),
(9, 'social_instagram', 'https://instagram.com/monnkama', 'string', 'social', 'Compte Instagram', 1, NOW(), NOW()),
(10, 'currency', 'XAF', 'string', 'general', 'Devise par défaut', 1, NOW(), NOW()),
(11, 'timezone', 'Africa/Libreville', 'string', 'general', 'Fuseau horaire', 1, NOW(), NOW()),
(12, 'language', 'fr', 'string', 'general', 'Langue par défaut', 1, NOW(), NOW());

-- --------------------------------------------------------

-- Structure de la table `subscriptions`
DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `plan` enum('basic','premium','pro') NOT NULL,
  `status` enum('active','inactive','cancelled','expired') NOT NULL DEFAULT 'active',
  `price` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'XAF',
  `billing_cycle` enum('monthly','yearly') NOT NULL DEFAULT 'monthly',
  `properties_limit` int(11) NOT NULL DEFAULT 5,
  `featured_properties_limit` int(11) NOT NULL DEFAULT 1,
  `starts_at` timestamp NOT NULL,
  `ends_at` timestamp NOT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_reference` varchar(255) DEFAULT NULL,
  `auto_renew` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriptions_user_id_foreign` (`user_id`),
  KEY `subscriptions_status_index` (`status`),
  KEY `subscriptions_plan_index` (`plan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  KEY `users_city_id_foreign` (`city_id`),
  KEY `users_subscription_id_foreign` (`subscription_id`),
  KEY `users_role_index` (`role`),
  KEY `users_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Données de la table `users` (Utilisateur admin par défaut)
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `phone`, `avatar`, `bio`, `company`, `website`, `address`, `city_id`, `is_active`, `is_verified`, `last_login_at`, `properties_count`, `subscription_id`, `created_at`, `updated_at`) VALUES
(1, 'Administrateur', 'admin@monnkama.shop', NOW(), '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin', '+241 01 02 03 04', NULL, 'Administrateur principal de Monnkama', 'Monnkama', 'https://monnkama.shop', 'Libreville, Gabon', 1, 1, 1, NULL, 0, NULL, NOW(), NOW());

-- --------------------------------------------------------

-- Contraintes pour les tables exportées

-- Contraintes pour la table `favorites`
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- Contraintes pour la table `messages`
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- Contraintes pour la table `neighborhoods`
ALTER TABLE `neighborhoods`
  ADD CONSTRAINT `neighborhoods_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE;

-- Contraintes pour la table `properties`
ALTER TABLE `properties`
  ADD CONSTRAINT `properties_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `properties_neighborhood_id_foreign` FOREIGN KEY (`neighborhood_id`) REFERENCES `neighborhoods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `properties_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- Contraintes pour la table `property_details`
ALTER TABLE `property_details`
  ADD CONSTRAINT `property_details_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

-- Contraintes pour la table `property_media`
ALTER TABLE `property_media`
  ADD CONSTRAINT `property_media_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

-- Contraintes pour la table `property_views`
ALTER TABLE `property_views`
  ADD CONSTRAINT `property_views_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `property_views_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- Contraintes pour la table `subscriptions`
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- Contraintes pour la table `users`
ALTER TABLE `users`
  ADD CONSTRAINT `users_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL;

-- --------------------------------------------------------

-- AUTO_INCREMENT pour les tables exportées

ALTER TABLE `cache` MODIFY `key` varchar(255) NOT NULL;
ALTER TABLE `cache_locks` MODIFY `key` varchar(255) NOT NULL;
ALTER TABLE `cities` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE `favorites` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `jobs` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `messages` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `migrations` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
ALTER TABLE `neighborhoods` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
ALTER TABLE `personal_access_tokens` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `properties` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `property_details` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `property_media` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `property_views` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `site_settings` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
ALTER TABLE `subscriptions` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `users` MODIFY `i
