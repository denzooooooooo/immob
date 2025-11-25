@extends('layouts.admin')

@section('title', $user->name)

@section('header')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Détails de l'utilisateur</h1>
        <a href="{{ route('admin.users.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour
        </a>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Informations de base -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-6">Informations de base</h3>
        
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <dt class="text-sm font-medium text-gray-500">Nom complet</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
            </div>
            
            <div>
                <dt class="text-sm font-medium text-gray-500">Email</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
            </div>
            
            <div>
                <dt class="text-sm font-medium text-gray-500">Rôle</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($user->role) }}</dd>
            </div>
            
            <div>
                <dt class="text-sm font-medium text-gray-500">Statut</dt>
                <dd class="mt-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $user->active ? 'Actif' : 'Inactif' }}
                    </span>
                </dd>
            </div>
            
            <div>
                <dt class="text-sm font-medium text-gray-500">Date d'inscription</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
            </div>
            
            <div>
                <dt class="text-sm font-medium text-gray-500">Dernière connexion</dt>
                <dd class="mt-1 text-sm text-gray-900">
                    {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Jamais' }}
                </dd>
            </div>
        </dl>
    </div>

    @if($user->role === 'agent')
        <!-- Abonnement -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Abonnement</h3>
            
            @if($user->current_subscription)
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Plan</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ \App\Models\Subscription::getPlanDetails($user->current_subscription->plan)['name'] }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Statut</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->current_subscription->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->current_subscription->status }}
                            </span>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date de début</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->current_subscription->starts_at->format('d/m/Y') }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date de fin</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->current_subscription->expires_at->format('d/m/Y') }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Propriétés utilisées</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $user->current_subscription->properties_used }} / {{ $user->current_subscription->properties_limit }}
                        </dd>
                    </div>
                </dl>
            @else
                <p class="text-sm text-gray-500">Aucun abonnement actif</p>
            @endif
        </div>

        <!-- Propriétés -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium text-gray-900">Propriétés</h3>
                <span class="text-sm text-gray-500">Total : {{ $user->properties->count() }}</span>
            </div>
            
            @if($user->properties->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Titre
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Prix
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ville
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($user->properties as $property)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $property->title }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ ucfirst($property->type) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $property->published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $property->published ? 'Publié' : 'Brouillon' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ number_format($property->price) }} {{ $property->currency }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $property->city }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.properties.show', $property) }}" class="text-green-600 hover:text-green-900">
                                            Voir
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-gray-500">Aucune propriété</p>
            @endif
        </div>
    @endif

    <!-- Actions -->
    <div class="flex justify-end space-x-4">
        <a href="{{ route('admin.users.edit', $user) }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <i class="fas fa-edit mr-2"></i>
            Modifier
        </a>
        
        @if(!$user->active)
            <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                    <i class="fas fa-check mr-2"></i>
                    Activer
                </button>
            </form>
        @else
            <form action="{{ route('admin.users.deactivate', $user) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                    <i class="fas fa-ban mr-2"></i>
                    Désactiver
                </button>
            </form>
        @endif
        
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" 
              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                <i class="fas fa-trash mr-2"></i>
                Supprimer
            </button>
        </form>
    </div>
</div>
@endsection
