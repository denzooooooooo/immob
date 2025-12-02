@extends('layouts.app')

@section('title', 'Propriétés - Monnkama')
@section('description', 'Découvrez toutes nos propriétés disponibles en Côte d\'Ivoire. Maisons, appartements, terrains et locaux commerciaux.')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[60vh] flex items-center justify-center overflow-hidden bg-gradient-to-br from-violet-50 via-red-50 to-violet-50">
    <!-- Background decoration -->
    <div class="absolute inset-0">
        <div class="absolute top-20 left-10 w-40 h-40 bg-red-100/20 rounded-full blur-2xl"></div>
        <div class="absolute bottom-20 right-10 w-32 h-32 bg-violet-200/15 rounded-full blur-xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-red-50/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="animate-fade-in-up">
            <h1 class="text-5xl md:text-7xl font-bold text-gray-900 mb-6 leading-tight">
                Toutes nos
                <span class="block bg-gradient-to-r from-violet-600 to-violet-800 bg-clip-text text-transparent">
                    propriétés
                </span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Découvrez {{ $properties->total() }} propriétés exceptionnelles dans toute la Côte d'Ivoire, soigneusement sélectionnées pour vous offrir le meilleur de l'immobilier local.
            </p>
        </div>
    </div>
</section>

<!-- Filters Section -->
<section class="bg-white shadow-xl sticky top-16 z-40 border-b border-violet-100/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Mobile Filter Toggle -->
        <div class="md:hidden mb-4">
            <button id="mobile-filters-toggle" class="w-full bg-gradient-to-r from-violet-600 to-violet-800 text-white font-bold py-3 px-6 rounded-2xl hover:shadow-xl hover:shadow-violet-500/25 transform hover:scale-105 transition-all duration-300 flex items-center justify-center shadow-lg">
                <i class="fas fa-filter mr-2"></i>
                Filtres de recherche
                <i class="fas fa-chevron-down ml-2 transform transition-transform duration-300" id="mobile-filter-icon"></i>
            </button>
        </div>

        <form method="GET" action="{{ route('properties.index') }}" class="hidden md:grid md:grid-cols-6 md:gap-6 space-y-3 md:space-y-0" id="filters-form">
            <!-- Search -->
            <div class="md:col-span-2">
                <div class="relative">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Rechercher..."
                           class="w-full px-3 md:px-6 py-2.5 md:py-4 border-2 border-violet-100 rounded-lg md:rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-lg text-sm md:text-base">
                    <i class="fas fa-search absolute right-2.5 md:right-4 top-1/2 transform -translate-y-1/2 text-violet-400 text-sm md:text-base"></i>
                </div>
            </div>

            <!-- Type -->
            <div>
                <select name="type" class="w-full px-3 md:px-6 py-2.5 md:py-4 border-2 border-violet-100 rounded-lg md:rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-lg appearance-none text-sm md:text-base">
                    <option value="">Type</option>
                    <option value="house" {{ request('type') === 'house' ? 'selected' : '' }}>Maison</option>
                    <option value="apartment" {{ request('type') === 'apartment' ? 'selected' : '' }}>Appartement</option>
                    <option value="land" {{ request('type') === 'land' ? 'selected' : '' }}>Terrain</option>
                    <option value="commercial" {{ request('type') === 'commercial' ? 'selected' : '' }}>Commercial</option>
                    <option value="hotel" {{ request('type') === 'hotel' ? 'selected' : '' }}>Hôtel</option>
                </select>
                <i class="fas fa-chevron-down absolute right-2.5 md:right-4 top-1/2 transform -translate-y-1/2 text-violet-400 pointer-events-none text-sm md:text-base"></i>
            </div>

            <!-- Status -->
            <div>
                <select name="status" class="w-full px-3 md:px-6 py-2.5 md:py-4 border-2 border-violet-100 rounded-lg md:rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-lg appearance-none text-sm md:text-base">
                    <option value="">Statut</option>
                    <option value="for_sale" {{ request('status') === 'for_sale' ? 'selected' : '' }}>À vendre</option>
                    <option value="for_rent" {{ request('status') === 'for_rent' ? 'selected' : '' }}>À louer</option>
                    <option value="hotel_room" {{ request('status') === 'hotel_room' ? 'selected' : '' }}>Hôtel</option>
                </select>
                <i class="fas fa-chevron-down absolute right-2.5 md:right-4 top-1/2 transform -translate-y-1/2 text-violet-400 pointer-events-none text-sm md:text-base"></i>
            </div>

            <!-- City -->
            <div>
                <select name="city" class="w-full px-3 md:px-6 py-2.5 md:py-4 border-2 border-violet-100 rounded-lg md:rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-lg appearance-none text-sm md:text-base">
                    <option value="">Ville</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->name }}" {{ request('city') === $city->name ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down absolute right-2.5 md:right-4 top-1/2 transform -translate-y-1/2 text-violet-400 pointer-events-none text-sm md:text-base"></i>
            </div>

            <!-- Submit -->
            <div>
                <button type="submit" class="w-full bg-gradient-to-r from-violet-600 to-violet-800 text-white font-bold py-2.5 md:py-4 px-3 md:px-6 rounded-lg md:rounded-2xl hover:shadow-2xl hover:shadow-violet-500/25 transform hover:scale-105 transition-all duration-300 flex items-center justify-center shadow-lg text-sm md:text-base">
                    <i class="fas fa-search mr-1 md:mr-2"></i>
                    <span class="hidden sm:inline">Filtrer</span>
                    <span class="sm:hidden">OK</span>
                </button>
            </div>
        </form>

        <!-- Advanced Filters Toggle -->
        <div class="mt-4 md:mt-6">
            <button id="advanced-filters-toggle" class="text-violet-600 hover:text-violet-800 transition-colors duration-300 font-semibold text-base md:text-lg">
                <i class="fas fa-sliders-h mr-2"></i>
                Filtres avancés
                <i class="fas fa-chevron-down ml-2 transform transition-transform duration-300"></i>
            </button>

            <div id="advanced-filters" class="hidden mt-4 md:mt-6 p-4 md:p-8 bg-gradient-to-br from-violet-50/50 to-white rounded-2xl md:rounded-3xl border border-violet-100 shadow-xl">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6">
                    <div>
                        <label class="block text-base md:text-lg font-semibold text-gray-800 mb-2 md:mb-3">Prix minimum</label>
                        <input type="number"
                               name="min_price"
                               value="{{ request('min_price') }}"
                               placeholder="0"
                               class="w-full px-4 md:px-6 py-3 md:py-4 border-2 border-violet-100 rounded-xl md:rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-lg text-sm md:text-base">
                    </div>

                    <div>
                        <label class="block text-base md:text-lg font-semibold text-gray-800 mb-2 md:mb-3">Prix maximum</label>
                        <input type="number"
                               name="max_price"
                               value="{{ request('max_price') }}"
                               placeholder="1000000000"
                               class="w-full px-4 md:px-6 py-3 md:py-4 border-2 border-violet-100 rounded-xl md:rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-lg text-sm md:text-base">
                    </div>

                    <div>
                        <label class="block text-base md:text-lg font-semibold text-gray-800 mb-2 md:mb-3">Chambres minimum</label>
                        <select name="bedrooms" class="w-full px-4 md:px-6 py-3 md:py-4 border-2 border-violet-100 rounded-xl md:rounded-2xl focus:ring-4 focus:ring-violet-500/20 focus:border-violet-500 transition-all duration-300 bg-white shadow-lg appearance-none text-sm md:text-base">
                            <option value="">Toutes</option>
                            <option value="1" {{ request('bedrooms') === '1' ? 'selected' : '' }}>1+</option>
                            <option value="2" {{ request('bedrooms') === '2' ? 'selected' : '' }}>2+</option>
                            <option value="3" {{ request('bedrooms') === '3' ? 'selected' : '' }}>3+</option>
                            <option value="4" {{ request('bedrooms') === '4' ? 'selected' : '' }}>4+</option>
                            <option value="5" {{ request('bedrooms') === '5' ? 'selected' : '' }}>5+</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 md:right-4 top-1/2 transform -translate-y-1/2 text-violet-400 pointer-events-none text-sm md:text-base"></i>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-gradient-to-r from-violet-600 to-violet-800 text-white font-bold py-3 md:py-4 px-4 md:px-6 rounded-xl md:rounded-2xl hover:shadow-2xl hover:shadow-violet-500/25 transform hover:scale-105 transition-all duration-300 shadow-lg text-sm md:text-base">
                            Appliquer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Results Section -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Results Header -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ $properties->total() }} propriété{{ $properties->total() > 1 ? 's' : '' }} trouvée{{ $properties->total() > 1 ? 's' : '' }}
                </h2>
                @if(request()->hasAny(['search', 'type', 'status', 'city', 'min_price', 'max_price', 'bedrooms']))
                    <p class="text-gray-600 mt-1">
                        Résultats filtrés - 
                        <a href="{{ route('properties.index') }}" class="text-violet-600 hover:text-violet-600 transition-colors duration-200">
                            Voir toutes les propriétés
                        </a>
                    </p>
                @endif
            </div>
            
            <div class="flex items-center space-x-4 mt-4 md:mt-0">
                <span class="text-gray-600">Trier par:</span>
                <select onchange="window.location.href=this.value" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-600 focus:border-transparent">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}">Plus récent</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}">Prix croissant</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}">Prix décroissant</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'featured']) }}">En vedette</option>
                </select>
            </div>
        </div>

        <!-- Properties Grid -->
        @if($properties->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($properties as $property)
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl hover:shadow-violet-500/10 transform hover:-translate-y-3 transition-all duration-500 group border border-violet-100/50">
                        <div class="relative overflow-hidden">
                            @if($property->media->count() > 0)
                                <img src="{{ $property->media->first()->url }}"
                                     alt="{{ $property->title }}"
                                     class="w-full h-72 object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-72 bg-gradient-to-br from-violet-400 to-violet-600 flex items-center justify-center">
                                    <i class="fas fa-home text-white text-5xl"></i>
                                </div>
                            @endif

                            <!-- Property Type Badge -->
                            <div class="absolute top-6 left-6">
                                <span class="bg-gradient-to-r from-violet-500 to-violet-600 text-white px-4 py-2 rounded-2xl text-sm font-bold shadow-lg backdrop-blur-sm">
                                    {{ ucfirst($property->type) }}
                                </span>
                            </div>

                            <!-- Status Badge -->
                            <div class="absolute top-6 right-6">
                                <span class="bg-gradient-to-r from-violet-600 to-violet-800 text-white px-4 py-2 rounded-2xl text-sm font-bold shadow-lg backdrop-blur-sm">
                                    {{ $property->status === 'for_sale' ? 'À vendre' : ($property->status === 'for_rent' ? 'À louer' : 'Hôtel') }}
                                </span>
                            </div>

                            @if($property->featured)
                                <div class="absolute bottom-6 left-6">
                                    <span class="bg-gradient-to-r from-violet-500 to-violet-700 text-white px-4 py-2 rounded-2xl text-sm font-bold shadow-lg backdrop-blur-sm">
                                        <i class="fas fa-star mr-2"></i>
                                        Vedette
                                    </span>
                                </div>
                            @endif

                            <!-- Favorite Button -->
                            <div class="absolute bottom-6 right-6">
                                @auth
                                    <button class="favorite-toggle w-12 h-12 bg-white/95 backdrop-blur-sm rounded-2xl flex items-center justify-center text-gray-600 hover:text-red-500 hover:bg-white shadow-lg transition-all duration-300 group-hover:scale-110" data-property-id="{{ $property->id }}">
                                        <i class="fas fa-heart text-lg"></i>
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="w-12 h-12 bg-white/95 backdrop-blur-sm rounded-2xl flex items-center justify-center text-gray-600 hover:text-red-500 hover:bg-white shadow-lg transition-all duration-300 group-hover:scale-110">
                                        <i class="fas fa-heart text-lg"></i>
                                    </a>
                                @endauth
                            </div>

                            <!-- Overlay gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>

                        <div class="p-8">
                            <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-violet-700 transition-colors duration-300 leading-tight">
                                {{ $property->title }}
                            </h3>

                            <div class="flex items-center text-gray-600 mb-4">
                                <i class="fas fa-map-marker-alt mr-3 text-violet-500 text-lg"></i>
                                <span class="font-medium">{{ $property->city }}</span>
                            </div>

                            <div class="text-3xl font-bold bg-gradient-to-r from-violet-600 to-violet-800 bg-clip-text text-transparent mb-6">
                                {{ number_format($property->price, 0, ',', ' ') }} {{ $property->currency }}
                            </div>

                            @if($property->bedrooms || $property->bathrooms || $property->surface_area)
                                <div class="flex items-center justify-between text-gray-500 text-sm mb-6 bg-gray-50 rounded-2xl p-4">
                                    @if($property->bedrooms)
                                        <div class="flex items-center">
                                            <i class="fas fa-bed mr-2 text-violet-500"></i>
                                            <span class="font-semibold">{{ $property->bedrooms }} ch.</span>
                                        </div>
                                    @endif

                                    @if($property->bathrooms)
                                        <div class="flex items-center">
                                            <i class="fas fa-bath mr-2 text-violet-500"></i>
                                            <span class="font-semibold">{{ $property->bathrooms }} SDB</span>
                                        </div>
                                    @endif

                                    <div class="flex items-center">
                                        <i class="fas fa-ruler-combined mr-2 text-violet-500"></i>
                                        <span class="font-semibold">{{ number_format($property->surface_area, 0) }} m²</span>
                                    </div>
                                </div>
                            @endif

                            <a href="{{ route('properties.show', $property->slug) }}"
                               class="block w-full bg-gradient-to-r from-violet-600 to-violet-800 text-white text-center py-4 rounded-2xl font-bold hover:shadow-xl hover:shadow-violet-500/25 transform hover:scale-105 transition-all duration-300 group">
                                <span class="flex items-center justify-center">
                                    Voir les détails
                                    <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-200"></i>
                                </span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-12">
                {{ $properties->appends(request()->query())->links() }}
            </div>
        @else
            <!-- No Results -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Aucune propriété trouvée</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Essayez de modifier vos critères de recherche ou explorez toutes nos propriétés.
                </p>
                <a href="{{ route('properties.index') }}" 
                   class="inline-flex items-center bg-gradient-to-r from-violet-600 to-violet-600 text-white font-bold py-3 px-6 rounded-lg hover:shadow-lg transition-all duration-200">
                    <i class="fas fa-home mr-2"></i>
                    Voir toutes les propriétés
                </a>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Mobile filters toggle
    document.getElementById('mobile-filters-toggle').addEventListener('click', function() {
        const form = document.getElementById('filters-form');
        const icon = document.getElementById('mobile-filter-icon');

        form.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    });

    // Advanced filters toggle
    document.getElementById('advanced-filters-toggle').addEventListener('click', function() {
        const filters = document.getElementById('advanced-filters');
        const icon = this.querySelector('.fa-chevron-down');

        filters.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    });

    // Favorites functionality
    document.addEventListener('DOMContentLoaded', function() {
        const favoriteButtons = document.querySelectorAll('.favorite-toggle');

        favoriteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const propertyId = this.dataset.propertyId;
                const icon = this.querySelector('i');

                // Disable button during request
                this.disabled = true;

                fetch(`/api/favorites/${propertyId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.favorited) {
                            icon.classList.remove('text-gray-600');
                            icon.classList.add('text-red-500');
                        } else {
                            icon.classList.remove('text-red-500');
                            icon.classList.add('text-gray-600');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                })
                .finally(() => {
                    this.disabled = false;
                });
            });
        });
    });
</script>
@endpush
