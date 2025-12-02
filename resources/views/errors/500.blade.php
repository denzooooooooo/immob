@extends('layouts.app')

@section('title', 'Erreur serveur - Monnkama')
@section('description', 'Une erreur inattendue s\'est produite sur notre serveur.')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Illustration 500 -->
        <div class="mb-8">
            <div class="relative">
                <!-- Numéro 500 stylisé -->
                <div class="text-9xl md:text-[12rem] font-bold text-gray-200 select-none">
                    500
                </div>
                
                <!-- Icône d'erreur au centre -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-24 h-24 bg-gradient-to-r from-red-500 to-violet-600 rounded-full flex items-center justify-center animate-pulse">
                        <i class="fas fa-exclamation-triangle text-white text-3xl"></i>
                    </div>
                </div>
                
                <!-- Éléments décoratifs -->
                <div class="absolute top-1/4 left-1/4 w-8 h-8 bg-red-400 rounded-full animate-ping opacity-60"></div>
                <div class="absolute top-1/3 right-1/4 w-6 h-6 bg-violet-600 rounded-full animate-pulse opacity-40"></div>
                <div class="absolute bottom-1/4 left-1/3 w-10 h-10 bg-violet-600 rounded-full animate-bounce opacity-50"></div>
            </div>
        </div>
        
        <!-- Message d'erreur -->
        <div class="mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Oups ! Une erreur s'est produite
            </h1>
            <p class="text-xl text-gray-600 mb-4 max-w-2xl mx-auto">
                Nos équipes ont été notifiées et travaillent à résoudre le problème.
            </p>
            <p class="text-lg text-gray-500 max-w-xl mx-auto">
                En attendant, vous pouvez essayer de rafraîchir la page ou revenir à l'accueil.
            </p>
        </div>
        
        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
            <button onclick="window.location.reload()" 
                    class="bg-gradient-to-r from-violet-600 to-violet-600 text-white font-bold py-4 px-8 rounded-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <i class="fas fa-redo-alt mr-2"></i>
                Rafraîchir la page
            </button>
            
            <a href="{{ route('home') }}" 
               class="bg-white border-2 border-violet-600 text-violet-600 font-bold py-4 px-8 rounded-lg hover:bg-violet-600 hover:text-white transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <i class="fas fa-home mr-2"></i>
                Retour à l'accueil
            </a>
            
            <button onclick="history.back()" 
                    class="bg-gray-100 text-gray-700 font-bold py-4 px-8 rounded-lg hover:bg-gray-200 transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Page précédente
            </button>
        </div>
        
        <!-- Informations de support -->
        <div class="bg-white rounded-2xl shadow-lg p-8 max-w-2xl mx-auto">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">
                Besoin d'aide ?
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="text-left">
                    <h4 class="font-semibold text-gray-900 mb-2">
                        <i class="fas fa-envelope text-violet-600 mr-2"></i>
                        Par email
                    </h4>
                    <p class="text-gray-600">
                        Contactez notre support à
                        <a href="mailto:support@monnkama.ga" class="text-violet-600 hover:underline">
                            support@monnkama.ga
                        </a>
                    </p>
                </div>
                
                <div class="text-left">
                    <h4 class="font-semibold text-gray-900 mb-2">
                        <i class="fas fa-phone text-violet-600 mr-2"></i>
                        Par téléphone
                    </h4>
                    <p class="text-gray-600">
                        Appelez-nous au
                        <a href="tel:+24106052263" class="text-violet-600 hover:underline">
                            +241 06 05 22 63
                        </a>
                    </p>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-gray-500 text-sm">
                    Si le problème persiste, n'hésitez pas à nous contacter en mentionnant le code d'erreur : 
                    <span class="font-mono bg-gray-100 px-2 py-1 rounded">
                        {{ request()->session()->get('error_reference', 'ERR-' . time()) }}
                    </span>
                </p>
            </div>
        </div>
        
        <!-- Liens rapides -->
        <div class="mt-12">
            <h3 class="text-xl font-bold text-gray-900 mb-6">
                En attendant, vous pouvez...
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <a href="{{ route('properties.index') }}" 
                   class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow duration-200 flex items-center">
                    <i class="fas fa-search text-violet-600 text-xl mr-3"></i>
                    <span class="text-gray-700">Parcourir les propriétés</span>
                </a>
                
                <a href="{{ route('contact') }}" 
                   class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow duration-200 flex items-center">
                    <i class="fas fa-envelope text-violet-600 text-xl mr-3"></i>
                    <span class="text-gray-700">Nous contacter</span>
                </a>
                
                <a href="{{ route('about') }}" 
                   class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow duration-200 flex items-center">
                    <i class="fas fa-info-circle text-violet-400 text-xl mr-3"></i>
                    <span class="text-gray-700">En savoir plus</span>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.bg-gradient-animate {
    background-size: 200% 200%;
    animation: gradient 15s ease infinite;
}
</style>
@endsection
