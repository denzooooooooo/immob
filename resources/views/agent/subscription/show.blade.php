@extends('layouts.agent')

@section('title', 'Mon Abonnement')

@section('header', 'Mon Abonnement')

@section('content')
<!-- Abonnement Actuel -->
@if($currentSubscription)
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-medium text-gray-900">Abonnement Actuel</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $currentSubscription->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ ucfirst($currentSubscription->status) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Plan Info -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        Plan {{ ucfirst($currentSubscription->plan) }}
                    </h3>
                    <p class="text-2xl font-bold text-green-600">
                        {{ number_format($currentSubscription->price) }} XAF
                    </p>
                    <p class="text-sm text-gray-500">par mois</p>
                </div>

                <!-- Usage -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Utilisation</h3>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Propriétés</span>
                        <span class="text-sm font-medium">{{ $usage['properties_used'] }} / {{ $usage['properties_limit'] }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $usage['usage_percentage'] }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $usage['usage_percentage'] }}% utilisé</p>
                </div>

                <!-- Expiration -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Expiration</h3>
                    <p class="text-lg font-medium text-gray-900">
                        {{ $usage['days_remaining'] }} jours
                    </p>
                    <p class="text-sm text-gray-500">
                        Expire le {{ $currentSubscription->expires_at->format('d/m/Y') }}
                    </p>
                </div>
            </div>

            <div class="mt-6 flex space-x-4">
                <button onclick="showRenewModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    <i class="fas fa-sync mr-2"></i>
                    Renouveler
                </button>
                
                <button onclick="document.getElementById('upgrade-section').scrollIntoView()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-arrow-up mr-2"></i>
                    Changer de plan
                </button>
            </div>
        </div>
    </div>
@else
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">
                    Aucun abonnement actif
                </h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p>Vous devez souscrire à un abonnement pour pouvoir publier des propriétés.</p>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Plans Disponibles -->
