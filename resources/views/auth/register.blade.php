@extends('layouts.app')

@section('title', 'Inscription - Monnkama')

@push('styles')
<style>
    .auth-bg {
        background: linear-gradient(135deg, #7C3AED 0%, #DC2626 50%, #7C3AED 100%);
        position: relative;
        overflow: hidden;
        opacity: 0.8;
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
        animation: float 8s ease-in-out infinite;
    }
    
    .floating-shapes::before {
        width: 180px;
        height: 180px;
        top: 20%;
        right: 10%;
        animation-delay: 1s;
    }
    
    .floating-shapes::after {
        width: 120px;
        height: 120px;
        bottom: 20%;
        left: 15%;
        animation-delay: 4s;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-30px) rotate(180deg); }
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
    
    .input-group input,
    .input-group select {
        transition: all 0.3s ease;
    }
    
    .input-group input:focus,
    .input-group select:focus {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(58, 117, 196, 0.1);
    }
    
    .role-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        border: 2px solid transparent;
    }
    
    .role-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .role-card.selected {
        border-color: #009639;
        background: linear-gradient(135deg, rgba(0, 150, 57, 0.1), rgba(58, 117, 196, 0.1));
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
    
    .social-btn {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .social-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    
    .social-btn:hover::before {
        left: 100%;
    }
    
    .social-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen auth-bg flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="floating-shapes"></div>
    
    <div class="max-w-lg w-full space-y-8 relative z-10">
        <!-- Logo et titre -->
        <div class="text-center animate-fade-in">
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-user-plus text-violet-600 text-2xl"></i>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2">
                Rejoignez Carre Premium
            </h2>
            <p class="text-white/80">
                Créez votre compte et trouvez votre prochain chez-vous
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
                                Erreurs de validation
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

            <form class="space-y-6" action="{{ route('register') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <!-- Nom complet -->
                    <div class="input-group">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-violet-600"></i>
                            Nom complet
                        </label>
                        <input id="name" 
                               name="name" 
                               type="text" 
                               autocomplete="name" 
                               required
                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-violet-600 transition-all duration-300"
                               placeholder="Votre nom complet"
                               value="{{ old('name') }}">
                    </div>

                    <!-- Email -->
                    <div class="input-group">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-violet-600"></i>
                            Adresse email
                        </label>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required
                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-violet-600 transition-all duration-300"
                               placeholder="votre@email.com"
                               value="{{ old('email') }}">
                    </div>

                    <!-- Téléphone -->
                    <div class="input-group">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone mr-2 text-violet-600"></i>
                            Numéro de téléphone
                        </label>
                        <input id="phone" 
                               name="phone" 
                               type="tel" 
                               autocomplete="tel"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-violet-600 transition-all duration-300"
                               placeholder="+241 XX XX XX XX"
                               value="{{ old('phone') }}">
                    </div>

                    <!-- Type de compte -->
                    <div class="input-group">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-users mr-2 text-violet-600"></i>
                            Type de compte
                        </label>
                        <div class="grid grid-cols-1 gap-3">
                            <div class="role-card rounded-xl p-4 border-2 border-gray-200 bg-white" onclick="selectRole('client')">
                                <div class="flex items-center">
                                    <input type="radio" id="role_client" name="role" value="client" class="sr-only" {{ old('role') == 'client' ? 'checked' : '' }}>
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-search text-violet-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-gray-900">Client</h3>
                                        <p class="text-xs text-gray-500">Je recherche des biens immobiliers</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="role-card rounded-xl p-4 border-2 border-gray-200 bg-white" onclick="selectRole('agent')">
                                <div class="flex items-center">
                                    <input type="radio" id="role_agent" name="role" value="agent" class="sr-only" {{ old('role') == 'agent' ? 'checked' : '' }}>
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-briefcase text-violet-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-gray-900">Agent immobilier</h3>
                                        <p class="text-xs text-gray-500">Je vends/loue des biens immobiliers</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mot de passe -->
                    <div class="input-group">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-violet-600"></i>
                            Mot de passe
                        </label>
                        <div class="relative">
                            <input id="password" 
                                   name="password" 
                                   type="password" 
                                   autocomplete="new-password" 
                                   required
                                   onkeyup="checkPasswordStrength(this.value)"
                                   class="block w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-violet-600 transition-all duration-300"
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
                            <i class="fas fa-lock mr-2 text-violet-600"></i>
                            Confirmer le mot de passe
                        </label>
                        <div class="relative">
                            <input id="password_confirmation" 
                                   name="password_confirmation" 
                                   type="password" 
                                   autocomplete="new-password" 
                                   required
                                   class="block w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-violet-600 transition-all duration-300"
                                   placeholder="••••••••">
                            <button type="button" 
                                    onclick="togglePasswordVisibility('password_confirmation', 'password-confirmation-icon')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i id="password-confirmation-icon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Conditions d'utilisation -->
                <div class="flex items-start">
                    <input id="terms" 
                           name="terms" 
                           type="checkbox" 
                           required
                           class="h-4 w-4 mt-1 text-violet-600 focus:ring-violet-600 border-gray-300 rounded">
                    <label for="terms" class="ml-3 block text-sm text-gray-700">
                        J'accepte les
                        <a href="{{ route('terms') }}" class="font-medium text-violet-600 hover:text-violet-600-light transition-colors duration-200">
                            conditions d'utilisation
                        </a>
                        et la
                        <a href="{{ route('privacy') }}" class="font-medium text-violet-600 hover:text-violet-600-light transition-colors duration-200">
                            politique de confidentialité
                        </a>
                    </label>
                </div>

                <!-- Bouton d'inscription -->
                <div>
                    <button type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white btn-gradient hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-600 transition-all duration-300 hover-lift">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-user-plus text-white/80 group-hover:text-white"></i>
                        </span>
                        Créer mon compte
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="mt-8">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500 font-medium">
                            Ou inscrivez-vous avec
                        </span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <a href="{{ route('auth.google') }}"
                       class="social-btn w-full inline-flex justify-center items-center py-3 px-4 border border-gray-300 rounded-xl shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-300">
                        <i class="fab fa-google text-red-500 mr-2"></i>
                        Google
                    </a>
                    <a href="{{ route('auth.facebook') }}"
                       class="social-btn w-full inline-flex justify-center items-center py-3 px-4 border border-gray-300 rounded-xl shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-300">
                        <i class="fab fa-facebook text-blue-600 mr-2"></i>
                        Facebook
                    </a>
                </div>
            </div>

            <!-- Lien de connexion -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    Déjà un compte ?
                    <a href="{{ route('login') }}" 
                       class="font-medium text-violet-600 hover:text-violet-600-light transition-colors duration-200">
                        Se connecter
                    </a>
                </p>
            </div>

            <!-- Information pour les agents -->
            <div id="agent-info" class="hidden mt-6 p-4 bg-blue-50 rounded-xl border border-blue-200 animate-scale-in">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">
                            Information pour les agents immobiliers
                        </h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>En tant qu'agent immobilier, vous devrez souscrire à un abonnement pour publier vos propriétés. Vous pourrez choisir votre plan après la création de votre compte.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function selectRole(role) {
    // Désélectionner toutes les cartes
    document.querySelectorAll('.role-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Sélectionner la carte cliquée
    event.currentTarget.classList.add('selected');
    
    // Cocher le radio button correspondant
    document.getElementById('role_' + role).checked = true;
    
    // Afficher/masquer l'info agent
    const agentInfo = document.getElementById('agent-info');
    if (role === 'agent') {
        agentInfo.classList.remove('hidden');
    } else {
        agentInfo.classList.add('hidden');
    }
}

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

// Initialiser la sélection du rôle si une valeur est déjà sélectionnée
document.addEventListener('DOMContentLoaded', function() {
    const selectedRole = document.querySelector('input[name="role"]:checked');
    if (selectedRole) {
        const roleCard = selectedRole.closest('.role-card') || 
                        document.querySelector(`[onclick="selectRole('${selectedRole.value}')"]`);
        if (roleCard) {
            roleCard.classList.add('selected');
            if (selectedRole.value === 'agent') {
                document.getElementById('agent-info').classList.remove('hidden');
            }
        }
    }
});
</script>
@endsection
