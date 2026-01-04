<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VillaRoomType extends Model
{
    use HasFactory;

    protected $table = 'villa_room_types';

    protected $fillable = [
        'villa_id',
        'name',
        'capacity',
        'price',
        'rooms_count',
        'amenities',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function villa()
    {
        return $this->belongsTo(Villa::class);
    }
}
