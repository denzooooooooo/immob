BEGIN;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2024_01_20_000000_create_properties_table',1);
INSERT INTO migrations VALUES(5,'2024_01_20_000001_create_property_details_table',1);
INSERT INTO migrations VALUES(6,'2024_01_20_000002_create_property_media_table',1);
INSERT INTO migrations VALUES(7,'2024_01_20_000003_create_subscriptions_table',1);
INSERT INTO migrations VALUES(8,'2024_01_20_000004_create_messages_table',1);
INSERT INTO migrations VALUES(9,'2024_01_20_000005_create_cities_table',1);
INSERT INTO migrations VALUES(10,'2024_01_20_000006_create_neighborhoods_table',1);
INSERT INTO migrations VALUES(11,'2024_01_20_000007_create_favorites_and_views_tables',1);
INSERT INTO migrations VALUES(12,'2024_01_20_000008_update_users_table',1);
INSERT INTO migrations VALUES(13,'2024_01_20_000009_add_deleted_at_to_properties_table',1);
INSERT INTO migrations VALUES(14,'2024_01_21_000001_create_site_settings_table',1);
INSERT INTO migrations VALUES(15,'2025_06_20_184636_create_personal_access_tokens_table',1);
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `bio` text,
  `website` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `email_notifications` tinyint(1) NOT NULL DEFAULT '1',
  `sms_notifications` tinyint(1) NOT NULL DEFAULT '0',
  `property_alerts` tinyint(1) NOT NULL DEFAULT '1',
  `price_alerts` tinyint(1) NOT NULL DEFAULT '0',
  `last_login_at` datetime DEFAULT NULL,
  `verification_code` varchar(255) DEFAULT NULL,
  `phone_verified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO users VALUES(1,'Administrateur Monnkama','admin@monnkama.ga','2025-06-24 21:20:08','$2y$12$cgcxKXlBMFA4qyh8diIUhei6arHYZJoFXCDF4K4.5cn5r0DhHbNJe',NULL,'2025-06-24 21:20:08','2025-06-24 21:20:08','admin','+241 01 02 03 04','active',NULL,NULL,NULL,NULL,NULL,NULL,1,1,1,0,NULL,NULL,'2025-06-24 21:20:08');
INSERT INTO users VALUES(2,'Jean-Claude Mbadinga','jc.mbadinga@monnkama.ga','2025-06-24 21:20:08','$2y$12$t03Xh2E2o3TxK4YgDJH/hODq8PFHkhKxqsFQsEpWrFLTQPc9gve.i',NULL,'2025-06-24 21:20:08','2025-06-24 21:20:08','agent','+241 06 12 34 56','active',NULL,'Immobilier Gabon Plus','Agent immobilier expérimenté spécialisé dans les propriétés de luxe à Libreville.',NULL,NULL,'libreville',1,0,1,0,NULL,NULL,'2025-06-24 21:20:08');
INSERT INTO users VALUES(3,'Marie-Claire Nzamba','mc.nzamba@monnkama.ga','2025-06-24 21:20:08','$2y$12$6NgJmPWYZZ6CGT0pvZAecePZQXFnFpt65jrM1CXuw.RbqXOOt82Pu',NULL,'2025-06-24 21:20:08','2025-06-24 21:20:08','agent','+241 07 23 45 67','active',NULL,'Port-Gentil Properties','Experte en immobilier commercial et résidentiel à Port-Gentil.',NULL,NULL,'port-gentil',1,0,1,0,NULL,NULL,'2025-06-24 21:20:08');
INSERT INTO users VALUES(4,'Pierre Obame','p.obame@monnkama.ga','2025-06-24 21:20:08','$2y$12$3BtKM/l9UhVX70VFv91Um.r.cqyMQqpc1sKDs7.r9MyvhGYubV8Gu',NULL,'2025-06-24 21:20:08','2025-06-24 21:20:08','agent','+241 05 34 56 78','active',NULL,'Franceville Immobilier','Spécialiste de l''immobilier dans la région du Haut-Ogooué.',NULL,NULL,'franceville',1,0,1,0,NULL,NULL,'2025-06-24 21:20:08');
INSERT INTO users VALUES(5,'Sylvie Ondo','sylvie.ondo@gmail.com','2025-06-24 21:20:08','$2y$12$gw7GAZadIt3WzD1ur7QBDO62yClYspuLYWqtHLHdwYqG4eIGu219q',NULL,'2025-06-24 21:20:08','2025-06-24 21:20:08','client','+241 06 78 90 12','active',NULL,NULL,NULL,NULL,NULL,'libreville',1,0,1,0,NULL,NULL,NULL);
INSERT INTO users VALUES(6,'Michel Nguema','michel.nguema@yahoo.fr','2025-06-24 21:20:09','$2y$12$YBtViHQc4p3e3553WPWOQugnf7Pr7H1I1Zi5Bw.cezG.dfOdiYTyy',NULL,'2025-06-24 21:20:09','2025-06-24 21:20:09','client','+241 07 89 01 23','active',NULL,NULL,NULL,NULL,NULL,'port-gentil',1,0,1,0,NULL,NULL,NULL);
INSERT INTO users VALUES(7,'Fatou Diallo','fatou.diallo@hotmail.com','2025-06-24 21:20:09','$2y$12$CcuVF0xSsTsb3WTRZBAkQeUo2xitSwT1SGVxmqc0gWZMv.gRg2w3G',NULL,'2025-06-24 21:20:09','2025-06-24 21:20:09','client','+241 05 90 12 34','active',NULL,NULL,NULL,NULL,NULL,'franceville',1,0,1,0,NULL,NULL,NULL);
INSERT INTO users VALUES(8,'André Mba','andre.mba@gmail.com','2025-06-24 21:20:09','$2y$12$ppjbD0fxHog11iKItNxO6.VLB/E5PTmEoRR2yLsDTJzOAsgsYVm9K',NULL,'2025-06-24 21:20:09','2025-06-24 21:20:09','client','+241 06 01 23 45','active',NULL,NULL,NULL,NULL,NULL,'oyem',1,0,1,0,NULL,NULL,NULL);
INSERT INTO users VALUES(9,'Christelle Eyeghe','christelle.eyeghe@yahoo.fr','2025-06-24 21:20:09','$2y$12$GOdVaVN3V.URO/3xYkKL1ed4svHtzwa.rKCohja/m4wTO0aDIvzoC',NULL,'2025-06-24 21:20:09','2025-06-24 21:20:09','client','+241 07 12 34 56','active',NULL,NULL,NULL,NULL,NULL,'libreville',1,0,1,0,NULL,NULL,NULL);
INSERT INTO users VALUES(10,'Vavoux Ange Marhial Alan djedjed','djedjedange20@gmail.com',NULL,'$2y$12$tuvHfzEljJbd97udDRymMuJlXhFoShyNDw.XTBBFmRKUvpDMrUJcC',NULL,'2025-06-24 21:25:54','2025-06-24 21:25:54','agent','+33769299085','active',NULL,NULL,NULL,NULL,NULL,NULL,1,0,1,0,NULL,NULL,NULL);
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` text,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO sessions VALUES('GCxGDsFzZz9T8iTa8zFlO2Uv8P7sXClE6kAxbR7K',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaDRGNEpQRE1DT1ZuaU5UZ3EwdUJVNzlpRkJ6a1BOTGtualVaVTVwTSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC92aWxsZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1750800021);
INSERT INTO sessions VALUES('QtkbCGuk93eyEhinQbbBI0S6468hIU3EGAezvGAO',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSmpncVAyd3FvTFRzV2o4dTRNNVo1bmF5OHhyOGdqWnFDMTR1UGxqNyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC92aWxsZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1750800051);
INSERT INTO sessions VALUES('RIV0j2mHvWRQf5x8fdxaRu0Isofls5IGxMm1ZI9Z',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiekd1RkZ4UklxbUczOXJGb0hHSktDcjFtRDVYa0ZDa3FVOU1XekIydyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9wcmlldGVzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1750802794);
INSERT INTO sessions VALUES('PDmCJBOTQ065OGU6VG4ybXeSvmpshcV33rbPPbBM',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 Edg/137.0.0.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZkRQd1U3dnczNU93Ym5wQjZXTWRralZqZkpIRTJJNEQzeDhmNWl4NyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9wcmlldGVzL2hvdGVsLWJvdXRpcXVlLWEtb3llbSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1750812890);
INSERT INTO sessions VALUES('sDDz0BnBo0oBDGWtIxyVubrQ0NLzPfhVOStd3RwH',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Safari/605.1.15','YTozOntzOjY6Il90b2tlbiI7czo0MDoibnpZVjF1SUhCcXdtQnlqcENsT1pORFpqTkhoQ1FvZVZBMjI2dTExeSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jb250YWN0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1751473126);
INSERT INTO sessions VALUES('LxCWocsXq9kI7IkkLEschmqv5jdANZ5sn0lRNvsx',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Safari/605.1.15','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSjA4WWhGWlB2VXZXeHREM1NzZ1QxdzZoVEdwcThmalJTYjN6WVdzaCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9pbnNjcmlwdGlvbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1751481490);
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO cache VALUES('laravel_cache_all_site_settings','a:14:{s:9:"site_name";s:19:"Monnkama Immobilier";s:16:"site_description";s:59:"Votre partenaire de confiance pour l''immobilier au Cameroun";s:10:"hero_title";s:36:"Trouvez votre bien immobilier idéal";s:13:"hero_subtitle";s:43:"Des milliers de propriétés vous attendent";s:10:"hero_image";N;s:13:"contact_email";s:20:"contact@monnkama.com";s:13:"contact_phone";s:16:"+237 123 456 789";s:15:"contact_address";s:16:"Douala, Cameroun";s:12:"facebook_url";s:29:"https://facebook.com/monnkama";s:11:"twitter_url";s:28:"https://twitter.com/monnkama";s:13:"instagram_url";s:30:"https://instagram.com/monnkama";s:11:"enable_blog";b:1;s:17:"enable_newsletter";b:1;s:19:"enable_testimonials";b:1;}',1751485055);
INSERT INTO cache VALUES('laravel_cache_site_settings','a:14:{s:9:"site_name";s:19:"Monnkama Immobilier";s:16:"site_description";s:59:"Votre partenaire de confiance pour l''immobilier au Cameroun";s:10:"hero_title";s:36:"Trouvez votre bien immobilier idéal";s:13:"hero_subtitle";s:43:"Des milliers de propriétés vous attendent";s:10:"hero_image";N;s:13:"contact_email";s:20:"contact@monnkama.com";s:13:"contact_phone";s:16:"+237 123 456 789";s:15:"contact_address";s:16:"Douala, Cameroun";s:12:"facebook_url";s:29:"https://facebook.com/monnkama";s:11:"twitter_url";s:28:"https://twitter.com/monnkama";s:13:"instagram_url";s:30:"https://instagram.com/monnkama";s:11:"enable_blog";s:4:"true";s:17:"enable_newsletter";s:4:"true";s:19:"enable_testimonials";s:4:"true";}',1751485055);
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` text NOT NULL,
  `attempts` int(11) NOT NULL,
  `reserved_at` int(11) DEFAULT NULL,
  `available_at` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` text NOT NULL,
  `options` text DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` text NOT NULL,
  `exception` text NOT NULL,
  `failed_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `views_count` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `furnished` tinyint(1) NOT NULL DEFAULT '0',
  `parking` tinyint(1) NOT NULL DEFAULT '0',
  `garden` tinyint(1) NOT NULL DEFAULT '0',
  `pool` tinyint(1) NOT NULL DEFAULT '0',
  `security` tinyint(1) NOT NULL DEFAULT '0',
  `elevator` tinyint(1) NOT NULL DEFAULT '0',
  `balcony` tinyint(1) NOT NULL DEFAULT '0',
  `air_conditioning` tinyint(1) NOT NULL DEFAULT '0',
  `floor` int(11) DEFAULT NULL,
  `total_floors` int(11) DEFAULT NULL,
  `construction_year` int(11) DEFAULT NULL,
  `energy_rating` varchar(255) DEFAULT NULL,
  `features` text,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO properties VALUES(1,4,'Villa de luxe avec piscine à Libreville','villa-de-luxe-avec-piscine-a-libreville','house','for_sale',450000000,'XAF','Magnifique villa de 5 chambres avec piscine, jardin tropical et vue sur mer. Située dans un quartier prisé de Libreville.',5,4,450,NULL,NULL,'Adresse à définir','Libreville','Batterie IV',1,1,2,'2025-06-24 21:20:09','2025-07-02 18:38:05',NULL,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL);
