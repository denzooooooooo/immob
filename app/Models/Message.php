<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'property_id',
        'content',
        'type',
        'attachment_path',
        'read_at',
        'is_system_message',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'is_system_message' => 'boolean',
    ];

    // Relations
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeBetweenUsers($query, $user1Id, $user2Id)
    {
        return $query->where(function ($q) use ($user1Id, $user2Id) {
            $q->where('sender_id', $user1Id)->where('receiver_id', $user2Id);
        })->orWhere(function ($q) use ($user1Id, $user2Id) {
            $q->where('sender_id', $user2Id)->where('receiver_id', $user1Id);
        });
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('sender_id', $userId)->orWhere('receiver_id', $userId);
    }

    public function scopeByProperty($query, $propertyId)
    {
        return $query->where('property_id', $propertyId);
    }

    // Accessors
    public function getIsReadAttribute()
    {
        return !is_null($this->read_at);
    }

    public function getAttachmentUrlAttribute()
    {
        return $this->attachment_path ? asset('storage/' . $this->attachment_path) : null;
    }

    // Méthodes utilitaires
    public function markAsRead()
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }

    public function isFromUser($userId)
    {
        return $this->sender_id == $userId;
    }

    public function isToUser($userId)
    {
        return $this->receiver_id == $userId;
    }

    public function hasAttachment()
    {
        return !empty($this->attachment_path);
    }

    public static function getConversation($user1Id, $user2Id, $propertyId = null)
    {
        $query = static::betweenUsers($user1Id, $user2Id)
            ->orderBy('created_at', 'asc');

        if ($propertyId) {
            $query->where('property_id', $propertyId);
        }

        return $query->get();
    }

    public static function getUnreadCount($userId)
    {
        return static::where('receiver_id', $userId)
            ->unread()
            ->count();
    }

    public static function markConversationAsRead($user1Id, $user2Id, $propertyId = null)
    {
        $query = static::where('sender_id', $user1Id)
            ->where('receiver_id', $user2Id)
            ->unread();

        if ($propertyId) {
            $query->where('property_id', $propertyId);
        }

        $query->update(['read_at' => now()]);
    }
}
