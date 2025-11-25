<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer l'administrateur principal
        User::create([
            'name' => 'Administrateur Monnkama',
            'email' => 'admin@monnkama.ga',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '+241 01 02 03 04',
            'status' => 'active',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'email_notifications' => true,
            'sms_notifications' => true,
        ]);

        // Créer quelques agents de test
        $agents = [
            [
                'name' => 'Jean-Claude Mbadinga',
                'email' => 'jc.mbadinga@monnkama.ga',
                'phone' => '+241 06 12 34 56',
                'company_name' => 'Immobilier Gabon Plus',
                'city' => 'libreville',
                'bio' => 'Agent immobilier expérimenté spécialisé dans les propriétés de luxe à Libreville.',
            ],
            [
                'name' => 'Marie-Claire Nzamba',
                'email' => 'mc.nzamba@monnkama.ga',
                'phone' => '+241 07 23 45 67',
                'company_name' => 'Port-Gentil Properties',
                'city' => 'port-gentil',
                'bio' => 'Experte en immobilier commercial et résidentiel à Port-Gentil.',
            ],
            [
                'name' => 'Pierre Obame',
                'email' => 'p.obame@monnkama.ga',
                'phone' => '+241 05 34 56 78',
                'company_name' => 'Franceville Immobilier',
                'city' => 'franceville',
                'bio' => 'Spécialiste de l\'immobilier dans la région du Haut-Ogooué.',
            ]
        ];

        foreach ($agents as $agentData) {
            User::create([
                'name' => $agentData['name'],
                'email' => $agentData['email'],
                'password' => Hash::make('password123'),
                'role' => 'agent',
                'phone' => $agentData['phone'],
                'status' => 'active',
                'company_name' => $agentData['company_name'],
                'city' => $agentData['city'],
                'bio' => $agentData['bio'],
                'email_verified_at' => now(),
                'phone_verified_at' => now(),
                'email_notifications' => true,
                'sms_notifications' => false,
            ]);
        }

        // Créer quelques clients de test
        $clients = [
            [
                'name' => 'Sylvie Ondo',
                'email' => 'sylvie.ondo@gmail.com',
                'phone' => '+241 06 78 90 12',
                'city' => 'libreville',
            ],
            [
                'name' => 'Michel Nguema',
                'email' => 'michel.nguema@yahoo.fr',
                'phone' => '+241 07 89 01 23',
                'city' => 'port-gentil',
            ],
            [
                'name' => 'Fatou Diallo',
                'email' => 'fatou.diallo@hotmail.com',
                'phone' => '+241 05 90 12 34',
                'city' => 'franceville',
            ],
            [
                'name' => 'André Mba',
                'email' => 'andre.mba@gmail.com',
                'phone' => '+241 06 01 23 45',
                'city' => 'oyem',
            ],
            [
                'name' => 'Christelle Eyeghe',
                'email' => 'christelle.eyeghe@yahoo.fr',
                'phone' => '+241 07 12 34 56',
                'city' => 'libreville',
            ]
        ];

        foreach ($clients as $clientData) {
            User::create([
                'name' => $clientData['name'],
                'email' => $clientData['email'],
                'password' => Hash::make('password123'),
                'role' => 'client',
                'phone' => $clientData['phone'],
                'status' => 'active',
                'city' => $clientData['city'],
                'email_verified_at' => now(),
                'email_notifications' => true,
                'sms_notifications' => false,
            ]);
        }
    }
}
