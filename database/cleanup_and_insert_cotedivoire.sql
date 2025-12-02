-- ============================================
-- SCRIPT DE NETTOYAGE ET INSERTION CÔTE D'IVOIRE
-- À exécuter dans phpMyAdmin
-- ============================================

-- ÉTAPE 1: DÉSACTIVER LES CONTRAINTES DE CLÉ ÉTRANGÈRE
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- NETTOYAGE DES DONNÉES GABONAISES
-- ============================================

-- Supprimer les propriétés existantes
DELETE FROM property_media WHERE property_id IN (SELECT id FROM properties);
DELETE FROM property_details WHERE property_id IN (SELECT id FROM properties);
DELETE FROM property_views WHERE property_id IN (SELECT id FROM properties);
DELETE FROM favorites WHERE property_id IN (SELECT id FROM properties);
DELETE FROM messages WHERE property_id IN (SELECT id FROM properties);
DELETE FROM properties;

-- Supprimer les quartiers et villes
DELETE FROM neighborhoods;
DELETE FROM cities;

-- Réinitialiser les compteurs auto-increment
ALTER TABLE properties AUTO_INCREMENT = 1;
ALTER TABLE property_details AUTO_INCREMENT = 1;
ALTER TABLE property_media AUTO_INCREMENT = 1;
ALTER TABLE cities AUTO_INCREMENT = 1;
ALTER TABLE neighborhoods AUTO_INCREMENT = 1;

-- ============================================
-- INSERTION DES VILLES IVOIRIENNES
-- ============================================

INSERT INTO cities (name, slug, region, latitude, longitude, properties_count, is_active, created_at, updated_at) VALUES
('Abidjan', 'abidjan', 'Lagunes', 5.3600, -4.0083, 0, 1, NOW(), NOW()),
('Yamoussoukro', 'yamoussoukro', 'Yamoussoukro', 6.8276, -5.2893, 0, 1, NOW(), NOW()),
('Bouaké', 'bouake', 'Vallée du Bandama', 7.6900, -5.0300, 0, 1, NOW(), NOW()),
('San-Pédro', 'san-pedro', 'Bas-Sassandra', 4.7500, -6.6333, 0, 1, NOW(), NOW()),
('Daloa', 'daloa', 'Sassandra-Marahoué', 6.8770, -6.4503, 0, 1, NOW(), NOW()),
('Korhogo', 'korhogo', 'Savanes', 9.4580, -5.6297, 0, 1, NOW(), NOW()),
('Man', 'man', 'Montagnes', 7.4125, -7.5539, 0, 1, NOW(), NOW()),
('Gagnoa', 'gagnoa', 'Gôh-Djiboua', 6.1319, -5.9506, 0, 1, NOW(), NOW()),
('Grand-Bassam', 'grand-bassam', 'Sud-Comoé', 5.2111, -3.7389, 0, 1, NOW(), NOW()),
('Sassandra', 'sassandra', 'Gbôklé', 4.9500, -6.0833, 0, 1, NOW(), NOW());

-- ============================================
-- INSERTION DES QUARTIERS D'ABIDJAN
-- ============================================

INSERT INTO neighborhoods (city_id, name, slug, description, latitude, longitude, properties_count, is_active, created_at, updated_at) VALUES
-- Plateau (Centre des affaires)
(1, 'Plateau', 'plateau', 'Quartier d''affaires et administratif', 5.3236, -4.0114, 0, 1, NOW(), NOW()),
(1, 'Cocody', 'cocody', 'Quartier résidentiel haut standing', 5.3515, -3.9872, 0, 1, NOW(), NOW()),
(1, 'Marcory', 'marcory', 'Quartier résidentiel et commercial', 5.2892, -3.9872, 0, 1, NOW(), NOW()),
(1, 'Treichville', 'treichville', 'Quartier populaire et commercial', 5.2833, -4.0167, 0, 1, NOW(), NOW()),
(1, 'Adjamé', 'adjame', 'Quartier commercial populaire', 5.3667, -4.0167, 0, 1, NOW(), NOW()),
(1, 'Yopougon', 'yopougon', 'Grande commune résidentielle', 5.3333, -4.0833, 0, 1, NOW(), NOW()),
(1, 'Abobo', 'abobo', 'Commune populaire', 5.4167, -4.0167, 0, 1, NOW(), NOW()),
(1, 'Koumassi', 'koumassi', 'Quartier résidentiel', 5.2833, -3.9500, 0, 1, NOW(), NOW()),
(1, 'Port-Bouët', 'port-bouet', 'Zone portuaire et résidentielle', 5.2500, -3.9167, 0, 1, NOW(), NOW()),
(1, 'Attécoubé', 'attecoube', 'Quartier résidentiel', 5.3333, -4.0500, 0, 1, NOW(), NOW()),

