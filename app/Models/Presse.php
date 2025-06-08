<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presse extends Model
{
    protected $fillable = [
        'ticket_presse_link',
        'first_title',
        'first_content',
        'second_title',
        'second_content',
        'second_ticket',
        'third_title',
        'third_content',
        'third_ticket',
        'salon_id'
    ];

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }
}
