@extends('layouts.app')

@section('title', 'À propos - Carre Premium Immo')
@section('description', 'Découvrez Carre Premium Immo, la première plateforme immobilière 100% ivoirienne. Notre mission est de faciliter vos transactions immobilières en Côte d\'Ivoire.')

@section('content')
<!-- Hero Section -->
<section class="relative py-24 overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-violet-600 to-red-500"></div>

    <!-- Decorative elements -->
    <div class="absolute inset-0">
        <div class="absolute top-20 left-10 w-40 h-40 bg-violet-100/20 rounded-full blur-2xl animate-pulse"></div>
        <div class="absolute top-40 right-20 w-32 h-32 bg-violet-200/15 rounded-full blur-xl animate-bounce"></div>
        <div class="absolute bottom-20 left-1/4 w-48 h-48 bg-violet-50/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-violet-100/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-5xl md:text-7xl font-bold text-white mb-8 leading-tight">
            À propos de
            <span class="block bg-gradient-to-r from-white to-gray-100 bg-clip-text text-transparent">
                Carre Premium Immo
            </span>
        </h1>
        <p class="text-xl md:text-2xl text-white/90 mb-12 max-w-4xl mx-auto leading-relaxed">
            La première plateforme immobilière 100% ivoirienne, dédiée à votre réussite immobilière
        </p>
    </div>
</section>

<!-- Mission Section -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-8 leading-tight">
                    Notre
                    <span class="block bg-gradient-to-r from-violet-600 to-violet-800 bg-clip-text text-transparent">
                        mission
                    </span>
                </h2>
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    Chez Carre Premium Immo, notre mission est de révolutionner le marché immobilier ivoirien en offrant une plateforme moderne, transparente et efficace pour connecter propriétaires, agents et acheteurs/locataires.
                </p>
                <p class="text-xl text-gray-600 leading-relaxed">
                    Nous croyons que chaque Ivoirien mérite de trouver facilement son chez-soi idéal, et que chaque propriétaire devrait pouvoir gérer ses biens en toute simplicité.
                </p>
            </div>

            <div class="relative">
                <!-- Animated background -->
                <div class="absolute inset-0 bg-gradient-to-br from-white via-violet-50/20 to-white rounded-3xl animate-pulse"></div>

                <div class="relative grid grid-cols-2 gap-8 p-8">
                    <div class="bg-gradient-to-br from-violet-600 to-violet-800 p-8 rounded-3xl text-white shadow-xl transform hover:scale-105 transition-all duration-300 animate-fade-in-up">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                            <i class="fas fa-home text-3xl"></i>
                        </div>
                        <div class="text-4xl font-bold mb-3">{{ $stats['total_properties'] ?? 0 }}</div>
                        <div class="text-lg opacity-90 font-medium">Propriétés listées</div>
                    </div>

                    <div class="bg-gradient-to-br from-violet-500 to-violet-700 p-8 rounded-3xl text-white shadow-xl transform hover:scale-105 transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.1s">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                            <i class="fas fa-city text-3xl"></i>
                        </div>
                        <div class="text-4xl font-bold mb-3">{{ $stats['total_cities'] ?? 0 }}</div>
                        <div class="text-lg opacity-90 font-medium">Villes couvertes</div>
                    </div>

                    <div class="bg-gradient-to-br from-violet-400 to-violet-600 p-8 rounded-3xl text-white shadow-xl transform hover:scale-105 transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.2s">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                            <i class="fas fa-users text-3xl"></i>
                        </div>
                        <div class="text-4xl font-bold mb-3">{{ $stats['total_agents'] ?? 0 }}</div>
                        <div class="text-lg opacity-90 font-medium">Agents certifiés</div>
                    </div>

                    <div class="bg-gradient-to-br from-violet-300 to-violet-500 p-8 rounded-3xl text-white shadow-xl transform hover:scale-105 transition-all duration-300 animate-fade-in-up" style="animation-delay: 0.3s">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                            <i class="fas fa-user-check text-3xl"></i>
                        </div>
                        <div class="text-4xl font-bold mb-3">{{ $stats['total_users'] ?? 0 }}</div>
                        <div class="text-lg opacity-90 font-medium">Utilisateurs actifs</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-20 bg-gradient-to-br from-violet-50/30 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                Nos
                <span class="block bg-gradient-to-r from-violet-600 to-violet-800 bg-clip-text text-transparent">
                    valeurs
                </span>
            </h2>
            <p class="text-xl md:text-2xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                Des principes qui guident chacune de nos actions pour votre satisfaction
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="bg-white p-10 rounded-3xl shadow-xl hover:shadow-2xl hover:shadow-violet-500/10 transition-all duration-500 transform hover:-translate-y-2 border border-violet-100/50">
                <div class="w-20 h-20 bg-gradient-to-br from-violet-600 to-violet-800 rounded-2xl flex items-center justify-center mb-8 shadow-lg">
                    <i class="fas fa-shield-alt text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Confiance & Sécurité</h3>
                <p class="text-lg text-gray-600 leading-relaxed">
                    Nous vérifions rigoureusement chaque propriété et agent pour garantir des transactions sûres et transparentes.
                </p>
            </div>

            <div class="bg-white p-10 rounded-3xl shadow-xl hover:shadow-2xl hover:shadow-violet-500/10 transition-all duration-500 transform hover:-translate-y-2 border border-violet-100/50">
                <div class="w-20 h-20 bg-gradient-to-br from-violet-500 to-violet-700 rounded-2xl flex items-center justify-center mb-8 shadow-lg">
                    <i class="fas fa-star text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Excellence</h3>
                <p class="text-lg text-gray-600 leading-relaxed">
                    Nous nous efforçons d'offrir le meilleur service possible à nos utilisateurs, avec une attention particulière aux détails.
                </p>
            </div>

            <div class="bg-white p-10 rounded-3xl shadow-xl hover:shadow-2xl hover:shadow-violet-500/10 transition-all duration-500 transform hover:-translate-y-2 border border-violet-100/50">
                <div class="w-20 h-20 bg-gradient-to-br from-violet-400 to-violet-600 rounded-2xl flex items-center justify-center mb-8 shadow-lg">
                    <i class="fas fa-handshake text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Innovation Locale</h3>
                <p class="text-lg text-gray-600 leading-relaxed">
                    Nous développons des solutions adaptées aux besoins spécifiques du marché immobilier ivoirien.
                </p>
            </div>
        </div>
    </div>
