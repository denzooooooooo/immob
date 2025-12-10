-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 10 déc. 2025 à 21:06
-- Version du serveur : 11.8.3-MariaDB-log
-- Version de PHP : 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `u608034730_immo`
--

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Déchargement des données de la table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('all_site_settings', 'a:0:{}', 1765403741),
('laravel_cache_all_site_settings', 'a:14:{s:9:\"site_name\";s:19:\"Monnkama Immobilier\";s:16:\"site_description\";s:59:\"Votre partenaire de confiance pour l\'immobilier au Cameroun\";s:10:\"hero_title\";s:36:\"Trouvez votre bien immobilier idéal\";s:13:\"hero_subtitle\";s:43:\"Des milliers de propriétés vous attendent\";s:10:\"hero_image\";N;s:13:\"contact_email\";s:20:\"contact@monnkama.com\";s:13:\"contact_phone\";s:16:\"+237 123 456 789\";s:15:\"contact_address\";s:16:\"Douala, Cameroun\";s:12:\"facebook_url\";s:29:\"https://facebook.com/monnkama\";s:11:\"twitter_url\";s:28:\"https://twitter.com/monnkama\";s:13:\"instagram_url\";s:30:\"https://instagram.com/monnkama\";s:11:\"enable_blog\";b:1;s:17:\"enable_newsletter\";b:1;s:19:\"enable_testimonials\";b:1;}', 1751485055),
('laravel_cache_site_settings', 'a:14:{s:9:\"site_name\";s:19:\"Monnkama Immobilier\";s:16:\"site_description\";s:59:\"Votre partenaire de confiance pour l\'immobilier au Cameroun\";s:10:\"hero_title\";s:36:\"Trouvez votre bien immobilier idéal\";s:13:\"hero_subtitle\";s:43:\"Des milliers de propriétés vous attendent\";s:10:\"hero_image\";N;s:13:\"contact_email\";s:20:\"contact@monnkama.com\";s:13:\"contact_phone\";s:16:\"+237 123 456 789\";s:15:\"contact_address\";s:16:\"Douala, Cameroun\";s:12:\"facebook_url\";s:29:\"https://facebook.com/monnkama\";s:11:\"twitter_url\";s:28:\"https://twitter.com/monnkama\";s:13:\"instagram_url\";s:30:\"https://instagram.com/monnkama\";s:11:\"enable_blog\";s:4:\"true\";s:17:\"enable_newsletter\";s:4:\"true\";s:19:\"enable_testimonials\";s:4:\"true\";}', 1751485055),
('site_settings', 'a:0:{}', 1765403566);

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `region` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `properties_count` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cities`
--

INSERT INTO `cities` (`id`, `name`, `slug`, `region`, `latitude`, `longitude`, `properties_count`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Abidjan', 'abidjan', 'Lagunes', 5.3600000, -4.0083000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(2, 'Yamoussoukro', 'yamoussoukro', 'Yamoussoukro', 6.8276000, -5.2893000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(3, 'Bouaké', 'bouake', 'Vallée du Bandama', 7.6900000, -5.0300000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(4, 'San-Pédro', 'san-pedro', 'Bas-Sassandra', 4.7500000, -6.6333000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(5, 'Daloa', 'daloa', 'Sassandra-Marahoué', 6.8770000, -6.4503000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(6, 'Korhogo', 'korhogo', 'Savanes', 9.4580000, -5.6297000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(7, 'Man', 'man', 'Montagnes', 7.4125000, -7.5539000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(8, 'Gagnoa', 'gagnoa', 'Gôh-Djiboua', 6.1319000, -5.9506000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(9, 'Grand-Bassam', 'grand-bassam', 'Sud-Comoé', 5.2111000, -3.7389000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(10, 'Sassandra', 'sassandra', 'Gbôklé', 4.9500000, -6.0833000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` text NOT NULL,
  `options` text DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `property_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `type` enum('text','image','document') NOT NULL DEFAULT 'text',
  `attachment_path` varchar(255) DEFAULT NULL,
  `read_at` datetime DEFAULT NULL,
  `is_system_message` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(11) NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

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

--
-- Structure de la table `neighborhoods`
--

CREATE TABLE `neighborhoods` (
  `id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `properties_count` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `neighborhoods`
--

