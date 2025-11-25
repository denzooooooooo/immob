@extends('layouts.app')

@section('title', 'Mes Favoris - ' . ($siteSettings['site_name'] ?? 'Monnkama'))
@section('description', 'Retrouvez toutes vos propriétés favorites sur ' . ($siteSettings['site_name'] ?? 'Monnkama'))

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Mes Favoris</h1>
                    <p class="text-gray-600 mt-2">Retrouvez toutes vos propriétés favorites</p>
                </div>
                
                <div class="flex items-center space-x-4">
                    <span class="favorites-count bg-gabon-blue text-white px-4 py-2 rounded-full font-semibold">
                        0 favoris
                    </span>
                    
                    @if(auth()->check())
                        <button class="bulk-favorite-btn bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors duration-200 hidden">
                            <i class="fas fa-heart-broken mr-2"></i>
                            Supprimer sélectionnés
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type de bien</label>
                    <select id="filter-type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gabon-blue focus:border-transparent">
                        <option value="">Tous les types</option>
                        <option value="house">Maison</option>
                        <option value="apartment">Appartement</option>
                        <option value="land">Terrain</option>
                        <option value="commercial">Commercial</option>
                        <option value="hotel">Hôtel</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                    <select id="filter-city" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gabon-blue focus:border-transparent">
                        <option value="">Toutes les villes</option>
                        <!-- Cities will be populated dynamically -->
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select id="filter-status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gabon-blue focus:border-transparent">
                        <option value="">Tous les statuts</option>
                        <option value="for_sale">À vendre</option>
                        <option value="for_rent">À louer</option>
                        <option value="hotel_room">Hôtel</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tri</label>
                    <select id="sort-by" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gabon-blue focus:border-transparent">
                        <option value="created_at">Plus récents</option>
                        <option value="price_asc">Prix croissant</option>
                        <option value="price_desc">Prix décroissant</option>
                        <option value="title">Nom A-Z</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Favorites Grid -->
        <div id="favorites-container">
            <!-- Loading state -->
            <div id="loading-state" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-gabon-blue"></div>
                <p class="text-gray-600 mt-4">Chargement de vos favoris...</p>
            </div>

            <!-- Empty state -->
            <div id="empty-state" class="text-center py-12 hidden">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-heart text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Aucun favori pour le moment</h3>
                <p class="text-gray-600 mb-6">Commencez à explorer nos propriétés et ajoutez vos coups de cœur !</p>
                <a href="{{ route('properties.index') }}" class="inline-flex items-center bg-gabon-blue text-white px-6 py-3 rounded-lg hover:bg-gabon-green transition-colors duration-200">
                    <i class="fas fa-search mr-2"></i>
                    Découvrir les propriétés
                </a>
            </div>

            <!-- Favorites grid -->
            <div id="favorites-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 hidden">
                <!-- Favorites will be populated here -->
            </div>
        </div>

        <!-- Pagination -->
        <div id="pagination-container" class="mt-8 hidden">
            <!-- Pagination will be populated here -->
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div id="bulk-actions-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Confirmer la suppression</h3>
            <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer les propriétés sélectionnées de vos favoris ?</p>
            
            <div class="flex justify-end space-x-4">
                <button id="cancel-bulk-action" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors duration-200">
                    Annuler
                </button>
                <button id="confirm-bulk-action" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors duration-200">
                    Supprimer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .property-card {
        transition: all 0.3s ease;
    }
    
    .property-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .property-checkbox {
        position: absolute;
        top: 1rem;
        left: 1rem;
        z-index: 10;
    }
    
    .bulk-actions-bar {
        position: fixed;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        padding: 1rem 2rem;
        z-index: 40;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let currentFilters = {};
    let selectedProperties = new Set();

    // Initialize favorites page
    loadFavorites();

    // Filter event listeners
    document.getElementById('filter-type').addEventListener('change', handleFilterChange);
    document.getElementById('filter-city').addEventListener('change', handleFilterChange);
    document.getElementById('filter-status').addEventListener('change', handleFilterChange);
    document.getElementById('sort-by').addEventListener('change', handleFilterChange);

    // Bulk actions
    document.getElementById('cancel-bulk-action').addEventListener('click', closeBulkModal);
    document.getElementById('confirm-bulk-action').addEventListener('click', confirmBulkAction);

    function handleFilterChange() {
        currentFilters = {
            type: document.getElementById('filter-type').value,
            city: document.getElementById('filter-city').value,
            status: document.getElementById('filter-status').value,
            sort: document.getElementById('sort-by').value
        };
        currentPage = 1;
        loadFavorites();
    }

    async function loadFavorites() {
        showLoading();
        
        try {
            const params = new URLSearchParams({
                page: currentPage,
                ...currentFilters
            });

            const response = await fetch(`/api/v1/favorites?${params}`, {
                headers: {
                    'Authorization': `Bearer ${getAuthToken()}`,
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Erreur lors du chargement des favoris');
            }

            const data = await response.json();
            displayFavorites(data.data);
            updateFavoritesCount(data.data.total);
            
        } catch (error) {
            console.error('Error loading favorites:', error);
            showError('Erreur lors du chargement des favoris');
        }
    }

    function displayFavorites(favoritesData) {
        const container = document.getElementById('favorites-container');
        const grid = document.getElementById('favorites-grid');
        const emptyState = document.getElementById('empty-state');
        const loadingState = document.getElementById('loading-state');

        loadingState.classList.add('hidden');

        if (!favoritesData.data || favoritesData.data.length === 0) {
            grid.classList.add('hidden');
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        grid.classList.remove('hidden');
        
        grid.innerHTML = favoritesData.data.map(favorite => createPropertyCard(favorite.property)).join('');
        
        // Setup pagination if needed
        if (favoritesData.last_page > 1) {
            setupPagination(favoritesData);
        }
    }

    function createPropertyCard(property) {
        const imageUrl = property.media && property.media.length > 0 
            ? property.media[0].path 
            : '/images/placeholder-property.jpg';

        return `
            <div class="property-card bg-white rounded-lg shadow-sm overflow-hidden relative">
                <input type="checkbox" class="property-checkbox" value="${property.id}" onchange="handlePropertySelection(this)">
                
                <div class="relative">
                    <img src="${imageUrl}" alt="${property.title}" class="w-full h-48 object-cover">
                    
                    <div class="absolute top-4 right-4">
                        <span class="bg-gabon-green text-white px-2 py-1 rounded text-sm">
                            ${property.status === 'for_sale' ? 'À vendre' : property.status === 'for_rent' ? 'À louer' : 'Hôtel'}
                        </span>
                    </div>
                    
                    <div class="absolute bottom-4 right-4">
                        <button class="favorite-btn w-10 h-10 bg-white/90 rounded-full flex items-center justify-center text-red-500 hover:bg-white transition-all duration-200" 
                                data-property-id="${property.id}" title="Retirer des favoris">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">${property.title}</h3>
                    
                    <div class="flex items-center text-gray-600 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-gabon-green"></i>
                        <span>${property.city}</span>
                    </div>
                    
                    <div class="text-xl font-bold text-gabon-blue mb-3">
                        ${new Intl.NumberFormat('fr-FR').format(property.price)} ${property.currency}
                    </div>
                    
                    ${property.bedrooms || property.bathrooms || property.surface_area ? `
                        <div class="flex items-center justify-between text-gray-500 text-sm mb-4">
                            ${property.bedrooms ? `<div class="flex items-center"><i class="fas fa-bed mr-1"></i><span>${property.bedrooms} ch.</span></div>` : ''}
                            ${property.bathrooms ? `<div class="flex items-center"><i class="fas fa-bath mr-1"></i><span>${property.bathrooms} SDB</span></div>` : ''}
                            <div class="flex items-center"><i class="fas fa-ruler-combined mr-1"></i><span>${new Intl.NumberFormat('fr-FR').format(property.surface_area)} m²</span></div>
                        </div>
                    ` : ''}
                    
                    <a href="/properties/${property.slug}" class="block w-full bg-gabon-blue text-white text-center py-2 rounded-lg hover:bg-gabon-green transition-colors duration-200">
                        Voir les détails
                    </a>
                </div>
            </div>
        `;
    }

    function showLoading() {
        document.getElementById('loading-state').classList.remove('hidden');
        document.getElementById('favorites-grid').classList.add('hidden');
        document.getElementById('empty-state').classList.add('hidden');
    }

    function showError(message) {
        document.getElementById('loading-state').classList.add('hidden');
        if (window.showNotification) {
            window.showNotification(message, 'error');
        }
    }

    function updateFavoritesCount(count) {
        const countElement = document.querySelector('.favorites-count');
        if (countElement) {
            countElement.textContent = `${count} favori${count !== 1 ? 's' : ''}`;
        }
    }

    function getAuthToken() {
        return localStorage.getItem('auth_token') || 
               sessionStorage.getItem('auth_token') || 
               document.querySelector('meta[name="auth-token"]')?.getAttribute('content');
    }

    // Global functions for property selection
    window.handlePropertySelection = function(checkbox) {
        if (checkbox.checked) {
            selectedProperties.add(checkbox.value);
        } else {
            selectedProperties.delete(checkbox.value);
        }
        
        updateBulkActionsVisibility();
    };

    function updateBulkActionsVisibility() {
        const bulkBtn = document.querySelector('.bulk-favorite-btn');
        if (selectedProperties.size > 0) {
            bulkBtn.classList.remove('hidden');
            bulkBtn.textContent = `Supprimer ${selectedProperties.size} sélectionné${selectedProperties.size > 1 ? 's' : ''}`;
        } else {
            bulkBtn.classList.add('hidden');
        }
    }

    function closeBulkModal() {
        document.getElementById('bulk-actions-modal').classList.add('hidden');
    }

    async function confirmBulkAction() {
        try {
            const response = await fetch('/api/v1/favorites/bulk', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${getAuthToken()}`,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    property_ids: Array.from(selectedProperties),
                    action: 'remove'
                })
            });

            if (response.ok) {
                const data = await response.json();
                if (window.showNotification) {
                    window.showNotification(data.message, 'success');
                }
                selectedProperties.clear();
                updateBulkActionsVisibility();
                loadFavorites();
            }
        } catch (error) {
            console.error('Bulk action error:', error);
            if (window.showNotification) {
                window.showNotification('Erreur lors de la suppression', 'error');
            }
        }
        
        closeBulkModal();
    }

    // Listen for favorite events from the favorites manager
    document.addEventListener('favoriteToggled', function(event) {
        if (!event.detail.isFavorited) {
            // Property was removed from favorites, reload the list
            loadFavorites();
        }
    });
});
</script>
@endpush
