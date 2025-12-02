@extends('layouts.app')

@section('title', 'Site en maintenance - Monnkama')
@section('description', 'Notre site est actuellement en maintenance. Nous serons bientôt de retour !')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Illustration Maintenance -->
        <div class="mb-8">
            <div class="relative">
                <!-- Texte 503 stylisé -->
                <div class="text-9xl md:text-[12rem] font-bold text-gray-200 select-none">
                    503
                </div>
                
                <!-- Icône d'outils au centre -->
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-24 h-24 bg-gradient-to-r from-violet-400 to-violet-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-tools text-white text-3xl animate-bounce"></i>
                    </div>
                </div>
                
                <!-- Éléments décoratifs animés -->
                <div class="absolute top-1/4 left-1/4 transform rotate-45">
                    <i class="fas fa-wrench text-violet-600 text-3xl animate-spin-slow"></i>
                </div>
                <div class="absolute bottom-1/4 right-1/4 transform -rotate-45">
                    <i class="fas fa-cog text-violet-600 text-4xl animate-spin-slow"></i>
                </div>
            </div>
        </div>
        
        <!-- Message de maintenance -->
        <div class="mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Site en maintenance
            </h1>
            <p class="text-xl text-gray-600 mb-4 max-w-2xl mx-auto">
                Nous effectuons actuellement une maintenance programmée pour améliorer votre expérience.
            </p>
            <p class="text-lg text-gray-500 max-w-xl mx-auto">
                Nous serons de retour très bientôt avec de nouvelles fonctionnalités !
            </p>
        </div>
        
        <!-- Temps estimé -->
        <div class="bg-white rounded-2xl shadow-lg p-8 max-w-2xl mx-auto mb-12">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">
                Durée estimée
            </h3>
            
            <div class="flex items-center justify-center space-x-8">
                <div class="text-center">
                    <div class="text-4xl font-bold text-violet-600 mb-2" id="maintenance-timer">
                        {{ $estimatedMinutes ?? 30 }}
                    </div>
                    <div class="text-gray-600">minutes</div>
                </div>
                
                <div class="h-16 w-px bg-gray-200"></div>
                
                <div class="text-center">
                    <div class="text-xl font-semibold text-gray-900 mb-2">
                        Retour prévu
                    </div>
                    <div class="text-violet-600 font-medium" id="estimated-time">
                        {{ now()->addMinutes($estimatedMinutes ?? 30)->format('H:i') }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Options de notification -->
        <div class="bg-gradient-to-r from-violet-600/10 to-violet-600/10 rounded-2xl p-8 max-w-2xl mx-auto mb-12">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">
                Être notifié de la reprise
            </h3>
            
            <form id="notification-form" class="space-y-4">
                <div class="flex flex-col sm:flex-row gap-4">
                    <input type="email" 
                           placeholder="Votre adresse email" 
                           class="flex-1 px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-violet-600 focus:border-transparent">
                    
                    <button type="submit" 
                            class="bg-gradient-to-r from-violet-600 to-violet-600 text-white font-bold py-3 px-6 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                        Me notifier
                    </button>
                </div>
                
                <p class="text-sm text-gray-500">
                    Nous vous enverrons un email dès que le site sera de nouveau accessible.
                </p>
            </form>
        </div>
        
        <!-- Réseaux sociaux -->
        <div class="text-center">
            <h3 class="text-xl font-bold text-gray-900 mb-4">
                Suivez-nous pour les dernières actualités
            </h3>
            
            <div class="flex justify-center space-x-6">
                <a href="#" class="text-gray-400 hover:text-violet-600 transition-colors duration-200">
                    <i class="fab fa-facebook-f text-2xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-violet-600 transition-colors duration-200">
                    <i class="fab fa-twitter text-2xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-violet-600 transition-colors duration-200">
                    <i class="fab fa-instagram text-2xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-violet-600 transition-colors duration-200">
                    <i class="fab fa-linkedin-in text-2xl"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes spin-slow {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.animate-spin-slow {
    animation: spin-slow 3s linear infinite;
}

/* Timer countdown animation */
@keyframes countdown {
    from {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    to {
        transform: scale(1);
    }
}

.countdown-animation {
    animation: countdown 1s ease-in-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du formulaire de notification
    const form = document.getElementById('notification-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = form.querySelector('input[type="email"]').value;
        
        // Simuler l'enregistrement
        form.innerHTML = `
            <div class="text-violet-600 flex items-center justify-center">
                <i class="fas fa-check-circle mr-2"></i>
                Vous serez notifié à ${email}
            </div>
        `;
    });
    
    // Animation du compteur
    const timer = document.getElementById('maintenance-timer');
    let minutes = parseInt(timer.textContent);
    
    function updateTimer() {
        if (minutes > 0) {
            minutes--;
            timer.textContent = minutes;
            timer.classList.add('countdown-animation');
            
            setTimeout(() => {
                timer.classList.remove('countdown-animation');
            }, 1000);
        }
    }
    
    // Mettre à jour toutes les minutes
    setInterval(updateTimer, 60000);
});
</script>
@endsection
