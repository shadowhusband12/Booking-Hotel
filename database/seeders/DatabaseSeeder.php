<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder lain jika ada
        $this->call([
            // UserSeeder::class, // Jika Anda ingin membuat user awal dengan seeder
            RoomSeeder::class, // Panggil RoomTypeSeeder
            AdminSeeder::class,
        ]);
    }
}
