@extends('layouts.admin')

@section('title', 'Gestion des Abonnements')

@section('header', 'Gestion des Abonnements')
 
@section('actions')
    <div class="flex space-x-3">
        <a href="{{ route('admin.subscriptions.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i>
            Créer un abonnement
        </a>
    </div>
@endsection

@section('content')
<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 bg-opacity-75">
                <i class="fas fa-users text-green-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">Abonnés Actifs</p>
                <p class="text-2xl font-semibold text-gray-700">{{ $stats['active_subscriptions'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 bg-opacity-75">
                <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">Revenus Mensuels</p>
                <p class="text-2xl font-semibold text-gray-700">{{ number_format($stats['monthly_revenue']) }} XAF</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 bg-opacity-75">
                <i class="fas fa-clock text-yellow-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">Expirent bientôt</p>
                <p class="text-2xl font-semibold text-gray-700">{{ $stats['expiring_soon'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 bg-opacity-75">
                <i class="fas fa-exclamation-circle text-red-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500 uppercase">Expirés</p>
                <p class="text-2xl font-semibold text-gray-700">{{ $stats['expired_subscriptions'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-4 sm:p-6">
        <form action="{{ route('admin.subscriptions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="plan" class="block text-sm font-medium text-gray-700">Plan</label>
                <select name="plan" id="plan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">Tous</option>
                    <option value="basic" {{ request('plan') === 'basic' ? 'selected' : '' }}>Basique</option>
                    <option value="standard" {{ request('plan') === 'standard' ? 'selected' : '' }}>Standard</option>
                    <option value="premium" {{ request('plan') === 'premium' ? 'selected' : '' }}>Premium</option>
                    <option value="enterprise" {{ request('plan') === 'enterprise' ? 'selected' : '' }}>Entreprise</option>
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">Tous</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expiré</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                </select>
            </div>

            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <select name="date" id="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">Toutes</option>
                    <option value="today" {{ request('date') === 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                    <option value="week" {{ request('date') === 'week' ? 'selected' : '' }}>Cette semaine</option>
                    <option value="month" {{ request('date') === 'month' ? 'selected' : '' }}>Ce mois</option>
                    <option value="year" {{ request('date') === 'year' ? 'selected' : '' }}>Cette année</option>
                </select>
            </div>

            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700">Trier par</label>
                <select name="sort" id="sort" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date de création</option>
                    <option value="expires_at" {{ request('sort') === 'expires_at' ? 'selected' : '' }}>Date d'expiration</option>
                    <option value="price" {{ request('sort') === 'price' ? 'selected' : '' }}>Prix</option>
                </select>
            </div>

            <div class="md:col-span-4 flex justify-end space-x-3">
                <a href="{{ route('admin.subscriptions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Réinitialiser
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Liste des abonnements -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-medium text-gray-900">
            {{ $subscriptions->total() }} abonnement(s)
        </h2>
    </div>

    @if($subscriptions->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Agent
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Plan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Prix
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Utilisation
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Période
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($subscriptions as $subscription)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if($subscription->user->avatar)
                                            <img class="h-10 w-10 rounded-full" src="{{ $subscription->user->avatar }}" alt="">
                                        @else
                                            <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($subscription->user->name) }}&color=7F9CF5&background=EBF4FF" alt="">
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $subscription->user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $subscription->user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                    $subscription->plan === 'enterprise' ? 'bg-purple-100 text-purple-800' : 
                                    ($subscription->plan === 'premium' ? 'bg-blue-100 text-blue-800' : 
                                    ($subscription->plan === 'standard' ? 'bg-green-100 text-green-800' : 
                                    'bg-gray-100 text-gray-800')) 
                                }}">
                                    {{ ucfirst($subscription->plan) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ number_format($subscription->price) }} XAF</div>
                                <div class="text-xs text-gray-500">par mois</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
                                    $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 
                                    ($subscription->status === 'expired' ? 'bg-red-100 text-red-800' : 
                                    'bg-yellow-100 text-yellow-800') 
                                }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $subscription->properties_used }} / {{ $subscription->properties_limit }}
                                </div>
                                <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ min(($subscription->properties_used / $subscription->properties_limit) * 100, 100) }}%"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $subscription->starts_at->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    → {{ $subscription->expires_at->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('admin.subscriptions.show', $subscription) }}" class="text-gray-400 hover:text-gray-500">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.subscriptions.edit', $subscription) }}" class="text-blue-400 hover:text-blue-500">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($subscription->status !== 'cancelled')
                                        <form action="{{ route('admin.subscriptions.cancel', $subscription) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cet abonnement ?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-400 hover:text-red-500">
                                                <i class="fas fa-ban"></i>
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
            {{ $subscriptions->withQueryString()->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-credit-card text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun abonnement trouvé</h3>
            <p class="text-gray-500">Aucun abonnement ne correspond à vos critères de recherche.</p>
        </div>
    @endif
</div>

<!-- Graphique des revenus -->
<div class="mt-8 bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-medium text-gray-900">Revenus des Abonnements</h2>
    </div>
    <div class="p-6">
        <canvas id="revenueChart" class="w-full h-80"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($chart['labels']),
        datasets: [{
            label: 'Revenus (XAF)',
            data: @json($chart['data']),
            borderColor: '#009639',
            backgroundColor: 'rgba(0, 150, 57, 0.1)',
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
</script>
@endpush
