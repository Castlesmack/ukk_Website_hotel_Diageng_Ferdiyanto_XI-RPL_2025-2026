<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillaVisibility extends Model
{
    protected $table = 'villa_visibility';
    protected $fillable = ['villa_id', 'is_visible', 'order'];

    public function villa(): BelongsTo
    {
        return $this->belongsTo(Villa::class);
    }
}
