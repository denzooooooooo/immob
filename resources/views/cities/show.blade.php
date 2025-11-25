@extends('layouts.app')

@section('title', $city->name . ' - Immobilier - Carre Premium')
@section('description', 'Découvrez toutes les propriétés disponibles à ' . $city->name . '. Maisons, appartements et locaux commerciaux.')

@section('content')
<!-- City Hero Section -->
<section class="relative py-20 overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-violet-600 via-violet-700 to-violet-800"></div>

    <!-- Decorative elements -->
    <div class="absolute inset-0">
        <div class="absolute top-10 left-10 w-32 h-32 bg-white/10 rounded-full animate-pulse"></div>
        <div class="absolute top-40 right-20 w-24 h-24 bg-violet-300/20 rounded-full animate-bounce"></div>
        <div class="absolute bottom-20 left-1/4 w-40 h-40 bg-white/5 rounded-full animate-pulse"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-white">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="hover:text-violet-300 transition-colors duration-200">Accueil</a></li>
                <li><i class="fas fa-chevron-right mx-2 text-xs"></i></li>
                <li><a href="{{ route('cities.index') }}" class="hover:text-violet-300 transition-colors duration-200">Villes</a></li>
                <li><i class="fas fa-chevron-right mx-2 text-xs"></i></li>
                <li class="text-violet-300">{{ $city->name }}</li>
            </ol>
        </nav>

        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                Immobilier à
                <span class="text-violet-300">{{ $city->name }}</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto opacity-90">
                Découvrez {{ $stats['total_properties'] }} propriétés exceptionnelles dans cette magnifique ville
            </p>
        </div>
    </div>
</section>

<!-- City Statistics -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center group">
                <div class="w-16 h-16 bg-gradient-to-r from-violet-600 to-violet-800 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-200">
                    <i class="fas fa-home text-white text-2xl"></i>
                </div>
                <div class="text-3xl font-bold text-violet-600 mb-2">{{ $stats['total_properties'] }}</div>
                <div class="text-gray-600 font-medium">Propriétés disponibles</div>
            </div>

            <div class="text-center group">
                <div class="w-16 h-16 bg-gradient-to-r from-violet-800 to-violet-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-200">
                    <i class="fas fa-tag text-white text-2xl"></i>
                </div>
                <div class="text-3xl font-bold text-violet-600 mb-2">{{ $stats['for_sale'] }}</div>
                <div class="text-gray-600 font-medium">À vendre</div>
            </div>

            <div class="text-center group">
                <div class="w-16 h-16 bg-gradient-to-r from-violet-600 to-violet-800 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-200">
                    <i class="fas fa-key text-white text-2xl"></i>
                </div>
                <div class="text-3xl font-bold text-violet-600 mb-2">{{ $stats['for_rent'] }}</div>
                <div class="text-gray-600 font-medium">À louer</div>
            </div>

            <div class="text-center group">
                <div class="w-16 h-16 bg-gradient-to-r from-violet-800 to-violet-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-200">
                    <i class="fas fa-chart-line text-white text-2xl"></i>
                </div>
                <div class="text-3xl font-bold text-violet-600 mb-2">
                    {{ $stats['average_price'] ? number_format($stats['average_price'] / 1000000, 1) . 'M' : 'N/A' }}
                </div>
                <div class="text-gray-600 font-medium">Prix moyen (XAF)</div>
            </div>
        </div>
    </div>
</section>

<!-- Price Range Info -->
@if($stats['min_price'] && $stats['max_price'])
    <section class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">
                    Fourchette de prix à {{ $city->name }}
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-sm text-gray-600 mb-2">Prix minimum</div>
                        <div class="text-2xl font-bold text-violet-600">
                            {{ number_format($stats['min_price'], 0, ',', ' ') }} XAF
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-sm text-gray-600 mb-2">Prix moyen</div>
                        <div class="text-2xl font-bold text-violet-800">
                            {{ number_format($stats['average_price'], 0, ',', ' ') }} XAF
                        </div>
                    </div>

                    <div class="text-center">
                        <div class="text-sm text-gray-600 mb-2">Prix maximum</div>
                        <div class="text-2xl font-bold text-violet-600">
                            {{ number_format($stats['max_price'], 0, ',', ' ') }} XAF
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

<!-- Neighborhoods Section -->
@if($neighborhoods->count() > 0)
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Quartiers populaires à
                    <span class="bg-gradient-to-r from-violet-600 to-violet-800 bg-clip-text text-transparent">{{ $city->name }}</span>
                </h2>
                <p class="text-xl text-gray-600">
                    Explorez les différents quartiers de la ville
                </p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($neighborhoods as $neighborhood)
                    <div class="bg-gradient-to-br from-violet-600 to-violet-800 rounded-xl p-6 text-white hover:shadow-xl transform hover:scale-105 transition-all duration-300 group cursor-pointer">
                        <h3 class="text-lg font-bold mb-2">{{ $neighborhood->name }}</h3>
                        <div class="flex items-center text-violet-300">
                            <i class="fas fa-home mr-2"></i>
                            <span class="font-semibold">{{ $neighborhood->properties_count }} propriétés</span>
                        </div>

                        <!-- Decorative element -->
                        <div class="mt-4 w-8 h-8 bg-white/20 rounded-full group-hover:scale-125 transition-transform duration-300"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

