<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'Deluxe Room',
                'slug' => 'deluxe-room',
                'description' => 'Kamar deluxe yang lebih luas dengan fasilitas premium dan balkon pribadi.',
                'price_per_night' => 850000.00,
                'capacity' => 2,
                'total_rooms' => 10,
                'available_rooms' => 10,
                'images' => json_encode(["room_images/deluxe.jpg"])
            ],
            [
                'name' => 'Family Suite',
                'slug' => 'family-suite',
                'description' => 'Suite keluarga yang luas dengan dua kamar tidur terpisah dan ruang tamu. Ideal untuk liburan keluarga besar.',
                'price_per_night' => 1500000.00,
                'capacity' => 4,
                'total_rooms' => 5,
                'available_rooms' => 5,
                'images' => json_encode(["room_images/family-suite.jpg"])
            ],
            [
                'name' => 'Presidential Suite',
                'slug' => 'presidential-suite',
                'description' => 'Suite termewah kami dengan pemandangan kota 360 derajat, jacuzzi, dan layanan butler pribadi.',
                'price_per_night' => 5000000.00,
                'capacity' => 2,
                'total_rooms' => 1,
                'available_rooms' => 1,
                'images' => json_encode(["room_images/presidential.jpg"])
            ]
        ];

        foreach ($rooms as $roomData) {
            Room::create($roomData);
        }
    }
}
