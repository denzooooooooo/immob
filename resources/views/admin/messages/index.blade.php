@extends('layouts.admin')

@section('title', 'Gestion des Messages')

@section('header', 'Gestion des Messages')

@section('content')
<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 bg-opacity-75">
                <i class="fas fa-envelope text-green-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">Total Messages</p>
                <p class="text-2xl font-semibold text-gray-700">{{ $stats['total_messages'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 bg-opacity-75">
                <i class="fas fa-clock text-blue-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">Non Lus</p>
                <p class="text-2xl font-semibold text-gray-700">{{ $stats['unread_messages'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 bg-opacity-75">
                <i class="fas fa-reply text-yellow-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">En Attente</p>
                <p class="text-2xl font-semibold text-gray-700">{{ $stats['pending_messages'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 bg-opacity-75">
                <i class="fas fa-exclamation-circle text-red-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">Signalés</p>
                <p class="text-2xl font-semibold text-gray-700">{{ $stats['reported_messages'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-4 sm:p-6">
        <form action="{{ route('admin.messages.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">Tous</option>
                    <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Non lu</option>
                    <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Lu</option>
                    <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Répondu</option>
                    <option value="reported" {{ request('status') === 'reported' ? 'selected' : '' }}>Signalé</option>
                </select>
            </div>

            <div>
                <label for="property" class="block text-sm font-medium text-gray-700">Propriété</label>
                <select name="property" id="property" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">Toutes</option>
                    @foreach($properties as $property)
                        <option value="{{ $property->id }}" {{ request('property') == $property->id ? 'selected' : '' }}>
                            {{ Str::limit($property->title, 50) }}
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
                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date d'envoi</option>
                    <option value="updated_at" {{ request('sort') === 'updated_at' ? 'selected' : '' }}>Dernière mise à jour</option>
                    <option value="status" {{ request('sort') === 'status' ? 'selected' : '' }}>Statut</option>
                </select>
            </div>

            <div class="md:col-span-4 flex justify-end space-x-3">
                <a href="{{ route('admin.messages.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Réinitialiser
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Liste des messages -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-medium text-gray-900">
            {{ $messages->total() }} message(s)
        </h2>
    </div>

    @if($messages->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Expéditeur
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Propriété
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Message
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($messages as $message)
                        <tr class="{{ $message->read_at ? '' : 'bg-blue-50' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($message->sender->avatar)
                                            <img class="h-10 w-10 rounded-full" src="{{ $message->sender->avatar }}" alt="">
                                        @else
                                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($message->sender->name) }}&color=7F9CF5&background=EBF4FF" alt="">
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $message->sender->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $message->sender->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($message->property)
                                    <div class="text-sm text-gray-900">
                                        {{ Str::limit($message->property->title, 30) }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $message->property->cityModel->name ?? $message->property->city }}
                                    </div>
                                @else
                                    <div class="text-sm text-gray-500 italic">
                                        Message général
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ Str::limit($message->content, 50) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                    $message->reported ? 'bg-red-100 text-red-800' : 
                                    ($message->replied_at ? 'bg-green-100 text-green-800' : 
                                    ($message->read_at ? 'bg-blue-100 text-blue-800' : 
                                    'bg-yellow-100 text-yellow-800')) 
                                }}">
                                    @if($message->reported)
                                        Signalé
                                    @elseif($message->replied_at)
                                        Répondu
                                    @elseif($message->read_at)
                                        Lu
                                    @else
                                        Non lu
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $message->created_at->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $message->created_at->format('H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.messages.show', $message) }}" class="text-gray-400 hover:text-gray-500">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(!$message->read_at)
                                        <form action="{{ route('admin.messages.mark-as-read', $message) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-blue-400 hover:text-blue-500">
                                                <i class="fas fa-envelope-open"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if(!$message->reported)
                                        <form action="{{ route('admin.messages.report', $message) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir signaler ce message ?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-yellow-400 hover:text-yellow-500">
                                                <i class="fas fa-flag"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
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
            {{ $messages->withQueryString()->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-envelope text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun message trouvé</h3>
            <p class="text-gray-500">Aucun message ne correspond à vos critères de recherche.</p>
        </div>
    @endif
</div>
@endsection
