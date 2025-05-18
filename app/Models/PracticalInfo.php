<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PracticalInfo extends Model
{
    protected $fillable = [
        'name',
        'order',
        'color',
        'icon',
        'mini_content',
        'content',
        'image',
        'salon_id'
    ];

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }
}