-- Quartiers huppés de Cocody
(1, 'Cocody Riviera', 'cocody-riviera', 'Zone résidentielle de luxe', 5.3600, -3.9700, 0, 1, NOW(), NOW()),
(1, 'Cocody II Plateaux', 'cocody-ii-plateaux', 'Quartier résidentiel moderne', 5.3700, -3.9800, 0, 1, NOW(), NOW()),
(1, 'Cocody Angré', 'cocody-angre', 'Zone résidentielle haut standing', 5.3800, -3.9600, 0, 1, NOW(), NOW()),
(1, 'Cocody Ambassades', 'cocody-ambassades', 'Quartier diplomatique', 5.3500, -3.9900, 0, 1, NOW(), NOW()),
(1, 'Cocody Danga', 'cocody-danga', 'Zone résidentielle', 5.3400, -4.0000, 0, 1, NOW(), NOW()),

-- Autres quartiers importants
(1, 'Marcory Zone 4', 'marcory-zone-4', 'Zone résidentielle et commerciale', 5.2900, -3.9800, 0, 1, NOW(), NOW()),
(1, 'Treichville Zone 3', 'treichville-zone-3', 'Zone commerciale', 5.2850, -4.0100, 0, 1, NOW(), NOW()),
(1, 'Yopougon Niangon', 'yopougon-niangon', 'Zone résidentielle', 5.3400, -4.0900, 0, 1, NOW(), NOW()),
(1, 'Bingerville', 'bingerville', 'Ville satellite résidentielle', 5.3550, -3.8950, 0, 1, NOW(), NOW()),
(1, 'Songon', 'songon', 'Zone en développement', 5.3000, -4.2500, 0, 1, NOW(), NOW());

-- ============================================
-- INSERTION DES QUARTIERS DE YAMOUSSOUKRO
-- ============================================

INSERT INTO neighborhoods (city_id, name, slug, description, latitude, longitude, properties_count, is_active, created_at, updated_at) VALUES
(2, 'Centre Ville', 'centre-ville', 'Centre administratif', 6.8276, -5.2893, 0, 1, NOW(), NOW()),
(2, 'Habitat', 'habitat', 'Quartier résidentiel', 6.8200, -5.2800, 0, 1, NOW(), NOW()),
(2, 'Morofé', 'morofe', 'Quartier résidentiel', 6.8300, -5.2900, 0, 1, NOW(), NOW()),
(2, 'N''Zuessy', 'nzuessy', 'Quartier résidentiel', 6.8350, -5.2950, 0, 1, NOW(), NOW()),
(2, 'Dioulakro', 'dioulakro', 'Quartier résidentiel', 6.8250, -5.2850, 0, 1, NOW(), NOW()),
(2, 'Kokrenou', 'kokrenou', 'Quartier résidentiel', 6.8400, -5.3000, 0, 1, NOW(), NOW());

-- ============================================
-- INSERTION DES QUARTIERS DE BOUAKÉ
-- ============================================

