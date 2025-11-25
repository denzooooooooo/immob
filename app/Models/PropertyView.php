<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyView extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'property_id',
        'user_id',
        'ip_address',
        'user_agent',
        'session_id',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    // Relations
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByIp($query, $ip)
    {
        return $query->where('ip_address', $ip);
    }

    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('viewed_at', '>=', now()->subHours($hours));
    }

    // Méthodes utilitaires
    public static function recordView($property, $user = null)
    {
        $data = [
            'property_id' => $property->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'session_id' => session()->getId(),
            'viewed_at' => now(),
        ];

        if ($user) {
            $data['user_id'] = $user->id;
        }

        // Éviter les doublons dans un court laps de temps
        $exists = static::where('property_id', $property->id)
            ->where('ip_address', $data['ip_address'])
            ->where('viewed_at', '>=', now()->subMinutes(30))
            ->exists();

        if (!$exists) {
            static::create($data);
            $property->increment('views_count');
        }
    }
}
