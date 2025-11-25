@extends('layouts.agent')

@section('title', 'Dashboard')

@section('header', 'Tableau de bord Agent')

@section('content')
<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Propriétés -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 bg-opacity-75">
                <i class="fas fa-building text-green-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">Mes Propriétés</p>
                <p class="text-2xl font-semibold text-gray-700">{{ $stats['total_properties'] }}</p>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex justify-between text-sm text-gray-500">
                <span>Publiées</span>
                <span class="text-green-600">{{ $stats['published_properties'] }}</span>
            </div>
        </div>
    </div>

    <!-- Vues -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 bg-opacity-75">
                <i class="fas fa-eye text-blue-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">Vues Totales</p>
                <p class="text-2xl font-semibold text-gray-700">{{ $stats['total_views'] }}</p>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex justify-between text-sm text-gray-500">
                <span>Ce mois</span>
                <span class="text-blue-600">+{{ $stats['views_this_month'] }}</span>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 bg-opacity-75">
                <i class="fas fa-envelope text-yellow-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">Messages</p>
                <p class="text-2xl font-semibold text-gray-700">{{ $stats['total_messages'] }}</p>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex justify-between text-sm text-gray-500">
                <span>Non lus</span>
                <span class="text-yellow-600">{{ $stats['unread_messages'] }}</span>
            </div>
        </div>
    </div>

    <!-- Abonnement -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 bg-opacity-75">
                <i class="fas fa-crown text-purple-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">{{ $subscription ? 'Abonnement Annuel' : 'Aucun abonnement' }}</p>
                <p class="text-2xl font-semibold text-gray-700">{{ $subscription ? $subscription->properties_used . '/' . $subscription->properties_limit : '0/0' }}</p>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex justify-between text-sm text-gray-500">
                <span>Expire dans</span>
                <span class="text-purple-600">{{ $subscription ? $subscription->expires_at->diffInDays(now()) . ' jours' : '-' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Propriétés Récentes et Messages -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Propriétés Récentes -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Propriétés Récentes</h3>
        </div>
        <div class="p-6">
            <div class="flow-root">
                <ul class="-my-5 divide-y divide-gray-200">
                    @forelse($recentProperties as $property)
                    <li class="py-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                @if($property->featured_image)
                                    <img class="h-12 w-12 rounded-md object-cover" src="{{ $property->featured_image }}" alt="">
                                @else
                                    <div class="h-12 w-12 rounded-md bg-gray-200 flex items-center justify-center">
                                        <i class="fas fa-building text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $property->title }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ number_format($property->price) }} XAF - {{ $property->city }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $property->published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $property->published ? 'Publié' : 'Brouillon' }}
                                </span>
                                <a href="{{ route('agent.properties.show', $property) }}" class="text-gray-400 hover:text-gray-500">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="py-4">
                        <div class="text-center text-gray-500">
                            Aucune propriété
                        </div>
                    </li>
                    @endforelse
                </ul>
            </div>
            <div class="mt-6">
                <a href="{{ route('agent.properties.create') }}" class="w-full flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    Ajouter une propriété
                </a>
            </div>
        </div>
    </div>

    <!-- Messages Récents -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Messages Récents</h3>
        </div>
        <div class="p-6">
            <div class="flow-root">
                <ul class="-my-5 divide-y divide-gray-200">
                    @forelse($recentMessages as $message)
                    <li class="py-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($message->sender->name) }}&color=7F9CF5&background=EBF4FF" alt="">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $message->sender->name }}
                                </p>
                                <p class="text-sm text-gray-500 truncate">
                                    {{ Str::limit($message->content, 50) }}
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('agent.messages.show', $message) }}" class="inline-flex items-center shadow-sm px-2.5 py-0.5 border border-gray-300 text-sm leading-5 font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50">
                                    Voir
                                </a>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="py-4">
                        <div class="text-center text-gray-500">
                            Aucun message
                        </div>
                    </li>
                    @endforelse
                </ul>
            </div>
            <div class="mt-6">
                <a href="{{ route('agent.messages.index') }}" class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Voir tous les messages
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques -->
<div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Vues par Propriété -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Vues par Propriété</h3>
        </div>
        <div class="p-6">
            <canvas id="viewsChart" class="w-full h-64"></canvas>
        </div>
    </div>

    <!-- Messages par Jour -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Messages par Jour</h3>
        </div>
        <div class="p-6">
            <canvas id="messagesChart" class="w-full h-64"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique des vues
const viewsCtx = document.getElementById('viewsChart').getContext('2d');
new Chart(viewsCtx, {
    type: 'bar',
    data: {
        labels: @json($charts['views_labels']),
        datasets: [{
            label: 'Vues',
            data: @json($charts['views_data']),
            backgroundColor: '#009639',
            borderColor: '#009639',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Graphique des messages
const messagesCtx = document.getElementById('messagesChart').getContext('2d');
new Chart(messagesCtx, {
    type: 'line',
    data: {
        labels: @json($charts['messages_labels']),
        datasets: [{
            label: 'Messages',
            data: @json($charts['messages_data']),
            borderColor: '#3A75C4',
            backgroundColor: 'rgba(58, 117, 196, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>
@endpush
