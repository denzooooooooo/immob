@extends('layouts.app')

@section('title', 'Nouveau mot de passe - Monnkama')

@push('styles')
<style>
    .auth-bg {
        background: linear-gradient(135deg, #009639 0%, #3A75C4 100%);
        position: relative;
        overflow: hidden;
    }
    
    .auth-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="50" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="30" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }
    
    .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 1;
    }
    
    .floating-shapes::before,
    .floating-shapes::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        animation: float 7s ease-in-out infinite;
    }
    
    .floating-shapes::before {
        width: 160px;
        height: 160px;
        top: 15%;
        left: 15%;
        animation-delay: 0.5s;
    }
    
    .floating-shapes::after {
        width: 100px;
        height: 100px;
        bottom: 15%;
        right: 20%;
        animation-delay: 3.5s;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-25px) rotate(180deg); }
    }
    
    .form-container {
        backdrop-filter: blur(20px);
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .input-group {
        position: relative;
    }
    
    .input-group input {
        transition: all 0.3s ease;
    }
    
    .input-group input:focus {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 150, 57, 0.1);
    }
    
    .password-strength {
        height: 4px;
        border-radius: 2px;
        transition: all 0.3s ease;
    }
    
    .strength-weak { background: #ef4444; width: 25%; }
    .strength-fair { background: #f59e0b; width: 50%; }
    .strength-good { background: #10b981; width: 75%; }
    .strength-strong { background: #059669; width: 100%; }
</style>
@endpush

@section('content')
<div class="min-h-screen auth-bg flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="floating-shapes"></div>
    
    <div class="max-w-md w-full space-y-8 relative z-10">
        <!-- Logo et titre -->
        <div class="text-center animate-fade-in">
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-shield-alt text-gabon-green text-2xl"></i>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2">
                Nouveau mot de passe
            </h2>
            <p class="text-white/80">
                Créez un nouveau mot de passe sécurisé pour votre compte
            </p>
        </div>

        <!-- Formulaire -->
        <div class="form-container rounded-2xl p-8 animate-slide-up">
            @if($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 animate-scale-in">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Erreur
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form class="space-y-6" action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="space-y-4">
                    <!-- Email -->
                    <div class="input-group">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-gabon-green"></i>
                            Adresse email
                        </label>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required
                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gabon-green focus:border-gabon-green transition-all duration-300"
                               placeholder="votre@email.com"
                               value="{{ $email ?? old('email') }}">
                    </div>

                    <!-- Nouveau mot de passe -->
                    <div class="input-group">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-gabon-green"></i>
                            Nouveau mot de passe
                        </label>
                        <div class="relative">
                            <input id="password" 
                                   name="password" 
                                   type="password" 
                                   autocomplete="new-password" 
                                   required
                                   onkeyup="checkPasswordStrength(this.value)"
                                   class="block w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gabon-green focus:border-gabon-green transition-all duration-300"
                                   placeholder="••••••••">
                            <button type="button" 
                                    onclick="togglePasswordVisibility('password', 'password-icon')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i id="password-icon" class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="mt-2">
                            <div class="password-strength bg-gray-200" id="password-strength"></div>
                            <p class="text-xs text-gray-500 mt-1" id="password-text">
                                Minimum 8 caractères avec majuscules, minuscules et chiffres
                            </p>
                        </div>
                    </div>

                    <!-- Confirmation mot de passe -->
                    <div class="input-group">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-gabon-green"></i>
                            Confirmer le mot de passe
                        </label>
                        <div class="relative">
                            <input id="password_confirmation" 
                                   name="password_confirmation" 
                                   type="password" 
                                   autocomplete="new-password" 
                                   required
                                   class="block w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-gabon-green focus:border-gabon-green transition-all duration-300"
                                   placeholder="••••••••">
                            <button type="button" 
                                    onclick="togglePasswordVisibility('password_confirmation', 'password-confirmation-icon')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i id="password-confirmation-icon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Bouton de réinitialisation -->
                <div>
                    <button type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white btn-gradient hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gabon-green transition-all duration-300 hover-lift">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-key text-white/80 group-hover:text-white"></i>
                        </span>
                        Réinitialiser le mot de passe
                    </button>
                </div>
            </form>

            <!-- Lien de retour -->
            <div class="text-center mt-6">
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center text-sm font-medium text-gabon-green hover:text-gabon-green-light transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la connexion
                </a>
            </div>

            <!-- Conseils de sécurité -->
            <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-100">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-shield-alt text-gabon-blue"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-800">
                            Conseils pour un mot de passe sécurisé
                        </h3>
                        <div class="mt-2 text-sm text-gray-600">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Utilisez au moins 8 caractères</li>
                                <li>Mélangez majuscules et minuscules</li>
                                <li>Incluez des chiffres et des symboles</li>
                                <li>Évitez les informations personnelles</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const passwordIcon = document.getElementById(iconId);
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordIcon.classList.remove('fa-eye');
        passwordIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        passwordIcon.classList.remove('fa-eye-slash');
        passwordIcon.classList.add('fa-eye');
    }
}

function checkPasswordStrength(password) {
    const strengthBar = document.getElementById('password-strength');
    const strengthText = document.getElementById('password-text');
    
    let strength = 0;
    let feedback = '';
    
    // Critères de force
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    // Mise à jour de l'affichage
    strengthBar.className = 'password-strength bg-gray-200';
    
    switch (strength) {
        case 0:
        case 1:
            strengthBar.classList.add('strength-weak');
            feedback = 'Mot de passe très faible';
            break;
        case 2:
            strengthBar.classList.add('strength-fair');
            feedback = 'Mot de passe faible';
            break;
        case 3:
            strengthBar.classList.add('strength-good');
            feedback = 'Mot de passe correct';
            break;
        case 4:
        case 5:
            strengthBar.classList.add('strength-strong');
            feedback = 'Mot de passe fort';
            break;
    }
    
    strengthText.textContent = feedback;
}
</script>
@endsection
