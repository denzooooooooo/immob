@extends('layouts.admin')

@section('title', 'Gestion des Propriétés')

@section('header', 'Gestion des Propriétés')

@section('actions')
    <div class="flex space-x-3">
        <a href="{{ route('admin.properties.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i>
            Ajouter une propriété
        </a>
        <button onclick="toggleBulkActions()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <i class="fas fa-tasks mr-2"></i>
            Actions groupées
        </button>
    </div>
@endsection

@section('content')
<!-- Filtres -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-4 sm:p-6">
        <form action="{{ route('admin.properties.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">Tous</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publié</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Brouillon</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                </select>
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">Tous</option>
                    <option value="house" {{ request('type') === 'house' ? 'selected' : '' }}>Maison</option>
                    <option value="apartment" {{ request('type') === 'apartment' ? 'selected' : '' }}>Appartement</option>
                    <option value="land" {{ request('type') === 'land' ? 'selected' : '' }}>Terrain</option>
                    <option value="commercial" {{ request('type') === 'commercial' ? 'selected' : '' }}>Commercial</option>
                </select>
            </div>

            <div>
                <label for="city" class="block text-sm font-medium text-gray-700">Ville</label>
                <select name="city" id="city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">Toutes</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="agent" class="block text-sm font-medium text-gray-700">Agent</label>
                <select name="agent" id="agent" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">Tous</option>
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}" {{ request('agent') == $agent->id ? 'selected' : '' }}>
                            {{ $agent->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700">Trier par</label>
                <select name="sort" id="sort" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date de création</option>
                    <option value="price" {{ request('sort') === 'price' ? 'selected' : '' }}>Prix</option>
                    <option value="views" {{ request('sort') === 'views' ? 'selected' : '' }}>Vues</option>
                </select>
            </div>

            <div class="md:col-span-5 flex justify-end space-x-3">
                <a href="{{ route('admin.properties.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Réinitialiser
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Actions groupées -->
<div id="bulk-actions" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6 hidden">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <span class="text-sm text-yellow-800">
                <span id="selected-count">0</span> propriété(s) sélectionnée(s)
            </span>
        </div>
        <div class="flex space-x-3">
            <button onclick="bulkAction('publish')" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded text-green-700 bg-green-100 hover:bg-green-200">
                Publier
            </button>
            <button onclick="bulkAction('unpublish')" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded text-gray-700 bg-gray-100 hover:bg-gray-200">
                Dépublier
            </button>
            <button onclick="bulkAction('delete')" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded text-red-700 bg-red-100 hover:bg-red-200">
                Supprimer
            </button>
        </div>
    </div>
</div>

<!-- Liste des propriétés -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-medium text-gray-900">
            {{ $properties->total() }} propriété(s)
        </h2>
    </div>

    @if($properties->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Propriété
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Agent
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Prix
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Vues
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($properties as $property)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="selected_properties[]" value="{{ $property->id }}" class="property-checkbox rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($property->featured_image)
                                            <img class="h-10 w-10 rounded-md object-cover" src="{{ $property->featured_image }}" alt="">
                                        @else
                                            <div class="h-10 w-10 rounded-md bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-building text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ Str::limit($property->title, 30) }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $property->cityModel->name ?? $property->city }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $property->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $property->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ ucfirst($property->type) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($property->price) }} XAF</div>
                                <div class="text-sm text-gray-500">{{ $property->price_type }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $property->published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $property->published ? 'Publié' : 'Brouillon' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $property->views_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.properties.show', $property) }}" class="text-gray-400 hover:text-gray-500">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.properties.edit', $property) }}" class="text-blue-400 hover:text-blue-500">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.properties.destroy', $property) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette propriété ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-500">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $properties->withQueryString()->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-building text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune propriété trouvée</h3>
            <p class="text-gray-500">Aucune propriété ne correspond à vos critères de recherche.</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Gestion des cases à cocher
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.property-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSelectedCount();
});

document.querySelectorAll('.property-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedCount);
});

function updateSelectedCount() {
    const selected = document.querySelectorAll('.property-checkbox:checked');
    const count = selected.length;
    document.getElementById('selected-count').textContent = count;
    
    const bulkActions = document.getElementById('bulk-actions');
    if (count > 0) {
        bulkActions.classList.remove('hidden');
    } else {
        bulkActions.classList.add('hidden');
    }
}

function toggleBulkActions() {
    const bulkActions = document.getElementById('bulk-actions');
    bulkActions.classList.toggle('hidden');
}

function bulkAction(action) {
    const selected = document.querySelectorAll('.property-checkbox:checked');
    const ids = Array.from(selected).map(checkbox => checkbox.value);
    
    if (ids.length === 0) {
        alert('Veuillez sélectionner au moins une propriété.');
        return;
    }
    
    let confirmMessage = '';
    switch(action) {
        case 'publish':
            confirmMessage = `Êtes-vous sûr de vouloir publier ${ids.length} propriété(s) ?`;
            break;
        case 'unpublish':
            confirmMessage = `Êtes-vous sûr de vouloir dépublier ${ids.length} propriété(s) ?`;
            break;
        case 'delete':
            confirmMessage = `Êtes-vous sûr de vouloir supprimer ${ids.length} propriété(s) ? Cette action est irréversible.`;
            break;
    }
    
    if (confirm(confirmMessage)) {
        // Créer un formulaire pour envoyer les données
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.properties.bulk-action") }}';
        
        // Token CSRF
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Action
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        form.appendChild(actionInput);
        
        // IDs
        ids.forEach(id => {
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'properties[]';
            idInput.value = id;
            form.appendChild(idInput);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