<div id="upgrade-section" class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-medium text-gray-900">Plans Disponibles</h2>
        <p class="text-gray-500">Choisissez le plan qui correspond à vos besoins</p>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($plans as $planKey => $plan)
                <div class="border rounded-lg p-6 {{ $currentSubscription && $currentSubscription->plan === $planKey ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                    <div class="text-center">
                        <h3 class="text-lg font-medium text-gray-900">{{ $plan['name'] }}</h3>
                        <div class="mt-4">
                            <span class="text-3xl font-bold text-gray-900">{{ number_format($plan['price']) }}</span>
                            <span class="text-gray-500">XAF/mois</span>
                        </div>
                        
                        @if($currentSubscription && $currentSubscription->plan === $planKey)
                            <div class="mt-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Plan actuel
                                </span>
                            </div>
                        @endif
                    </div>

                    <ul class="mt-6 space-y-3">
                        @foreach($plan['features'] as $feature)
                            <li class="flex items-start">
                                <i class="fas fa-check text-green-500 text-sm mt-1 mr-3"></i>
                                <span class="text-sm text-gray-700">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-6">
                        @if(!$currentSubscription || $currentSubscription->plan !== $planKey)
                            <button onclick="showUpgradeModal('{{ $planKey }}', '{{ $plan['name'] }}', {{ $plan['price'] }})" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                @if($currentSubscription)
                                    Changer de plan
                                @else
                                    Choisir ce plan
                                @endif
                            </button>
                        @else
                            <button disabled class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                                Plan actuel
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Historique des Abonnements -->
@if($subscriptionHistory->count() > 0)
    <div class="bg-white rounded-lg shadow mt-8">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-medium text-gray-900">Historique des Abonnements</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Plan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Prix
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Période
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($subscriptionHistory as $subscription)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ ucfirst($subscription->plan) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ number_format($subscription->price) }} {{ $subscription->currency }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $subscription->starts_at->format('d/m/Y') }} - {{ $subscription->expires_at->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : ($subscription->status === 'expired' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- Informations de Paiement -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-400 text-xl"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800">
                Informations de Paiement
            </h3>
            <div class="mt-2 text-sm text-blue-700">
                <p>Les paiements sont traités de manière sécurisée via Mobile Money (MTN Money, Orange Money).</p>
                <p class="mt-1">Votre abonnement sera activé immédiatement après confirmation du paiement.</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal de sélection de méthode de paiement pour upgrade -->
<div id="upgradeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Choisir une méthode de paiement</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="modalDescription">
                    Sélectionnez votre méthode de paiement préférée
                </p>
                
                <form id="upgradeForm" action="{{ route('agent.subscription.upgrade') }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="plan" id="selectedPlan">
                    
                    <div class="space-y-3">
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="mtn_money" class="mr-3" required>
                            <div class="flex items-center">
                                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiByeD0iNCIgZmlsbD0iI0ZGQ0MwMCIvPgo8dGV4dCB4PSIxMiIgeT0iMTUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxMCIgZm9udC13ZWlnaHQ9ImJvbGQiIGZpbGw9IiMwMDAiIHRleHQtYW5jaG9yPSJtaWRkbGUiPk1UTjwvdGV4dD4KPC9zdmc+" alt="MTN" class="w-6 h-6 mr-2">
                                <span class="font-medium">MTN Mobile Money</span>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="orange_money" class="mr-3" required>
                            <div class="flex items-center">
                                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiByeD0iNCIgZmlsbD0iI0ZGNjYwMCIvPgo8dGV4dCB4PSIxMiIgeT0iMTAiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSI4IiBmb250LXdlaWdodD0iYm9sZCIgZmlsbD0iI0ZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+T1JBTkdFPC90ZXh0Pgo8dGV4dCB4PSIxMiIgeT0iMTgiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSI4IiBmb250LXdlaWdodD0iYm9sZCIgZmlsbD0iI0ZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+TU9ORVk8L3RleHQ+Cjwvc3ZnPg==" alt="Orange" class="w-6 h-6 mr-2">
                                <span class="font-medium">Orange Money</span>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="airtel_money" class="mr-3" required>
                            <div class="flex items-center">
                                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiByeD0iNCIgZmlsbD0iI0VEMUMyNCIvPgo8dGV4dCB4PSIxMiIgeT0iMTUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSI5IiBmb250LXdlaWdodD0iYm9sZCIgZmlsbD0iI0ZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+QUlSVEVMPC90ZXh0Pgo8L3N2Zz4=" alt="Airtel" class="w-6 h-6 mr-2">
                                <span class="font-medium">Airtel Money</span>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="paypal" class="mr-3" required>
                            <div class="flex items-center">
                                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiByeD0iNCIgZmlsbD0iIzAwMzA4NyIvPgo8dGV4dCB4PSIxMiIgeT0iMTUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSI5IiBmb250LXdlaWdodD0iYm9sZCIgZmlsbD0iI0ZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+UGF5UGFsPC90ZXh0Pgo8L3N2Zz4=" alt="PayPal" class="w-6 h-6 mr-2">
                                <span class="font-medium">PayPal</span>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="stripe" class="mr-3" required>
                            <div class="flex items-center">
                                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiByeD0iNCIgZmlsbD0iIzYzNUJGRiIvPgo8dGV4dCB4PSIxMiIgeT0iMTAiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSI4IiBmb250LXdlaWdodD0iYm9sZCIgZmlsbD0iI0ZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+Q0FSREU8L3RleHQ+Cjx0ZXh0IHg9IjEyIiB5PSIxOCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjgiIGZvbnQtd2VpZ2h0PSJib2xkIiBmaWxsPSIjRkZGIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5CQU5DQUlSRTwvdGV4dD4KPC9zdmc+" alt="Carte" class="w-6 h-6 mr-2">
                                <span class="font-medium">Carte bancaire (Visa, MasterCard)</span>
                            </div>
                        </label>
                    </div>
                    
                    <div class="flex justify-between mt-6">
                        <button type="button" onclick="hideUpgradeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Procéder au paiement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de renouvellement -->
<div id="renewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900">Renouveler l'abonnement</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Sélectionnez votre méthode de paiement pour renouveler
                </p>
                
                <form action="{{ route('agent.subscription.renew') }}" method="POST" class="mt-4">
                    @csrf
                    
                    <div class="space-y-3">
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="mtn_money" class="mr-3" required>
                            <div class="flex items-center">
                                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiByeD0iNCIgZmlsbD0iI0ZGQ0MwMCIvPgo8dGV4dCB4PSIxMiIgeT0iMTUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxMCIgZm9udC13ZWlnaHQ9ImJvbGQiIGZpbGw9IiMwMDAiIHRleHQtYW5jaG9yPSJtaWRkbGUiPk1UTjwvdGV4dD4KPC9zdmc+" alt="MTN" class="w-6 h-6 mr-2">
                                <span class="font-medium">MTN Mobile Money</span>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="orange_money" class="mr-3" required>
                            <div class="flex items-center">
                                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiByeD0iNCIgZmlsbD0iI0ZGNjYwMCIvPgo8dGV4dCB4PSIxMiIgeT0iMTAiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSI4IiBmb250LXdlaWdodD0iYm9sZCIgZmlsbD0iI0ZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+T1JBTkdFPC90ZXh0Pgo8dGV4dCB4PSIxMiIgeT0iMTgiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSI4IiBmb250LXdlaWdodD0iYm9sZCIgZmlsbD0iI0ZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+TU9ORVk8L3RleHQ+Cjwvc3ZnPg==" alt="Orange" class="w-6 h-6 mr-2">
                                <span class="font-medium">Orange Money</span>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="airtel_money" class="mr-3" required>
                            <div class="flex items-center">
                                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiByeD0iNCIgZmlsbD0iI0VEMUMyNCIvPgo8dGV4dCB4PSIxMiIgeT0iMTUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSI5IiBmb250LXdlaWdodD0iYm9sZCIgZmlsbD0iI0ZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+QUlSVEVMPC90ZXh0Pgo8L3N2Zz4=" alt="Airtel" class="w-6 h-6 mr-2">
                                <span class="font-medium">Airtel Money</span>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="paypal" class="mr-3" required>
                            <div class="flex items-center">
                                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiByeD0iNCIgZmlsbD0iIzAwMzA4NyIvPgo8dGV4dCB4PSIxMiIgeT0iMTUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSI5IiBmb250LXdlaWdodD0iYm9sZCIgZmlsbD0iI0ZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+UGF5UGFsPC90ZXh0Pgo8L3N2Zz4=" alt="PayPal" class="w-6 h-6 mr-2">
                                <span class="font-medium">PayPal</span>
                            </div>
                        </label>
                        
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="payment_method" value="stripe" class="mr-3" required>
                            <div class="flex items-center">
                                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjI0IiBoZWlnaHQ9IjI0IiByeD0iNCIgZmlsbD0iIzYzNUJGRiIvPgo8dGV4dCB4PSIxMiIgeT0iMTAiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSI4IiBmb250LXdlaWdodD0iYm9sZCIgZmlsbD0iI0ZGRiIgdGV4dC1hbmNob3I9Im1pZGRsZSI+Q0FSREU8L3RleHQ+Cjx0ZXh0IHg9IjEyIiB5PSIxOCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjgiIGZvbnQtd2VpZ2h0PSJib2xkIiBmaWxsPSIjRkZGIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5CQU5DQUlSRTwvdGV4dD4KPC9zdmc+" alt="Carte" class="w-6 h-6 mr-2">
                                <span class="font-medium">Carte bancaire (Visa, MasterCard)</span>
                            </div>
                        </label>
                    </div>
                    
                    <div class="flex justify-between mt-6">
                        <button type="button" onclick="hideRenewModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Annuler
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Renouveler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showUpgradeModal(plan, planName, price) {
    document.getElementById('selectedPlan').value = plan;
    document.getElementById('modalTitle').textContent = 'Souscrire au ' + planName;
    document.getElementById('modalDescription').textContent = 'Prix: ' + new Intl.NumberFormat('fr-FR').format(price) + ' XAF/mois';
    document.getElementById('upgradeModal').classList.remove('hidden');
}

function hideUpgradeModal() {
    document.getElementById('upgradeModal').classList.add('hidden');
}

function showRenewModal() {
    document.getElementById('renewModal').classList.remove('hidden');
}

function hideRenewModal() {
    document.getElementById('renewModal').classList.add('hidden');
}

// Fermer les modals en cliquant à l'extérieur
window.onclick = function(event) {
    const upgradeModal = document.getElementById('upgradeModal');
    const renewModal = document.getElementById('renewModal');
    
    if (event.target === upgradeModal) {
        hideUpgradeModal();
    }
    if (event.target === renewModal) {
        hideRenewModal();
    }
}

// Vérification du statut de paiement MTN (si applicable)
@if(session('subscription_id'))
    let subscriptionId = {{ session('subscription_id') }};
    let checkInterval = setInterval(function() {
        fetch('/payment/status/mtn?subscription_id=' + subscriptionId)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    clearInterval(checkInterval);
                    location.reload();
                } else if (data.status === 'failed') {
                    clearInterval(checkInterval);
                    alert('Le paiement a échoué. Veuillez réessayer.');
                }
            })
            .catch(error => {
                console.error('Erreur lors de la vérification:', error);
            });
    }, 5000); // Vérifier toutes les 5 secondes
    
    // Arrêter la vérification après 5 minutes
    setTimeout(function() {
        clearInterval(checkInterval);
    }, 300000);
@endif
</script>
@endsection
