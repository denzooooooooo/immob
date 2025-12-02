@extends('layouts.app')

@section('title', 'Page non trouvée - Monnkama')
@section('description', 'La page que vous recherchez n\'existe pas ou a été déplacée.')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Illustration 404 -->
        <div class="mb-8">
            <div class="relative">
                <!-- Numéro 404 stylisé -->
                <div class="text-9xl md:text-[12rem] font-bold text-gray-200 select-none">
                    404
                </div>
                
                <!-- Icône de maison au centre -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-24 h-24 bg-gradient-to-r from-violet-600 to-violet-600 rounded-full flex items-center justify-center animate-bounce">
                        <i class="fas fa-home text-white text-3xl"></i>
                    </div>
                </div>
                
                <!-- Éléments décoratifs flottants -->
                <div class="absolute top-10 left-10 w-8 h-8 bg-violet-400 rounded-full animate-pulse opacity-60"></div>
                <div class="absolute top-20 right-16 w-6 h-6 bg-violet-600 rounded-full animate-pulse opacity-40 animation-delay-1000"></div>
                <div class="absolute bottom-16 left-20 w-10 h-10 bg-violet-600 rounded-full animate-pulse opacity-50 animation-delay-2000"></div>
                <div class="absolute bottom-10 right-10 w-4 h-4 bg-violet-400 rounded-full animate-pulse opacity-70 animation-delay-1500"></div>
            </div>
        </div>
        
        <!-- Titre et description -->
        <div class="mb-8">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Oups ! Page non trouvée
            </h1>
            <p class="text-xl text-gray-600 mb-2 max-w-2xl mx-auto">
                La page que vous recherchez n'existe pas ou a été déplacée.
            </p>
            <p class="text-lg text-gray-500 max-w-xl mx-auto">
                Mais ne vous inquiétez pas, nous avons plein d'autres belles propriétés à vous montrer !
            </p>
        </div>
        
        <!-- Suggestions d'actions -->
        <div class="mb-12">
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}" 
                   class="bg-gradient-to-r from-violet-600 to-violet-600 text-white font-bold py-4 px-8 rounded-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-home mr-2"></i>
                    Retour à l'accueil
                </a>
                
                <a href="{{ route('properties.index') }}" 
                   class="bg-white border-2 border-violet-600 text-violet-600 font-bold py-4 px-8 rounded-lg hover:bg-violet-600 hover:text-white transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i>
                    Voir les propriétés
                </a>
                
                <button onclick="history.back()" 
                        class="bg-gray-100 text-gray-700 font-bold py-4 px-8 rounded-lg hover:bg-gray-200 transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Page précédente
                </button>
            </div>
        </div>
        
        <!-- Recherche rapide -->
        <div class="bg-white rounded-2xl shadow-lg p-8 max-w-2xl mx-auto">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">
                Ou recherchez directement ce que vous cherchez
            </h3>
            
            <form action="{{ route('search.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <select name="type" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-violet-600 focus:border-transparent">
                        <option value="">Type de bien</option>
                        <option value="house">Maison</option>
                        <option value="apartment">Appartement</option>
                        <option value="land">Terrain</option>
                        <option value="commercial">Commercial</option>
                    </select>
                    
                    <select name="city" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-violet-600 focus:border-transparent">
                        <option value="">Ville</option>
                        <option value="libreville">Libreville</option>
                        <option value="port-gentil">Port-Gentil</option>
                        <option value="franceville">Franceville</option>
                        <option value="oyem">Oyem</option>
                    </select>
                </div>
                
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-violet-600 to-violet-600 text-white font-bold py-3 px-6 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                    <i class="fas fa-search mr-2"></i>
                    Rechercher
                </button>
            </form>
        </div>
        
        <!-- Propriétés populaires -->
        <div class="mt-16">
            <h3 class="text-2xl font-bold text-gray-900 mb-8">
                Propriétés populaires
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Ces données pourraient être dynamiques -->
                <a href="{{ route('properties.index') }}" class="group">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden group-hover:shadow-xl transform group-hover:scale-105 transition-all duration-200">
                        <div class="h-48 bg-gradient-to-br from-violet-600 to-violet-600 flex items-center justify-center">
                            <i class="fas fa-home text-white text-4xl"></i>
                        </div>
                        <div class="p-4">
                            <h4 class="font-bold text-gray-900 mb-2">Maisons à Libreville</h4>
                            <p class="text-gray-600 text-sm">Découvrez nos maisons disponibles</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('properties.index') }}" class="group">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden group-hover:shadow-xl transform group-hover:scale-105 transition-all duration-200">
                        <div class="h-48 bg-gradient-to-br from-violet-600 to-violet-400 flex items-center justify-center">
                            <i class="fas fa-building text-white text-4xl"></i>
                        </div>
                        <div class="p-4">
                            <h4 class="font-bold text-gray-900 mb-2">Appartements modernes</h4>
                            <p class="text-gray-600 text-sm">Appartements tout équipés</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('properties.index') }}" class="group">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden group-hover:shadow-xl transform group-hover:scale-105 transition-all duration-200">
                        <div class="h-48 bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center">
                            <i class="fas fa-map text-white text-4xl"></i>
                        </div>
                        <div class="p-4">
                            <h4 class="font-bold text-gray-900 mb-2">Terrains à bâtir</h4>
                            <p class="text-gray-600 text-sm">Terrains dans toutes les villes</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .animation-delay-1000 {
        animation-delay: 1s;
    }
    .animation-delay-1500 {
        animation-delay: 1.5s;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
</style>
@endsection
