<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Neighborhood extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'name',
        'slug',
        'description',
        'latitude',
        'longitude',
        'properties_count',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'properties_count' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($neighborhood) {
            if (empty($neighborhood->slug)) {
                $neighborhood->slug = Str::slug($neighborhood->name);
            }
        });

        static::updating(function ($neighborhood) {
            if ($neighborhood->isDirty('name')) {
                $neighborhood->slug = Str::slug($neighborhood->name);
            }
        });
    }

    // Relations
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'neighborhood', 'slug');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCity($query, $cityId)
    {
        return $query->where('city_id', $cityId);
    }

    public function scopeWithPropertiesCount($query)
    {
        return $query->withCount('properties');
    }

    // Méthodes utilitaires
    public function updatePropertiesCount()
    {
        $this->properties_count = $this->properties()->count();
        $this->save();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getCoordinatesAttribute()
    {
        return [
            'lat' => $this->latitude,
            'lng' => $this->longitude,
        ];
    }

    public function getFullNameAttribute()
    {
        return $this->name . ', ' . $this->city->name;
    }

    public function getPropertiesCountByType($type)
    {
        return $this->properties()
            ->where('type', $type)
            ->count();
    }

    public function getAveragePriceByType($type)
    {
        return $this->properties()
            ->where('type', $type)
            ->avg('price');
    }
}
