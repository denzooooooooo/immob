<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PropertyMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'type',
        'path',
        'title',
        'thumbnail_path',
        'order',
        'is_featured',
        'mime_type',
        'size',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_featured' => 'boolean',
        'size' => 'integer',
    ];

    protected $appends = [
        'url',
        'thumbnail_url',
    ];

    // Relations
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    // Scopes
    public function scopeImages($query)
    {
        return $query->where('type', 'image');
    }

    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    public function scopeVirtualTours($query)
    {
        return $query->where('type', '360_view');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Accessors
    public function getUrlAttribute()
    {
        // DEBUG: Log pour voir ce qui se passe
        \Log::info('PropertyMedia getUrlAttribute', [
            'original_path' => $this->path,
            'cleaned_path' => str_replace('storage/', '', $this->path),
            'final_url' => '/storage/' . str_replace('storage/', '', $this->path),
            'file_exists' => file_exists(public_path('storage/' . str_replace('storage/', '', $this->path))),
            'full_path' => public_path('storage/' . str_replace('storage/', '', $this->path)),
        ]);
        
        // Retourner directement le chemin avec /storage/ au début
        return '/storage/' . str_replace('storage/', '', $this->path);
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail_path ? Storage::url($this->thumbnail_path) : null;
    }

    public function getFormattedSizeAttribute()
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    // Méthodes utilitaires
    public function isImage()
    {
        return $this->type === 'image';
    }

    public function isVideo()
    {
        return $this->type === 'video';
    }

    public function isVirtualTour()
    {
        return $this->type === '360_view';
    }

    public function delete()
    {
        // Supprimer les fichiers physiques
        Storage::delete($this->path);
        if ($this->thumbnail_path) {
            Storage::delete($this->thumbnail_path);
        }

        return parent::delete();
    }

    protected static function boot()
    {
        parent::boot();

        // Réorganiser l'ordre des médias après suppression
        static::deleted(function ($media) {
            $media->property->media()
                ->where('order', '>', $media->order)
                ->decrement('order');
        });
    }
}
