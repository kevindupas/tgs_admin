<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'featured_image',
        'gallery',
        'videos',
        'social_links'
    ];

    protected $casts = [
        'gallery' => 'array',
        'videos' => 'array',
        'social_links' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    public function salons(): BelongsToMany
    {
        return $this->belongsToMany(Salon::class, 'article_salon')
            ->withPivot([
                'category_id',
                'availability_id',
                'is_featured',
                'is_published',
                'published_at',
                'is_scheduled',
                'is_cancelled',
                'schedule_content',
                'display_order',
            ])
            ->withTimestamps();
    }

    // Relation spécifique pour un salon donné, avec paramètre optionnel
    public function salon(Salon $salon = null)
    {
        // Si aucun salon n'est fourni, essayer de récupérer le salon courant de Filament
        if ($salon === null) {
            // Vérifier si nous sommes dans un contexte Filament avec tenancy
            try {
                if (class_exists('Filament\Facades\Filament') && Filament::hasTenancy()) {
                    $salon = Filament::getTenant();
                }
            } catch (\Throwable $e) {
                // Si une erreur se produit, retourner une relation vide
                return $this->salons()->whereRaw('1 = 0');
            }

            // Si toujours pas de salon, retourner une relation vide
            if ($salon === null) {
                return $this->salons()->whereRaw('1 = 0');
            }
        }

        return $this->salons()->where('salon_id', $salon->id);
    }

    // Raccourci pour obtenir les articles d'un salon
    public static function forSalon(Salon $salon = null)
    {
        // Si aucun salon n'est fourni, essayer de récupérer le salon courant de Filament
        if ($salon === null) {
            try {
                if (class_exists('Filament\Facades\Filament') && Filament::hasTenancy()) {
                    $salon = Filament::getTenant();
                }
            } catch (\Throwable $e) {
                // Si une erreur se produit, retourner une requête vide
                return self::whereRaw('1 = 0');
            }

            // Si toujours pas de salon, retourner une requête vide
            if ($salon === null) {
                return self::whereRaw('1 = 0');
            }
        }

        return self::whereHas('salons', function ($query) use ($salon) {
            $query->where('salon_id', $salon->id);
        });
    }

    // Accesseurs personnalisés pour gérer les données JSON
    public function getGalleryArrayAttribute()
    {
        if (is_string($this->gallery)) {
            return json_decode($this->gallery, true) ?? [];
        }

        return is_array($this->gallery) ? $this->gallery : [];
    }

    // public function getVideosArrayAttribute()
    // {
    //     if (is_string($this->videos)) {
    //         return json_decode($this->videos, true) ?? [];
    //     }

    //     return is_array($this->videos) ? $this->videos : [];
    // }
}
