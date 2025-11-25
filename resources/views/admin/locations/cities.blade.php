@extends('layouts.admin')

@section('title', 'Gestion des Villes')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des Villes</h1>
        <button id="addCityBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            <i class="fas fa-plus mr-2"></i>Ajouter une ville
        </button>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.locations.cities.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                       placeholder="Nom de ville ou région..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="region" class="block text-sm font-medium text-gray-700 mb-2">Région</label>
                <select id="region" name="region" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Toutes les régions</option>
                    @foreach($regions as $region)
                        <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>
                            {{ $region }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md mr-2">
                    <i class="fas fa-search mr-2"></i>Filtrer
                </button>
                <a href="{{ route('admin.locations.cities.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Liste des villes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ville</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Région</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Propriétés</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quartiers</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($cities as $city)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $city->name }}</div>
                                <div class="text-sm text-gray-500">{{ $city->slug }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $city->region }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                    {{ $city->properties_count }} propriétés
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                                    {{ $city->neighborhoods_count }} quartiers
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($city->is_active)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Actif</span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Inactif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="edit-city-btn text-blue-600 hover:text-blue-900 mr-3" data-city-id="{{ $city->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @if($city->properties_count == 0)
                                    <button class="delete-city-btn text-red-600 hover:text-red-900" data-city-id="{{ $city->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Aucune ville trouvée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($cities->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $cities->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Créer/Modifier -->
<div id="cityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">Ajouter une ville</h3>
            <form id="cityForm" method="POST">
                @csrf
                <div id="methodField"></div>
                
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nom de la ville *</label>
                    <input type="text" id="name" name="name" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label for="region" class="block text-sm font-medium text-gray-700 mb-2">Région *</label>
                    <input type="text" id="region" name="region" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                        <span class="ml-2 text-sm text-gray-700">Ville active</span>
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
    const cityModal = document.getElementById('cityModal');
    const cityForm = document.getElementById('cityForm');
    const methodField = document.getElementById('methodField');
    const modalTitle = document.getElementById('modalTitle');
    const addCityBtn = document.getElementById('addCityBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const isActiveCheckbox = document.getElementById('is_active');

    function openCreateModal() {
        modalTitle.textContent = 'Ajouter une ville';
        cityForm.action = "{{ route('admin.locations.cities.store') }}";
        methodField.innerHTML = '';
        cityForm.reset();
        isActiveCheckbox.checked = true;
        cityModal.classList.remove('hidden');
    }

    function openEditModal(cityId) {
        modalTitle.textContent = 'Modifier la ville';
        cityForm.action = '/admin/locations/cities/' + cityId;
        methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        cityModal.classList.remove('hidden');
    }

    function closeModal() {
        cityModal.classList.add('hidden');
    }

    function handleDelete(cityId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette ville ?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/locations/cities/' + cityId;
            form.innerHTML = `
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Event Listeners
    addCityBtn.addEventListener('click', openCreateModal);
    cancelBtn.addEventListener('click', closeModal);

    document.querySelectorAll('.edit-city-btn').forEach(button => {
        button.addEventListener('click', () => {
            openEditModal(button.dataset.cityId);
        });
    });

    document.querySelectorAll('.delete-city-btn').forEach(button => {
        button.addEventListener('click', () => {
            handleDelete(button.dataset.cityId);
        });
    });

    cityModal.addEventListener('click', (e) => {
        if (e.target === cityModal) {
            closeModal();
        }
    });
});
</script>
@endpush
