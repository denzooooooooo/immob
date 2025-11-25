<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'year_built',
        'parking_spaces',
        'furnished',
        'air_conditioning',
        'swimming_pool',
        'security_system',
        'internet',
        'garden',
        'balcony',
        'elevator',
        'garage',
        'terrace',
    ];

    protected $casts = [
        'year_built' => 'integer',
        'parking_spaces' => 'integer',
        'furnished' => 'boolean',
        'air_conditioning' => 'boolean',
        'swimming_pool' => 'boolean',
        'security_system' => 'boolean',
        'internet' => 'boolean',
        'garden' => 'boolean',
        'balcony' => 'boolean',
        'elevator' => 'boolean',
        'garage' => 'boolean',
        'terrace' => 'boolean',
    ];

    // Relations
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    // Méthodes utilitaires
    public function getAmenitiesAttribute()
    {
        $amenities = [];
        
        if ($this->furnished) $amenities[] = 'Meublé';
        if ($this->air_conditioning) $amenities[] = 'Climatisation';
        if ($this->swimming_pool) $amenities[] = 'Piscine';
        if ($this->security_system) $amenities[] = 'Système de sécurité';
        if ($this->internet) $amenities[] = 'Internet';
        if ($this->garden) $amenities[] = 'Jardin';
        if ($this->balcony) $amenities[] = 'Balcon';
        if ($this->elevator) $amenities[] = 'Ascenseur';
        if ($this->garage) $amenities[] = 'Garage';
        if ($this->terrace) $amenities[] = 'Terrasse';
        
        return $amenities;
    }

    public function getAmenitiesCountAttribute()
    {
        return count($this->amenities);
    }
}
