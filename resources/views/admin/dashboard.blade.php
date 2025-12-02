@extends('layouts.admin')

@section('title', 'Dashboard')

@section('header', 'Tableau de bord')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Statistiques Globales -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-gradient-to-br from-violet-100 to-red-100">
                <i class="fas fa-building text-violet-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">Propriété</p>
                <p class="text-2xl font-semibold text-gray-700">{{ $stats['total_properties'] }}</p>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex justify-between text-sm text-gray-500">
                <span>Ce mois</span>
                <span class="text-violet-600">+{{ $stats['new_properties_month'] }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-gradient-to-br from-violet-100 to-red-100">
                <i class="fas fa-users text-violet-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">Utilisateurs</p>
                <p class="text-2xl font-semibold text-gray-700">{{ $stats['total_users'] }}</p>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex justify-between text-sm text-gray-500">
                <span>Agents actifs</span>
                <span class="text-violet-600">{{ $stats['active_agents'] }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-gradient-to-br from-violet-100 to-red-100">
                <i class="fas fa-credit-card text-violet-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">Revenus</p>
                <p class="text-2xl font-semibold text-gray-700">{{ number_format($stats['total_revenue']) }} XAF</p>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex justify-between text-sm text-gray-500">
                <span>Ce mois</span>
                <span class="text-violet-600">{{ number_format($stats['revenue_month']) }} XAF</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-gradient-to-br from-violet-100 to-red-100">
                <i class="fas fa-envelope text-violet-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">Messages</p>
                <p class="text-2xl font-semibold text-gray-700">{{ $stats['total_messages'] ?? 0 }}</p>
            </div>
        </div>
        <div class="mt-4">
            <div class="flex justify-between text-sm text-gray-500">
                <span>Non lus</span>
                <span class="text-violet-600">{{ $stats['unread_messages'] ?? 0 }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Graphiques et Tableaux -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Propriétés Récentes -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Propriétés Récentes</h3>
        </div>
        <div class="p-6">
            <div class="flow-root">
                <ul class="-my-5 divide-y divide-gray-200">
                    @foreach($recentProperties as $property)
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
                            <div>
                                <a href="{{ route('admin.properties.show', $property) }}" class="inline-flex items-center shadow-sm px-2.5 py-0.5 border border-gray-300 text-sm leading-5 font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50">
                                    Voir
                                </a>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="mt-6">
                <a href="{{ route('admin.properties.index') }}" class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Voir toutes les propriétés
                </a>
            </div>
        </div>
    </div>

    <!-- Derniers Utilisateurs -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Derniers Utilisateurs</h3>
        </div>
        <div class="p-6">
            <div class="flow-root">
                <ul class="-my-5 divide-y divide-gray-200">
                    @foreach($recentUsers as $user)
                    <li class="py-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF" alt="">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $user->name }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $user->email }}
                                </p>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->role === 'agent' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="mt-6">
                <a href="{{ route('admin.users.index') }}" class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Voir tous les utilisateurs
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques Détaillées -->
<div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Graphique des Revenus -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Revenus Mensuels</h3>
        </div>
        <div class="p-6">
            <canvas id="revenueChart" class="w-full h-64"></canvas>
        </div>
    </div>

    <!-- Graphique des Propriétés -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Propriétés par Type</h3>
        </div>
        <div class="p-6">
            <canvas id="propertiesChart" class="w-full h-64"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique des Revenus
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        const revenueChart = new Chart(revenueCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($charts['revenue_labels']),
                datasets: [{
                    label: 'Revenus (XAF)',
                    data: @json($charts['revenue_data']),
                    borderColor: '#7C3AED',
                    backgroundColor: 'rgba(124, 58, 237, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' XAF';
                            }
                        }
                    }
                }
            }
        });
    }

    // Graphique des Propriétés
    const propertiesCtx = document.getElementById('propertiesChart');
    if (propertiesCtx) {
        const propertiesChart = new Chart(propertiesCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: @json($charts['properties_labels']),
                datasets: [{
                    data: @json($charts['properties_data']),
                    backgroundColor: [
                        '#009639', // Vert
                        '#FCD116', // Jaune
                        '#3A75C4', // Bleu
                        '#6B7280', // Gris
                        '#9061F9', // Violet
                        '#E74694'  // Rose
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>
@endpush
