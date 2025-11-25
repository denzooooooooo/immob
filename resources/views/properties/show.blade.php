@extends('layouts.app')

@section('title', $property->title . ' - Carre Premium')
@section('description', Str::limit($property->description, 160))

@section('content')
<!-- Property Hero Section -->
<section class="relative bg-gradient-to-br from-violet-900 via-red-900 to-violet-900 overflow-hidden">
    <!-- Background decoration -->
    <div class="absolute inset-0">
        <div class="absolute top-10 left-10 w-32 h-32 bg-violet-500/20 rounded-full blur-xl"></div>
        <div class="absolute bottom-10 right-10 w-40 h-40 bg-red-500/15 rounded-full blur-xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-violet-500/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 py-12">
        <div class="text-center text-white">
            <div class="inline-flex items-center bg-white/10 backdrop-blur-sm rounded-full px-6 py-3 mb-6">
                <span class="bg-gabon-yellow text-gray-900 px-3 py-1 rounded-full text-sm font-bold mr-3">
                    {{ ucfirst($property->type) }}
                </span>
                <span class="text-sm">{{ $property->status === 'for_sale' ? 'À vendre' : ($property->status === 'for_rent' ? 'À louer' : 'Hôtel') }}</span>
                @if($property->featured)
                    <span class="ml-3 bg-gabon-blue text-white px-3 py-1 rounded-full text-sm font-bold">
                        <i class="fas fa-star mr-1"></i>Vedette
                    </span>
                @endif
            </div>

            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                {{ $property->title }}
            </h1>

            <div class="flex items-center justify-center text-xl mb-8">
                <i class="fas fa-map-marker-alt mr-3 text-violet-300"></i>
                <span>{{ $property->address }}, {{ $property->city }}</span>
            </div>

            <div class="text-6xl md:text-7xl font-bold mb-8 bg-gradient-to-r from-white to-gray-200 bg-clip-text text-transparent">
                {{ number_format($property->price, 0, ',', ' ') }} {{ $property->currency }}
            </div>

            <div class="flex flex-wrap justify-center gap-4">
                <button class="bg-white text-gray-900 font-bold py-4 px-8 rounded-2xl hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-xl flex items-center">
                    <i class="fas fa-phone mr-3"></i>
                    Appeler maintenant
                </button>
                <button class="bg-violet-600 text-white font-bold py-4 px-8 rounded-2xl hover:bg-violet-700 transition-all duration-300 transform hover:scale-105 shadow-xl flex items-center">
                    <i class="fas fa-envelope mr-3"></i>
                    Envoyer un message
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Property Images Gallery -->
@if($property->media->count() > 0)
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Main Image -->
            <div class="relative h-96 md:h-[600px] overflow-hidden">
                <img id="main-image" src="{{ $property->media->first()->url }}"
                     alt="{{ $property->title }}"
                     class="w-full h-full object-cover">

                <!-- Navigation Arrows -->
                @if($property->media->count() > 1)
                    <button id="prev-btn" class="absolute left-6 top-1/2 transform -translate-y-1/2 w-14 h-14 bg-white/20 backdrop-blur-sm text-white rounded-full flex items-center justify-center hover:bg-white/30 transition-all duration-300 shadow-lg">
                        <i class="fas fa-chevron-left text-xl"></i>
                    </button>
                    <button id="next-btn" class="absolute right-6 top-1/2 transform -translate-y-1/2 w-14 h-14 bg-white/20 backdrop-blur-sm text-white rounded-full flex items-center justify-center hover:bg-white/30 transition-all duration-300 shadow-lg">
                        <i class="fas fa-chevron-right text-xl"></i>
                    </button>
                @endif

                <!-- Image Counter -->
                <div class="absolute bottom-6 right-6 bg-black/70 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-semibold">
                    <span id="current-image">1</span> / {{ $property->media->count() }}
                </div>
            </div>

            <!-- Thumbnails -->
            @if($property->media->count() > 1)
                <div class="p-6 bg-gray-50">
                    <div class="flex space-x-4 overflow-x-auto pb-2">
                        @foreach($property->media as $index => $media)
                            <button class="thumbnail flex-shrink-0 w-24 h-24 rounded-xl overflow-hidden border-3 {{ $index === 0 ? 'border-violet-500 shadow-lg' : 'border-gray-300' }} hover:border-violet-500 transition-all duration-300"
                                    data-index="{{ $index }}">
                                <img src="{{ $media->url }}"
                                     alt="Image {{ $index + 1 }}"
                                     class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endif

