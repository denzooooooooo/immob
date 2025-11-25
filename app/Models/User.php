<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'status',
        'avatar',
        'company_name',
        'bio',
        'website',
        'address',
        'city',
        'email_notifications',
        'sms_notifications',
        'property_alerts',
        'price_alerts',
        'verification_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'property_alerts' => 'boolean',
        'price_alerts' => 'boolean',
        'password' => 'hashed',
    ];

    // Relations
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function propertyViews(): HasMany
    {
        return $this->hasMany(PropertyView::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    // Méthodes de vérification de rôle
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAgent(): bool
    {
        return $this->role === 'agent';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getActiveAttribute(): bool
    {
        return $this->status === 'active';
    }

    // Méthodes utilitaires
    public function getAvatarUrlAttribute()
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->exists();
    }

    public function getCurrentSubscriptionAttribute()
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }

    public function canPostProperty(): bool
    {
        $subscription = $this->current_subscription;
        if (!$subscription) return false;

        return $subscription->properties_used < $subscription->properties_limit;
    }

    public function getRemainingPropertiesAttribute(): int
    {
        $subscription = $this->current_subscription;
        if (!$subscription) return 0;

        return max(0, $subscription->properties_limit - $subscription->properties_used);
    }

    public function getUnreadMessagesCountAttribute(): int
    {
        return $this->receivedMessages()
            ->whereNull('read_at')
            ->count();
    }

    public function getFavoritedPropertiesAttribute()
    {
        return Property::whereIn('id', $this->favorites()->pluck('property_id'));
    }

    public function markEmailAsVerified()
    {
        if (is_null($this->email_verified_at)) {
            $this->forceFill([
                'email_verified_at' => now(),
            ])->save();
        }
    }

    public function markPhoneAsVerified()
    {
        if (is_null($this->phone_verified_at)) {
            $this->forceFill([
                'phone_verified_at' => now(),
                'verification_code' => null,
            ])->save();
        }
    }

    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }
}
