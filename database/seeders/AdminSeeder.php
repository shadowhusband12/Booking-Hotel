<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table(table: 'users')->insert([
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@hotel.com',
                'isAdmin' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Hotel Manager',
                'username' => 'manager',
                'email' => 'manager@hotel.com',
                'isAdmin' => true,
                'email_verified_at' => now(),
                'password' => Hash::make(value: 'manager123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        $this->command->info('Admin accounts created successfully!');
    }
}
