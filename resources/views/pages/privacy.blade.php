@extends('layouts.app')

@section('title', 'Politique de confidentialité - ' . ($siteSettings['site_name'] ?? 'Monnkama'))
@section('description', 'Notre politique de confidentialité détaille comment nous protégeons vos données personnelles')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-8">Politique de confidentialité</h1>
            
            <div class="space-y-8">
                <section>
                    <h2 class="text-2xl font-bold text-gabon-blue mb-4">1. Introduction</h2>
                    <p class="text-gray-600 leading-relaxed">
                        {{ $siteSettings['site_name'] ?? 'Monnkama' }} s'engage à protéger votre vie privée. Cette politique de confidentialité explique comment nous collectons, utilisons et protégeons vos données personnelles lorsque vous utilisez notre plateforme immobilière.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gabon-blue mb-4">2. Données collectées</h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Nous collectons les informations suivantes :
                    </p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <li>Informations d'identification : nom, prénom, adresse e-mail</li>
                        <li>Informations de contact : numéro de téléphone, adresse postale</li>
                        <li>Données de navigation : adresse IP, cookies, pages visitées</li>
                        <li>Préférences de recherche : critères de propriétés, favoris</li>
                        <li>Informations de transaction : historique des achats ou locations</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gabon-blue mb-4">3. Utilisation des données</h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Nous utilisons vos données pour :
                    </p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <li>Fournir nos services immobiliers</li>
                        <li>Personnaliser votre expérience utilisateur</li>
                        <li>Vous envoyer des notifications pertinentes</li>
                        <li>Améliorer notre plateforme</li>
                        <li>Respecter nos obligations légales</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gabon-blue mb-4">4. Cookies</h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Nous utilisons des cookies pour :
                    </p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <li>Mémoriser vos préférences de navigation</li>
                        <li>Analyser le trafic de notre site</li>
                        <li>Personnaliser le contenu affiché</li>
                        <li>Améliorer la sécurité de votre compte</li>
                    </ul>
                    <p class="text-gray-600 leading-relaxed mt-4">
                        Vous pouvez désactiver les cookies dans les paramètres de votre navigateur, mais cela peut affecter le fonctionnement de notre site.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gabon-blue mb-4">5. Partage des données</h2>
                    <p class="text-gray-600 leading-relaxed">
                        Nous ne vendons jamais vos données personnelles. Nous pouvons partager vos informations uniquement avec :
                    </p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2 mt-4">
                        <li>Nos partenaires immobiliers agréés</li>
                        <li>Les prestataires de services techniques</li>
                        <li>Les autorités légales si requis par la loi</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gabon-blue mb-4">6. Sécurité des données</h2>
                    <p class="text-gray-600 leading-relaxed">
                        Nous mettons en place des mesures de sécurité techniques et organisationnelles pour protéger vos données contre tout accès non autorisé, modification, divulgation ou destruction.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gabon-blue mb-4">7. Vos droits</h2>
                    <p class="text-gray-600 leading-relaxed mb-4">
                        Vous disposez des droits suivants :
                    </p>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <li>Droit d'accès à vos données personnelles</li>
                        <li>Droit de rectification des données inexactes</li>
                        <li>Droit à l'effacement de vos données</li>
                        <li>Droit à la portabilité de vos données</li>
                        <li>Droit d'opposition au traitement</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gabon-blue mb-4">8. Conservation des données</h2>
                    <p class="text-gray-600 leading-relaxed">
                        Nous conservons vos données personnelles pendant la durée nécessaire aux finalités pour lesquelles elles ont été collectées, conformément à la législation gabonaise en vigueur.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gabon-blue mb-4">9. Contact</h2>
                    <p class="text-gray-600 leading-relaxed">
                        Pour toute question concernant cette politique de confidentialité ou pour exercer vos droits, contactez-nous :
                    </p>
                    <div class="bg-gray-50 p-4 rounded-lg mt-4">
                        <p class="text-gray-600">
                            <strong>Email :</strong> privacy@{{ strtolower($siteSettings['site_name'] ?? 'monnkama') }}.com<br>
                            <strong>Téléphone :</strong> +241 01 23 45 67<br>
                            <strong>Adresse :</strong> Libreville, Gabon
                        </p>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gabon-blue mb-4">10. Modifications</h2>
                    <p class="text-gray-600 leading-relaxed">
                        Cette politique de confidentialité peut être modifiée à tout moment. Les modifications seront publiées sur cette page avec la date de mise à jour.
                    </p>
                    <p class="text-sm text-gray-500 mt-4">
                        <strong>Dernière mise à jour :</strong> {{ date('d/m/Y') }}
                    </p>
                </section>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('home') }}" class="bg-gabon-blue text-white px-6 py-3 rounded-lg hover:bg-gabon-green transition-colors duration-200 text-center">
                        Retour à l'accueil
                    </a>
                    <a href="{{ route('contact') }}" class="border border-gabon-blue text-gabon-blue px-6 py-3 rounded-lg hover:bg-gabon-blue hover:text-white transition-colors duration-200 text-center">
                        Nous contacter
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
