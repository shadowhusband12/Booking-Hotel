<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nama tipe kamar, unik (misal: "Standard", "Deluxe")
            $table->string('slug')->unique(); // Untuk URL yang bersih (misal: "standard-room")
            $table->text('description')->nullable(); // Deskripsi tipe kamar
            $table->decimal('price_per_night', 10, 2); // Harga per malam, 10 digit total, 2 di belakang koma
            $table->unsignedInteger('capacity'); // Kapasitas orang per kamar
            $table->unsignedInteger('total_rooms'); // Total jumlah kamar dengan tipe ini di hotel
            $table->unsignedInteger('available_rooms'); // Jumlah kamar yang tersedia (akan dinamis)
            $table->json('images')->nullable(); // Path gambar utama tipe kamar
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
