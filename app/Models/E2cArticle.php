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
}