INSERT INTO neighborhoods (city_id, name, slug, description, latitude, longitude, properties_count, is_active, created_at, updated_at) VALUES
(3, 'Centre Ville', 'centre-ville', 'Centre commercial', 7.6900, -5.0300, 0, 1, NOW(), NOW()),
(3, 'Commerce', 'commerce', 'Zone commerciale', 7.6850, -5.0250, 0, 1, NOW(), NOW()),
(3, 'Dar-Es-Salam', 'dar-es-salam', 'Quartier résidentiel', 7.6950, -5.0350, 0, 1, NOW(), NOW()),
(3, 'Koko', 'koko', 'Quartier résidentiel', 7.7000, -5.0400, 0, 1, NOW(), NOW()),
(3, 'Air France', 'air-france', 'Quartier résidentiel', 7.6800, -5.0200, 0, 1, NOW(), NOW());

-- ============================================
-- INSERTION DES QUARTIERS POUR AUTRES VILLES
-- ============================================

-- San-Pédro
INSERT INTO neighborhoods (city_id, name, slug, description, latitude, longitude, properties_count, is_active, created_at, updated_at) VALUES
(4, 'Centre Ville', 'centre-ville', 'Centre de San-Pédro', 4.7500, -6.6333, 0, 1, NOW(), NOW()),
(4, 'Bardot', 'bardot', 'Quartier résidentiel', 4.7550, -6.6300, 0, 1, NOW(), NOW()),
(4, 'Balmer', 'balmer', 'Zone portuaire', 4.7450, -6.6400, 0, 1, NOW(), NOW()),
(4, 'Bardo', 'bardo', 'Quartier résidentiel', 4.7600, -6.6250, 0, 1, NOW(), NOW());

-- Daloa
INSERT INTO neighborhoods (city_id, name, slug, description, latitude, longitude, properties_count, is_active, created_at, updated_at) VALUES
(5, 'Centre Ville', 'centre-ville', 'Centre de Daloa', 6.8770, -6.4503, 0, 1, NOW(), NOW()),
(5, 'Commerce', 'commerce', 'Zone commerciale', 6.8800, -6.4550, 0, 1, NOW(), NOW()),
(5, 'Lobia', 'lobia', 'Quartier résidentiel', 6.8850, -6.4600, 0, 1, NOW(), NOW());

-- Korhogo
INSERT INTO neighborhoods (city_id, name, slug, description, latitude, longitude, properties_count, is_active, created_at, updated_at) VALUES
(6, 'Centre Ville', 'centre-ville', 'Centre de Korhogo', 9.4580, -5.6297, 0, 1, NOW(), NOW()),
(6, 'Petit Paris', 'petit-paris', 'Quartier résidentiel', 9.4600, -5.6250, 0, 1, NOW(), NOW()),
(6, 'Koko', 'koko', 'Quartier résidentiel', 9.4650, -5.6350, 0, 1, NOW(), NOW());

-- Man
INSERT INTO neighborhoods (city_id, name, slug, description, latitude, longitude, properties_count, is_active, created_at, updated_at) VALUES
(7, 'Centre Ville', 'centre-ville', 'Centre de Man', 7.4125, -7.5539, 0, 1, NOW(), NOW()),
(7, 'Libreville', 'libreville', 'Quartier résidentiel', 7.4150, -7.5500, 0, 1, NOW(), NOW()),
(7, 'Dogomet', 'dogomet', 'Quartier résidentiel', 7.4100, -7.5600, 0, 1, NOW(), NOW());

-- Gagnoa
INSERT INTO neighborhoods (city_id, name, slug, description, latitude, longitude, properties_count, is_active, created_at, updated_at) VALUES
(8, 'Centre Ville', 'centre-ville', 'Centre de Gagnoa', 6.1319, -5.9506, 0, 1, NOW(), NOW()),
(8, 'Commerce', 'commerce', 'Zone commerciale', 6.1350, -5.9550, 0, 1, NOW(), NOW());

