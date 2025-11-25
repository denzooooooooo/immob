@extends('layouts.app')

@section('title', 'Trop de requêtes - Monnkama')
@section('description', 'Vous avez effectué trop de requêtes. Veuillez patienter avant de réessayer.')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Illustration 429 -->
        <div class="mb-8">
            <div class="relative">
                <!-- Numéro 429 stylisé -->
                <div class="text-9xl md:text-[12rem] font-bold text-gray-200 select-none">
                    429
                </div>
                
                <!-- Icône de chronomètre au centre -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-24 h-24 bg-gradient-to-r from-gabon-yellow to-gabon-blue rounded-full flex items-center justify-center">
                        <i class="fas fa-stopwatch text-white text-3xl animate-pulse"></i>
                    </div>
                </div>
                
                <!-- Éléments décoratifs animés -->
                <div class="absolute top-1/4 left-1/4 w-8 h-8 bg-gabon-yellow rounded-full animate-ping opacity-60"></div>
                <div class="absolute top-1/3 right-1/4 w-6 h-6 bg-gabon-blue rounded-full animate-bounce opacity-40"></div>
                <div class="absolute bottom-1/4 left-1/3 w-10 h-10 bg-gabon-green rounded-full animate-pulse opacity-50"></div>
                
                <!-- Barres de progression animées -->
                <div class="absolute bottom-1/3 right-1/3">
                    <div class="flex space-x-1">
                        <div class="w-2 h-8 bg-gabon-green rounded animate-pulse"></div>
                        <div class="w-2 h-6 bg-gabon-blue rounded animate-pulse animation-delay-200"></div>
                        <div class="w-2 h-10 bg-gabon-yellow rounded animate-pulse animation-delay-400"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Message d'erreur -->
        <div class="mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Ralentissez un peu !
            </h1>
            <p class="text-xl text-gray-600 mb-4 max-w-2xl mx-auto">
                Vous avez effectué trop de requêtes en peu de temps.
            </p>
            <p class="text-lg text-gray-500 max-w-xl mx-auto">
                Veuillez patienter quelques instants avant de réessayer.
            </p>
        </div>
        
        <!-- Compteur de temps d'attente -->
        <div class="bg-white rounded-2xl shadow-lg p-8 max-w-md mx-auto mb-12">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">
                Temps d'attente
            </h3>
            
            <div class="flex items-center justify-center">
                <div class="text-center">
                    <div class="text-6xl font-bold text-gabon-blue mb-2" id="countdown-timer">
                        {{ $retryAfter ?? 60 }}
                    </div>
                    <div class="text-gray-600">secondes</div>
                </div>
            </div>
            
            <!-- Barre de progression -->
            <div class="mt-6">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="progress-bar" class="bg-gradient-to-r from-gabon-green to-gabon-blue h-2 rounded-full transition-all duration-1000" style="width: 100%"></div>
                </div>
            </div>
            
            <p class="text-sm text-gray-500 mt-4">
                La page se rechargera automatiquement
            </p>
        </div>
        
        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
            <button id="retry-btn" disabled
                    class="bg-gradient-to-r from-gabon-green to-gabon-blue text-white font-bold py-4 px-8 rounded-lg opacity-50 cursor-not-allowed transition-all duration-200 flex items-center justify-center">
                <i class="fas fa-redo-alt mr-2"></i>
                <span id="retry-text">Réessayer dans <span id="retry-countdown">{{ $retryAfter ?? 60 }}</span>s</span>
            </button>
            
            <a href="{{ route('home') }}" 
               class="bg-white border-2 border-gabon-blue text-gabon-blue font-bold py-4 px-8 rounded-lg hover:bg-gabon-blue hover:text-white transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <i class="fas fa-home mr-2"></i>
                Retour à l'accueil
            </a>
        </div>
        
        <!-- Informations sur les limites -->
        <div class="bg-white rounded-2xl shadow-lg p-8 max-w-2xl mx-auto">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">
                Pourquoi cette limitation ?
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-left">
                <div>
                    <div class="flex items-center mb-3">
                        <i class="fas fa-shield-alt text-gabon-green text-xl mr-3"></i>
                        <h4 class="font-semibold text-gray-900">Protection du service</h4>
                    </div>
                    <p class="text-gray-600 text-sm">
                        Ces limites protègent notre plateforme contre les abus et garantissent une expérience fluide pour tous.
                    </p>
                </div>
                
                <div>
                    <div class="flex items-center mb-3">
                        <i class="fas fa-tachometer-alt text-gabon-blue text-xl mr-3"></i>
                        <h4 class="font-semibold text-gray-900">Performance optimale</h4>
                    </div>
                    <p class="text-gray-600 text-sm">
                        En limitant les requêtes, nous maintenons des temps de réponse rapides pour tous les utilisateurs.
                    </p>
                </div>
                
                <div>
                    <div class="flex items-center mb-3">
                        <i class="fas fa-users text-gabon-yellow text-xl mr-3"></i>
                        <h4 class="font-semibold text-gray-900">Équité d'accès</h4>
                    </div>
                    <p class="text-gray-600 text-sm">
                        Chaque utilisateur a un accès équitable aux ressources de la plateforme.
                    </p>
                </div>
                
                <div>
                    <div class="flex items-center mb-3">
                        <i class="fas fa-clock text-gabon-green text-xl mr-3"></i>
                        <h4 class="font-semibold text-gray-900">Limites temporaires</h4>
                    </div>
                    <p class="text-gray-600 text-sm">
                        Ces restrictions se lèvent automatiquement après un court délai.
                    </p>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h4 class="font-semibold text-gray-900 mb-3">Limites actuelles :</h4>
                <ul class="text-gray-600 text-sm space-y-1">
                    <li>• API : 60 requêtes par minute</li>
                    <li>• Recherche : 100 requêtes par heure</li>
                    <li>• Connexion : 5 tentatives par minute</li>
                    <li>• Contact : 3 messages par heure</li>
                </ul>
            </div>
        </div>
        
        <!-- Conseils -->
        <div class="mt-12">
            <h3 class="text-xl font-bold text-gray-900 mb-6">
                Conseils pour éviter cette erreur
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow duration-200">
                    <i class="fas fa-pause text-gabon-green text-xl mb-2"></i>
                    <h4 class="font-semibold text-gray-900 mb-2">Prenez votre temps</h4>
                    <p class="text-gray-600 text-sm">Espacez vos actions de quelques secondes</p>
                </div>
                
                <div class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow duration-200">
                    <i class="fas fa-bookmark text-gabon-blue text-xl mb-2"></i>
                    <h4 class="font-semibold text-gray-900 mb-2">Utilisez les favoris</h4>
                    <p class="text-gray-600 text-sm">Sauvegardez les propriétés qui vous intéressent</p>
                </div>
                
                <div class="bg-white p-4 rounded-lg shadow hover:shadow-md transition-shadow duration-200">
                    <i class="fas fa-filter text-gabon-yellow text-xl mb-2"></i>
                    <h4 class="font-semibold text-gray-900 mb-2">Affinez vos recherches</h4>
                    <p class="text-gray-600 text-sm">Utilisez les filtres pour des résultats précis</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.animation-delay-200 {
    animation-delay: 0.2s;
}
.animation-delay-400 {
    animation-delay: 0.4s;
}

