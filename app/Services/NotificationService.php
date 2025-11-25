<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Property;
use App\Mail\WelcomeEmail;

class NotificationService
{
    /**
     * Types de notifications
     */
    const NOTIFICATION_TYPES = [
        'welcome' => 'Bienvenue',
        'property_approved' => 'Propriété approuvée',
        'property_rejected' => 'Propriété rejetée',
        'new_message' => 'Nouveau message',
        'subscription_expiring' => 'Abonnement expirant',
        'subscription_expired' => 'Abonnement expiré',
        'payment_success' => 'Paiement réussi',
        'payment_failed' => 'Paiement échoué',
        'property_inquiry' => 'Demande de renseignements',
        'system_maintenance' => 'Maintenance système',
    ];

    /**
     * Canaux de notification disponibles
     */
    const CHANNELS = [
        'email' => 'Email',
        'sms' => 'SMS',
        'slack' => 'Slack',
        'database' => 'Base de données',
        'push' => 'Notification push',
    ];

    /**
     * Envoyer un email de bienvenue
     */
    public function sendWelcomeEmail(User $user): bool
    {
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));
            
            $this->logNotification('welcome', $user->id, [
                'email' => $user->email,
                'name' => $user->name,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de bienvenue', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Notifier l'approbation d'une propriété
     */
    public function notifyPropertyApproved(Property $property): bool
    {
        try {
            $user = $property->user;
            
            // Email de notification
            $this->sendEmail($user->email, 'Propriété approuvée', 'emails.property-approved', [
                'user' => $user,
                'property' => $property,
            ]);

            // Notification en base de données
            $this->createDatabaseNotification($user->id, 'property_approved', [
                'title' => 'Propriété approuvée',
                'message' => "Votre propriété '{$property->title}' a été approuvée et est maintenant visible sur le site.",
                'property_id' => $property->id,
                'property_title' => $property->title,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la notification d\'approbation de propriété', [
                'property_id' => $property->id,
                'user_id' => $property->user_id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Notifier le rejet d'une propriété
     */
    public function notifyPropertyRejected(Property $property, string $reason = ''): bool
    {
        try {
            $user = $property->user;
            
            // Email de notification
            $this->sendEmail($user->email, 'Propriété rejetée', 'emails.property-rejected', [
                'user' => $user,
                'property' => $property,
                'reason' => $reason,
            ]);

            // Notification en base de données
            $this->createDatabaseNotification($user->id, 'property_rejected', [
                'title' => 'Propriété rejetée',
                'message' => "Votre propriété '{$property->title}' a été rejetée. Raison: {$reason}",
                'property_id' => $property->id,
                'property_title' => $property->title,
                'reason' => $reason,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la notification de rejet de propriété', [
                'property_id' => $property->id,
                'user_id' => $property->user_id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Notifier un nouveau message
     */
    public function notifyNewMessage(User $recipient, array $messageData): bool
    {
        try {
            // Email de notification
            $this->sendEmail($recipient->email, 'Nouveau message', 'emails.new-message', [
                'recipient' => $recipient,
                'message_data' => $messageData,
            ]);

            // Notification en base de données
            $this->createDatabaseNotification($recipient->id, 'new_message', [
                'title' => 'Nouveau message',
                'message' => "Vous avez reçu un nouveau message de {$messageData['sender_name']}",
                'sender_name' => $messageData['sender_name'],
                'subject' => $messageData['subject'] ?? '',
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la notification de nouveau message', [
                'recipient_id' => $recipient->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Notifier l'expiration prochaine d'un abonnement
     */
    public function notifySubscriptionExpiring(User $user, int $daysLeft): bool
    {
        try {
            // Email de notification
            $this->sendEmail($user->email, 'Abonnement expirant', 'emails.subscription-expiring', [
                'user' => $user,
                'days_left' => $daysLeft,
            ]);

            // Notification en base de données
            $this->createDatabaseNotification($user->id, 'subscription_expiring', [
                'title' => 'Abonnement expirant',
                'message' => "Votre abonnement expire dans {$daysLeft} jour(s). Renouvelez-le pour continuer à profiter de nos services.",
                'days_left' => $daysLeft,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la notification d\'expiration d\'abonnement', [
                'user_id' => $user->id,
                'days_left' => $daysLeft,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Notifier le succès d'un paiement
     */
    public function notifyPaymentSuccess(User $user, array $paymentData): bool
    {
        try {
            // Email de notification
            $this->sendEmail($user->email, 'Paiement confirmé', 'emails.payment-success', [
                'user' => $user,
                'payment' => $paymentData,
            ]);

            // Notification en base de données
            $this->createDatabaseNotification($user->id, 'payment_success', [
                'title' => 'Paiement confirmé',
                'message' => "Votre paiement de {$paymentData['amount']} {$paymentData['currency']} a été confirmé.",
                'amount' => $paymentData['amount'],
                'currency' => $paymentData['currency'],
                'transaction_id' => $paymentData['transaction_id'] ?? '',
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la notification de succès de paiement', [
                'user_id' => $user->id,
                'payment_data' => $paymentData,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Notifier l'échec d'un paiement
     */
    public function notifyPaymentFailed(User $user, array $paymentData): bool
    {
        try {
            // Email de notification
            $this->sendEmail($user->email, 'Échec du paiement', 'emails.payment-failed', [
                'user' => $user,
                'payment' => $paymentData,
            ]);

            // Notification en base de données
            $this->createDatabaseNotification($user->id, 'payment_failed', [
                'title' => 'Échec du paiement',
                'message' => "Votre paiement de {$paymentData['amount']} {$paymentData['currency']} a échoué. Veuillez réessayer.",
                'amount' => $paymentData['amount'],
                'currency' => $paymentData['currency'],
                'reason' => $paymentData['reason'] ?? '',
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la notification d\'échec de paiement', [
                'user_id' => $user->id,
                'payment_data' => $paymentData,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Envoyer une notification Slack
     */
    public function sendSlackNotification(string $message, string $channel = '#general'): bool
    {
        $webhookUrl = config('services.slack.webhook_url');
        
        if (!$webhookUrl) {
            Log::warning('URL webhook Slack non configurée');
            return false;
        }

        try {
            $response = Http::post($webhookUrl, [
                'channel' => $channel,
                'text' => $message,
                'username' => config('app.name'),
                'icon_emoji' => ':house:',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de notification Slack', [
                'message' => $message,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Envoyer un SMS (intégration future)
     */
    public function sendSMS(string $phoneNumber, string $message): bool
    {
        // TODO: Implémenter l'envoi de SMS avec un service comme Twilio
        Log::info('SMS à envoyer', [
            'phone' => $phoneNumber,
            'message' => $message
        ]);
        
        return true;
    }

    /**
     * Créer une notification en base de données
     */
    private function createDatabaseNotification(int $userId, string $type, array $data): void
    {
        try {
            // TODO: Créer le modèle Notification si nécessaire
            // Notification::create([
            //     'user_id' => $userId,
            //     'type' => $type,
            //     'title' => $data['title'],
            //     'message' => $data['message'],
            //     'data' => json_encode($data),
            //     'read_at' => null,
            // ]);

            Log::info('Notification en base de données créée', [
                'user_id' => $userId,
                'type' => $type,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de notification en base', [
                'user_id' => $userId,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Envoyer un email générique
     */
    private function sendEmail(string $to, string $subject, string $template, array $data): bool
    {
        try {
            // TODO: Créer les templates d'email correspondants
            Mail::send($template, $data, function ($message) use ($to, $subject) {
                $message->to($to)
                        ->subject($subject)
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi d\'email', [
                'to' => $to,
                'subject' => $subject,
                'template' => $template,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Logger une notification
     */
    private function logNotification(string $type, int $userId, array $data): void
    {
        Log::info('Notification envoyée', [
            'type' => $type,
            'user_id' => $userId,
            'data' => $data,
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Notifier les administrateurs d'un événement important
     */
    public function notifyAdmins(string $subject, string $message, array $data = []): bool
    {
        try {
            $admins = User::where('role', 'admin')->get();
            
            foreach ($admins as $admin) {
                $this->sendEmail($admin->email, $subject, 'emails.admin-notification', [
                    'admin' => $admin,
                    'subject' => $subject,
                    'message' => $message,
                    'data' => $data,
                ]);
            }

            // Notification Slack pour les admins
            $this->sendSlackNotification("🔔 {$subject}: {$message}", '#admin');

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la notification des administrateurs', [
                'subject' => $subject,
                'message' => $message,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Obtenir les préférences de notification d'un utilisateur
     */
    public function getUserNotificationPreferences(int $userId): array
    {
        // TODO: Implémenter la gestion des préférences utilisateur
        return [
            'email' => true,
            'sms' => false,
            'push' => true,
            'marketing' => false,
        ];
    }

    /**
     * Mettre à jour les préférences de notification d'un utilisateur
     */
    public function updateUserNotificationPreferences(int $userId, array $preferences): bool
    {
        try {
            // TODO: Sauvegarder les préférences en base de données
            Log::info('Préférences de notification mises à jour', [
                'user_id' => $userId,
                'preferences' => $preferences
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour des préférences', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Envoyer une notification de maintenance système
     */
    public function notifySystemMaintenance(\DateTime $startTime, \DateTime $endTime, string $reason = ''): bool
    {
        try {
            $users = User::where('role', '!=', 'client')->get(); // Notifier agents et admins
            
            foreach ($users as $user) {
                $this->sendEmail($user->email, 'Maintenance programmée', 'emails.system-maintenance', [
                    'user' => $user,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'reason' => $reason,
                ]);
            }

            // Notification Slack
            $duration = $endTime->diff($startTime)->format('%h heures %i minutes');
            $this->sendSlackNotification(
                "🔧 Maintenance programmée le {$startTime->format('d/m/Y à H:i')} (durée: {$duration}). Raison: {$reason}",
                '#general'
            );

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la notification de maintenance', [
                'start_time' => $startTime,
                'end_time' => $endTime,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
