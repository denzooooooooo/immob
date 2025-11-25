<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Neighborhood;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Villes principales de Côte d'Ivoire
        $cities = [
            [
                'name' => 'Abidjan',
                'region' => 'Abidjan',
                'latitude' => 5.3600,
                'longitude' => -4.0083,
                'neighborhoods' => [
                    'Plateau', 'Cocody', 'Marcory', 'Treichville',
                    'Yopougon', 'Koumassi', 'Port-Bouët', 'Adjame',
                    'Attecoube', 'Bassam', 'Bingerville', 'Anyama',
                    'Songon', 'Riviera', 'Cocody Centre', 'Deux Plateaux',
                    'Palmeraie', 'Bonoumin', 'Abobo', 'Williamsville'
                ]
            ],
            [
                'name' => 'Bouaké',
                'region' => 'Vallée du Bandama',
                'latitude' => 7.6938,
                'longitude' => -5.0306,
                'neighborhoods' => [
                    'Centre Ville', 'Dar Es Salam', 'N\'Gattakro', 'Air France',
                    'Kennedy', 'Soweto', 'Quartier Nord', 'Quartier Sud',
                    'Zone Industrielle', 'Université'
                ]
            ],
            [
                'name' => 'Daloa',
                'region' => 'Haut-Sassandra',
                'latitude' => 6.8774,
                'longitude' => -6.4502,
                'neighborhoods' => [
                    'Centre Ville', 'Quartier Nord', 'Quartier Sud',
                    'Zone Industrielle', 'Université', 'Marché Central'
                ]
            ],
            [
                'name' => 'Yamoussoukro',
                'region' => 'Yamoussoukro',
                'latitude' => 6.8276,
                'longitude' => -5.2893,
                'neighborhoods' => [
                    'Centre Ville', 'Quartier Administratif', 'Résidentiel',
                    'Université', 'Basilique', 'Zone Hôtelière'
                ]
            ],
            [
                'name' => 'San-Pédro',
                'region' => 'Bas-Sassandra',
                'latitude' => 4.7485,
                'longitude' => -6.6363,
                'neighborhoods' => [
                    'Centre Ville', 'Port', 'Zone Industrielle',
                    'Quartier Nord', 'Quartier Sud', 'Marché Central'
                ]
            ],
            [
                'name' => 'Korhogo',
                'region' => 'Savanes',
                'latitude' => 9.4580,
                'longitude' => -5.6296,
                'neighborhoods' => [
                    'Centre Ville', 'Quartier Nord', 'Quartier Sud',
                    'Zone Industrielle', 'Université', 'Marché Central'
                ]
            ],
            [
                'name' => 'Man',
                'region' => 'Montagnes',
                'latitude' => 7.4125,
                'longitude' => -7.5538,
                'neighborhoods' => [
                    'Centre Ville', 'Quartier Nord', 'Quartier Sud',
                    'Zone Industrielle', 'Université', 'Marché Central'
                ]
            ],
            [
                'name' => 'Gagnoa',
                'region' => 'Gôh-Djiboua',
                'latitude' => 6.1319,
                'longitude' => -5.9506,
                'neighborhoods' => [
                    'Centre Ville', 'Quartier Nord', 'Quartier Sud',
                    'Zone Industrielle', 'Université', 'Marché Central'
                ]
            ],
            [
                'name' => 'Divo',
                'region' => 'Gôh-Djiboua',
                'latitude' => 5.8374,
                'longitude' => -5.3572,
                'neighborhoods' => [
                    'Centre Ville', 'Quartier Nord', 'Quartier Sud',
                    'Zone Industrielle', 'Université', 'Marché Central'
                ]
            ],
            [
                'name' => 'Abengourou',
                'region' => 'Comoé',
                'latitude' => 6.7297,
                'longitude' => -3.4964,
                'neighborhoods' => [
                    'Centre Ville', 'Quartier Nord', 'Quartier Sud',
                    'Zone Industrielle', 'Université', 'Marché Central'
                ]
            ],
            [
                'name' => 'Agboville',
                'region' => 'Agneby-Tiassa',
                'latitude' => 5.9280,
                'longitude' => -4.2132,
                'neighborhoods' => [
                    'Centre Ville', 'Quartier Nord', 'Quartier Sud',
                    'Zone Industrielle', 'Université', 'Marché Central'
                ]
            ],
            [
                'name' => 'Grand-Bassam',
                'region' => 'Comoé',
                'latitude' => 5.2118,
                'longitude' => -3.7388,
                'neighborhoods' => [
                    'Centre Ville', 'Quartier Nord', 'Quartier Sud',
                    'Zone Touristique', 'Port', 'Marché Central'
                ]
            ]
        ];

        foreach ($cities as $cityData) {
            $neighborhoods = $cityData['neighborhoods'];
            unset($cityData['neighborhoods']);

            $city = City::create($cityData);

            // Créer les quartiers
            foreach ($neighborhoods as $neighborhoodName) {
                Neighborhood::create([
                    'city_id' => $city->id,
                    'name' => $neighborhoodName,
                    'is_active' => true,
                ]);
            }
        }
    }
}
