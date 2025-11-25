<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any subscriptions.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'agent';
    }

    /**
     * Determine whether the user can view the subscription.
     */
    public function view(User $user, Subscription $subscription): bool
    {
        return $user->role === 'admin' || $user->id === $subscription->user_id;
    }

    /**
     * Determine whether the user can create subscriptions.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'agent';
    }

    /**
     * Determine whether the user can update the subscription.
     */
    public function update(User $user, Subscription $subscription): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the subscription.
     */
    public function delete(User $user, Subscription $subscription): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the subscription.
     */
    public function restore(User $user, Subscription $subscription): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the subscription.
     */
    public function forceDelete(User $user, Subscription $subscription): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can cancel the subscription.
     */
    public function cancel(User $user, Subscription $subscription): bool
    {
        return $user->role === 'admin' || $user->id === $subscription->user_id;
    }

    /**
     * Determine whether the user can renew the subscription.
     */
    public function renew(User $user, Subscription $subscription): bool
    {
        return $user->role === 'admin' || $user->id === $subscription->user_id;
    }

    /**
     * Determine whether the user can upgrade the subscription.
     */
    public function upgrade(User $user, Subscription $subscription): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->id === $subscription->user_id && 
               $subscription->isActive() &&
               !in_array($subscription->plan, ['enterprise']);
    }

    /**
     * Determine whether the user can downgrade the subscription.
     */
    public function downgrade(User $user, Subscription $subscription): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->id !== $subscription->user_id || !$subscription->isActive()) {
            return false;
        }

        // Vérifier si le nouveau plan peut supporter le nombre actuel de propriétés
        $propertiesCount = $user->properties()->count();
        $planLimits = [
            'basic' => 3,
            'standard' => 10,
            'premium' => 25,
            'enterprise' => 100
        ];

        foreach ($planLimits as $plan => $limit) {
            if ($subscription->plan === $plan) {
                break;
            }
            if ($propertiesCount <= $limit) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can view subscription statistics.
     */
    public function viewStatistics(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can manage payment methods.
     */
    public function managePaymentMethods(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'agent';
    }

    /**
     * Determine whether the user can view payment history.
     */
    public function viewPaymentHistory(User $user, Subscription $subscription): bool
    {
        return $user->role === 'admin' || $user->id === $subscription->user_id;
    }

    /**
     * Determine whether the user can generate invoices.
     */
    public function generateInvoice(User $user, Subscription $subscription): bool
    {
        return $user->role === 'admin' || $user->id === $subscription->user_id;
    }

    /**
     * Determine whether the user can apply promotional codes.
     */
    public function applyPromoCode(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'agent';
    }

    /**
     * Determine whether the user can get a refund.
     */
    public function getRefund(User $user, Subscription $subscription): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        // L'utilisateur peut demander un remboursement dans les 24h suivant le paiement
        return $user->id === $subscription->user_id && 
               $subscription->created_at->diffInHours(now()) <= 24 &&
               !$subscription->hasUsedServices();
    }
}