@keyframes countdown-pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.countdown-animation {
    animation: countdown-pulse 1s ease-in-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const initialTime = parseInt("{{ $retryAfter ?? 60 }}");
    let timeLeft = initialTime;
    
    const timerElement = document.getElementById('countdown-timer');
    const retryBtn = document.getElementById('retry-btn');
    const retryText = document.getElementById('retry-text');
    const retryCountdown = document.getElementById('retry-countdown');
    const progressBar = document.getElementById('progress-bar');
    
    function updateTimer() {
        timerElement.textContent = timeLeft;
        retryCountdown.textContent = timeLeft;
        
        // Mettre à jour la barre de progression
        const progressPercent = (timeLeft / initialTime) * 100;
        progressBar.style.width = progressPercent + '%';
        
        // Animation du compteur
        timerElement.classList.add('countdown-animation');
        setTimeout(() => {
            timerElement.classList.remove('countdown-animation');
        }, 1000);
        
        if (timeLeft <= 0) {
            // Activer le bouton et recharger la page
            retryBtn.disabled = false;
            retryBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            retryBtn.classList.add('hover:shadow-xl', 'transform', 'hover:scale-105');
            retryText.innerHTML = '<i class="fas fa-redo-alt mr-2"></i>Réessayer maintenant';
            
            // Recharger automatiquement après 2 secondes
            setTimeout(() => {
                window.location.reload();
            }, 2000);
            
            return;
        }
        
        timeLeft--;
    }
    
    // Mettre à jour toutes les secondes
    const interval = setInterval(updateTimer, 1000);
    
    // Gérer le clic sur le bouton retry
    retryBtn.addEventListener('click', function() {
        if (!this.disabled) {
            window.location.reload();
        }
    });
    
    // Arrêter le timer si l'utilisateur quitte la page
    window.addEventListener('beforeunload', function() {
        clearInterval(interval);
    });
});
</script>
@endsection