</section>


        

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-br from-violet-600 to-violet-800 text-white relative overflow-hidden">
    <!-- Background decoration -->
    <div class="absolute inset-0">
        <div class="absolute top-10 left-10 w-32 h-32 bg-white/10 rounded-full blur-xl animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-24 h-24 bg-violet-300/20 rounded-full blur-lg animate-bounce"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-violet-400/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl md:text-5xl font-bold mb-8 leading-tight">
            Prêt à commencer votre recherche ?
        </h2>
        <p class="text-xl md:text-2xl mb-12 opacity-90 max-w-3xl mx-auto leading-relaxed">
            Rejoignez des milliers d'Ivoiriens qui ont déjà trouvé leur bonheur sur Carre Premium Immo
        </p>

        <div class="flex flex-col sm:flex-row gap-6 justify-center">
            <a href="{{ route('properties.index') }}"
               class="bg-white text-violet-700 font-bold py-5 px-10 rounded-2xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center shadow-xl">
                <i class="fas fa-search mr-3 text-xl"></i>
                <span class="text-lg">Rechercher un bien</span>
            </a>
            <a href="{{ route('contact') }}"
               class="border-2 border-white text-white font-bold py-5 px-10 rounded-2xl hover:bg-white hover:text-violet-700 transform hover:scale-105 transition-all duration-300 flex items-center justify-center shadow-xl">
                <i class="fas fa-envelope mr-3 text-xl"></i>
                <span class="text-lg">Nous contacter</span>
            </a>
        </div>
    </div>
</section>
@endsection
