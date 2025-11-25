@extends('layouts.admin')

@section('title', 'Gestion des Quartiers')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des Quartiers</h1>
        <button id="addNeighborhoodBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Ajouter un quartier
        </button>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.locations.neighborhoods.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                       placeholder="Nom du quartier..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                <select id="city" name="city" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Toutes les villes</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md mr-2">
                    <i class="fas fa-search mr-2"></i>Filtrer
                </button>
                <a href="{{ route('admin.locations.neighborhoods.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Liste des quartiers -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quartier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ville</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Propriétés</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($neighborhoods as $neighborhood)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $neighborhood->name }}</div>
                                <div class="text-sm text-gray-500">{{ $neighborhood->slug }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $neighborhood->city->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                    {{ $neighborhood->properties_count }} propriétés
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($neighborhood->is_active)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Actif</span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Inactif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="edit-neighborhood-btn text-blue-600 hover:text-blue-900 mr-3" data-neighborhood-id="{{ $neighborhood->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($neighborhood->properties_count == 0)
                                    <button class="delete-neighborhood-btn text-red-600 hover:text-red-900" data-neighborhood-id="{{ $neighborhood->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Aucun quartier trouvé
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($neighborhoods->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $neighborhoods->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Créer/Modifier -->
<div id="neighborhoodModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">Ajouter un quartier</h3>
            <form id="neighborhoodForm" method="POST">
                @csrf
                <div id="methodField"></div>
                
                <div class="mb-4">
                    <label for="city_id" class="block text-sm font-medium text-gray-700 mb-2">Ville *</label>
                    <select id="city_id" name="city_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sélectionnez une ville</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom du quartier *</label>
                    <input type="text" id="name" name="name" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="3" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                        <input type="number" id="latitude" name="latitude" step="any" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                        <input type="number" id="longitude" name="longitude" step="any" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" checked 
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Quartier actif</span>
                    </label>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelBtn"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = '{{ csrf_token() }}';
    const neighborhoodModal = document.getElementById('neighborhoodModal');
    const neighborhoodForm = document.getElementById('neighborhoodForm');
    const methodField = document.getElementById('methodField');
    const modalTitle = document.getElementById('modalTitle');
    const addNeighborhoodBtn = document.getElementById('addNeighborhoodBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const isActiveCheckbox = document.getElementById('is_active');

    function openCreateModal() {
        modalTitle.textContent = 'Ajouter un quartier';
        neighborhoodForm.action = "{{ route('admin.locations.neighborhoods.store') }}";
        methodField.innerHTML = '';
        neighborhoodForm.reset();
        isActiveCheckbox.checked = true;
        neighborhoodModal.classList.remove('hidden');
    }

    function openEditModal(neighborhoodId) {
        modalTitle.textContent = 'Modifier le quartier';
        neighborhoodForm.action = `/admin/locations/neighborhoods/${neighborhoodId}`;
        methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        neighborhoodModal.classList.remove('hidden');
    }

    function closeModal() {
        neighborhoodModal.classList.add('hidden');
    }

    function handleDelete(neighborhoodId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce quartier ?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/locations/neighborhoods/${neighborhoodId}`;
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Event Listeners
    addNeighborhoodBtn.addEventListener('click', openCreateModal);
    cancelBtn.addEventListener('click', closeModal);

    document.querySelectorAll('.edit-neighborhood-btn').forEach(button => {
        button.addEventListener('click', () => {
            openEditModal(button.dataset.neighborhoodId);
        });
    });

    document.querySelectorAll('.delete-neighborhood-btn').forEach(button => {
        button.addEventListener('click', () => {
            handleDelete(button.dataset.neighborhoodId);
        });
    });

    neighborhoodModal.addEventListener('click', (e) => {
        if (e.target === neighborhoodModal) {
            closeModal();
        }
    });
});
</script>
@endpush
