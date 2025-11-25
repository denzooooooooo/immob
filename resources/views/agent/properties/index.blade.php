@extends('layouts.agent')

@section('title', 'Mes Propriétés')

@section('header', 'Mes Propriétés')

@section('actions')
    @if(auth()->user()->canPostProperty())
        <a href="{{ route('agent.properties.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i>
            Ajouter une propriété
        </a>
    @endif
@endsection

@section('content')
<!-- Filtres -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-4 sm:p-6">
        <form action="{{ route('agent.properties.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">Tous</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publié</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Brouillon</option>
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
                <label for="sort" class="block text-sm font-medium text-gray-700">Trier par</label>
                <select name="sort" id="sort" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date de création</option>
                    <option value="price" {{ request('sort') === 'price' ? 'selected' : '' }}>Prix</option>
                    <option value="views" {{ request('sort') === 'views' ? 'selected' : '' }}>Vues</option>
                </select>
            </div>

            <div class="md:col-span-4 flex justify-end space-x-3">
                <a href="{{ route('agent.properties.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Réinitialiser
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Liste des propriétés -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-medium text-gray-900">
                {{ $properties->total() }} propriété(s)
            </h2>
            
            @if(auth()->user()->current_subscription)
                <div class="text-sm text-gray-500">
                    {{ auth()->user()->properties()->count() }} / {{ auth()->user()->current_subscription->properties_limit }} propriétés utilisées
                </div>
            @endif
        </div>
    </div>

    @if($properties->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Propriété
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Messages
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $property->messages_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('agent.properties.show', $property) }}" class="text-gray-400 hover:text-gray-500">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('agent.properties.edit', $property) }}" class="text-blue-400 hover:text-blue-500">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('agent.properties.destroy', $property) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette propriété ?');">
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
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune propriété</h3>
            @if(auth()->user()->canPostProperty())
                <p class="text-gray-500 mb-6">Commencez par ajouter votre première propriété !</p>
                <a href="{{ route('agent.properties.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter une propriété
                </a>
            @else
                <p class="text-gray-500">Vous avez atteint la limite de propriétés de votre abonnement.</p>
                <a href="{{ route('agent.subscription.show') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    Mettre à niveau mon abonnement
                </a>
            @endif
        </div>
    @endif
</div>
@endsection
