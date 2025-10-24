<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class E2cArticle extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'featured_image',
        'gallery',
        'videos',
        'social_links',
        'photographer',
        'photographer_link',
        'is_jury',
        'display_order',
        'salon_id'
    ];

    protected $casts = [
        'gallery' => 'array',
        'videos' => 'array',
        'social_links' => 'array',
        'is_jury' => 'boolean',
    ];

    protected $hidden = [
        'featured_image',
        'gallery',
    ];

    protected $appends = [
        'featured_image_url',
        'gallery_urls',
    ];

    protected static function booted()
    {
        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('title')) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }

    // Scopes pour faciliter les requêtes
    public function scopeJury($query)
    {
        return $query->where('is_jury', true);
    }

    public function scopeParticipants($query)
    {
        return $query->where('is_jury', false);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order')->orderBy('created_at');
    }

    // Accessors pour transformer les IDs de médias en URLs
    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (!$this->featured_image) {
            return null;
        }

        // Si c'est déjà une URL complète, la retourner telle quelle
        if (filter_var($this->featured_image, FILTER_VALIDATE_URL)) {
            return $this->featured_image;
        }

        // Si c'est un ID numérique, récupérer l'URL depuis Spatie Media Library
        if (is_numeric($this->featured_image)) {
            $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($this->featured_image);
            return $media ? $media->getUrl() : null;
        }

        return $this->featured_image;
    }

    public function getGalleryUrlsAttribute(): array
    {
        if (!$this->gallery || !is_array($this->gallery)) {
            return [];
        }

        return array_map(function ($item) {
            // Si c'est déjà une URL complète, la retourner telle quelle
            if (filter_var($item, FILTER_VALIDATE_URL)) {
                return $item;
            }

            // Si c'est un ID numérique, récupérer l'URL depuis Spatie Media Library
            if (is_numeric($item)) {
                $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($item);
                return $media ? $media->getUrl() : $item;
            }

            return $item;
        }, $this->gallery);
    }
}
