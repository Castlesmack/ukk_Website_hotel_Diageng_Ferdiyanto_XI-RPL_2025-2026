<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Villa extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'capacity',
        'base_price',
        'rooms_total',
        'description',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
        ];
    }

    /**
     * Get the room types for this villa
     */
    public function roomTypes()
    {
        return $this->hasMany(\App\Models\VillaRoomType::class);
    }

    /**
     * Get the bookings for this villa
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}