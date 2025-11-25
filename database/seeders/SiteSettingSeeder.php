<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            // Paramètres généraux
            [
                'key' => 'site_name',
                'value' => 'Monnkama Immobilier',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Nom du site',
                'description' => 'Le nom qui apparaît dans le titre du site'
            ],
            [
                'key' => 'site_description',
                'value' => 'Votre partenaire de confiance pour l\'immobilier au Cameroun',
                'type' => 'text',
                'group' => 'general',
                'label' => 'Description du site',
                'description' => 'Une brève description du site pour les moteurs de recherche'
            ],

            // Section Hero
            [
                'key' => 'hero_title',
                'value' => 'Trouvez votre bien immobilier idéal',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Titre principal',
                'description' => 'Le titre principal de la page d\'accueil'
            ],
            [
                'key' => 'hero_subtitle',
                'value' => 'Des milliers de propriétés vous attendent',
                'type' => 'text',
                'group' => 'hero',
                'label' => 'Sous-titre',
                'description' => 'Le sous-titre sous le titre principal'
            ],
            [
                'key' => 'hero_image',
                'value' => null,
                'type' => 'image',
                'group' => 'hero',
                'label' => 'Image d\'arrière-plan',
                'description' => 'L\'image de fond de la section hero (1920x1080px recommandé)'
            ],

            // Contact
            [
                'key' => 'contact_email',
                'value' => 'contact@monnkama.com',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Email de contact',
                'description' => 'L\'adresse email principale de contact'
            ],
            [
                'key' => 'contact_phone',
                'value' => '+237 123 456 789',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Téléphone',
                'description' => 'Le numéro de téléphone principal'
            ],
            [
                'key' => 'contact_address',
                'value' => 'Douala, Cameroun',
                'type' => 'text',
                'group' => 'contact',
                'label' => 'Adresse',
                'description' => 'L\'adresse physique de l\'entreprise'
            ],

            // Réseaux sociaux
            [
                'key' => 'facebook_url',
                'value' => 'https://facebook.com/monnkama',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Facebook',
                'description' => 'URL de la page Facebook'
            ],
            [
                'key' => 'twitter_url',
                'value' => 'https://twitter.com/monnkama',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Twitter',
                'description' => 'URL du compte Twitter'
            ],
            [
                'key' => 'instagram_url',
                'value' => 'https://instagram.com/monnkama',
                'type' => 'text',
                'group' => 'social',
                'label' => 'Instagram',
                'description' => 'URL du compte Instagram'
            ],

            // Fonctionnalités
            [
                'key' => 'enable_blog',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'features',
                'label' => 'Activer le blog',
                'description' => 'Afficher la section blog sur le site'
            ],
            [
                'key' => 'enable_newsletter',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'features',
                'label' => 'Activer la newsletter',
                'description' => 'Afficher le formulaire d\'inscription à la newsletter'
            ],
            [
                'key' => 'enable_testimonials',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'features',
                'label' => 'Activer les témoignages',
                'description' => 'Afficher la section témoignages sur la page d\'accueil'
            ]
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
