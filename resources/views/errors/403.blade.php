@extends('layouts.app')

@section('title', 'Accès interdit - Monnkama')
@section('description', 'Vous n\'avez pas les autorisations nécessaires pour accéder à cette page.')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Illustration 403 -->
        <div class="mb-8">
            <div class="relative">
                <!-- Numéro 403 stylisé -->
                <div class="text-9xl md:text-[12rem] font-bold text-gray-200 select-none">
                    403
                </div>
                
                <!-- Icône de cadenas au centre -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-24 h-24 bg-gradient-to-r from-red-500 to-violet-600 rounded-full flex items-center justify-center animate-pulse">
                        <i class="fas fa-lock text-white text-3xl"></i>
                    </div>
                </div>
                
                <!-- Éléments décoratifs -->
                <div class="absolute top-1/4 left-1/4 w-8 h-8 bg-red-400 rounded-full animate-bounce opacity-60"></div>
                <div class="absolute top-1/3 right-1/4 w-6 h-6 bg-violet-600 rounded-full animate-pulse opacity-40"></div>
                <div class="absolute bottom-1/4 left-1/3 w-10 h-10 bg-violet-600 rounded-full animate-ping opacity-50"></div>
            </div>
        </div>
        
        <!-- Message d'erreur -->
        <div class="mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Accès interdit
            </h1>
            <p class="text-xl text-gray-600 mb-4 max-w-2xl mx-auto">
                Vous n'avez pas les autorisations nécessaires pour accéder à cette page.
            </p>
            <p class="text-lg text-gray-500 max-w-xl mx-auto">
                Si vous pensez qu'il s'agit d'une erreur, veuillez contacter l'administrateur.
            </p>
        </div>
        
        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" 
                       class="bg-gradient-to-r from-violet-600 to-violet-600 text-white font-bold py-4 px-8 rounded-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        Tableau de bord Admin
                    </a>
                @elseif(auth()->user()->role === 'agent')
                    <a href="{{ route('agent.dashboard') }}" 
                       class="bg-gradient-to-r from-violet-600 to-violet-600 text-white font-bold py-4 px-8 rounded-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-tachometer-alt mr-2"></i>
                        Tableau de bord Agent
                    </a>
                @else
                    <a href="{{ route('home') }}" 
                       class="bg-gradient-to-r from-violet-600 to-violet-600 text-white font-bold py-4 px-8 rounded-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-home mr-2"></i>
                        Retour à l'accueil
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}" 
                   class="bg-gradient-to-r from-violet-600 to-violet-600 text-white font-bold py-4 px-8 rounded-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Se connecter
                </a>
            @endauth
            
            <a href="{{ route('home') }}" 
               class="bg-white border-2 border-violet-600 text-violet-600 font-bold py-4 px-8 rounded-lg hover:bg-violet-600 hover:text-white transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <i class="fas fa-home mr-2"></i>
                Accueil
            </a>
            
            <button onclick="history.back()" 
                    class="bg-gray-100 text-gray-700 font-bold py-4 px-8 rounded-lg hover:bg-gray-200 transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Page précédente
            </button>
        </div>
        
        <!-- Informations sur les rôles -->
        <div class="bg-white rounded-2xl shadow-lg p-8 max-w-2xl mx-auto">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">
                Niveaux d'accès sur Monnkama
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-violet-600 to-violet-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user text-white text-2xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">Client</h4>
                    <p class="text-gray-600 text-sm">
                        Recherche et consultation des propriétés, gestion des favoris
                    </p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-violet-600 to-violet-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-tie text-white text-2xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">Agent</h4>
                    <p class="text-gray-600 text-sm">
                        Gestion des propriétés, messages clients, statistiques
                    </p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-violet-400 to-violet-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-crown text-white text-2xl"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-2">Admin</h4>
                    <p class="text-gray-600 text-sm">
                        Administration complète de la plateforme
                    </p>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-gray-500 text-sm">
                    Pour obtenir des privilèges supplémentaires, contactez-nous à 
                    <a href="mailto:admin@monnkama.ga" class="text-violet-600 hover:underline">
                        admin@monnkama.ga
                    </a>
                </p>
            </div>
        </div>
        
        <!-- Contact support -->
        <div class="mt-12">
            <h3 class="text-xl font-bold text-gray-900 mb-4">
                Besoin d'aide ?
            </h3>
            
            <div class="flex justify-center space-x-6">
                <a href="mailto:support@monnkama.ga" 
                   class="text-gray-600 hover:text-violet-600 transition-colors duration-200 flex items-center">
                    <i class="fas fa-envelope mr-2"></i>
                    Support par email
                </a>
                
                <a href="tel:+24106052263" 
                   class="text-gray-600 hover:text-violet-600 transition-colors duration-200 flex items-center">
                    <i class="fas fa-phone mr-2"></i>
                    +241 06 05 22 63
                </a>
                
                <a href="{{ route('contact') }}" 
                   class="text-gray-600 hover:text-violet-600 transition-colors duration-200 flex items-center">
                    <i class="fas fa-comment mr-2"></i>
                    Formulaire de contact
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
