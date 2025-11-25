<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any messages.
     */
    public function viewAny(User $user): bool
    {
        return true; // Les utilisateurs peuvent voir leurs propres messages
    }

    /**
     * Determine whether the user can view the message.
     */
    public function view(User $user, Message $message): bool
    {
        return $user->id === $message->sender_id || 
               $user->id === $message->receiver_id || 
               $user->role === 'admin';
    }

    /**
     * Determine whether the user can create messages.
     */
    public function create(User $user): bool
    {
        return true; // Tous les utilisateurs connectés peuvent envoyer des messages
    }

    /**
     * Determine whether the user can update the message.
     */
    public function update(User $user, Message $message): bool
    {
        // Seul l'expéditeur peut modifier son message dans les 5 minutes
        if ($user->id !== $message->sender_id) {
            return false;
        }

        return $message->created_at->diffInMinutes(now()) <= 5;
    }

    /**
     * Determine whether the user can delete the message.
     */
    public function delete(User $user, Message $message): bool
    {
        return $user->id === $message->sender_id || 
               $user->id === $message->receiver_id || 
               $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the message.
     */
    public function restore(User $user, Message $message): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the message.
     */
    public function forceDelete(User $user, Message $message): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can reply to the message.
     */
    public function reply(User $user, Message $message): bool
    {
        return $user->id === $message->receiver_id;
    }

    /**
     * Determine whether the user can mark the message as read.
     */
    public function markAsRead(User $user, Message $message): bool
    {
        return $user->id === $message->receiver_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can report the message.
     */
    public function report(User $user, Message $message): bool
    {
        return $user->id === $message->receiver_id;
    }

    /**
     * Determine whether the user can moderate the message.
     */
    public function moderate(User $user, Message $message): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can view message statistics.
     */
    public function viewStatistics(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can export messages.
     */
    public function export(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can send messages to this property owner.
     */
    public function sendToProperty(User $user, $property): bool
    {
        // Un utilisateur ne peut pas s'envoyer un message à lui-même
        if ($user->id === $property->user_id) {
            return false;
        }

        // Vérifier si l'utilisateur n'a pas déjà envoyé trop de messages récemment
        $recentMessages = Message::where('sender_id', $user->id)
            ->where('receiver_id', $property->user_id)
            ->where('created_at', '>', now()->subHour())
            ->count();

        return $recentMessages < 3; // Maximum 3 messages par heure au même destinataire
    }

    /**
     * Determine whether the user can view conversation.
     */
    public function viewConversation(User $user, $otherUserId): bool
    {
        return Message::where(function ($query) use ($user, $otherUserId) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', $otherUserId);
        })->orWhere(function ($query) use ($user, $otherUserId) {
            $query->where('sender_id', $otherUserId)
                  ->where('receiver_id', $user->id);
        })->exists();
    }
}
