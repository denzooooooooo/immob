@extends('layouts.app')

@section('title', 'Mon Profil - Monnkama')

@push('styles')
<style>
    .profile-header {
        background: linear-gradient(135deg, #3A75C4 0%, #009639 100%);
        position: relative;
        overflow: hidden;
    }
    
    .profile-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="50" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="30" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }
    
    .form-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }
    
    .form-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
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
        transform: translateY(-1px);
        box-shadow: 0 8px 16px rgba(58, 117, 196, 0.1);
    }
    
    .custom-checkbox {
        position: relative;
        padding-left: 2.5rem;
        cursor: pointer;
        user-select: none;
        display: inline-block;
    }
    
    .custom-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }
    
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 1.5rem;
        width: 1.5rem;
        background-color: #fff;
        border: 2px solid #e2e8f0;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
    }
    
    .custom-checkbox:hover input ~ .checkmark {
        border-color: #3A75C4;
    }
    
    .custom-checkbox input:checked ~ .checkmark {
        background-color: #3A75C4;
        border-color: #3A75C4;
    }
    
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
        left: 0.4rem;
        top: 0.2rem;
        width: 0.4rem;
        height: 0.7rem;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
    
    .custom-checkbox input:checked ~ .checkmark:after {
        display: block;
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
<div class="min-h-screen bg-gray-50">
    <!-- En-tête du profil -->
    <div class="profile-header py-12 px-4 sm:px-6 lg:px-8 relative">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center space-x-6">
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 bg-white rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-circle text-violet-600 text-4xl"></i>
                    </div>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white">
                        {{ auth()->user()->name }}
                    </h1>
                    <p class="mt-1 text-white/80">
                        {{ auth()->user()->role === 'agent' ? 'Agent Immobilier' : 'Client' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-8 rounded-xl bg-green-50 border border-green-200 p-4 animate-scale-in">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations personnelles -->
            <div class="lg:col-span-2">
                <div class="form-card p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">
                            Informations personnelles
                        </h2>
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Dernière mise à jour : {{ auth()->user()->updated_at->format('d/m/Y') }}
                        </span>
                    </div>
                    
                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom complet -->
                            <div class="input-group">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2 text-violet-600"></i>
                                    Nom complet
                                </label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name', auth()->user()->name) }}"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-violet-600 transition-all duration-300">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="input-group">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope mr-2 text-violet-600"></i>
                                    Email
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="email" 
                                       value="{{ old('email', auth()->user()->email) }}"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-violet-600 transition-all duration-300">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Téléphone -->
                            <div class="input-group">
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-phone mr-2 text-violet-600"></i>
                                    Téléphone
                                </label>
                                <input type="tel" 
                                       name="phone" 
                                       id="phone" 
                                       value="{{ old('phone', auth()->user()->phone) }}"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-violet-600 transition-all duration-300">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Préférences de notification -->
                        <div class="space-y-4 border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-bell mr-2 text-violet-600"></i>
                                Préférences de notification
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="custom-checkbox">
                                    <span class="text-sm text-gray-700">Recevoir les notifications par email</span>
                                    <input type="checkbox" 
                                           name="email_notifications" 
                                           value="1" 
                                           {{ old('email_notifications', auth()->user()->email_notifications) ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                </label>

                                <label class="custom-checkbox">
                                    <span class="text-sm text-gray-700">Alertes de nouvelles propriétés</span>
                                    <input type="checkbox" 
                                           name="property_alerts" 
                                           value="1" 
                                           {{ old('property_alerts', auth()->user()->property_alerts) ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                </label>

                                <label class="custom-checkbox">
                                    <span class="text-sm text-gray-700">Alertes de changement de prix</span>
                                    <input type="checkbox" 
                                           name="price_alerts" 
                                           value="1" 
                                           {{ old('price_alerts', auth()->user()->price_alerts) ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-xl text-white bg-violet-600 hover:bg-violet-600-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-600 transition-all duration-300">
                                <i class="fas fa-save mr-2"></i>
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sécurité -->
            <div class="lg:col-span-1">
                <div class="form-card p-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">
                        <i class="fas fa-shield-alt mr-2 text-violet-600"></i>
                        Sécurité
                    </h2>
                    
                    <form action="{{ route('profile.password') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Mot de passe actuel -->
                        <div class="input-group">
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Mot de passe actuel
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="current_password" 
                                       id="current_password"
                                       class="block w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-violet-600 transition-all duration-300"
                                       placeholder="••••••••">
                                <button type="button" 
                                        onclick="togglePasswordVisibility('current_password', 'current-password-icon')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i id="current-password-icon" class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nouveau mot de passe -->
                        <div class="input-group">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Nouveau mot de passe
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="password" 
                                       id="password"
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
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirmation du nouveau mot de passe -->
                        <div class="input-group">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmer le nouveau mot de passe
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="password_confirmation"
                                       class="block w-full px-4 py-3 pr-12 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-violet-600 transition-all duration-300"
                                       placeholder="••••••••">
                                <button type="button" 
                                        onclick="togglePasswordVisibility('password_confirmation', 'password-confirmation-icon')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <i id="password-confirmation-icon" class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-xl text-white bg-violet-600 hover:bg-violet-600-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-600 transition-all duration-300">
                                <i class="fas fa-key mr-2"></i>
                                Changer le mot de passe
                            </button>
                        </div>
                    </form>

                    <!-- Conseils de sécurité -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-100">
                        <h3 class="text-sm font-medium text-blue-800 mb-2">
                            <i class="fas fa-shield-alt mr-2"></i>
                            Conseils de sécurité
                        </h3>
                        <ul class="text-sm text-blue-700 space-y-1 list-disc pl-5">
                            <li>Utilisez un mot de passe unique</li>
                            <li>Évitez les informations personnelles</li>
                            <li>Changez régulièrement votre mot de passe</li>
                            <li>Activez l'authentification à deux facteurs</li>
                        </ul>
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