-- Grand-Bassam
INSERT INTO neighborhoods (city_id, name, slug, description, latitude, longitude, properties_count, is_active, created_at, updated_at) VALUES
(9, 'Quartier France', 'quartier-france', 'Centre historique', 5.2111, -3.7389, 0, 1, NOW(), NOW()),
(9, 'Phare', 'phare', 'Zone balnéaire', 5.2150, -3.7350, 0, 1, NOW(), NOW()),
(9, 'Moossou', 'moossou', 'Quartier résidentiel', 5.2200, -3.7300, 0, 1, NOW(), NOW());

-- Sassandra
INSERT INTO neighborhoods (city_id, name, slug, description, latitude, longitude, properties_count, is_active, created_at, updated_at) VALUES
(10, 'Centre Ville', 'centre-ville', 'Centre de Sassandra', 4.9500, -6.0833, 0, 1, NOW(), NOW()),
(10, 'Plage', 'plage', 'Zone balnéaire', 4.9550, -6.0800, 0, 1, NOW(), NOW());

-- ============================================
-- INSERTION DES PROPRIÉTÉS IVOIRIENNES
-- ============================================

-- Propriétés à Abidjan - Cocody (Quartiers huppés)
INSERT INTO properties (user_id, title, slug, type, status, price, currency, description, bedrooms, bathrooms, surface_area, latitude, longitude, address, city, neighborhood, featured, published, views_count, created_at, updated_at, furnished, parking, garden, pool, security, elevator, balcony, air_conditioning, floor, total_floors, construction_year, energy_rating, features) VALUES
(1, 'Villa de prestige avec piscine à Cocody Riviera', 'villa-de-prestige-avec-piscine-a-cocody-riviera', 'house', 'for_sale', 450000000, 'XAF', 'Magnifique villa de 6 chambres avec piscine, jardin tropical et vue panoramique. Située dans le quartier le plus prisé d''Abidjan, Cocody Riviera. Finitions luxueuses, sécurité 24h/24.', 6, 5, 500, 5.3600, -3.9700, 'Boulevard Latrille, Cocody Riviera', 'Abidjan', 'Cocody Riviera', 1, 1, 0, NOW(), NOW(), 1, 1, 1, 1, 1, 0, 1, 1, NULL, NULL, 2021, 'A', NULL),

(1, 'Appartement standing aux II Plateaux', 'appartement-standing-aux-ii-plateaux', 'apartment', 'for_rent', 800000, 'XAF', 'Superbe appartement de 4 chambres entièrement meublé dans une résidence sécurisée. Idéal pour expatriés. Proche de toutes commodités, centres commerciaux et écoles internationales.', 4, 3, 180, 5.3700, -3.9800, 'Rue des Jardins, II Plateaux', 'Abidjan', 'Cocody II Plateaux', 1, 1, 0, NOW(), NOW(), 1, 1, 0, 0, 1, 1, 1, 1, 5, 8, 2020, 'A', NULL),

(1, 'Villa moderne à Cocody Angré', 'villa-moderne-a-cocody-angre', 'house', 'for_sale', 380000000, 'XAF', 'Belle villa contemporaine de 5 chambres avec architecture moderne. Quartier calme et sécurisé, proche des ambassades. Piscine, jardin paysager, garage 3 voitures.', 5, 4, 420, 5.3800, -3.9600, 'Angré 8ème Tranche', 'Abidjan', 'Cocody Angré', 1, 1, 0, NOW(), NOW(), 1, 1, 1, 1, 1, 0, 1, 1, NULL, NULL, 2022, 'A', NULL),

