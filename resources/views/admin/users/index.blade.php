@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')

@section('header', 'Gestion des Utilisateurs')

@section('actions')
    <div class="flex space-x-3">
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
            <i class="fas fa-user-plus mr-2"></i>
            Ajouter un utilisateur
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
        <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
                <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">Tous</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="agent" {{ request('role') === 'agent' ? 'selected' : '' }}>Agent</option>
                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Utilisateur</option>
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">Tous</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactif</option>
                    <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Banni</option>
                </select>
            </div>

            <div>
                <label for="subscription" class="block text-sm font-medium text-gray-700">Abonnement</label>
                <select name="subscription" id="subscription" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">Tous</option>
                    <option value="active" {{ request('subscription') === 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="expired" {{ request('subscription') === 'expired' ? 'selected' : '' }}>Expiré</option>
                    <option value="none" {{ request('subscription') === 'none' ? 'selected' : '' }}>Aucun</option>
                </select>
            </div>

            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700">Trier par</label>
                <select name="sort" id="sort" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date d'inscription</option>
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nom</option>
                    <option value="properties_count" {{ request('sort') === 'properties_count' ? 'selected' : '' }}>Nombre de propriétés</option>
                </select>
            </div>

            <div class="md:col-span-4 flex justify-end space-x-3">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
                <span id="selected-count">0</span> utilisateur(s) sélectionné(s)
            </span>
        </div>
        <div class="flex space-x-3">
            <button onclick="bulkAction('activate')" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded text-green-700 bg-green-100 hover:bg-green-200">
                Activer
            </button>
            <button onclick="bulkAction('deactivate')" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded text-gray-700 bg-gray-100 hover:bg-gray-200">
                Désactiver
            </button>
            <button onclick="bulkAction('ban')" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded text-red-700 bg-red-100 hover:bg-red-200">
                Bannir
            </button>
            <button onclick="bulkAction('delete')" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded text-red-700 bg-red-100 hover:bg-red-200">
                Supprimer
            </button>
        </div>
    </div>
</div>

<!-- Liste des utilisateurs -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-medium text-gray-900">
            {{ $users->total() }} utilisateur(s)
        </h2>
    </div>

    @if($users->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Utilisateur
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rôle
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Abonnement
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Propriétés
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="selected_users[]" value="{{ $user->id }}" class="user-checkbox rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($user->avatar)
                                            <img class="h-10 w-10 rounded-full" src="{{ $user->avatar }}" alt="">
                                        @else
                                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF" alt="">
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : ($user->role === 'agent' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : ($user->status === 'banned' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->current_subscription)
                                    <div class="text-sm text-gray-900">{{ ucfirst($user->current_subscription->plan) }}</div>
                                    <div class="text-xs text-gray-500">Expire le {{ $user->current_subscription->expires_at->format('d/m/Y') }}</div>
                                @else
                                    <span class="text-sm text-gray-500">Aucun</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->properties_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-gray-400 hover:text-gray-500">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-400 hover:text-blue-500">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-500">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $users->withQueryString()->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-users text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun utilisateur trouvé</h3>
            <p class="text-gray-500">Aucun utilisateur ne correspond à vos critères de recherche.</p>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Gestion des cases à cocher
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSelectedCount();
});

document.querySelectorAll('.user-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedCount);
});

function updateSelectedCount() {
    const selected = document.querySelectorAll('.user-checkbox:checked');
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
    const selected = document.querySelectorAll('.user-checkbox:checked');
    const ids = Array.from(selected).map(checkbox => checkbox.value);
    
    if (ids.length === 0) {
        alert('Veuillez sélectionner au moins un utilisateur.');
        return;
    }
    
    let confirmMessage = '';
    switch(action) {
        case 'activate':
            confirmMessage = `Êtes-vous sûr de vouloir activer ${ids.length} utilisateur(s) ?`;
            break;
        case 'deactivate':
            confirmMessage = `Êtes-vous sûr de vouloir désactiver ${ids.length} utilisateur(s) ?`;
            break;
        case 'ban':
            confirmMessage = `Êtes-vous sûr de vouloir bannir ${ids.length} utilisateur(s) ?`;
            break;
        case 'delete':
            confirmMessage = `Êtes-vous sûr de vouloir supprimer ${ids.length} utilisateur(s) ? Cette action est irréversible.`;
            break;
    }
    
    if (confirm(confirmMessage)) {
        // Créer un formulaire pour envoyer les données
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.users.bulk-action") }}';
        
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
            idInput.name = 'ids[]';
            idInput.value = id;
            form.appendChild(idInput);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
