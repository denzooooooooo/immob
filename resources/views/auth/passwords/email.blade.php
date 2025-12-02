@extends('layouts.app')

@section('title', 'Réinitialiser le mot de passe - Monnkama')

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
                    <i class="fas fa-key text-violet-600 text-2xl"></i>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2">
                Mot de passe oublié ?
            </h2>
            <p class="text-white/80">
                Pas de souci ! Entrez votre email pour recevoir un lien de réinitialisation
            </p>
        </div>

        <!-- Formulaire -->
        <div class="form-container rounded-2xl p-8 animate-slide-up">
            @if (session('status'))
                <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 animate-scale-in">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">
                                Email envoyé avec succès !
                            </h3>
                            <p class="text-sm text-green-700 mt-1">
                                {{ session('status') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

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

            <form class="space-y-6" action="{{ route('password.email') }}" method="POST">
                @csrf

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

                <div>
                    <button type="submit"
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white btn-gradient hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-600 transition-all duration-300 hover-lift">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-paper-plane text-white/80 group-hover:text-white"></i>
                        </span>
                        Envoyer le lien de réinitialisation
                    </button>
                </div>
            </form>

            <!-- Lien de retour -->
            <div class="text-center mt-6">
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center text-sm font-medium text-violet-600 hover:text-violet-600-light transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la connexion
                </a>
            </div>

            <!-- Aide supplémentaire -->
            <div class="mt-6 p-4 bg-gray-50 rounded-xl">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-lightbulb text-yellow-500"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-800">
                            Conseils
                        </h3>
                        <div class="mt-1 text-sm text-gray-600">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Vérifiez votre dossier spam/courrier indésirable</li>
                                <li>Le lien expire dans 60 minutes</li>
                                <li>Vous pouvez demander un nouveau lien si nécessaire</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
