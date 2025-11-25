<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PropertyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any properties.
     */
    public function viewAny(?User $user): bool
    {
        return true; // Tout le monde peut voir la liste des propriétés publiées
    }

    /**
     * Determine whether the user can view the property.
     */
    public function view(?User $user, Property $property): bool
    {
        if ($property->published) {
            return true;
        }

        return $user && ($user->id === $property->user_id || $user->role === 'admin');
    }

    /**
     * Determine whether the user can create properties.
     */
    public function create(User $user): bool
    {
        if ($user->role !== 'agent') {
            return false;
        }

        $subscription = $user->currentSubscription();
        if (!$subscription || !$subscription->isActive()) {
            return false;
        }

        return $user->properties()->count() < $subscription->properties_limit;
    }

    /**
     * Determine whether the user can update the property.
     */
    public function update(User $user, Property $property): bool
    {
        return $user->id === $property->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the property.
     */
    public function delete(User $user, Property $property): bool
    {
        return $user->id === $property->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the property.
     */
    public function restore(User $user, Property $property): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the property.
     */
    public function forceDelete(User $user, Property $property): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can publish the property.
     */
    public function publish(User $user, Property $property): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->id !== $property->user_id) {
            return false;
        }

        $subscription = $user->currentSubscription();
        return $subscription && $subscription->isActive();
    }

    /**
     * Determine whether the user can unpublish the property.
     */
    public function unpublish(User $user, Property $property): bool
    {
        return $user->id === $property->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can feature the property.
     */
    public function feature(User $user, Property $property): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->id !== $property->user_id) {
            return false;
        }

        $subscription = $user->currentSubscription();
        return $subscription && 
               $subscription->isActive() && 
               in_array($subscription->plan, ['premium', 'enterprise']);
    }

    /**
     * Determine whether the user can manage media for the property.
     */
    public function manageMedia(User $user, Property $property): bool
    {
        return $user->id === $property->user_id || $user->role === 'admin';
    }
}
