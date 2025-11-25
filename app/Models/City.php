<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'region',
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

        static::creating(function ($city) {
            if (empty($city->slug)) {
                $city->slug = Str::slug($city->name);
            }
        });

        static::updating(function ($city) {
            if ($city->isDirty('name')) {
                $city->slug = Str::slug($city->name);
            }
        });
    }

    // Relations
    public function neighborhoods(): HasMany
    {
        return $this->hasMany(Neighborhood::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'city', 'name');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
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

    public static function getRegions()
    {
        return static::distinct('region')
            ->whereNotNull('region')
            ->pluck('region')
            ->sort()
            ->values();
    }

    public function getPropertiesCountByType($type)
    {
        return $this->properties()
            ->where('type', $type)
            ->count();
    }
}
