<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Availability extends Model
{
    protected $fillable = ['name', 'salon_id'];

    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class);
    }
}
