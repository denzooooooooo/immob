<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $currentSubscription = $user->current_subscription;
        
        // Plans disponibles
        $plans = [
            'annual' => [
                'name' => 'Abonnement Annuel',
                'price' => 2000,
                'currency' => 'XAF',
                'duration' => 365,
                'properties_limit' => 999,
                'features' => [
                    'Annonces illimitées',
                    'Annonces mises en avant',
                    'Statistiques complètes',
                    'Support prioritaire',
                    'Badge professionnel',
                    'Prix: 2 000 XAF/an'
                ]
            ]
        ];
        
        // Historique des abonnements
        $subscriptionHistory = $user->subscriptions()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Statistiques d'utilisation
        $usage = [
            'properties_used' => $currentSubscription ? $currentSubscription->properties_used : 0,
            'properties_limit' => $currentSubscription ? $currentSubscription->properties_limit : 0,
            'days_remaining' => $currentSubscription ? $currentSubscription->expires_at->diffInDays(now()) : 0,
            'usage_percentage' => $currentSubscription && $currentSubscription->properties_limit > 0 
                ? round(($currentSubscription->properties_used / $currentSubscription->properties_limit) * 100, 1)
                : 0
        ];
        
        return view('agent.subscription.show', compact(
            'currentSubscription',
            'plans',
            'subscriptionHistory',
            'usage'
        ));
    }

    public function upgrade(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'plan' => 'required|in:annual',
            'payment_method' => 'required|in:mtn_money,orange_money,airtel_money,paypal,stripe'
        ]);

        $plans = [
            'annual' => ['price' => 2000, 'properties_limit' => 999],
        ];

        $selectedPlan = $plans[$validated['plan']];

        // Créer un nouvel abonnement
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan' => $validated['plan'],
            'status' => 'pending', // En attente de paiement
            'price_paid' => $selectedPlan['price'],
            'currency' => 'XAF',
            'properties_limit' => $selectedPlan['properties_limit'],
            'properties_used' => 0,
            'starts_at' => now(),
            'expires_at' => now()->addYear(),
            'payment_method' => $validated['payment_method'],
        ]);
        
        // Initier le paiement selon la méthode choisie
        $paymentService = new PaymentService();
        
        switch ($validated['payment_method']) {
            case 'mtn_money':
                $result = $paymentService->initiateMTNPayment($subscription);
                break;
            case 'orange_money':
                $result = $paymentService->initiateOrangePayment($subscription);
                break;
            case 'airtel_money':
                $result = $paymentService->initiateAirtelPayment($subscription);
                break;
            case 'paypal':
                $result = $paymentService->initiatePayPalPayment($subscription);
                break;
            case 'stripe':
                $result = $paymentService->initiateStripePayment($subscription);
                break;
            default:
                $result = ['success' => false, 'message' => 'Méthode de paiement non supportée'];
        }
        
        if ($result['success']) {
            if (isset($result['payment_url'])) {
                return redirect($result['payment_url']);
            } elseif (isset($result['approval_url'])) {
                return redirect($result['approval_url']);
            } elseif (isset($result['checkout_url'])) {
                return redirect($result['checkout_url']);
            } else {
                return redirect()->route('agent.subscription.show')
                    ->with('info', 'Paiement initié. Veuillez confirmer sur votre téléphone.')
                    ->with('subscription_id', $subscription->id);
            }
        } else {
            $subscription->delete();
            return redirect()->route('agent.subscription.show')
                ->with('error', $result['message']);
        }
    }

    public function renew(Request $request)
    {
        $user = Auth::user();
        $currentSubscription = $user->current_subscription;
        
        if (!$currentSubscription) {
            return redirect()->route('agent.subscription.show')
                ->with('error', 'Aucun abonnement actif à renouveler.');
        }
        
        $validated = $request->validate([
            'payment_method' => 'required|in:mtn_money,orange_money'
        ]);
        
        $plans = [
            'annual' => ['price' => 2000, 'properties_limit' => 999],
        ];

        $planDetails = $plans[$currentSubscription->plan];

        // Créer un renouvellement
        $newSubscription = Subscription::create([
            'user_id' => $user->id,
            'plan' => $currentSubscription->plan,
            'status' => 'pending',
            'price_paid' => $planDetails['price'],
            'currency' => 'XAF',
            'properties_limit' => $planDetails['properties_limit'],
            'properties_used' => 0, // Reset du compteur
            'starts_at' => $currentSubscription->expires_at,
            'expires_at' => $currentSubscription->expires_at->addYear(),
            'payment_method' => $validated['payment_method'],
        ]);
        
        // Initier le paiement selon la méthode choisie
        $paymentService = new PaymentService();
        
        if ($validated['payment_method'] === 'mtn_money') {
            $result = $paymentService->initiateMTNPayment($newSubscription);
            
            if ($result['success']) {
                return redirect()->route('agent.subscription.show')
                    ->with('info', 'Renouvellement initié. Veuillez confirmer sur votre téléphone MTN.')
                    ->with('subscription_id', $newSubscription->id);
            } else {
                $newSubscription->delete();
                return redirect()->route('agent.subscription.show')
                    ->with('error', $result['message']);
            }
        } elseif ($validated['payment_method'] === 'orange_money') {
            $result = $paymentService->initiateOrangePayment($newSubscription);
            
            if ($result['success']) {
                return redirect($result['payment_url']);
            } else {
                $newSubscription->delete();
                return redirect()->route('agent.subscription.show')
                    ->with('error', $result['message']);
            }
        }
        
        return redirect()->route('agent.subscription.show')
            ->with('error', 'Méthode de paiement non supportée.');
    }
}
