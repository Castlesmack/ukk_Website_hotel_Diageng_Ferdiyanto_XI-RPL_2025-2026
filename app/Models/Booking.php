<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'villa_id',
        'villa_room_type_id',
        'booking_code',
        'check_in_date',
        'check_out_date',
        'nights',
        'rooms_booked',
        'guests',
        'guest_count',
        'guest_name',
        'guest_email',
        'guest_phone',
        'special_requests',
        'total_price',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'check_in_date' => 'date',
            'check_out_date' => 'date',
            'total_price' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function villa()
    {
        return $this->belongsTo(Villa::class);
    }
}