INSERT INTO `neighborhoods` (`id`, `city_id`, `name`, `slug`, `description`, `latitude`, `longitude`, `properties_count`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Plateau', 'plateau', 'Quartier d\'affaires et administratif', 5.3236000, -4.0114000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(2, 1, 'Cocody', 'cocody', 'Quartier résidentiel haut standing', 5.3515000, -3.9872000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(3, 1, 'Marcory', 'marcory', 'Quartier résidentiel et commercial', 5.2892000, -3.9872000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(4, 1, 'Treichville', 'treichville', 'Quartier populaire et commercial', 5.2833000, -4.0167000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(5, 1, 'Adjamé', 'adjame', 'Quartier commercial populaire', 5.3667000, -4.0167000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(6, 1, 'Yopougon', 'yopougon', 'Grande commune résidentielle', 5.3333000, -4.0833000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(7, 1, 'Abobo', 'abobo', 'Commune populaire', 5.4167000, -4.0167000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(8, 1, 'Koumassi', 'koumassi', 'Quartier résidentiel', 5.2833000, -3.9500000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(9, 1, 'Port-Bouët', 'port-bouet', 'Zone portuaire et résidentielle', 5.2500000, -3.9167000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(10, 1, 'Attécoubé', 'attecoube', 'Quartier résidentiel', 5.3333000, -4.0500000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(11, 1, 'Cocody Riviera', 'cocody-riviera', 'Zone résidentielle de luxe', 5.3600000, -3.9700000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(12, 1, 'Cocody II Plateaux', 'cocody-ii-plateaux', 'Quartier résidentiel moderne', 5.3700000, -3.9800000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(13, 1, 'Cocody Angré', 'cocody-angre', 'Zone résidentielle haut standing', 5.3800000, -3.9600000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(14, 1, 'Cocody Ambassades', 'cocody-ambassades', 'Quartier diplomatique', 5.3500000, -3.9900000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(15, 1, 'Cocody Danga', 'cocody-danga', 'Zone résidentielle', 5.3400000, -4.0000000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(16, 1, 'Marcory Zone 4', 'marcory-zone-4', 'Zone résidentielle et commerciale', 5.2900000, -3.9800000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(17, 1, 'Treichville Zone 3', 'treichville-zone-3', 'Zone commerciale', 5.2850000, -4.0100000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(18, 1, 'Yopougon Niangon', 'yopougon-niangon', 'Zone résidentielle', 5.3400000, -4.0900000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(19, 1, 'Bingerville', 'bingerville', 'Ville satellite résidentielle', 5.3550000, -3.8950000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(20, 1, 'Songon', 'songon', 'Zone en développement', 5.3000000, -4.2500000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(21, 2, 'Centre Ville', 'centre-ville', 'Centre administratif', 6.8276000, -5.2893000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(22, 2, 'Habitat', 'habitat', 'Quartier résidentiel', 6.8200000, -5.2800000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(23, 2, 'Morofé', 'morofe', 'Quartier résidentiel', 6.8300000, -5.2900000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(24, 2, 'N\'Zuessy', 'nzuessy', 'Quartier résidentiel', 6.8350000, -5.2950000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(25, 2, 'Dioulakro', 'dioulakro', 'Quartier résidentiel', 6.8250000, -5.2850000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(26, 2, 'Kokrenou', 'kokrenou', 'Quartier résidentiel', 6.8400000, -5.3000000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(27, 3, 'Centre Ville', 'centre-ville', 'Centre commercial', 7.6900000, -5.0300000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(28, 3, 'Commerce', 'commerce', 'Zone commerciale', 7.6850000, -5.0250000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(29, 3, 'Dar-Es-Salam', 'dar-es-salam', 'Quartier résidentiel', 7.6950000, -5.0350000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(30, 3, 'Koko', 'koko', 'Quartier résidentiel', 7.7000000, -5.0400000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(31, 3, 'Air France', 'air-france', 'Quartier résidentiel', 7.6800000, -5.0200000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(32, 4, 'Centre Ville', 'centre-ville', 'Centre de San-Pédro', 4.7500000, -6.6333000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(33, 4, 'Bardot', 'bardot', 'Quartier résidentiel', 4.7550000, -6.6300000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(34, 4, 'Balmer', 'balmer', 'Zone portuaire', 4.7450000, -6.6400000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(35, 4, 'Bardo', 'bardo', 'Quartier résidentiel', 4.7600000, -6.6250000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(36, 5, 'Centre Ville', 'centre-ville', 'Centre de Daloa', 6.8770000, -6.4503000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(37, 5, 'Commerce', 'commerce', 'Zone commerciale', 6.8800000, -6.4550000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(38, 5, 'Lobia', 'lobia', 'Quartier résidentiel', 6.8850000, -6.4600000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(39, 6, 'Centre Ville', 'centre-ville', 'Centre de Korhogo', 9.4580000, -5.6297000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(40, 6, 'Petit Paris', 'petit-paris', 'Quartier résidentiel', 9.4600000, -5.6250000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(41, 6, 'Koko', 'koko', 'Quartier résidentiel', 9.4650000, -5.6350000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(42, 7, 'Centre Ville', 'centre-ville', 'Centre de Man', 7.4125000, -7.5539000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(43, 7, 'Libreville', 'libreville', 'Quartier résidentiel', 7.4150000, -7.5500000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(44, 7, 'Dogomet', 'dogomet', 'Quartier résidentiel', 7.4100000, -7.5600000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(45, 8, 'Centre Ville', 'centre-ville', 'Centre de Gagnoa', 6.1319000, -5.9506000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(46, 8, 'Commerce', 'commerce', 'Zone commerciale', 6.1350000, -5.9550000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(47, 9, 'Quartier France', 'quartier-france', 'Centre historique', 5.2111000, -3.7389000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(48, 9, 'Phare', 'phare', 'Zone balnéaire', 5.2150000, -3.7350000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(49, 9, 'Moossou', 'moossou', 'Quartier résidentiel', 5.2200000, -3.7300000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(50, 10, 'Centre Ville', 'centre-ville', 'Centre de Sassandra', 4.9500000, -6.0833000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40'),
(51, 10, 'Plage', 'plage', 'Zone balnéaire', 4.9550000, -6.0800000, 0, 1, '2025-12-10 20:59:40', '2025-12-10 20:59:40');

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` enum('house','apartment','land','commercial','hotel') NOT NULL,
  `status` enum('for_sale','for_rent','hotel_room') NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `currency` enum('XAF','EUR','USD') NOT NULL DEFAULT 'XAF',
  `description` text NOT NULL,
  `bedrooms` int(11) DEFAULT NULL,
  `bathrooms` int(11) DEFAULT NULL,
  `surface_area` decimal(10,2) NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `neighborhood` varchar(255) NOT NULL,
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `published` tinyint(1) NOT NULL DEFAULT 0,
  `views_count` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `furnished` tinyint(1) NOT NULL DEFAULT 0,
  `parking` tinyint(1) NOT NULL DEFAULT 0,
  `garden` tinyint(1) NOT NULL DEFAULT 0,
  `pool` tinyint(1) NOT NULL DEFAULT 0,
  `security` tinyint(1) NOT NULL DEFAULT 0,
  `elevator` tinyint(1) NOT NULL DEFAULT 0,
  `balcony` tinyint(1) NOT NULL DEFAULT 0,
  `air_conditioning` tinyint(1) NOT NULL DEFAULT 0,
  `floor` int(11) DEFAULT NULL,
  `total_floors` int(11) DEFAULT NULL,
  `construction_year` int(11) DEFAULT NULL,
  `energy_rating` varchar(255) DEFAULT NULL,
  `features` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `property_details`
--

CREATE TABLE `property_details` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `year_built` int(11) DEFAULT NULL,
  `parking_spaces` int(11) DEFAULT NULL,
  `furnished` tinyint(1) NOT NULL DEFAULT 0,
  `air_conditioning` tinyint(1) NOT NULL DEFAULT 0,
  `swimming_pool` tinyint(1) NOT NULL DEFAULT 0,
  `security_system` tinyint(1) NOT NULL DEFAULT 0,
  `internet` tinyint(1) NOT NULL DEFAULT 0,
  `garden` tinyint(1) NOT NULL DEFAULT 0,
  `balcony` tinyint(1) NOT NULL DEFAULT 0,
  `elevator` tinyint(1) NOT NULL DEFAULT 0,
  `garage` tinyint(1) NOT NULL DEFAULT 0,
  `terrace` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `property_media`
--

CREATE TABLE `property_media` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `type` enum('image','video','360_view') NOT NULL,
  `path` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `mime_type` varchar(255) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `property_views`
--

CREATE TABLE `property_views` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `viewed_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `search_alerts`
--

CREATE TABLE `search_alerts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `criteria` text NOT NULL,
  `frequency` enum('daily','weekly','monthly') NOT NULL,
  `last_sent_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('KyPT899iXlIQ1RdmudWNhyGt2c7N9tfl4VQ5XBBK', 1, '2a01:e0a:e4d:2090:e191:89d8:7c11:f010', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.1 Safari/605.1.15', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiR284ZDM3N0daYnlNUU9HN0tTS1FZOXlpbmhUOXZ5a0d4Y3lQdDZwaSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjUyOiJodHRwczovL2ltbW9jYXJyZXByZW1pdW0uY29tL2FkbWluL3Byb3BlcnRpZXMvY3JlYXRlIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1765400641),
('PSXI42iDHTnGZeAoPI5AqFsYF8Atjq0mkhyR3yVs', 1, '2a01:e0a:e4d:2090:d402:9289:4f6c:20c0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYmg0eWloOUh1WnhzNnlwWHR3OHRubkpOcWdxd1VYb3lVQVV3UEsxZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHBzOi8vaW1tb2NhcnJlcHJlbWl1bS5jb20vYWRtaW4vdXNlcnMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1765400440);

-- --------------------------------------------------------

--
-- Structure de la table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plan` enum('basic','premium','pro') NOT NULL,
  `price_paid` decimal(15,2) NOT NULL,
  `currency` enum('XAF','EUR','USD') NOT NULL DEFAULT 'XAF',
  `starts_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL,
  `status` enum('active','expired','cancelled','pending') NOT NULL,
  `payment_method` enum('airtel_money','orange_money','card','bank_transfer') NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_details` text DEFAULT NULL,
  `properties_limit` int(11) NOT NULL,
  `properties_used` int(11) NOT NULL DEFAULT 0,
  `featured_listings` tinyint(1) NOT NULL DEFAULT 0,
  `priority_support` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `role` enum('admin','agent','client') NOT NULL DEFAULT 'client',
  `phone` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `avatar` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `email_notifications` tinyint(1) NOT NULL DEFAULT 1,
  `sms_notifications` tinyint(1) NOT NULL DEFAULT 0,
  `property_alerts` tinyint(1) NOT NULL DEFAULT 1,
  `price_alerts` tinyint(1) NOT NULL DEFAULT 0,
  `last_login_at` datetime DEFAULT NULL,
  `verification_code` varchar(255) DEFAULT NULL,
  `phone_verified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `phone`, `status`, `avatar`, `company_name`, `bio`, `website`, `address`, `city`, `email_notifications`, `sms_notifications`, `property_alerts`, `price_alerts`, `last_login_at`, `verification_code`, `phone_verified_at`) VALUES
(1, 'Administrateur Monnkama', 'admin@monnkama.ga', '2025-06-24 21:20:08', '$2y$12$cgcxKXlBMFA4qyh8diIUhei6arHYZJoFXCDF4K4.5cn5r0DhHbNJe', NULL, '2025-06-24 21:20:08', '2025-06-24 21:20:08', 'admin', '+241 01 02 03 04', 'active', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 0, NULL, NULL, '2025-06-24 21:20:08'),
(10, 'Vavoux Ange Marhial Alan djedjed', 'djedjedange20@gmail.com', NULL, '$2y$12$tuvHfzEljJbd97udDRymMuJlXhFoShyNDw.XTBBFmRKUvpDMrUJcC', NULL, '2025-06-24 21:25:54', '2025-06-24 21:25:54', 'agent', '+33769299085', 'active', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, 1, 0, NULL, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `neighborhoods`
--
ALTER TABLE `neighborhoods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_id` (`city_id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `property_details`
--
ALTER TABLE `property_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`);

--
-- Index pour la table `property_media`
--
ALTER TABLE `property_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`);

--
-- Index pour la table `property_views`
--
ALTER TABLE `property_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `search_alerts`
--
ALTER TABLE `search_alerts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Index pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `neighborhoods`
--
ALTER TABLE `neighborhoods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT pour la table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `property_details`
--
ALTER TABLE `property_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `property_media`
--
ALTER TABLE `property_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `property_views`
--
ALTER TABLE `property_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `search_alerts`
--
ALTER TABLE `search_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `neighborhoods`
--
ALTER TABLE `neighborhoods`
  ADD CONSTRAINT `neighborhoods_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `properties`
--
ALTER TABLE `properties`
  ADD CONSTRAINT `properties_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `property_details`
--
ALTER TABLE `property_details`
  ADD CONSTRAINT `property_details_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `property_media`
--
ALTER TABLE `property_media`
  ADD CONSTRAINT `property_media_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `property_views`
--
ALTER TABLE `property_views`
  ADD CONSTRAINT `property_views_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `property_views_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `search_alerts`
--
ALTER TABLE `search_alerts`
  ADD CONSTRAINT `search_alerts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
