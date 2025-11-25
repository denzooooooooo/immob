@extends('layouts.agent')

@section('title', $property->title)

@section('header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $property->title }}</h1>
            <p class="text-gray-600">{{ ucfirst($property->type) }} • {{ ucfirst(str_replace('_', ' ', $property->status)) }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('agent.properties.edit', $property) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-edit mr-2"></i>
                Modifier
            </a>
            <a href="{{ route('agent.properties.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour
            </a>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-eye text-blue-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Vues totales</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_views'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar text-green-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Vues ce mois</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['this_month_views'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-envelope text-yellow-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Messages</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['messages_count'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-bell text-red-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Non lus</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['unread_messages'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statut et actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Statut de la propriété</h3>
            <div class="flex space-x-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $property->published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $property->published ? 'Publiée' : 'Brouillon' }}
                </span>
                @if($property->featured)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        En vedette
                    </span>
                @endif
            </div>
        </div>

        <div class="flex space-x-4">
            <button onclick="togglePublished({{ $property->id }})" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white {{ $property->published ? 'bg-gray-600 hover:bg-gray-700' : 'bg-green-600 hover:bg-green-700' }}">
                <i class="fas {{ $property->published ? 'fa-eye-slash' : 'fa-eye' }} mr-2"></i>
                {{ $property->published ? 'Dépublier' : 'Publier' }}
            </button>

            <button onclick="toggleFeatured({{ $property->id }})" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white {{ $property->featured ? 'bg-gray-600 hover:bg-gray-700' : 'bg-yellow-600 hover:bg-yellow-700' }}">
                <i class="fas fa-star mr-2"></i>
                {{ $property->featured ? 'Retirer vedette' : 'Mettre en vedette' }}
            </button>

            <button onclick="duplicateProperty({{ $property->id }})" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-copy mr-2"></i>
                Dupliquer
            </button>
        </div>
    </div>

    <!-- Informations de la propriété -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Images -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Images</h3>
            @if($property->media->count() > 0)
                <div class="grid grid-cols-2 gap-4">
                    @foreach($property->media->take(4) as $media)
                        <img src="{{ $media->path }}" alt="Image de la propriété" 
                             class="w-full h-32 object-cover rounded-lg">
                    @endforeach
                </div>
                @if($property->media->count() > 4)
                    <p class="text-sm text-gray-500 mt-2">
                        +{{ $property->media->count() - 4 }} autres images
                    </p>
                @endif
            @else
                <div class="text-center py-8">
                    <i class="fas fa-image text-gray-400 text-4xl mb-2"></i>
                    <p class="text-gray-500">Aucune image</p>
                </div>
            @endif
        </div>

        <!-- Détails -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Détails</h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Prix</dt>
                    <dd class="text-sm text-gray-900">{{ number_format($property->price) }} {{ $property->currency }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Surface</dt>
                    <dd class="text-sm text-gray-900">{{ $property->surface_area }} m²</dd>
                </div>
                @if($property->bedrooms)
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Chambres</dt>
                        <dd class="text-sm text-gray-900">{{ $property->bedrooms }}</dd>
                    </div>
                @endif
                @if($property->bathrooms)
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Salles de bain</dt>
                        <dd class="text-sm text-gray-900">{{ $property->bathrooms }}</dd>
                    </div>
                @endif
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Ville</dt>
                    <dd class="text-sm text-gray-900">{{ $property->city }}</dd>
                </div>
                @if($property->neighborhood)
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Quartier</dt>
                        <dd class="text-sm text-gray-900">{{ $property->neighborhood }}</dd>
                    </div>
                @endif
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Créée le</dt>
                    <dd class="text-sm text-gray-900">{{ $property->created_at->format('d/m/Y') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Description -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
        <p class="text-gray-700 whitespace-pre-line">{{ $property->description }}</p>
    </div>

    <!-- Localisation -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Localisation</h3>
        <p class="text-gray-700 mb-4">{{ $property->address }}</p>
        @if($property->latitude && $property->longitude)
            <div class="bg-gray-100 rounded-lg p-4">
                <p class="text-sm text-gray-600">
                    Coordonnées GPS: {{ $property->latitude }}, {{ $property->longitude }}
                </p>
            </div>
        @endif
    </div>

    <!-- Messages récents -->
    @if($property->messages->count() > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Messages récents</h3>
                <a href="{{ route('agent.messages.index', ['property' => $property->id]) }}" 
                   class="text-sm text-green-600 hover:text-green-500">
                    Voir tous les messages
                </a>
            </div>
            <div class="space-y-4">
                @foreach($property->messages->take(3) as $message)
                    <div class="border-l-4 border-green-500 pl-4">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">{{ $message->sender->name }}</p>
                            <p class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</p>
                        </div>
                        <p class="text-sm text-gray-700 mt-1">{{ Str::limit($message->content, 100) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
function togglePublished(propertyId) {
    fetch(`/agent/properties/${propertyId}/toggle-published`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur lors de la mise à jour');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la mise à jour');
    });
}

function toggleFeatured(propertyId) {
    fetch(`/agent/properties/${propertyId}/toggle-featured`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erreur lors de la mise à jour');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la mise à jour');
    });
}

function duplicateProperty(propertyId) {
    if (confirm('Voulez-vous vraiment dupliquer cette propriété ?')) {
        fetch(`/agent/properties/${propertyId}/duplicate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert(data.message || 'Erreur lors de la duplication');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la duplication');
        });
    }
}
</script>
@endsection
