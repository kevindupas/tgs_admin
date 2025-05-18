<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketPrice extends Model
{
    protected $fillable = ['name', 'price', 'sold_out', 'contents', 'salon_id'];

    protected $casts = [
        'price' => 'decimal:2',
        'sold_out' => 'boolean',
        'contents' => 'array'
    ];

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }
}
