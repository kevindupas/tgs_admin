<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhotosInvite extends Model
{
    protected $fillable = [
        'mini_title',
        'title',
        'first_subtitle',
        'first_content',
        'second_subtitle',
        'second_content',
        'first_title_link',
        'first_link',
        'second_title_link',
        'second_link',
        'third_subtitle',
        'third_link',
        'salon_id'
    ];

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }
}