<!-- Properties Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center mb-12">
            <div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Propriétés à
                    <span class="bg-gradient-to-r from-violet-600 to-violet-800 bg-clip-text text-transparent">{{ $city->name }}</span>
                </h2>
                <p class="text-xl text-gray-600">
                    {{ $properties->total() }} propriété{{ $properties->total() > 1 ? 's' : '' }} disponible{{ $properties->total() > 1 ? 's' : '' }}
                </p>
            </div>
            
            <!-- Quick Filters -->
            <div class="flex flex-wrap gap-3 mt-6 md:mt-0">
                <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}"
                   class="px-4 py-2 rounded-full {{ !request('status') ? 'bg-violet-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }} transition-all duration-200">
                    Toutes
                </a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'for_sale']) }}"
                   class="px-4 py-2 rounded-full {{ request('status') === 'for_sale' ? 'bg-violet-800 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }} transition-all duration-200">
                    À vendre
                </a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'for_rent']) }}"
                   class="px-4 py-2 rounded-full {{ request('status') === 'for_rent' ? 'bg-violet-300 text-gray-900' : 'bg-white text-gray-700 hover:bg-gray-100' }} transition-all duration-200">
                    À louer
                </a>
            </div>
        </div>

        @if($properties->count() > 0)
            <!-- Properties Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($properties as $property)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 group">
                        <div class="relative overflow-hidden">
                            @if($property->media->count() > 0)
                                <img src="{{ $property->media->first()->path }}" 
                                     alt="{{ $property->title }}"
                                     class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                                <div class="w-full h-64 bg-gradient-to-br from-gabon-green to-gabon-blue flex items-center justify-center">
                                    <i class="fas fa-home text-white text-4xl"></i>
                                </div>
                            @endif
                            
                            <!-- Property Type Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="bg-gabon-yellow text-gray-900 px-3 py-1 rounded-full text-sm font-bold">
                                    {{ ucfirst($property->type) }}
                                </span>
                            </div>
                            
                            <!-- Status Badge -->
                            <div class="absolute top-4 right-4">
                                <span class="bg-gabon-green text-white px-3 py-1 rounded-full text-sm font-bold">
                                    {{ $property->status === 'for_sale' ? 'À vendre' : ($property->status === 'for_rent' ? 'À louer' : 'Hôtel') }}
                                </span>
                            </div>
                            
                            @if($property->featured)
                                <div class="absolute bottom-4 left-4">
                                    <span class="bg-gabon-blue text-white px-3 py-1 rounded-full text-sm font-bold">
                                        <i class="fas fa-star mr-1"></i>
                                        Vedette
                                    </span>
                                </div>
                            @endif
                            
                            <!-- Favorite Button -->
                            <div class="absolute bottom-4 right-4">
                                <button class="w-10 h-10 bg-white/90 rounded-full flex items-center justify-center text-gray-600 hover:text-red-500 hover:bg-white transition-all duration-200">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-violet-600 transition-colors duration-200">
                                {{ $property->title }}
                            </h3>
                            
                            <div class="flex items-center text-gray-600 mb-3">
                                <i class="fas fa-map-marker-alt mr-2 text-violet-600"></i>
                                <span>{{ $property->address }}</span>
                            </div>

                            <div class="text-2xl font-bold text-violet-600 mb-4">
                                {{ number_format($property->price, 0, ',', ' ') }} {{ $property->currency }}
                            </div>
                            
                            @if($property->bedrooms || $property->bathrooms || $property->surface_area)
                                <div class="flex items-center justify-between text-gray-500 text-sm mb-4">
                                    @if($property->bedrooms)
                                        <div class="flex items-center">
                                            <i class="fas fa-bed mr-1"></i>
                                            <span>{{ $property->bedrooms }} ch.</span>
                                        </div>
                                    @endif
                                    
                                    @if($property->bathrooms)
                                        <div class="flex items-center">
                                            <i class="fas fa-bath mr-1"></i>
                                            <span>{{ $property->bathrooms }} SDB</span>
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center">
                                        <i class="fas fa-ruler-combined mr-1"></i>
                                        <span>{{ number_format($property->surface_area, 0) }} m²</span>
                                    </div>
                                </div>
                            @endif
                            
                            <a href="{{ route('properties.show', $property->slug) }}"
                               class="block w-full bg-gradient-to-r from-violet-600 to-violet-800 text-white text-center py-3 rounded-lg font-bold hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                Voir les détails
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
            <!-- No Properties -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-home text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Aucune propriété disponible</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Il n'y a actuellement aucune propriété disponible à {{ $city->name }} avec vos critères.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('properties.index') }}"
                       class="bg-gradient-to-r from-violet-600 to-violet-800 text-white font-bold py-3 px-6 rounded-lg hover:shadow-lg transition-all duration-200">
                        <i class="fas fa-search mr-2"></i>
                        Voir toutes les propriétés
                    </a>
                    <a href="{{ route('cities.index') }}"
                       class="border-2 border-violet-600 text-violet-600 font-bold py-3 px-6 rounded-lg hover:bg-violet-600 hover:text-white transition-all duration-200">
                        <i class="fas fa-map mr-2"></i>
                        Autres villes
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-violet-600 to-violet-800 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
            Vous cherchez quelque chose de spécifique à {{ $city->name }} ?
        </h2>
        <p class="text-xl mb-8 opacity-90">
            Nos agents locaux connaissent parfaitement le marché et peuvent vous aider
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('contact') }}"
               class="bg-violet-300 text-gray-900 font-bold py-4 px-8 rounded-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <i class="fas fa-user-tie mr-2"></i>
                Contacter un agent
            </a>
            <a href="{{ route('properties.index', ['city' => $city->name]) }}"
               class="border-2 border-white text-white font-bold py-4 px-8 rounded-lg hover:bg-white hover:text-violet-600 transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <i class="fas fa-filter mr-2"></i>
                Recherche avancée
            </a>
        </div>
    </div>
</section>
@endsection