<!-- Property Details -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-12">
                <!-- Property Features -->
                @if($property->bedrooms || $property->bathrooms || $property->surface_area)
                    <div class="bg-gradient-to-br from-gray-50 to-white rounded-3xl p-8 shadow-xl border border-gray-100">
                        <h3 class="text-3xl font-bold text-gray-900 mb-8 text-center">Caractéristiques principales</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                            @if($property->bedrooms)
                                <div class="text-center group">
                                    <div class="w-20 h-20 bg-gradient-to-br from-violet-500 to-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:shadow-xl transition-all duration-300">
                                        <i class="fas fa-bed text-white text-2xl"></i>
                                    </div>
                                    <div class="text-3xl font-bold text-gray-900 mb-1">{{ $property->bedrooms }}</div>
                                    <div class="text-gray-600 font-medium">Chambres</div>
                                </div>
                            @endif

                            @if($property->bathrooms)
                                <div class="text-center group">
                                    <div class="w-20 h-20 bg-gradient-to-br from-violet-500 to-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:shadow-xl transition-all duration-300">
                                        <i class="fas fa-bath text-white text-2xl"></i>
                                    </div>
                                    <div class="text-3xl font-bold text-gray-900 mb-1">{{ $property->bathrooms }}</div>
                                    <div class="text-gray-600 font-medium">Salles de bain</div>
                                </div>
                            @endif

                            <div class="text-center group">
                                <div class="w-20 h-20 bg-gradient-to-br from-violet-500 to-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:shadow-xl transition-all duration-300">
                                    <i class="fas fa-ruler-combined text-white text-2xl"></i>
                                </div>
                                <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($property->surface_area, 0) }}</div>
                                <div class="text-gray-600 font-medium">m²</div>
                            </div>

                            <div class="text-center group">
                                <div class="w-20 h-20 bg-gradient-to-br from-violet-500 to-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:shadow-xl transition-all duration-300">
                                    <i class="fas fa-eye text-white text-2xl"></i>
                                </div>
                                <div class="text-3xl font-bold text-gray-900 mb-1">{{ $property->views_count }}</div>
                                <div class="text-gray-600 font-medium">Vues</div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Description -->
                <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100">
                    <h3 class="text-3xl font-bold text-gray-900 mb-6">Description de la propriété</h3>
                    <div class="prose prose-xl max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($property->description)) !!}
                    </div>
                </div>

                <!-- Property Details -->
                @if($property->details)
                    <div class="bg-white rounded-3xl p-8 shadow-xl border border-gray-100">
                        <h3 class="text-3xl font-bold text-gray-900 mb-8">Détails supplémentaires</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($property->details->year_built)
                                <div class="flex justify-between items-center py-4 px-6 bg-gray-50 rounded-xl">
                                    <span class="text-gray-700 font-medium">Année de construction</span>
                                    <span class="font-bold text-violet-600">{{ $property->details->year_built }}</span>
                                </div>
                            @endif

                            @if($property->details->parking_spaces)
                                <div class="flex justify-between items-center py-4 px-6 bg-gray-50 rounded-xl">
                                    <span class="text-gray-700 font-medium">Places de parking</span>
                                    <span class="font-bold text-violet-600">{{ $property->details->parking_spaces }}</span>
                                </div>
                            @endif

                            @if($property->details->garden)
                                <div class="flex justify-between items-center py-4 px-6 bg-green-50 rounded-xl">
                                    <span class="text-gray-700 font-medium">Jardin</span>
                                    <span class="font-bold text-green-600 flex items-center">
                                        <i class="fas fa-check-circle mr-2"></i>Oui
                                    </span>
                                </div>
                            @endif

                            @if($property->details->swimming_pool)
                                <div class="flex justify-between items-center py-4 px-6 bg-blue-50 rounded-xl">
                                    <span class="text-gray-700 font-medium">Piscine</span>
                                    <span class="font-bold text-blue-600 flex items-center">
                                        <i class="fas fa-check-circle mr-2"></i>Oui
                                    </span>
                                </div>
                            @endif

                            @if($property->details->security_system)
                                <div class="flex justify-between items-center py-4 px-6 bg-yellow-50 rounded-xl">
                                    <span class="text-gray-700 font-medium">Sécurité</span>
                                    <span class="font-bold text-yellow-600 flex items-center">
                                        <i class="fas fa-shield-alt mr-2"></i>Sécurisé
                                    </span>
                                </div>
                            @endif

                            @if($property->details->furnished)
                                <div class="flex justify-between items-center py-4 px-6 bg-purple-50 rounded-xl">
                                    <span class="text-gray-700 font-medium">Meublé</span>
                                    <span class="font-bold text-purple-600 flex items-center">
                                        <i class="fas fa-couch mr-2"></i>Oui
                                    </span>
                                </div>
                            @endif

                            @if($property->details->air_conditioning)
                                <div class="flex justify-between items-center py-4 px-6 bg-orange-50 rounded-xl">
                                    <span class="text-gray-700 font-medium">Climatisation</span>
                                    <span class="font-bold text-orange-600 flex items-center">
                                        <i class="fas fa-snowflake mr-2"></i>Oui
                                    </span>
                                </div>
                            @endif

                            @if($property->details->internet)
                                <div class="flex justify-between items-center py-4 px-6 bg-indigo-50 rounded-xl">
                                    <span class="text-gray-700 font-medium">Internet</span>
                                    <span class="font-bold text-indigo-600 flex items-center">
                                        <i class="fas fa-wifi mr-2"></i>Oui
                                    </span>
                                </div>
                            @endif

                            @if($property->details->balcony)
                                <div class="flex justify-between items-center py-4 px-6 bg-teal-50 rounded-xl">
                                    <span class="text-gray-700 font-medium">Balcon</span>
                                    <span class="font-bold text-teal-600 flex items-center">
                                        <i class="fas fa-building mr-2"></i>Oui
                                    </span>
                                </div>
                            @endif

                            @if($property->details->elevator)
                                <div class="flex justify-between items-center py-4 px-6 bg-cyan-50 rounded-xl">
                                    <span class="text-gray-700 font-medium">Ascenseur</span>
                                    <span class="font-bold text-cyan-600 flex items-center">
                                        <i class="fas fa-arrow-up mr-2"></i>Oui
                                    </span>
                                </div>
                            @endif

                            @if($property->details->garage)
                                <div class="flex justify-between items-center py-4 px-6 bg-slate-50 rounded-xl">
                                    <span class="text-gray-700 font-medium">Garage</span>
                                    <span class="font-bold text-slate-600 flex items-center">
                                        <i class="fas fa-car mr-2"></i>Oui
                                    </span>
                                </div>
                            @endif

                            @if($property->details->terrace)
                                <div class="flex justify-between items-center py-4 px-6 bg-emerald-50 rounded-xl">
                                    <span class="text-gray-700 font-medium">Terrasse</span>
                                    <span class="font-bold text-emerald-600 flex items-center">
                                        <i class="fas fa-home mr-2"></i>Oui
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Contact Card -->
                <div class="bg-gradient-to-br from-violet-600 via-red-600 to-violet-800 rounded-3xl p-8 text-white shadow-2xl sticky top-24">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold mb-3">Intéressé par cette propriété ?</h3>
                        <p class="text-violet-100">Notre équipe vous répondra rapidement</p>
                    </div>

                    <!-- Agent Info -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 mb-8">
                        <div class="flex items-center">
                            <img src="{{ $property->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($property->user->name) }}"
                                 alt="{{ $property->user->name }}"
                                 class="w-16 h-16 rounded-full mr-4 border-2 border-white/30">
                            <div>
                                <div class="font-bold text-lg mb-1">{{ $property->user->name }}</div>
                                <div class="text-violet-200 flex items-center text-sm">
                                    <i class="fas fa-user-tie mr-2"></i>
                                    Agent immobilier certifié
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <form action="{{ route('contact.property', $property) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <input type="text"
                                   name="name"
                                   placeholder="Votre nom complet"
                                   required
                                   class="w-full px-4 py-3 rounded-xl text-gray-900 border-0 focus:ring-4 focus:ring-white/30 bg-white/90 backdrop-blur-sm placeholder-gray-500 font-medium">
                        </div>

                        <div>
                            <input type="email"
                                   name="email"
                                   placeholder="votre.email@exemple.com"
                                   required
                                   class="w-full px-4 py-3 rounded-xl text-gray-900 border-0 focus:ring-4 focus:ring-white/30 bg-white/90 backdrop-blur-sm placeholder-gray-500 font-medium">
                        </div>

                        <div>
                            <input type="tel"
                                   name="phone"
                                   placeholder="+225 XX XX XX XX"
                                   class="w-full px-4 py-3 rounded-xl text-gray-900 border-0 focus:ring-4 focus:ring-white/30 bg-white/90 backdrop-blur-sm placeholder-gray-500 font-medium">
                        </div>

                        <div>
                            <textarea name="message"
                                      rows="4"
                                      placeholder="Bonjour, je suis intéressé(e) par cette propriété. Pourriez-vous me contacter pour organiser une visite ?"
                                      class="w-full px-4 py-3 rounded-xl text-gray-900 border-0 focus:ring-4 focus:ring-white/30 bg-white/90 backdrop-blur-sm placeholder-gray-500 resize-none font-medium"></textarea>
                        </div>

                        <button type="submit"
                                class="w-full bg-gradient-to-r from-white to-gray-100 text-gray-900 font-bold py-4 px-6 rounded-xl hover:shadow-xl hover:shadow-white/25 transform hover:scale-105 transition-all duration-300 flex items-center justify-center text-lg">
                            <i class="fas fa-paper-plane mr-3"></i>
                            Envoyer ma demande
                        </button>
                    </form>

                    <!-- Quick Actions -->
                    <div class="grid grid-cols-2 gap-4 mt-8">
                        @auth
                            <button id="favorite-btn"
                                    data-property-id="{{ $property->id }}"
                                    class="favorite-toggle bg-white/20 backdrop-blur-sm text-white font-semibold py-3 px-4 rounded-xl hover:bg-white/30 transition-all duration-300 flex items-center justify-center shadow-lg">
                                <i class="fas fa-heart mr-2 {{ $property->isFavoritedBy(auth()->user()) ? 'text-red-300' : '' }}"></i>
                                <span class="text-sm">{{ $property->isFavoritedBy(auth()->user()) ? 'Favoris' : 'Favoris' }}</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="bg-white/20 backdrop-blur-sm text-white font-semibold py-3 px-4 rounded-xl hover:bg-white/30 transition-all duration-300 flex items-center justify-center shadow-lg">
                                <i class="fas fa-heart mr-2"></i>
                                <span class="text-sm">Favoris</span>
                            </a>
                        @endauth
                        <button id="share-btn" class="bg-white/20 backdrop-blur-sm text-white font-semibold py-3 px-4 rounded-xl hover:bg-white/30 transition-all duration-300 flex items-center justify-center shadow-lg">
                            <i class="fas fa-share mr-2"></i>
                            <span class="text-sm">Partager</span>
                        </button>
                    </div>
                </div>

                <!-- Property Stats -->
                <div class="bg-white/90 backdrop-blur-sm border border-white/20 rounded-3xl p-8 shadow-xl mt-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-chart-bar mr-3 text-violet-600"></i>
                        Informations
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-gray-600 flex items-center">
                                <i class="fas fa-calendar mr-2 text-violet-500"></i>
                                Publié le
                            </span>
                            <span class="font-bold text-gray-900">{{ $property->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-gray-600 flex items-center">
                                <i class="fas fa-eye mr-2 text-violet-500"></i>
                                Nombre de vues
                            </span>
                            <span class="font-bold text-gray-900">{{ $property->views_count }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3">
                            <span class="text-gray-600 flex items-center">
                                <i class="fas fa-hashtag mr-2 text-violet-500"></i>
                                Référence
                            </span>
                            <span class="font-bold text-gray-900">#{{ $property->id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Similar Properties -->
@if($similarProperties->count() > 0)
    <section class="py-16 bg-gradient-to-br from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">
                    Propriétés <span class="bg-gradient-to-r from-violet-600 to-red-600 bg-clip-text text-transparent">similaires</span>
                </h2>
                <p class="text-xl text-gray-600">Découvrez d'autres biens qui pourraient vous intéresser</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($similarProperties as $similar)
                    <div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 group">
                        <div class="relative overflow-hidden">
                                @if($similar->media->count() > 0)
                                    <img src="{{ $similar->media->first()->url }}"
                                         alt="{{ $similar->title }}"
                                         class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-violet-500 to-red-500 flex items-center justify-center">
                                        <i class="fas fa-home text-white text-3xl"></i>
                                    </div>
                                @endif
                                <div class="absolute top-4 right-4 bg-violet-600 text-white px-3 py-1 rounded-full text-xs font-bold">
                                    {{ ucfirst($similar->type) }}
                                </div>
                        </div>

                        <div class="p-6">
                            <h3 class="font-bold text-gray-900 mb-3 group-hover:text-violet-600 transition-colors duration-300 line-clamp-2">
                                {{ Str::limit($similar->title, 50) }}
                            </h3>

                            <div class="text-violet-600 font-bold text-xl mb-4">
                                {{ number_format($similar->price, 0, ',', ' ') }} {{ $similar->currency }}
                            </div>

                            <div class="flex items-center text-gray-500 text-sm mb-4">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                {{ $similar->city }}
                            </div>

                            <a href="{{ route('properties.show', $similar->slug) }}"
                               class="block w-full bg-gradient-to-r from-violet-600 to-red-600 text-white text-center py-3 rounded-xl font-semibold hover:shadow-xl hover:shadow-violet-500/25 transform hover:scale-105 transition-all duration-300">
                                Voir les détails
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image gallery functionality
    const images = @json($property->media->pluck('url'));
    let currentIndex = 0;

    function updateMainImage(index) {
        const mainImage = document.querySelector('#main-image');
        const currentImageSpan = document.getElementById('current-image');
        const thumbnails = document.querySelectorAll('.thumbnail');

        if (mainImage && images[index]) {
            mainImage.src = images[index];
            currentImageSpan.textContent = index + 1;
            currentIndex = index;

            // Update thumbnail borders
            thumbnails.forEach((thumb, i) => {
                if (i === index) {
                    thumb.classList.remove('border-gray-300');
                    thumb.classList.add('border-violet-500', 'shadow-lg');
                } else {
                    thumb.classList.remove('border-violet-500', 'shadow-lg');
                    thumb.classList.add('border-gray-300');
                }
            });
        }
    }

    // Thumbnail clicks
    document.querySelectorAll('.thumbnail').forEach((thumb, index) => {
        thumb.addEventListener('click', () => updateMainImage(index));
    });

    // Navigation arrows
    const prevBtn = document.getElementById('prev-btn');
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            const newIndex = currentIndex > 0 ? currentIndex - 1 : images.length - 1;
            updateMainImage(newIndex);
        });
    }

    const nextBtn = document.getElementById('next-btn');
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            const newIndex = currentIndex < images.length - 1 ? currentIndex + 1 : 0;
            updateMainImage(newIndex);
        });
    }

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
            const prevBtn = document.getElementById('prev-btn');
            if (prevBtn) prevBtn.click();
        } else if (e.key === 'ArrowRight') {
            const nextBtn = document.getElementById('next-btn');
            if (nextBtn) nextBtn.click();
        }
    });

    // Favorites functionality
    const favoriteBtn = document.getElementById('favorite-btn');
    if (favoriteBtn) {
        favoriteBtn.addEventListener('click', function() {
            const propertyId = this.dataset.propertyId;
            const icon = this.querySelector('i');
            const span = this.querySelector('span');

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
                        icon.classList.add('text-red-300');
                        span.textContent = 'Favoris';
                    } else {
                        icon.classList.remove('text-red-300');
                        span.textContent = 'Favoris';
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
    }

    // Share functionality
    const shareBtn = document.getElementById('share-btn');
    if (shareBtn) {
        shareBtn.addEventListener('click', function() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $property->title }}',
                    text: 'Découvrez cette propriété exceptionnelle sur Carre Premium',
                    url: window.location.href
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    // Show success message
                    const notification = document.createElement('div');
                    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-xl shadow-2xl z-50 font-semibold';
                    notification.textContent = 'Lien copié dans le presse-papiers !';
                    document.body.appendChild(notification);
                    setTimeout(() => notification.remove(), 3000);
                });
            }
        });
    }
});
</script>
@endpush
