<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Property;
use App\Models\Message;
use App\Models\Subscription;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Property::class => \App\Policies\PropertyPolicy::class,
        Message::class => \App\Policies\MessagePolicy::class,
        Subscription::class => \App\Policies\SubscriptionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gates pour les rôles
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('agent', function (User $user) {
            return $user->role === 'agent';
        });

        Gate::define('user', function (User $user) {
            return $user->role === 'user';
        });

        // Gates pour les permissions spécifiques
        Gate::define('manage-users', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-properties', function (User $user) {
            return in_array($user->role, ['admin', 'agent']);
        });

        Gate::define('manage-subscriptions', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('view-admin-dashboard', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('view-agent-dashboard', function (User $user) {
            return $user->role === 'agent';
        });

        // Gate pour vérifier si un agent peut poster des propriétés
        Gate::define('can-post-property', function (User $user) {
            if ($user->role !== 'agent') {
                return false;
            }

            $subscription = $user->currentSubscription();
            if (!$subscription || !$subscription->isActive()) {
                return false;
            }

            return $user->properties()->count() < $subscription->properties_limit;
        });

        // Gate pour vérifier si un utilisateur peut contacter un agent
        Gate::define('can-contact-agent', function (User $user, Property $property) {
            return $user->id !== $property->user_id;
        });

        // Gate pour vérifier si un utilisateur peut voir les détails d'une propriété
        Gate::define('view-property', function (?User $user, Property $property) {
            if (!$property->published) {
                return $user && ($user->id === $property->user_id || $user->role === 'admin');
            }
            return true;
        });

        // Gate pour vérifier si un utilisateur peut modifier une propriété
        Gate::define('update-property', function (User $user, Property $property) {
            return $user->id === $property->user_id || $user->role === 'admin';
        });

        // Gate pour vérifier si un utilisateur peut supprimer une propriété
        Gate::define('delete-property', function (User $user, Property $property) {
            return $user->id === $property->user_id || $user->role === 'admin';
        });

        // Gate pour vérifier si un utilisateur peut voir un message
        Gate::define('view-message', function (User $user, Message $message) {
            return $user->id === $message->sender_id || 
                   $user->id === $message->receiver_id || 
                   $user->role === 'admin';
        });

        // Gate pour vérifier si un utilisateur peut répondre à un message
        Gate::define('reply-message', function (User $user, Message $message) {
            return $user->id === $message->receiver_id;
        });
    }
}