INSERT INTO properties VALUES(2,2,'Appartement moderne au centre-ville','appartement-moderne-au-centre-ville','apartment','for_rent',800000,'XAF','Bel appartement de 3 chambres entièrement rénové, proche de toutes commodités.',3,2,120,NULL,NULL,'Adresse à définir','Libreville','Louis',1,1,1,'2025-06-24 21:20:09','2025-07-02 18:38:02',NULL,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL);
INSERT INTO properties VALUES(3,2,'Local commercial à Port-Gentil','local-commercial-a-port-gentil','commercial','for_rent',1500000,'XAF','Local commercial idéalement situé au cœur du quartier des affaires.',NULL,NULL,200,NULL,NULL,'Adresse à définir','Port-Gentil','Bord de Mer',1,1,0,'2025-06-24 21:20:09','2025-06-24 21:20:09',NULL,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL);
INSERT INTO properties VALUES(4,4,'Terrain constructible à Franceville','terrain-constructible-a-franceville','land','for_sale',75000000,'XAF','Grand terrain constructible avec tous les papiers en règle.',NULL,NULL,1500,NULL,NULL,'Adresse à définir','Franceville','Potos',0,1,0,'2025-06-24 21:20:09','2025-06-24 21:20:09',NULL,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL);
INSERT INTO properties VALUES(5,2,'Hôtel boutique à Oyem','hotel-boutique-a-oyem','hotel','hotel_room',75000,'XAF','Charmant hôtel boutique avec 12 chambres, restaurant et piscine.',12,14,800,NULL,NULL,'Adresse à définir','Oyem','Quartier Commercial',1,1,1,'2025-06-24 21:20:09','2025-06-25 00:54:50',NULL,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL);
INSERT INTO properties VALUES(6,1,'CRITIQUE','critique','apartment','for_sale',12,'XAF','hhj,n',2,2,22,NULL,NULL,'88 av. gabriel peri','libreville','okala',1,1,2,'2025-06-24 23:52:16','2025-06-25 00:51:00','2025-06-25 00:51:00',0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL);
CREATE TABLE IF NOT EXISTS `property_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `year_built` int(11) DEFAULT NULL,
  `parking_spaces` int(11) DEFAULT NULL,
  `furnished` tinyint(1) NOT NULL DEFAULT '0',
  `air_conditioning` tinyint(1) NOT NULL DEFAULT '0',
  `swimming_pool` tinyint(1) NOT NULL DEFAULT '0',
  `security_system` tinyint(1) NOT NULL DEFAULT '0',
  `internet` tinyint(1) NOT NULL DEFAULT '0',
  `garden` tinyint(1) NOT NULL DEFAULT '0',
  `balcony` tinyint(1) NOT NULL DEFAULT '0',
  `elevator` tinyint(1) NOT NULL DEFAULT '0',
  `garage` tinyint(1) NOT NULL DEFAULT '0',
  `terrace` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO property_details VALUES(1,6,2022,20,0,0,0,0,0,0,0,0,0,0,'2025-06-24 23:52:16','2025-06-24 23:52:16');
CREATE TABLE IF NOT EXISTS `property_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `type` enum('image','video','360_view') NOT NULL,
  `path` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `mime_type` varchar(255) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO property_media VALUES(1,6,'image','properties/images/1G9KAUtSp4UzXGJKcvLIxkwrJNTNO1Sc73Ctpsw1.jpg',NULL,NULL,0,1,NULL,NULL,'2025-06-24 23:52:16','2025-06-24 23:52:16');
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `plan` enum('basic','premium','pro') NOT NULL,
  `price_paid` decimal(15,2) NOT NULL,
  `currency` enum('XAF','EUR','USD') NOT NULL DEFAULT 'XAF',
  `starts_at` datetime NOT NULL,
  `expires_at` datetime NOT NULL,
  `status` enum('active','expired','cancelled','pending') NOT NULL,
  `payment_method` enum('airtel_money','orange_money','card','bank_transfer') NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_details` text,
  `properties_limit` int(11) NOT NULL,
  `properties_used` int(11) NOT NULL DEFAULT '0',
  `featured_listings` tinyint(1) NOT NULL DEFAULT '0',
  `priority_support` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `property_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `type` enum('text','image','document') NOT NULL DEFAULT 'text',
  `attachment_path` varchar(255) DEFAULT NULL,
  `read_at` datetime DEFAULT NULL,
  `is_system_message` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`sender_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`receiver_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `region` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `properties_count` int(11) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO cities VALUES(1,'Libreville','libreville','Estuaire',0.4162000000000000143,9.46729999999999983,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO cities VALUES(2,'Port-Gentil','port-gentil','Ogooué-Maritime',-0.7193000000000000504,8.781499999999999417,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO cities VALUES(3,'Franceville','franceville','Haut-Ogooué',-1.633299999999999975,13.58329999999999949,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO cities VALUES(4,'Oyem','oyem','Woleu-Ntem',1.599299999999999944,11.57929999999999993,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO cities VALUES(5,'Moanda','moanda','Haut-Ogooué',-1.566699999999999982,13.19999999999999929,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO cities VALUES(6,'Lambaréné','lambarene','Moyen-Ogooué',-0.6999999999999999556,10.23329999999999985,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO cities VALUES(7,'Tchibanga','tchibanga','Nyanga',-2.850000000000000088,11.01670000000000015,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO cities VALUES(8,'Koulamoutou','koulamoutou','Ogooué-Lolo',-1.133299999999999975,12.46669999999999945,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO cities VALUES(9,'Mouila','mouila','Ngounié',-1.866700000000000025,11.05560000000000009,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO cities VALUES(10,'Bitam','bitam','Woleu-Ntem',2.08329999999999993,11.5,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO cities VALUES(11,'djedjed','djedjed','europe',2,2,0,1,'2025-06-24 23:35:25','2025-06-24 23:35:25');
CREATE TABLE IF NOT EXISTS `neighborhoods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `properties_count` int(11) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`city_id`) REFERENCES `cities`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO neighborhoods VALUES(1,1,'Akanda','akanda',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(2,1,'Batterie IV','batterie-iv',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(3,1,'Cocotiers','cocotiers',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(4,1,'Derrière l''Hôpital','derriere-lhopital',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(5,1,'Gros-Bouquet','gros-bouquet',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(6,1,'Lalala','lalala',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(7,1,'Louis','louis',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(8,1,'Nombakélé','nombakele',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(9,1,'Okala','okala',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(10,1,'Oloumi','oloumi',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(11,1,'Plaine Orety','plaine-orety',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(12,1,'Quaben','quaben',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(13,1,'Rio','rio',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(14,1,'Sainte-Marie','sainte-marie',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(15,1,'Sotega','sotega',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(16,1,'Terre Nouvelle','terre-nouvelle',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(17,2,'Azingo','azingo',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(18,2,'Bord de Mer','bord-de-mer',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(19,2,'Cité Damas','cite-damas',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(20,2,'Cité Nouvelle','cite-nouvelle',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(21,2,'Faubourg','faubourg',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(22,2,'Grand Village','grand-village',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(23,2,'Lowé','lowe',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(24,2,'Mbigou','mbigou',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(25,2,'Nzeng-Ayong','nzeng-ayong',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(26,2,'Ozouri','ozouri',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(27,2,'Petit Paris','petit-paris',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(28,2,'Sobraga','sobraga',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(29,3,'Bangouabi','bangouabi',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(30,3,'Bongolo','bongolo',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(31,3,'Carrefour','carrefour',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(32,3,'Cité Nouvelle','cite-nouvelle',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(33,3,'Djoumou','djoumou',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(34,3,'Mbaya','mbaya',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(35,3,'Ndoumou','ndoumou',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(36,3,'Potos','potos',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(37,3,'Quartier Administratif','quartier-administratif',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(38,3,'Quartier Commercial','quartier-commercial',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(39,4,'Assok-Ngomo','assok-ngomo',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(40,4,'Carrefour','carrefour',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(41,4,'Ekouk','ekouk',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(42,4,'Mbang','mbang',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(43,4,'Nkol-Eton','nkol-eton',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(44,4,'Quartier Administratif','quartier-administratif',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(45,4,'Quartier Commercial','quartier-commercial',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(46,5,'Bakoumba','bakoumba',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(47,5,'Cité COMILOG','cite-comilog',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(48,5,'Quartier Administratif','quartier-administratif',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(49,5,'Quartier Commercial','quartier-commercial',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(50,5,'Quartier Résidentiel','quartier-residentiel',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(51,6,'Adouma','adouma',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(52,6,'Isaac','isaac',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(53,6,'Quartier Administratif','quartier-administratif',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(54,6,'Quartier Commercial','quartier-commercial',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(55,6,'Rive Droite','rive-droite',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(56,6,'Rive Gauche','rive-gauche',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(57,7,'Carrefour','carrefour',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(58,7,'Quartier Administratif','quartier-administratif',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(59,7,'Quartier Commercial','quartier-commercial',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(60,7,'Quartier Résidentiel','quartier-residentiel',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(61,8,'Carrefour','carrefour',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(62,8,'Quartier Administratif','quartier-administratif',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(63,8,'Quartier Commercial','quartier-commercial',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(64,8,'Quartier Résidentiel','quartier-residentiel',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(65,9,'Carrefour','carrefour',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(66,9,'Quartier Administratif','quartier-administratif',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(67,9,'Quartier Commercial','quartier-commercial',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(68,9,'Quartier Résidentiel','quartier-residentiel',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(69,10,'Carrefour','carrefour',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(70,10,'Quartier Administratif','quartier-administratif',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(71,10,'Quartier Commercial','quartier-commercial',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
INSERT INTO neighborhoods VALUES(72,10,'Quartier Résidentiel','quartier-residentiel',NULL,NULL,NULL,0,1,'2025-06-24 21:20:07','2025-06-24 21:20:07');
CREATE TABLE IF NOT EXISTS `favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `property_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `viewed_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`property_id`) REFERENCES `properties`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `search_alerts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `criteria` text NOT NULL,
  `frequency` enum('daily','weekly','monthly') NOT NULL,
  `last_sent_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `label` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO site_settings VALUES(1,'site_name','Monnkama Immobilier','text','general','Nom du site','Le nom qui apparaît dans le titre du site','2025-06-24 21:20:09','2025-06-24 21:20:09');
INSERT INTO site_settings VALUES(2,'site_description','Votre partenaire de confiance pour l''immobilier au Cameroun','text','general','Description du site','Une brève description du site pour les moteurs de recherche','2025-06-24 21:20:09','2025-06-24 21:20:09');
INSERT INTO site_settings VALUES(3,'hero_title','Trouvez votre bien immobilier idéal','text','hero','Titre principal','Le titre principal de la page d''accueil','2025-06-24 21:20:09','2025-06-24 21:20:09');
INSERT INTO site_settings VALUES(4,'hero_subtitle','Des milliers de propriétés vous attendent','text','hero','Sous-titre','Le sous-titre sous le titre principal','2025-06-24 21:20:09','2025-06-24 21:20:09');
INSERT INTO site_settings VALUES(5,'hero_image',NULL,'image','hero','Image d''arrière-plan','L''image de fond de la section hero (1920x1080px recommandé)','2025-06-24 21:20:09','2025-06-24 21:20:09');
INSERT INTO site_settings VALUES(6,'contact_email','contact@monnkama.com','text','contact','Email de contact','L''adresse email principale de contact','2025-06-24 21:20:09','2025-06-24 21:20:09');
INSERT INTO site_settings VALUES(7,'contact_phone','+237 123 456 789','text','contact','Téléphone','Le numéro de téléphone principal','2025-06-24 21:20:09','2025-06-24 21:20:09');
INSERT INTO site_settings VALUES(8,'contact_address','Douala, Cameroun','text','contact','Adresse','L''adresse physique de l''entreprise','2025-06-24 21:20:09','2025-06-24 21:20:09');
INSERT INTO site_settings VALUES(9,'facebook_url','https://facebook.com/monnkama','text','social','Facebook','URL de la page Facebook','2025-06-24 21:20:09','2025-06-24 21:20:09');
INSERT INTO site_settings VALUES(10,'twitter_url','https://twitter.com/monnkama','text','social','Twitter','URL du compte Twitter','2025-06-24 21:20:09','2025-06-24 21:20:09');
INSERT INTO site_settings VALUES(11,'instagram_url','https://instagram.com/monnkama','text','social','Instagram','URL du compte Instagram','2025-06-24 21:20:09','2025-06-24 21:20:09');
INSERT INTO site_settings VALUES(12,'enable_blog','true','boolean','features','Activer le blog','Afficher la section blog sur le site','2025-06-24 21:20:09','2025-06-24 21:20:09');
INSERT INTO site_settings VALUES(13,'enable_newsletter','true','boolean','features','Activer la newsletter','Afficher le formulaire d''inscription à la newsletter','2025-06-24 21:20:09','2025-06-24 21:20:09');
INSERT INTO site_settings VALUES(14,'enable_testimonials','true','boolean','features','Activer les témoignages','Afficher la section témoignages sur la page d''accueil','2025-06-24 21:20:09','2025-06-24 21:20:09');
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` datetime DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
DELETE FROM sqlite_sequence;
INSERT INTO sqlite_sequence VALUES('migrations',15);
INSERT INTO sqlite_sequence VALUES('cities',11);
INSERT INTO sqlite_sequence VALUES('neighborhoods',72);
INSERT INTO sqlite_sequence VALUES('users',10);
INSERT INTO sqlite_sequence VALUES('properties',6);
INSERT INTO sqlite_sequence VALUES('site_settings',14);
INSERT INTO sqlite_sequence VALUES('property_details',1);
INSERT INTO sqlite_sequence VALUES('property_media',1);
CREATE UNIQUE INDEX "users_email_unique" on "users" ("email");
CREATE INDEX "sessions_user_id_index" on "sessions" ("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions" ("last_activity");
CREATE INDEX "jobs_queue_index" on "jobs" ("queue");
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs" ("uuid");
CREATE INDEX "properties_type_status_city_index" on "properties" ("type", "status", "city");
CREATE INDEX "properties_price_index" on "properties" ("price");
CREATE INDEX "properties_surface_area_index" on "properties" ("surface_area");
CREATE INDEX "properties_latitude_longitude_index" on "properties" ("latitude", "longitude");
CREATE UNIQUE INDEX "properties_slug_unique" on "properties" ("slug");
CREATE INDEX "property_media_property_id_type_order_index" on "property_media" ("property_id", "type", "order");
CREATE INDEX "subscriptions_user_id_status_index" on "subscriptions" ("user_id", "status");
CREATE INDEX "subscriptions_expires_at_status_index" on "subscriptions" ("expires_at", "status");
CREATE INDEX "messages_sender_id_receiver_id_created_at_index" on "messages" ("sender_id", "receiver_id", "created_at");
CREATE INDEX "messages_property_id_created_at_index" on "messages" ("property_id", "created_at");
CREATE INDEX "messages_read_at_index" on "messages" ("read_at");
CREATE INDEX "cities_slug_index" on "cities" ("slug");
CREATE INDEX "cities_region_index" on "cities" ("region");
CREATE UNIQUE INDEX "cities_slug_unique" on "cities" ("slug");
CREATE INDEX "neighborhoods_city_id_slug_index" on "neighborhoods" ("city_id", "slug");
CREATE UNIQUE INDEX "neighborhoods_city_id_slug_unique" on "neighborhoods" ("city_id", "slug");
CREATE UNIQUE INDEX "favorites_user_id_property_id_unique" on "favorites" ("user_id", "property_id");
CREATE INDEX "property_views_property_id_viewed_at_index" on "property_views" ("property_id", "viewed_at");
CREATE INDEX "property_views_ip_address_property_id_viewed_at_index" on "property_views" ("ip_address", "property_id", "viewed_at");
CREATE INDEX "search_alerts_user_id_is_active_index" on "search_alerts" ("user_id", "is_active");
CREATE UNIQUE INDEX "site_settings_key_unique" on "site_settings" ("key");
CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" on "personal_access_tokens" ("tokenable_type", "tokenable_id");
CREATE UNIQUE INDEX "personal_access_tokens_token_unique" on "personal_access_tokens" ("token");
CREATE INDEX "properties_type_status_published_index" on "properties" ("type", "status", "published");
CREATE INDEX "properties_city_price_index" on "properties" ("city", "price");
CREATE INDEX "properties_bedrooms_bathrooms_index" on "properties" ("bedrooms", "bathrooms");
CREATE INDEX "properties_surface_area_price_index" on "properties" ("surface_area", "price");
COMMIT;
