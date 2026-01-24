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
        'thumbnail_path',
        'images',
        'closed_dates',
        'availability',
        'available_days',
        'available_from',
        'available_to',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'base_price' => 'integer',
            'thumbnail_path' => 'string',
            'images' => 'array',  // Auto JSON decode/encode
            'closed_dates' => 'array',
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

    /**
     * Get image URL accessor - returns the thumbnail path (without asset wrapper)
     */
    public function getImageUrlAttribute()
    {
        return $this->thumbnail_path;
    }
}