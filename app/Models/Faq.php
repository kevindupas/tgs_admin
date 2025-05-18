<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Faq extends Model
{
    protected $fillable = ['name', 'mini_content', 'content', 'order', 'salon_id'];

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }
}
