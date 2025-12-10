<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Property extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'type',
        'status',
        'price',
        'currency',
        'description',
        'bedrooms',
        'bathrooms',
        'surface_area',
        'location',
        'address',
        'city',
        'neighborhood',
        'featured',
        'published',
        'views_count',
        'furnished',
        'parking',
        'garden',
        'pool',
        'security',
        'elevator',
        'balcony',
        'air_conditioning',
        'floor',
        'total_floors',
        'construction_year',
        'energy_rating',
        'latitude',
        'longitude',
        'nearby_amenities',
        'features',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'surface_area' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'featured' => 'boolean',
        'published' => 'boolean',
        'furnished' => 'boolean',
        'parking' => 'boolean',
        'garden' => 'boolean',
        'pool' => 'boolean',
        'security' => 'boolean',
        'elevator' => 'boolean',
        'balcony' => 'boolean',
        'air_conditioning' => 'boolean',
        'views_count' => 'integer',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'floor' => 'integer',
        'total_floors' => 'integer',
        'construction_year' => 'integer',
        'features' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($property) {
            if (empty($property->slug)) {
                $property->slug = Str::slug($property->title);
            }
        });

        static::updating(function ($property) {
            if ($property->isDirty('title')) {
                $property->slug = Str::slug($property->title);
            }
        });
    }

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasOne
    {
        return $this->hasOne(PropertyDetail::class);
    }

    // Spatie Media Library - Ancienne relation media() conservée pour compatibilité
    public function propertyMedia(): HasMany
    {
        return $this->hasMany(PropertyMedia::class)->orderBy('order');
    }

    public function featuredImage(): HasOne
    {
        return $this->hasOne(PropertyMedia::class)->where('is_featured', true);
    }

    // Méthodes helper pour Spatie Media Library
    public function getImagesAttribute()
    {
        return $this->getMedia('images');
    }

    public function getVideosAttribute()
    {
        return $this->getMedia('videos');
    }

    public function getFirstImageUrlAttribute()
    {
        return $this->getFirstMediaUrl('images');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useFallbackUrl('/images/placeholder.jpg')
            ->useFallbackPath(public_path('/images/placeholder.jpg'));

        $this->addMediaCollection('videos')
            ->useFallbackUrl('/images/video-placeholder.jpg');
    }

    public function views(): HasMany
    {
        return $this->hasMany(PropertyView::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function cityModel(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city', 'slug');
    }

    public function neighborhoodModel(): BelongsTo
    {
        return $this->belongsTo(Neighborhood::class, 'neighborhood', 'slug');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    public function scopeSurfaceRange($query, $min, $max)
    {
        return $query->whereBetween('surface_area', [$min, $max]);
    }

    public function scopeWithFeatures($query, $features)
    {
        foreach ($features as $feature) {
            $query->where($feature, true);
        }
        return $query;
    }

    public function scopeMinBedrooms($query, $min)
    {
        return $query->where('bedrooms', '>=', $min);
    }

    public function scopeMinBathrooms($query, $min)
    {
        return $query->where('bathrooms', '>=', $min);
    }

    public function scopeNearLocation($query, $latitude, $longitude, $radius = 10)
    {
        return $query->whereRaw(
            "ST_Distance_Sphere(POINT(longitude, latitude), POINT(?, ?)) <= ?",
            [$longitude, $latitude, $radius * 1000]
        );
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', ' ') . ' ' . $this->currency;
    }

    public function getFormattedSurfaceAttribute()
    {
        return number_format($this->surface_area, 0, ',', ' ') . ' m²';
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Méthodes utilitaires
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function isFavoritedBy($user)
    {
        if (!$user) return false;
        return $this->favorites()->where('user_id', $user->id)->exists();
    }

    public function canBeEditedBy($user)
    {
        return $user && ($user->id === $this->user_id || $user->role === 'admin');
    }

    public function isActive()
    {
        return $this->published && $this->user->status === 'active';
    }
}
