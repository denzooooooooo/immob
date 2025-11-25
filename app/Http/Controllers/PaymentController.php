<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Callback pour Orange Money
     */
    public function orangeCallback(Request $request)
    {
        try {
            $orderId = $request->get('order_id');
            $status = $request->get('status');
            
            $subscription = Subscription::find($orderId);
            
            if (!$subscription) {
                return redirect()->route('agent.subscription.show')
                    ->with('error', 'Abonnement introuvable');
            }

            if ($status === 'SUCCESS') {
                $this->paymentService->updateSubscriptionAfterPayment($subscription, 'SUCCESSFUL');
                
                return redirect()->route('agent.subscription.show')
                    ->with('success', 'Paiement effectué avec succès ! Votre abonnement est maintenant actif.');
            } else {
                $this->paymentService->updateSubscriptionAfterPayment($subscription, 'FAILED');
                
                return redirect()->route('agent.subscription.show')
                    ->with('error', 'Le paiement a échoué. Veuillez réessayer.');
            }

        } catch (\Exception $e) {
            Log::error('Orange Callback Error: ' . $e->getMessage());
            
            return redirect()->route('agent.subscription.show')
                ->with('error', 'Une erreur est survenue lors du traitement du paiement.');
        }
    }

    /**
     * Callback d'annulation pour Orange Money
     */
    public function orangeCancelCallback(Request $request)
    {
        $orderId = $request->get('order_id');
        
        if ($orderId) {
            $subscription = Subscription::find($orderId);
            if ($subscription) {
                $subscription->update(['status' => 'cancelled']);
            }
        }

        return redirect()->route('agent.subscription.show')
            ->with('warning', 'Paiement annulé par l\'utilisateur.');
    }

    /**
     * Webhook pour Orange Money
     */
    public function orangeWebhook(Request $request)
    {
        try {
            $data = $request->all();
            Log::info('Orange Webhook received:', $data);

            $orderId = $data['order_id'] ?? null;
            $status = $data['status'] ?? null;

            if ($orderId && $status) {
                $subscription = Subscription::find($orderId);
                
                if ($subscription) {
                    $this->paymentService->updateSubscriptionAfterPayment(
                        $subscription, 
                        $status === 'SUCCESS' ? 'SUCCESSFUL' : 'FAILED'
                    );
                }
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Orange Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Vérifier le statut d'un paiement MTN
     */
    public function checkMTNStatus(Request $request)
    {
        try {
            $subscriptionId = $request->get('subscription_id');
            $subscription = Subscription::find($subscriptionId);

            if (!$subscription) {
                return response()->json(['error' => 'Abonnement introuvable'], 404);
            }

            $paymentDetails = $subscription->payment_details;
            $transactionId = $paymentDetails['transaction_id'] ?? null;

            if (!$transactionId) {
                return response()->json(['error' => 'Transaction introuvable'], 404);
            }

            $result = $this->paymentService->checkMTNPaymentStatus($transactionId);

            if ($result['success'] && $result['status'] === 'SUCCESSFUL') {
                $this->paymentService->updateSubscriptionAfterPayment($subscription, 'SUCCESSFUL');
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Paiement confirmé avec succès'
                ]);
            }

            return response()->json([
                'status' => $result['status'] ?? 'pending',
                'message' => $result['message']
            ]);

        } catch (\Exception $e) {
            Log::error('MTN Status Check Error: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la vérification'], 500);
        }
    }

    /**
     * Webhook pour MTN Mobile Money
     */
    public function mtnWebhook(Request $request)
    {
        try {
            $data = $request->all();
            Log::info('MTN Webhook received:', $data);

            $transactionId = $data['transactionId'] ?? null;
            $status = $data['status'] ?? null;

            if ($transactionId && $status) {
                $subscription = Subscription::whereJsonContains('payment_details->transaction_id', $transactionId)->first();
                
                if ($subscription) {
                    $this->paymentService->updateSubscriptionAfterPayment(
                        $subscription, 
                        $status
                    );
                }
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('MTN Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Callback pour PayPal
     */
    public function paypalCallback(Request $request)
    {
        try {
            $orderId = $request->get('token');
            
            if (!$orderId) {
                return redirect()->route('agent.subscription.show')
                    ->with('error', 'Token PayPal manquant');
            }

            $subscription = Subscription::whereJsonContains('payment_details->order_id', $orderId)->first();
            
            if (!$subscription) {
                return redirect()->route('agent.subscription.show')
                    ->with('error', 'Abonnement introuvable');
            }

            // Capturer le paiement PayPal
            $paymentDetails = $subscription->payment_details;
            $paypalClientId = config('services.paypal.client_id');
            $paypalSecret = config('services.paypal.secret');
            $paypalEndpoint = config('services.paypal.environment') === 'sandbox' 
                ? 'https://api-m.sandbox.paypal.com'
                : 'https://api-m.paypal.com';

            // Obtenir le token d'accès
            $tokenResponse = Http::withBasicAuth($paypalClientId, $paypalSecret)
                ->asForm()
                ->post($paypalEndpoint . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials'
                ]);

            if ($tokenResponse->successful()) {
                $accessToken = $tokenResponse->json('access_token');

                // Capturer l'ordre
                $captureResponse = Http::withToken($accessToken)
                    ->post($paypalEndpoint . '/v2/checkout/orders/' . $orderId . '/capture');

                if ($captureResponse->successful()) {
                    $this->paymentService->updateSubscriptionAfterPayment($subscription, 'SUCCESSFUL');
                    
                    return redirect()->route('agent.subscription.show')
                        ->with('success', 'Paiement PayPal effectué avec succès ! Votre abonnement est maintenant actif.');
                }
            }

            $this->paymentService->updateSubscriptionAfterPayment($subscription, 'FAILED');
            
            return redirect()->route('agent.subscription.show')
                ->with('error', 'Le paiement PayPal a échoué. Veuillez réessayer.');

        } catch (\Exception $e) {
            Log::error('PayPal Callback Error: ' . $e->getMessage());
            
            return redirect()->route('agent.subscription.show')
                ->with('error', 'Une erreur est survenue lors du traitement du paiement PayPal.');
        }
    }

    /**
     * Callback d'annulation pour PayPal
     */
    public function paypalCancelCallback(Request $request)
    {
        $orderId = $request->get('token');
        
        if ($orderId) {
            $subscription = Subscription::whereJsonContains('payment_details->order_id', $orderId)->first();
            if ($subscription) {
                $subscription->update(['status' => 'cancelled']);
            }
        }

        return redirect()->route('agent.subscription.show')
            ->with('warning', 'Paiement PayPal annulé par l\'utilisateur.');
    }

    /**
     * Callback pour Stripe
     */
    public function stripeCallback(Request $request)
    {
        try {
            $sessionId = $request->get('session_id');
            
            if (!$sessionId) {
                return redirect()->route('agent.subscription.show')
                    ->with('error', 'Session Stripe manquante');
            }

            $subscription = Subscription::whereJsonContains('payment_details->session_id', $sessionId)->first();
            
            if (!$subscription) {
                return redirect()->route('agent.subscription.show')
                    ->with('error', 'Abonnement introuvable');
            }

            // Vérifier le statut de la session Stripe
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                $this->paymentService->updateSubscriptionAfterPayment($subscription, 'SUCCESSFUL');
                
                return redirect()->route('agent.subscription.show')
                    ->with('success', 'Paiement par carte bancaire effectué avec succès ! Votre abonnement est maintenant actif.');
            } else {
                $this->paymentService->updateSubscriptionAfterPayment($subscription, 'FAILED');
                
                return redirect()->route('agent.subscription.show')
                    ->with('error', 'Le paiement par carte bancaire a échoué. Veuillez réessayer.');
            }

        } catch (\Exception $e) {
            Log::error('Stripe Callback Error: ' . $e->getMessage());
            
            return redirect()->route('agent.subscription.show')
                ->with('error', 'Une erreur est survenue lors du traitement du paiement par carte bancaire.');
        }
    }

    /**
     * Callback d'annulation pour Stripe
     */
    public function stripeCancelCallback(Request $request)
    {
        return redirect()->route('agent.subscription.show')
            ->with('warning', 'Paiement par carte bancaire annulé par l\'utilisateur.');
    }

    /**
     * Vérifier le statut d'un paiement Airtel
     */
    public function checkAirtelStatus(Request $request)
    {
        try {
            $subscriptionId = $request->get('subscription_id');
            $subscription = Subscription::find($subscriptionId);

            if (!$subscription) {
                return response()->json(['error' => 'Abonnement introuvable'], 404);
            }

            $paymentDetails = $subscription->payment_details;
            $transactionId = $paymentDetails['transaction_id'] ?? null;

            if (!$transactionId) {
                return response()->json(['error' => 'Transaction introuvable'], 404);
            }

            $result = $this->paymentService->checkAirtelPaymentStatus($transactionId);

            if ($result['success'] && $result['status'] === 'SUCCESS') {
                $this->paymentService->updateSubscriptionAfterPayment($subscription, 'SUCCESSFUL');
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Paiement confirmé avec succès'
                ]);
            }

            return response()->json([
                'status' => $result['status'] ?? 'pending',
                'message' => $result['message']
            ]);

        } catch (\Exception $e) {
            Log::error('Airtel Status Check Error: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la vérification'], 500);
        }
    }

    /**
     * Webhook pour Airtel Money
     */
    public function airtelWebhook(Request $request)
    {
        try {
            $data = $request->all();
            Log::info('Airtel Webhook received:', $data);

            $transactionId = $data['transaction']['id'] ?? null;
            $status = $data['transaction']['status'] ?? null;

            if ($transactionId && $status) {
                $subscription = Subscription::whereJsonContains('payment_details->transaction_id', $transactionId)->first();
                
                if ($subscription) {
                    $this->paymentService->updateSubscriptionAfterPayment(
                        $subscription, 
                        $status === 'SUCCESS' ? 'SUCCESSFUL' : 'FAILED'
                    );
                }
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Airtel Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Webhook pour Stripe
     */
    public function stripeWebhook(Request $request)
    {
        try {
            $payload = $request->getContent();
            $sigHeader = $request->header('Stripe-Signature');
            $webhookSecret = config('services.stripe.webhook_secret');

            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);

            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;
                $subscriptionId = $session->metadata->subscription_id ?? null;

                if ($subscriptionId) {
                    $subscription = Subscription::find($subscriptionId);
                    
                    if ($subscription) {
                        $this->paymentService->updateSubscriptionAfterPayment($subscription, 'SUCCESSFUL');
                    }
                }
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Stripe Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
}
