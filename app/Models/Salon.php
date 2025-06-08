<?php

namespace App\Models;

use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Salon extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'event_logo',
        'event_date',
        'edition',
        'edition_color',
        'countdown',
        'ticket_link',
        'second_ticket_link',
        'message_ticket_link',
        'website_link',
        'park_address',
        'park_link',
        'footer_image',
        'all_salon_image',
        'salon_pop_culture_image',
        'youtube_image',
        'title_discover',
        'content_discover',
        'youtube_discover',
        'halls',
        'scenes',
        'invites',
        'exposants',
        'associations',
        'animations',
        'plan_pdf',
        'planning_pdf',
        'facebook_link',
        'twitter_link',
        'instagram_link',
        'youtube_link',
        'tiktok_link',
        'presse_kit',
        'about_us',
        'practical_info',
        'show_presses',
        'show_photos_invites',
        'show_become_an_exhibitor',
        'show_become_a_staff',
        'e2c'
    ];

    protected $casts = [
        'e2c' => 'boolean',
        'show_presses' => 'boolean',
        'show_photos_invites' => 'boolean',
        'show_become_an_exhibitor' => 'boolean',
        'show_become_a_staff' => 'boolean',
        'countdown' => 'datetime',
    ];

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->eventLogo ? $this->eventLogo->getUrl('thumb') : null;
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'salon_user'
        )->withTimestamps();
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Categorie::class);
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
    }

    public function practicalInfos(): HasMany
    {
        return $this->hasMany(PracticalInfo::class);
    }

    public function partners(): HasMany
    {
        return $this->hasMany(Partner::class);
    }

    public function ticketPrices(): HasMany
    {
        return $this->hasMany(TicketPrice::class);
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_salon')
            ->withPivot([
                'category_id',
                'availability_id',
                'is_featured',
                'is_published',
                'published_at',
                'content_salon',
                'gallery_salon',
                'videos_salon',
                'is_scheduled',
                'is_cancelled',
                'schedule_content',
                'display_order',
            ])
            ->withTimestamps();
    }

    // Relations E2C
    public function e2cContent(): HasOne
    {
        return $this->hasOne(E2cContent::class);
    }

    public function e2cArticles(): HasMany
    {
        return $this->hasMany(E2cArticle::class);
    }

    public function e2cJury(): HasMany
    {
        return $this->hasMany(E2cArticle::class)->where('is_jury', true);
    }

    public function e2cParticipants(): HasMany
    {
        return $this->hasMany(E2cArticle::class)->where('is_jury', false);
    }

    // Pages spéciales
    public function presse(): HasOne
    {
        return $this->hasOne(Presse::class);
    }

    public function photosInvites(): HasOne
    {
        return $this->hasOne(PhotosInvite::class);
    }

    public function becomeAnExhibitor(): HasOne
    {
        return $this->hasOne(BecomeAnExhibitor::class);
    }

    public function becomeAStaff(): HasOne
    {
        return $this->hasOne(BecomeAStaff::class);
    }

    // Articles avec planning
    public function scheduledArticles()
    {
        return $this->articles()
            ->wherePivot('is_scheduled', true)
            ->orderBy('start_datetime');
    }

    // Articles mis en avant
    public function featuredArticles()
    {
        return $this->articles()
            ->wherePivot('is_featured', true)
            ->orderBy('pivot_display_order');
    }
}