-- Propriétés à Abidjan - Plateau (Centre d'affaires)
(1, 'Bureaux modernes au Plateau', 'bureaux-modernes-au-plateau', 'commercial', 'for_rent', 2500000, 'XAF', 'Espace de bureaux de 300m² au cœur du quartier des affaires. Immeuble moderne avec ascenseur, climatisation centrale, parking sécurisé. Vue sur la lagune.', NULL, 3, 300, 5.3236, -4.0114, 'Avenue Franchet d''Esperey, Plateau', 'Abidjan', 'Plateau', 1, 1, 0, NOW(), NOW(), 0, 1, 0, 0, 1, 1, 0, 1, 7, 12, 2019, 'B', NULL),

(1, 'Local commercial au Plateau', 'local-commercial-au-plateau', 'commercial', 'for_rent', 1800000, 'XAF', 'Local commercial de 150m² idéalement situé dans la zone commerciale du Plateau. Parfait pour boutique, agence ou showroom. Grande vitrine, climatisation.', NULL, 2, 150, 5.3236, -4.0114, 'Boulevard de la République, Plateau', 'Abidjan', 'Plateau', 1, 1, 0, NOW(), NOW(), 0, 1, 0, 0, 1, 0, 0, 1, 1, 3, 2018, 'B', NULL),

-- Propriétés à Abidjan - Marcory
(1, 'Appartement familial à Marcory Zone 4', 'appartement-familial-a-marcory-zone-4', 'apartment', 'for_rent', 450000, 'XAF', 'Bel appartement de 3 chambres dans résidence calme et sécurisée. Proche des écoles, supermarchés et transports. Parking privé, balcon spacieux.', 3, 2, 120, 5.2900, -3.9800, 'Marcory Zone 4', 'Abidjan', 'Marcory Zone 4', 0, 1, 0, NOW(), NOW(), 0, 1, 0, 0, 1, 0, 1, 1, 3, 5, 2019, 'B', NULL),

(1, 'Villa à Marcory', 'villa-a-marcory', 'house', 'for_sale', 180000000, 'XAF', 'Villa de 4 chambres avec jardin dans quartier résidentiel calme. Idéale pour famille. Proche de toutes commodités.', 4, 3, 280, 5.2892, -3.9872, 'Marcory Résidentiel', 'Abidjan', 'Marcory', 0, 1, 0, NOW(), NOW(), 0, 1, 1, 0, 1, 0, 0, 1, NULL, NULL, 2017, 'C', NULL),

-- Propriétés à Abidjan - Yopougon
(1, 'Maison à Yopougon Niangon', 'maison-a-yopougon-niangon', 'house', 'for_sale', 95000000, 'XAF', 'Maison de 3 chambres dans quartier en développement. Bon rapport qualité-prix. Titre foncier disponible.', 3, 2, 200, 5.3400, -4.0900, 'Yopougon Niangon Nord', 'Abidjan', 'Yopougon Niangon', 0, 1, 0, NOW(), NOW(), 0, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, 2020, 'C', NULL),

-- Propriétés à Abidjan - Bingerville
(1, 'Villa neuve à Bingerville', 'villa-neuve-a-bingerville', 'house', 'for_sale', 220000000, 'XAF', 'Villa moderne de 4 chambres dans la ville satellite de Bingerville. Environnement calme et verdoyant. Construction récente avec finitions de qualité.', 4, 3, 320, 5.3550, -3.8950, 'Bingerville Centre', 'Abidjan', 'Bingerville', 1, 1, 0, NOW(), NOW(), 1, 1, 1, 1, 1, 0, 1, 1, NULL, NULL, 2023, 'A', NULL),

-- Propriétés à Abidjan - Grand-Bassam
(1, 'Villa balnéaire à Grand-Bassam', 'villa-balneaire-a-grand-bassam', 'house', 'for_sale', 280000000, 'XAF', 'Superbe villa les pieds dans l''eau à Grand-Bassam. 5 chambres, piscine, accès direct à la plage. Parfait pour résidence secondaire ou location touristique.', 5, 4, 400, 5.2150, -3.7350, 'Boulevard de la Plage', 'Grand-Bassam', 'Phare', 1, 1, 0, NOW(), NOW(), 1, 1, 1, 1, 1, 0, 1, 1, NULL, NULL, 2021, 'A', NULL),

-- Terrains
(1, 'Terrain constructible à Cocody', 'terrain-constructible-a-cocody', 'land', 'for_sale', 85000000, 'XAF', 'Terrain de 800m² dans zone résidentielle de Cocody. Titre foncier en règle. Viabilisé (eau, électricité). Idéal pour construction villa.', NULL, NULL, 800, 5.3500, -3.9900, 'Cocody Danga', 'Abidjan', 'Cocody Danga', 0, 1, 0, NOW(), NOW(), 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL),

(1, 'Grand terrain à Bingerville', 'grand-terrain-a-bingerville', 'land', 'for_sale', 120000000, 'XAF', 'Vaste terrain de 1500m² à Bingerville. Zone calme en développement. Parfait pour projet immobilier ou résidence de prestige.', NULL, NULL, 1500, 5.3550, -3.8950, 'Bingerville Extension', 'Abidjan', 'Bingerville', 0, 1, 0, NOW(), NOW(), 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL),

-- Propriétés à Yamoussoukro
(1, 'Villa moderne à Yamoussoukro', 'villa-moderne-a-yamoussoukro', 'house', 'for_sale', 150000000, 'XAF', 'Belle villa de 4 chambres dans la capitale politique. Architecture moderne, jardin arboré. Quartier calme et sécurisé.', 4, 3, 300, 6.8200, -5.2800, 'Quartier Habitat', 'Yamoussoukro', 'Habitat', 0, 1, 0, NOW(), NOW(), 1, 1, 1, 0, 1, 0, 1, 1, NULL, NULL, 2020, 'B', NULL),

-- Hôtel
(1, 'Hôtel boutique à Cocody', 'hotel-boutique-a-cocody', 'hotel', 'hotel_room', 65000, 'XAF', 'Charmant hôtel boutique de 20 chambres à Cocody. Restaurant, bar, piscine. Clientèle d''affaires et touristique. Excellent emplacement.', 20, 22, 800, 5.3515, -3.9872, 'Boulevard Latrille, Cocody', 'Abidjan', 'Cocody', 1, 1, 0, NOW(), NOW(), 1, 1, 1, 1, 1, 1, 1, 1, NULL, NULL, 2019, 'A', NULL),

-- Propriétés à Bouaké
(1, 'Maison à Bouaké Centre', 'maison-a-bouake-centre', 'house', 'for_sale', 75000000, 'XAF', 'Maison de 3 chambres au centre de Bouaké. Proche du marché et des commerces. Bon état général.', 3, 2, 180, 7.6900, -5.0300, 'Centre Ville', 'Bouaké', 'Centre Ville', 0, 1, 0, NOW(), NOW(), 0, 1, 1, 0, 0, 0, 0, 0, NULL, NULL, 2015, 'C', NULL),

-- Propriétés à San-Pédro
(1, 'Villa à San-Pédro', 'villa-a-san-pedro', 'house', 'for_sale', 120000000, 'XAF', 'Villa de 4 chambres dans la ville portuaire de San-Pédro. Proche de la plage. Idéal pour cadres du port.', 4, 3, 250, 4.7550, -6.6300, 'Quartier Bardot', 'San-Pédro', 'Bardot', 0, 1, 0, NOW(), NOW(), 1, 1, 1, 0, 1, 0, 1, 1, NULL, NULL, 2018, 'B', NULL);

-- ============================================
-- RÉACTIVER LES CONTRAINTES DE CLÉ ÉTRANGÈRE
-- ============================================

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- VÉRIFICATION DES DONNÉES INSÉRÉES
-- ============================================

-- Compter les villes
SELECT COUNT(*) as total_villes FROM cities;

-- Compter les quartiers
SELECT COUNT(*) as total_quartiers FROM neighborhoods;

-- Compter les propriétés
SELECT COUNT(*) as total_proprietes FROM properties;

-- Afficher les villes avec leurs quartiers
SELECT c.name as ville, COUNT(n.id) as nombre_quartiers 
FROM cities c 
LEFT JOIN neighborhoods n ON c.id = n.city_id 
GROUP BY c.id, c.name 
ORDER BY c.name;

-- Afficher les propriétés par ville
SELECT city, COUNT(*) as nombre_proprietes 
FROM properties 
GROUP BY city 
ORDER BY nombre_proprietes DESC;
