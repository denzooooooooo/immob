<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_id',
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByProperty($query, $propertyId)
    {
        return $query->where('property_id', $propertyId);
    }

    // Méthodes utilitaires
    public static function toggle($userId, $propertyId)
    {
        $favorite = static::where('user_id', $userId)
            ->where('property_id', $propertyId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return false; // Supprimé des favoris
        } else {
            static::create([
                'user_id' => $userId,
                'property_id' => $propertyId,
            ]);
            return true; // Ajouté aux favoris
        }
    }

    public static function isFavorited($userId, $propertyId)
    {
        return static::where('user_id', $userId)
            ->where('property_id', $propertyId)
            ->exists();
    }
}
