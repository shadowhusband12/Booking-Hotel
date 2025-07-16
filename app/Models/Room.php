<?php

// app/Models/RoomType.php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_per_night',
        'capacity',
        'total_rooms',
        'available_rooms',
        'images',
    ]; // PASTIKAN SEMUA KOLOM INI ADA DI SINI

    protected $casts = [
        'images' => 'array',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    // Pastikan ini ada dan mengembalikan 'slug'
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'room_id');
    }

    public function room()
    {
        // Secara default, Laravel akan mencari 'room_id' di tabel 'bookings'
        // dan 'id' di tabel 'rooms'. Jadi, ini seharusnya cukup:
        return $this->belongsTo(Room::class);

        // Jika foreign key di tabel 'bookings' bukan 'room_id' (misal: 'id_kamar'),
        // maka Anda harus menentukannya secara eksplisit:
        // return $this->belongsTo(Room::class, 'id_kamar');
    }
}
