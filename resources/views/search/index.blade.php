@extends('layouts.app')

@section('title', 'Recherche avancée - Monnkama')
@section('description', 'Recherchez votre bien immobilier idéal au Gabon avec nos filtres avancés.')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-white via-violet-50/30 to-white py-12">
    <!-- Background decoration -->
    <div class="absolute inset-0">
        <div class="absolute top-20 left-10 w-40 h-40 bg-violet-100/20 rounded-full blur-2xl"></div>
        <div class="absolute bottom-20 right-10 w-32 h-32 bg-violet-200/15 rounded-full blur-xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-violet-50/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20 animate-fade-in">
            <h1 class="text-5xl md:text-7xl font-bold text-gray-900 mb-6 leading-tight">
                Recherche
                <span class="block bg-gradient-to-r from-violet-600 to-violet-800 bg-clip-text text-transparent">
                    avancée
                </span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Trouvez le bien immobilier qui correspond exactement à vos critères parmi notre large sélection
            </p>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-20">
            <div class="bg-white rounded-3xl shadow-xl p-8 text-center transform hover:scale-110 hover:-translate-y-2 transition-all duration-500 border border-violet-100/50 group">
                <div class="relative mx-auto mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br from-violet-600 to-violet-800 rounded-2xl flex items-center justify-center mx-auto shadow-lg group-hover:shadow-2xl group-hover:shadow-violet-500/25 transition-all duration-300 transform group-hover:scale-110 group-hover:rotate-6">
                        <i class="fas fa-home text-white text-2xl"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-violet-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-star text-white text-xs"></i>
                    </div>
                </div>
                <div class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-violet-600 to-violet-800 bg-clip-text text-transparent mb-3">{{ $stats['total_properties'] ?? 0 }}</div>
                <div class="text-gray-600 font-semibold text-lg">Biens disponibles</div>
                <div class="w-16 h-1 bg-gradient-to-r from-violet-600 to-violet-800 rounded-full mx-auto mt-3"></div>
            </div>

            <div class="bg-white rounded-3xl shadow-xl p-8 text-center transform hover:scale-110 hover:-translate-y-2 transition-all duration-500 border border-violet-100/50 group">
                <div class="relative mx-auto mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-violet-500 to-violet-700 rounded-2xl flex items-center justify-center mx-auto shadow-lg group-hover:shadow-2xl group-hover:shadow-violet-500/25 transition-all duration-300 transform group-hover:scale-110 group-hover:-rotate-6">
                        <i class="fas fa-tag text-white text-2xl"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-violet-400 rounded-full flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-white text-xs"></i>
                    </div>
                </div>
                <div class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-violet-500 to-violet-700 bg-clip-text text-transparent mb-3">{{ $stats['for_sale'] ?? 0 }}</div>
                <div class="text-gray-600 font-semibold text-lg">À vendre</div>
                <div class="w-16 h-1 bg-gradient-to-r from-violet-500 to-violet-700 rounded-full mx-auto mt-3"></div>
            </div>

            <div class="bg-white rounded-3xl shadow-xl p-8 text-center transform hover:scale-110 hover:-translate-y-2 transition-all duration-500 border border-violet-100/50 group">
                <div class="relative mx-auto mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-violet-400 to-violet-600 rounded-2xl flex items-center justify-center mx-auto shadow-lg group-hover:shadow-2xl group-hover:shadow-violet-500/25 transition-all duration-300 transform group-hover:scale-110 group-hover:rotate-6">
                        <i class="fas fa-key text-white text-2xl"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-violet-300 rounded-full flex items-center justify-center">
                        <i class="fas fa-home text-violet-600 text-xs"></i>
                    </div>
                </div>
                <div class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-violet-400 to-violet-600 bg-clip-text text-transparent mb-3">{{ $stats['for_rent'] ?? 0 }}</div>
                <div class="text-gray-600 font-semibold text-lg">À louer</div>
                <div class="w-16 h-1 bg-gradient-to-r from-violet-400 to-violet-600 rounded-full mx-auto mt-3"></div>
            </div>

            <div class="bg-white rounded-3xl shadow-xl p-8 text-center transform hover:scale-110 hover:-translate-y-2 transition-all duration-500 border border-violet-100/50 group">
                <div class="relative mx-auto mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-violet-300 to-violet-500 rounded-2xl flex items-center justify-center mx-auto shadow-lg group-hover:shadow-2xl group-hover:shadow-violet-500/25 transition-all duration-300 transform group-hover:scale-110 group-hover:-rotate-6">
                        <i class="fas fa-map-marker-alt text-white text-2xl"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-violet-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-map text-white text-xs"></i>
                    </div>
                </div>
                <div class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-violet-300 to-violet-500 bg-clip-text text-transparent mb-3">{{ $stats['cities'] ?? 0 }}</div>
                <div class="text-gray-600 font-semibold text-lg">Villes</div>
                <div class="w-16 h-1 bg-gradient-to-r from-violet-300 to-violet-500 rounded-full mx-auto mt-3"></div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-2xl p-12 mb-12 border border-violet-100/50">
            <form action="{{ route('search.index') }}" method="GET" class="space-y-12">
                <!-- Type et Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="type" class="block text-lg font-semibold text-gray-800 mb-4">Type de bien</label>
                        <select name="type" id="type" class="w-full px-6 py-4 border-2 border-violet-100 rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-lg appearance-none">
                            <option value="">Tous les types</option>
                            <option value="house" {{ request('type') == 'house' ? 'selected' : '' }}>Maison</option>
                            <option value="apartment" {{ request('type') == 'apartment' ? 'selected' : '' }}>Appartement</option>
                            <option value="land" {{ request('type') == 'land' ? 'selected' : '' }}>Terrain</option>
                            <option value="commercial" {{ request('type') == 'commercial' ? 'selected' : '' }}>Local commercial</option>
                            <option value="office" {{ request('type') == 'office' ? 'selected' : '' }}>Bureau</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-violet-400 pointer-events-none"></i>
                    </div>
                    <div>
                        <label for="status" class="block text-lg font-semibold text-gray-800 mb-4">Status</label>
                        <select name="status" id="status" class="w-full px-6 py-4 border-2 border-violet-100 rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-lg appearance-none">
                            <option value="">Tous les status</option>
                            <option value="for_sale" {{ request('status') == 'for_sale' ? 'selected' : '' }}>À vendre</option>
                            <option value="for_rent" {{ request('status') == 'for_rent' ? 'selected' : '' }}>À louer</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-violet-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Localisation -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="city" class="block text-lg font-semibold text-gray-800 mb-4">Ville</label>
                        <select name="city" id="city" class="w-full px-6 py-4 border-2 border-violet-100 rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-lg appearance-none">
                            <option value="">Toutes les villes</option>
                            @foreach($cities as $citySlug => $cityName)
                                <option value="{{ $citySlug }}" {{ request('city') == $citySlug ? 'selected' : '' }}>
                                    {{ $cityName }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-violet-400 pointer-events-none"></i>
                    </div>
                    <div>
                        <label for="neighborhood" class="block text-lg font-semibold text-gray-800 mb-4">Quartier</label>
                        <select name="neighborhood" id="neighborhood" class="w-full px-6 py-4 border-2 border-violet-100 rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-lg appearance-none">
                            <option value="">Tous les quartiers</option>
                            @foreach($neighborhoods as $neighborhoodSlug => $neighborhoodName)
                                <option value="{{ $neighborhoodSlug }}" {{ request('neighborhood') == $neighborhoodSlug ? 'selected' : '' }}>
                                    {{ $neighborhoodName }}
                                </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-violet-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Prix et Surface -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="min_price" class="block text-lg font-semibold text-gray-800 mb-4">Prix minimum</label>
                            <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}"
                                   class="w-full px-6 py-4 border-2 border-violet-100 rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-lg"
                                   placeholder="0">
                        </div>
                        <div>
                            <label for="max_price" class="block text-lg font-semibold text-gray-800 mb-4">Prix maximum</label>
                            <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}"
                                   class="w-full px-6 py-4 border-2 border-violet-100 rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-lg"
                                   placeholder="Sans limite">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="min_surface" class="block text-lg font-semibold text-gray-800 mb-4">Surface min. (m²)</label>
                            <input type="number" name="min_surface" id="min_surface" value="{{ request('min_surface') }}"
                                   class="w-full px-6 py-4 border-2 border-violet-100 rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-lg"
                                   placeholder="0">
                        </div>
                        <div>
                            <label for="max_surface" class="block text-lg font-semibold text-gray-800 mb-4">Surface max. (m²)</label>
                            <input type="number" name="max_surface" id="max_surface" value="{{ request('max_surface') }}"
                                   class="w-full px-6 py-4 border-2 border-violet-100 rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-lg"
                                   placeholder="Sans limite">
                        </div>
                    </div>
                </div>

                <!-- Chambres et Salles de bain -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="min_bedrooms" class="block text-lg font-semibold text-gray-800 mb-4">Chambres (minimum)</label>
                        <select name="min_bedrooms" id="min_bedrooms" class="w-full px-6 py-4 border-2 border-violet-100 rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-lg appearance-none">
                            <option value="">Indifférent</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" {{ request('min_bedrooms') == $i ? 'selected' : '' }}>
                                    {{ $i }}+
                                </option>
                            @endfor
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-violet-400 pointer-events-none"></i>
                    </div>
                    <div>
                        <label for="min_bathrooms" class="block text-lg font-semibold text-gray-800 mb-4">Salles de bain (minimum)</label>
                        <select name="min_bathrooms" id="min_bathrooms" class="w-full px-6 py-4 border-2 border-violet-100 rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-lg appearance-none">
                            <option value="">Indifférent</option>
                            @for($i = 1; $i <= 4; $i++)
                                <option value="{{ $i }}" {{ request('min_bathrooms') == $i ? 'selected' : '' }}>
                                    {{ $i }}+
                                </option>
                            @endfor
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-violet-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Caractéristiques -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-8">Caractéristiques</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach([
                            'furnished' => 'Meublé',
                            'parking' => 'Parking',
                            'garden' => 'Jardin',
                            'pool' => 'Piscine',
                            'security' => 'Sécurité',
                            'elevator' => 'Ascenseur',
                            'balcony' => 'Balcon',
                            'air_conditioning' => 'Climatisation'
                        ] as $feature => $label)
                            <div class="flex items-center p-4 bg-violet-50/50 rounded-2xl border border-violet-100 hover:bg-violet-50 transition-all duration-300">
                                <input type="checkbox" name="features[]" id="{{ $feature }}" value="{{ $feature }}"
                                       class="w-5 h-5 rounded border-2 border-violet-300 text-violet-600 focus:ring-violet-500/20 focus:ring-4 transition-all duration-300"
                                       {{ in_array($feature, request('features', [])) ? 'checked' : '' }}>
                                <label for="{{ $feature }}" class="ml-4 text-lg font-medium text-gray-800">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex justify-center space-x-6 pt-8">
                    <button type="submit" class="px-12 py-5 bg-gradient-to-r from-violet-600 to-violet-800 text-white font-bold rounded-2xl hover:shadow-2xl hover:shadow-violet-500/25 transform hover:scale-105 transition-all duration-300 flex items-center justify-center shadow-lg">
                        <i class="fas fa-search mr-3 text-xl"></i>
                        <span class="text-lg">Rechercher</span>
                    </button>
                    <a href="{{ route('search.index') }}" class="px-12 py-5 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 font-bold rounded-2xl hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center shadow-lg">
                        <i class="fas fa-undo mr-3 text-xl"></i>
                        <span class="text-lg">Réinitialiser</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Résultats -->
        @if($properties->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($properties as $property)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        <a href="{{ route('properties.show', $property) }}" class="block relative pb-[60%]">
                            @if($property->featuredImage)
                                <img src="{{ $property->featuredImage->url }}" 
                                     alt="{{ $property->title }}"
                                     class="absolute inset-0 w-full h-full object-cover">
                            @else
                                <div class="absolute inset-0 bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-home text-4xl text-gray-400"></i>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4 bg-gabon-green text-white px-3 py-1 rounded-full text-sm">
                                {{ $property->type === 'for_sale' ? 'À vendre' : 'À louer' }}
                            </div>
                            @if($property->featured)
                                <div class="absolute top-4 right-4 bg-gabon-yellow text-gray-900 px-3 py-1 rounded-full text-sm">
                                    <i class="fas fa-star mr-1"></i> Premium
                                </div>
                            @endif
                        </a>
                        <div class="p-4">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                <a href="{{ route('properties.show', $property) }}" class="hover:text-gabon-green">
                                    {{ $property->title }}
                                </a>
                            </h3>
                            <p class="text-gray-600 mb-4">{{ Str::limit($property->description, 100) }}</p>
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-2xl font-bold text-gabon-green">{{ $property->formatted_price }}</span>
                                <span class="text-gray-600">{{ $property->formatted_surface }}</span>
                            </div>
                            <div class="flex items-center text-gray-600 text-sm">
                                <span class="mr-4"><i class="fas fa-bed mr-1"></i> {{ $property->bedrooms }}</span>
                                <span class="mr-4"><i class="fas fa-bath mr-1"></i> {{ $property->bathrooms }}</span>
                                <span><i class="fas fa-map-marker-alt mr-1"></i> {{ $property->city }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $properties->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4"><i class="fas fa-search text-gray-300"></i></div>
                <h3 class="text-2xl font-semibold text-gray-700 mb-2">Aucun résultat trouvé</h3>
                <p class="text-gray-600">Essayez de modifier vos critères de recherche</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const citySelect = document.getElementById('city');
    const neighborhoodSelect = document.getElementById('neighborhood');
    
    // Mettre à jour les quartiers en fonction de la ville sélectionnée
    citySelect.addEventListener('change', function() {
        const citySlug = this.value;
        
        // Vider la liste des quartiers
        neighborhoodSelect.innerHTML = '<option value="">Tous les quartiers</option>';
        
        if (citySlug) {
            // Charger les quartiers de la ville sélectionnée
            fetch(`/api/cities/${citySlug}/neighborhoods`)
                .then(response => response.json())
                .then(neighborhoods => {
                    neighborhoods.forEach(neighborhood => {
                        const option = document.createElement('option');
                        option.value = neighborhood.slug;
                        option.textContent = neighborhood.name;
                        neighborhoodSelect.appendChild(option);
                    });
                });
        }
    });
});
</script>
@endpush
