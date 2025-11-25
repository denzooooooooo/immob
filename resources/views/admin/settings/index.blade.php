@extends('layouts.admin')

@section('title', 'Paramètres')

@section('header', 'Paramètres du site')

@section('content')
<div class="mb-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Paramètres du Site -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-globe text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Paramètres du Site</h3>
                    <p class="text-sm text-gray-500">Configuration générale du site</p>
                </div>
            </div>
            <p class="text-gray-600 mb-4">
                Gérez les paramètres généraux du site, les textes de la page d'accueil, les informations de contact et les réseaux sociaux.
            </p>
            <a href="{{ route('admin.settings.site') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-cog mr-2"></i>
                Configurer
            </a>
        </div>

        <!-- Paramètres Système -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-server text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Paramètres Système</h3>
                    <p class="text-sm text-gray-500">Configuration système et maintenance</p>
                </div>
            </div>
            <p class="text-gray-600 mb-4">
                Configurez les paramètres système, le mode maintenance et les informations de contact de base.
            </p>
            <button onclick="document.getElementById('system-settings').scrollIntoView()" 
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-tools mr-2"></i>
                Configurer
            </button>
        </div>
    </div>
</div>

<div id="system-settings" class="bg-white shadow overflow-hidden sm:rounded-lg">
    <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <!-- Informations générales -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="site_name" class="block text-sm font-medium text-gray-700">Nom du site</label>
                        <input type="text" name="site_name" id="site_name" value="{{ old('site_name', $settings['site_name']) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        @error('site_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700">Email de contact</label>
                        <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        @error('contact_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Numéro de téléphone</label>
                        <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $settings['phone_number']) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        @error('phone_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                        <input type="text" name="address" id="address" value="{{ old('address', $settings['address']) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Réseaux sociaux -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Réseaux sociaux</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="facebook_url" class="block text-sm font-medium text-gray-700">Facebook</label>
                        <input type="url" name="facebook_url" id="facebook_url" value="{{ old('facebook_url', $settings['facebook_url']) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        @error('facebook_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="twitter_url" class="block text-sm font-medium text-gray-700">Twitter</label>
                        <input type="url" name="twitter_url" id="twitter_url" value="{{ old('twitter_url', $settings['twitter_url']) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        @error('twitter_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="instagram_url" class="block text-sm font-medium text-gray-700">Instagram</label>
                        <input type="url" name="instagram_url" id="instagram_url" value="{{ old('instagram_url', $settings['instagram_url']) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        @error('instagram_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Maintenance -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Maintenance</h3>
                <div class="flex items-center">
                    <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1" {{ $settings['maintenance_mode'] ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500">
                    <label for="maintenance_mode" class="ml-2 block text-sm text-gray-900">
                        Activer le mode maintenance
                    </label>
                </div>
                <p class="mt-1 text-sm text-gray-500">
                    Lorsque le mode maintenance est activé, seuls les administrateurs peuvent accéder au site.
                </p>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-green-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>
@endsection
