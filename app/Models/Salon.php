<?php

namespace App\Models;

use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    ];

    // public function getFilamentAvatarUrl(): ?string
    // {
    //     return $this->eventLogo ? $this->eventLogo->getUrl('thumb') : null;
    // }

    // public function eventLogo()
    // {
    //     return $this->belongsTo(Media::class, 'event_logo');
    // }

    // // Et modifier getFilamentAvatarUrl pour utiliser cette relation
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
