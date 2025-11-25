<?php

namespace App\Services;

use App\Models\Subscription;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentService
{
    protected $mtnApiKey;
    protected $orangeApiKey;
    protected $airtelApiKey;
    protected $paypalClientId;
    protected $paypalSecret;
    protected $stripeKey;
    protected $stripeSecret;
    protected $mtnEndpoint;
    protected $orangeEndpoint;
    protected $airtelEndpoint;
    protected $paypalEndpoint;

    public function __construct()
    {
        $this->mtnApiKey = config('services.mtn_momo.api_key');
        $this->orangeApiKey = config('services.orange_money.merchant_key');
        $this->airtelApiKey = config('services.airtel_money.client_secret');
        $this->paypalClientId = config('services.paypal.sandbox.client_id');
        $this->paypalSecret = config('services.paypal.sandbox.client_secret');
        $this->stripeKey = config('services.stripe.key');
        $this->stripeSecret = config('services.stripe.secret');
        $this->mtnEndpoint = config('services.mtn_momo.api_url');
        $this->orangeEndpoint = config('services.orange_money.api_url');
        $this->airtelEndpoint = config('services.airtel_money.api_url');
        $this->paypalEndpoint = config('services.paypal.mode') === 'sandbox'
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api-m.paypal.com';
    }

    /**
     * Initier un paiement MTN Mobile Money
     */
    public function initiateMTNPayment(Subscription $subscription)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->mtnApiKey,
                'Content-Type' => 'application/json',
            ])->post($this->mtnEndpoint . '/collection/v1/requesttopay', [
                'amount' => $subscription->price_paid,
                'currency' => $subscription->currency,
                'externalId' => $subscription->id,
                'payer' => [
                    'partyIdType' => 'MSISDN',
                    'partyId' => $subscription->user->phone
                ],
                'payerMessage' => "Paiement abonnement " . ucfirst($subscription->plan),
                'payeeNote' => "Abonnement ID: " . $subscription->id
            ]);

            if ($response->successful()) {
                $subscription->update([
                    'payment_details' => [
                        'provider' => 'mtn',
                        'transaction_id' => $response->json('transactionId'),
                        'status' => 'pending'
                    ]
                ]);

                return [
                    'success' => true,
                    'transaction_id' => $response->json('transactionId'),
                    'message' => 'Paiement initié avec succès'
                ];
            }

            throw new Exception('Échec de l\'initiation du paiement: ' . $response->body());

        } catch (Exception $e) {
            Log::error('MTN Payment Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur lors de l\'initiation du paiement'
            ];
        }
    }

    /**
     * Initier un paiement Orange Money
     */
    public function initiateOrangePayment(Subscription $subscription)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->orangeApiKey,
                'Content-Type' => 'application/json',
            ])->post($this->orangeEndpoint . '/orange-money-webpay/cm/v1/webpayment', [
                'merchant_key' => config('services.orange.merchant_key'),
                'currency' => $subscription->currency,
                'order_id' => $subscription->id,
                'amount' => $subscription->price_paid,
                'return_url' => route('payment.callback.orange'),
                'cancel_url' => route('payment.callback.orange.cancel'),
                'notif_url' => route('payment.webhook.orange'),
                'lang' => 'fr',
                'reference' => "SUB-" . $subscription->id,
            ]);

            if ($response->successful()) {
                $subscription->update([
                    'payment_details' => [
                        'provider' => 'orange',
                        'payment_url' => $response->json('payment_url'),
                        'payment_token' => $response->json('pay_token'),
                        'status' => 'pending'
                    ]
                ]);

                return [
                    'success' => true,
                    'payment_url' => $response->json('payment_url'),
                    'message' => 'Redirection vers la page de paiement'
                ];
            }

            throw new Exception('Échec de l\'initiation du paiement: ' . $response->body());

        } catch (Exception $e) {
            Log::error('Orange Payment Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur lors de l\'initiation du paiement'
            ];
        }
    }

    /**
     * Vérifier le statut d'un paiement MTN
     */
    public function checkMTNPaymentStatus($transactionId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->mtnApiKey,
                'Content-Type' => 'application/json',
            ])->get($this->mtnEndpoint . '/collection/v1/requesttopay/' . $transactionId);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'status' => $response->json('status'),
                    'message' => 'Statut récupéré avec succès'
                ];
            }

            throw new Exception('Échec de la vérification: ' . $response->body());

        } catch (Exception $e) {
            Log::error('MTN Status Check Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur lors de la vérification du statut'
            ];
        }
    }

    /**
     * Vérifier le statut d'un paiement Orange Money
     */
    public function checkOrangePaymentStatus($payToken)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->orangeApiKey,
                'Content-Type' => 'application/json',
            ])->get($this->orangeEndpoint . '/orange-money-webpay/cm/v1/transactionstatus', [
                'order_id' => $payToken
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'status' => $response->json('status'),
                    'message' => 'Statut récupéré avec succès'
                ];
            }

            throw new Exception('Échec de la vérification: ' . $response->body());

        } catch (Exception $e) {
            Log::error('Orange Status Check Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur lors de la vérification du statut'
            ];
        }
    }

    /**
     * Mettre à jour le statut d'un abonnement après paiement
     */
    public function updateSubscriptionAfterPayment(Subscription $subscription, $status)
    {
        if ($status === 'SUCCESSFUL') {
            $subscription->update([
                'status' => 'active',
                'payment_details' => array_merge(
                    $subscription->payment_details ?? [],
                    ['status' => 'completed', 'completed_at' => now()]
                )
            ]);

            // Désactiver les anciens abonnements actifs
            Subscription::where('user_id', $subscription->user_id)
                ->where('id', '!=', $subscription->id)
                ->where('status', 'active')
                ->update(['status' => 'expired']);

            // Activer le compte de l'agent
            $subscription->user->update([
                'status' => 'active',
                'email_verified_at' => now()
            ]);

            return true;
        }

        $subscription->update([
            'status' => 'failed',
            'payment_details' => array_merge(
                $subscription->payment_details ?? [],
                ['status' => 'failed', 'failed_at' => now()]
            )
        ]);

        // Désactiver le compte de l'agent si le paiement échoue
        $subscription->user->update([
            'status' => 'inactive'
        ]);

        return false;
    }

    /**
     * Initier un paiement Airtel Money
     */
    public function initiateAirtelPayment(Subscription $subscription)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->airtelApiKey,
                'Content-Type' => 'application/json',
            ])->post($this->airtelEndpoint . '/merchant/v1/payments', [
                'reference' => $subscription->id,
                'subscriber' => [
                    'country' => 'GA',
                    'currency' => $subscription->currency,
                    'msisdn' => $subscription->user->phone
                ],
                'transaction' => [
                    'amount' => $subscription->price_paid,
                    'country' => 'GA',
                    'currency' => $subscription->currency,
                    'id' => "SUB-" . $subscription->id
                ]
            ]);

            if ($response->successful()) {
                $subscription->update([
                    'payment_details' => [
                        'provider' => 'airtel',
                        'transaction_id' => $response->json('transaction.id'),
                        'status' => 'pending'
                    ]
                ]);

                return [
                    'success' => true,
                    'transaction_id' => $response->json('transaction.id'),
                    'message' => 'Paiement initié avec succès'
                ];
            }

            throw new Exception('Échec de l\'initiation du paiement: ' . $response->body());

        } catch (Exception $e) {
            Log::error('Airtel Payment Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur lors de l\'initiation du paiement'
            ];
        }
    }

    /**
     * Initier un paiement PayPal
     */
    public function initiatePayPalPayment(Subscription $subscription)
    {
        try {
            // Obtenir le token d'accès
            $tokenResponse = Http::withBasicAuth($this->paypalClientId, $this->paypalSecret)
                ->asForm()
                ->post($this->paypalEndpoint . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials'
                ]);

            if (!$tokenResponse->successful()) {
                throw new Exception('Échec de l\'authentification PayPal');
            }

            $accessToken = $tokenResponse->json('access_token');

            // Créer l'ordre
            $response = Http::withToken($accessToken)
                ->post($this->paypalEndpoint . '/v2/checkout/orders', [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'reference_id' => $subscription->id,
                        'amount' => [
                            'currency_code' => $subscription->currency,
                            'value' => number_format($subscription->price_paid, 2, '.', '')
                        ],
                        'description' => "Abonnement " . ucfirst($subscription->plan)
                    ]],
                    'application_context' => [
                        'return_url' => route('payment.callback.paypal'),
                        'cancel_url' => route('payment.callback.paypal.cancel')
                    ]
                ]);

            if ($response->successful()) {
                $subscription->update([
                    'payment_details' => [
                        'provider' => 'paypal',
                        'order_id' => $response->json('id'),
                        'status' => 'pending'
                    ]
                ]);

                return [
                    'success' => true,
                    'approval_url' => collect($response->json('links'))
                        ->firstWhere('rel', 'approve')['href'],
                    'message' => 'Redirection vers PayPal'
                ];
            }

            throw new Exception('Échec de l\'initiation du paiement: ' . $response->body());

        } catch (Exception $e) {
            Log::error('PayPal Payment Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur lors de l\'initiation du paiement'
            ];
        }
    }

    /**
     * Initier un paiement par carte bancaire (Stripe)
     */
    public function initiateStripePayment(Subscription $subscription)
    {
        try {
            Stripe::setApiKey($this->stripeSecret);

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower($subscription->currency),
                        'unit_amount' => $subscription->price_paid * 100,
                        'product_data' => [
                            'name' => "Abonnement " . ucfirst($subscription->plan),
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.callback.stripe', ['session_id' => '{CHECKOUT_SESSION_ID}']),
                'cancel_url' => route('payment.callback.stripe.cancel'),
                'metadata' => [
                    'subscription_id' => $subscription->id
                ]
            ]);

            $subscription->update([
                'payment_details' => [
                    'provider' => 'stripe',
                    'session_id' => $session->id,
                    'status' => 'pending'
                ]
            ]);

            return [
                'success' => true,
                'session_id' => $session->id,
                'checkout_url' => $session->url,
                'message' => 'Redirection vers la page de paiement'
            ];

        } catch (Exception $e) {
            Log::error('Stripe Payment Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur lors de l\'initiation du paiement'
            ];
        }
    }

    /**
     * Vérifier le statut d'un paiement Airtel
     */
    public function checkAirtelPaymentStatus($transactionId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->airtelApiKey,
                'Content-Type' => 'application/json',
            ])->get($this->airtelEndpoint . '/standard/v1/payments/' . $transactionId);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'status' => $response->json('status'),
                    'message' => 'Statut récupéré avec succès'
                ];
            }

            throw new Exception('Échec de la vérification: ' . $response->body());

        } catch (Exception $e) {
            Log::error('Airtel Status Check Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur lors de la vérification du statut'
            ];
        }
    }
}
