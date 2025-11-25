@extends('layouts.admin')

@section('title', 'Modifier l\'utilisateur - ' . $user->name)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="relative overflow-hidden">
                <!-- Background decoration -->
                <div class="absolute inset-0 bg-gradient-to-br from-violet-50 via-red-50 to-violet-50"></div>
                <div class="absolute top-10 left-10 w-32 h-32 bg-violet-100/20 rounded-full blur-xl"></div>
                <div class="absolute bottom-10 right-10 w-40 h-40 bg-red-100/15 rounded-full blur-xl"></div>

                <div class="relative p-6 lg:p-8 bg-white/80 backdrop-blur-sm border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-gradient-to-br from-violet-100 to-red-100">
                            <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h1 class="text-3xl font-bold bg-gradient-to-r from-violet-600 to-red-600 bg-clip-text text-transparent">
                                Modifier l'utilisateur
                            </h1>
                            <p class="mt-2 text-gray-600">
                                Modifiez les informations de l'utilisateur <span class="font-semibold text-violet-600">{{ $user->name }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-50 to-white grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="md:col-span-2">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nom complet</label>
                        <input id="name" name="name" type="text" class="block w-full px-4 py-3 border-2 border-violet-100 rounded-lg focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-sm" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Adresse e-mail</label>
                        <input id="email" name="email" type="email" class="block w-full px-4 py-3 border-2 border-violet-100 rounded-lg focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-sm" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Nouveau mot de passe <span class="text-gray-500 font-normal">(laisser vide pour ne pas changer)</span></label>
                        <input id="password" name="password" type="password" class="block w-full px-4 py-3 border-2 border-violet-100 rounded-lg focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-sm" autocomplete="new-password" />
                        @error('password')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirmer le mot de passe</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="block w-full px-4 py-3 border-2 border-violet-100 rounded-lg focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-sm" autocomplete="new-password" />
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg">
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">Rôle</label>
                        <select id="role" name="role" class="block w-full px-4 py-3 border-2 border-violet-100 rounded-lg focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-sm appearance-none">
                            <option value="client" {{ old('role', $user->role) === 'client' ? 'selected' : '' }}>👤 Client</option>
                            <option value="agent" {{ old('role', $user->role) === 'agent' ? 'selected' : '' }}>🏠 Agent</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>⚡ Administrateur</option>
                        </select>
                        @error('role')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg">
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Statut</label>
                        <select id="status" name="status" class="block w-full px-4 py-3 border-2 border-violet-100 rounded-lg focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-sm appearance-none">
                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>✅ Actif</option>
                            <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>⏸️ Inactif</option>
                            <option value="suspended" {{ old('status', $user->status) === 'suspended' ? 'selected' : '' }}>🚫 Suspendu</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg">
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Téléphone</label>
                        <input id="phone" name="phone" type="text" class="block w-full px-4 py-3 border-2 border-violet-100 rounded-lg focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-sm" value="{{ old('phone', $user->phone) }}" />
                        @error('phone')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg md:col-span-2">
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Adresse</label>
                        <textarea id="address" name="address" class="block w-full px-4 py-3 border-2 border-violet-100 rounded-lg focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-sm" rows="4">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="md:col-span-2 flex items-center justify-between mt-8 bg-white/60 backdrop-blur-sm rounded-xl p-6 border border-white/20 shadow-lg">
                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-100 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-200 focus:ring-4 focus:ring-gray-500/20 transition-all duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Annuler
                        </a>
                        <button type="submit" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-violet-600 to-red-600 border border-transparent rounded-lg font-semibold text-white hover:shadow-xl hover:shadow-violet-500/25 transform hover:scale-105 focus:ring-4 focus:ring-violet-500/20 transition-all duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
