@extends('layouts.app')

@section('title', 'Villes - Carre Premium')
@section('description', 'Explorez les opportunités immobilières dans toutes les villes du Gabon.')

@section('content')
<!-- Hero Section -->
<section class="relative py-20 overflow-hidden">
    <!-- Background with violet gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-violet-600 via-violet-700 to-violet-800"></div>

    <!-- Decorative elements -->
    <div class="absolute inset-0">
        <div class="absolute top-10 left-10 w-32 h-32 bg-white/10 rounded-full animate-pulse"></div>
        <div class="absolute top-40 right-20 w-24 h-24 bg-violet-300/20 rounded-full animate-bounce"></div>
        <div class="absolute bottom-20 left-1/4 w-40 h-40 bg-white/5 rounded-full animate-pulse"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h1 class="text-4xl md:text-6xl font-bold mb-6">
            Explorez nos
            <span class="text-violet-200">villes</span>
        </h1>
        <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto opacity-90">
            Découvrez les opportunités immobilières dans toutes nos villes couvertes
        </p>

        <!-- Search Cities -->
        <div class="max-w-md mx-auto">
            <div class="relative">
                <input type="text"
                       id="city-search"
                       placeholder="Rechercher une ville..."
                       class="w-full px-6 py-4 rounded-full text-gray-900 border-0 focus:ring-4 focus:ring-violet-300/50 text-lg">
                <div class="absolute right-2 top-2 w-12 h-12 bg-violet-300 rounded-full flex items-center justify-center">
                    <i class="fas fa-search text-gray-900"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cities Grid -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Nos
                <span class="bg-gradient-to-r from-violet-600 to-violet-800 bg-clip-text text-transparent">destinations</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                {{ $cityStats->count() }} villes avec des opportunités immobilières exceptionnelles
            </p>
        </div>

        <div id="cities-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($cityStats as $city)
                <div class="city-card bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300 group"
                     data-city="{{ strtolower($city['name']) }}">
                    
                    <!-- City Header -->
                    <div class="relative h-48 bg-gradient-to-br from-violet-600 to-violet-800 overflow-hidden">
                        <!-- Decorative Pattern -->
                        <div class="absolute inset-0 opacity-20">
                            <div class="absolute top-4 left-4 w-16 h-16 border-2 border-white rounded-full"></div>
                            <div class="absolute top-8 right-8 w-12 h-12 border-2 border-violet-300 rounded-full"></div>
                            <div class="absolute bottom-6 left-8 w-20 h-20 border-2 border-white/50 rounded-full"></div>
                        </div>

                        <div class="relative z-10 p-6 h-full flex flex-col justify-between text-white">
                            <div>
                                <h3 class="text-2xl font-bold mb-2">{{ $city['name'] }}</h3>
                                <div class="flex items-center text-violet-300">
                                    <i class="fas fa-home mr-2"></i>
                                    <span class="font-semibold">{{ $city['total_properties'] }} propriétés</span>
                                </div>
                            </div>

                            <div class="flex justify-between items-end">
                                <div class="text-sm opacity-90">
                                    <div>{{ $city['for_sale'] }} à vendre</div>
                                    <div>{{ $city['for_rent'] }} à louer</div>
                                </div>

                                @if($city['average_price'])
                                    <div class="text-right">
                                        <div class="text-xs opacity-75">Prix moyen</div>
                                        <div class="font-bold text-violet-300">
                                            {{ number_format($city['average_price'], 0, ',', ' ') }} XAF
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Hover Effect -->
                        <div class="absolute inset-0 bg-violet-300/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>
                    
                    <!-- City Stats -->
                    <div class="p-6">
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="text-center">
                                <div class="w-12 h-12 bg-violet-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-home text-violet-600"></i>
                                </div>
                                <div class="text-lg font-bold text-gray-900">{{ $city['total_properties'] }}</div>
                                <div class="text-xs text-gray-600">Total</div>
                            </div>

                            <div class="text-center">
                                <div class="w-12 h-12 bg-violet-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-tag text-violet-600"></i>
                                </div>
                                <div class="text-lg font-bold text-gray-900">{{ $city['for_sale'] }}</div>
                                <div class="text-xs text-gray-600">À vendre</div>
                            </div>

                            <div class="text-center">
                                <div class="w-12 h-12 bg-violet-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-key text-violet-600"></i>
                                </div>
                                <div class="text-lg font-bold text-gray-900">{{ $city['for_rent'] }}</div>
                                <div class="text-xs text-gray-600">À louer</div>
                            </div>
                        </div>

                        <a href="{{ route('city', $city['slug']) }}"
                           class="block w-full bg-gradient-to-r from-violet-600 to-violet-800 text-white text-center py-3 rounded-lg font-bold hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                            <i class="fas fa-arrow-right mr-2"></i>
                            Explorer {{ $city['name'] }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- No Results Message -->
        <div id="no-results" class="hidden text-center py-16">
            <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-search text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Aucune ville trouvée</h3>
            <p class="text-gray-600 mb-8">
                Essayez avec un autre terme de recherche.
            </p>
            <button onclick="clearSearch()"
                    class="bg-gradient-to-r from-violet-600 to-violet-800 text-white font-bold py-3 px-6 rounded-lg hover:shadow-lg transition-all duration-200">
                Voir toutes les villes
            </button>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Nos statistiques en
                <span class="bg-gradient-to-r from-violet-600 to-violet-800 bg-clip-text text-transparent">chiffres</span>
            </h2>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-r from-violet-600 to-violet-800 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-200">
                    <i class="fas fa-city text-white text-2xl"></i>
                </div>
                <div class="text-3xl font-bold text-violet-600 mb-2">{{ $cityStats->count() }}</div>
                <div class="text-gray-600 font-medium">Villes couvertes</div>
            </div>

            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-r from-violet-800 to-violet-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-200">
                    <i class="fas fa-home text-white text-2xl"></i>
                </div>
                <div class="text-3xl font-bold text-violet-600 mb-2">{{ $cityStats->sum('total_properties') }}</div>
                <div class="text-gray-600 font-medium">Propriétés totales</div>
            </div>

            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-r from-violet-600 to-violet-800 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-200">
                    <i class="fas fa-tag text-white text-2xl"></i>
                </div>
                <div class="text-3xl font-bold text-violet-600 mb-2">{{ $cityStats->sum('for_sale') }}</div>
                <div class="text-gray-600 font-medium">À vendre</div>
            </div>

            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-r from-violet-800 to-violet-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-200">
                    <i class="fas fa-key text-white text-2xl"></i>
                </div>
                <div class="text-3xl font-bold text-violet-600 mb-2">{{ $cityStats->sum('for_rent') }}</div>
                <div class="text-gray-600 font-medium">À louer</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-violet-600 to-violet-800 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
            Votre ville n'est pas listée ?
        </h2>
        <p class="text-xl mb-8 opacity-90">
            Contactez-nous pour étendre notre couverture dans votre région
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('contact') }}"
               class="bg-violet-300 text-gray-900 font-bold py-4 px-8 rounded-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <i class="fas fa-envelope mr-2"></i>
                Nous contacter
            </a>
            <a href="{{ route('properties.index') }}"
               class="border-2 border-white text-white font-bold py-4 px-8 rounded-lg hover:bg-white hover:text-violet-600 transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <i class="fas fa-search mr-2"></i>
                Voir toutes les propriétés
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // City search functionality
    const searchInput = document.getElementById('city-search');
    const cityCards = document.querySelectorAll('.city-card');
    const noResults = document.getElementById('no-results');
    const citiesGrid = document.getElementById('cities-grid');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        let visibleCards = 0;
        
        cityCards.forEach(card => {
            const cityName = card.dataset.city;
            if (cityName.includes(searchTerm)) {
                card.style.display = 'block';
                visibleCards++;
            } else {
                card.style.display = 'none';
            }
        });
        
        if (visibleCards === 0 && searchTerm !== '') {
            noResults.classList.remove('hidden');
            citiesGrid.classList.add('hidden');
        } else {
            noResults.classList.add('hidden');
            citiesGrid.classList.remove('hidden');
        }
    });
    
    function clearSearch() {
        searchInput.value = '';
        cityCards.forEach(card => {
            card.style.display = 'block';
        });
        noResults.classList.add('hidden');
        citiesGrid.classList.remove('hidden');
    }
    
    // Add some animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    cityCards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
</script>
@endpush
