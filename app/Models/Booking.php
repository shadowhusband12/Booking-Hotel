<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'room_id',
        'number_of_rooms_booked',
        'check_in_date',
        'check_out_date',
        'total_price',
        'status',
    ];

    protected $casts = [
        'status' => BookingStatus::class,
        'check_in_date' => 'date',
        'check_out_date' => 'date',
    ];

    /**
     * Get the user that owns the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the room type that the booking is for.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function getRouteKeyName()
    {
        return 'booking_code';
    }
}
