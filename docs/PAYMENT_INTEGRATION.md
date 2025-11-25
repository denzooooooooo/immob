# Intégration des Paiements Mobile Money

## Vue d'ensemble

Le système de paiement intègre les principales méthodes de paiement mobile du Cameroun :
- **MTN Mobile Money** - Paiement direct via API
- **Orange Money** - Redirection vers page de paiement

## Configuration

### 1. Variables d'environnement

Ajoutez ces variables à votre fichier `.env` :

```env
# MTN Mobile Money
MTN_API_KEY=your_mtn_api_key_here
MTN_API_ENDPOINT=https://proxy.momoapi.mtn.com
MTN_MERCHANT_ID=your_mtn_merchant_id_here
MTN_ENVIRONMENT=sandbox

# Orange Money
ORANGE_API_KEY=your_orange_api_key_here
ORANGE_API_ENDPOINT=https://api.orange.com
ORANGE_MERCHANT_KEY=your_orange_merchant_key_here
ORANGE_ENVIRONMENT=sandbox
```

### 2. Obtenir les clés API

#### MTN Mobile Money
1. Créez un compte développeur sur [MTN Developer Portal](https://momodeveloper.mtn.com/)
2. Créez une application et obtenez votre API Key
3. Configurez votre Merchant ID
4. Testez en mode sandbox avant la production

#### Orange Money
1. Créez un compte développeur sur [Orange Developer Portal](https://developer.orange.com/)
2. Souscrivez à l'API Orange Money
3. Obtenez votre API Key et Merchant Key
4. Configurez les URLs de callback

## Architecture

### Services

#### PaymentService (`app/Services/PaymentService.php`)
Service principal gérant les interactions avec les APIs de paiement :

- `initiateMTNPayment()` - Initie un paiement MTN
- `initiateOrangePayment()` - Initie un paiement Orange Money
- `checkMTNPaymentStatus()` - Vérifie le statut d'un paiement MTN
- `checkOrangePaymentStatus()` - Vérifie le statut d'un paiement Orange
- `updateSubscriptionAfterPayment()` - Met à jour l'abonnement après paiement

#### PaymentController (`app/Http/Controllers/PaymentController.php`)
Contrôleur gérant les callbacks et webhooks :

- `orangeCallback()` - Callback de retour Orange Money
- `orangeCancelCallback()` - Callback d'annulation Orange Money
- `orangeWebhook()` - Webhook Orange Money
- `mtnWebhook()` - Webhook MTN
- `checkMTNStatus()` - Endpoint de vérification statut MTN

### Routes

```php
// Callbacks Orange Money
Route::get('/payment/callback/orange', [PaymentController::class, 'orangeCallback']);
Route::get('/payment/callback/orange/cancel', [PaymentController::class, 'orangeCancelCallback']);

// Webhooks
Route::post('/payment/webhook/orange', [PaymentController::class, 'orangeWebhook']);
Route::post('/payment/webhook/mtn', [PaymentController::class, 'mtnWebhook']);

// Vérification de statut
Route::get('/payment/status/mtn', [PaymentController::class, 'checkMTNStatus']);
```

## Flux de paiement

### MTN Mobile Money

1. **Initiation** : L'utilisateur sélectionne MTN Money
2. **API Call** : Appel à l'API MTN pour initier le paiement
3. **Push USSD** : L'utilisateur reçoit un push USSD sur son téléphone
4. **Confirmation** : L'utilisateur confirme le paiement
5. **Vérification** : Le système vérifie périodiquement le statut
6. **Activation** : L'abonnement est activé une fois le paiement confirmé

### Orange Money

1. **Initiation** : L'utilisateur sélectionne Orange Money
2. **API Call** : Appel à l'API Orange pour obtenir l'URL de paiement
3. **Redirection** : Redirection vers la page de paiement Orange
4. **Paiement** : L'utilisateur effectue le paiement sur la page Orange
5. **Callback** : Orange redirige vers notre callback avec le résultat
6. **Activation** : L'abonnement est activé selon le résultat

## Sécurité

### Validation des webhooks

Les webhooks doivent être validés pour s'assurer qu'ils proviennent bien des fournisseurs :

```php
// Exemple de validation (à adapter selon la documentation du fournisseur)
$signature = $request->header('X-Signature');
$payload = $request->getContent();
$expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);

if (!hash_equals($signature, $expectedSignature)) {
    abort(403, 'Invalid signature');
}
```

### Gestion des erreurs

- **Timeouts** : Gestion des timeouts d'API
- **Erreurs réseau** : Retry automatique avec backoff
- **Erreurs de validation** : Messages d'erreur clairs pour l'utilisateur
- **Logging** : Tous les événements sont loggés pour le debugging

## Tests

### Mode Sandbox

Utilisez les environnements de test :
- MTN : `MTN_ENVIRONMENT=sandbox`
- Orange : `ORANGE_ENVIRONMENT=sandbox`

### Numéros de test

#### MTN Sandbox
- Numéro de test : `237650000000`
- PIN : `0000`

#### Orange Sandbox
- Numéro de test : `237690000000`
- PIN : `1234`

## Monitoring

### Logs

Les événements suivants sont loggés :
- Initiation de paiement
- Callbacks reçus
- Webhooks reçus
- Erreurs d'API
- Changements de statut d'abonnement

### Métriques recommandées

- Taux de succès des paiements
- Temps de traitement moyen
- Erreurs par fournisseur
- Volume de transactions

## Dépannage

### Problèmes courants

1. **Paiement en attente** : Vérifier la connectivité réseau de l'utilisateur
2. **Callback non reçu** : Vérifier la configuration des URLs
3. **Webhook en échec** : Vérifier les logs et la validation des signatures
4. **API timeout** : Augmenter les timeouts ou implémenter un retry

### Commandes utiles

```bash
# Vérifier les logs de paiement
tail -f storage/logs/laravel.log | grep -i payment

# Tester la connectivité API
curl -H "Authorization: Bearer YOUR_API_KEY" https://api.endpoint.com/test

# Vérifier le statut d'un abonnement
php artisan tinker
>>> App\Models\Subscription::find(1)->payment_details
```

## Production

### Checklist de déploiement

- [ ] Variables d'environnement de production configurées
- [ ] URLs de callback configurées chez les fournisseurs
- [ ] Certificats SSL valides
- [ ] Monitoring et alertes configurés
- [ ] Tests de bout en bout effectués
- [ ] Documentation utilisateur mise à jour

### URLs de production

Configurez ces URLs chez vos fournisseurs :
- Callback Orange : `https://votre-domaine.com/payment/callback/orange`
- Webhook Orange : `https://votre-domaine.com/payment/webhook/orange`
- Webhook MTN : `https://votre-domaine.com/payment/webhook/mtn`

## Support

Pour toute question technique :
1. Consultez les logs d'application
2. Vérifiez la documentation des fournisseurs
3. Testez en mode sandbox
4. Contactez le support technique des fournisseurs si nécessaire
