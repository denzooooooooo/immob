<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan',
        'price_paid',
        'currency',
        'starts_at',
        'expires_at',
        'status',
        'payment_method',
        'transaction_id',
        'payment_details',
        'properties_limit',
        'properties_used',
        'featured_listings',
        'priority_support',
    ];

    protected $casts = [
        'price_paid' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'properties_limit' => 'integer',
        'properties_used' => 'integer',
        'featured_listings' => 'boolean',
        'priority_support' => 'boolean',
        'payment_details' => 'array',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    public function scopeByPlan($query, $plan)
    {
        return $query->where('plan', $plan);
    }

    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    // Accessors
    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active' && $this->expires_at > now();
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expires_at <= now();
    }

    public function getDaysRemainingAttribute(): int
    {
        if ($this->is_expired) return 0;
        return now()->diffInDays($this->expires_at);
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price_paid, 0, ',', ' ') . ' ' . $this->currency;
    }

    public function getUsagePercentageAttribute(): float
    {
        if ($this->properties_limit === 0) return 0;
        return ($this->properties_used / $this->properties_limit) * 100;
    }

    // Méthodes utilitaires
    public function incrementUsage(): void
    {
        $this->increment('properties_used');
    }

    public function decrementUsage(): void
    {
        if ($this->properties_used > 0) {
            $this->decrement('properties_used');
        }
    }

    public function canAddProperty(): bool
    {
        return $this->is_active && $this->properties_used < $this->properties_limit;
    }

    public function extend($days): void
    {
        $this->expires_at = $this->expires_at->addDays($days);
        $this->save();
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    public function renew($newExpiryDate = null): void
    {
        $this->update([
            'status' => 'active',
            'expires_at' => $newExpiryDate ?? now()->addMonth(),
        ]);
    }

    // Méthodes statiques pour les plans
    public static function getPlanDetails($plan): array
    {
        $plans = [
            'basic' => [
                'name' => 'Pack Basic',
                'price' => 10000, // 10M FCFA
                'properties_limit' => 5,
                'duration_days' => 30,
                'featured_listings' => false,
                'priority_support' => false,
                'features' => [
                    '5 annonces par mois',
                    'Support standard',
                    'Statistiques de base',
                ]
            ],
            'premium' => [
                'name' => 'Pack Premium',
                'price' => 20000, // 20M FCFA
                'properties_limit' => 15,
                'duration_days' => 30,
                'featured_listings' => true,
                'priority_support' => false,
                'features' => [
                    '15 annonces par mois',
                    'Annonces mises en avant',
                    'Statistiques avancées',
                    'Support standard',
                ]
            ],
            'pro' => [
                'name' => 'Pack Pro',
                'price' => 30000, // 30M FCFA
                'properties_limit' => 999,
                'duration_days' => 30,
                'featured_listings' => true,
                'priority_support' => true,
                'features' => [
                    'Annonces illimitées',
                    'Annonces mises en avant',
                    'Statistiques complètes',
                    'Support prioritaire',
                    'Badge professionnel',
                ]
            ],
        ];

        return $plans[$plan] ?? [];
    }

    public static function getAllPlans(): array
    {
        return [
            'basic' => self::getPlanDetails('basic'),
            'premium' => self::getPlanDetails('premium'),
            'pro' => self::getPlanDetails('pro'),
        ];
    }
}
