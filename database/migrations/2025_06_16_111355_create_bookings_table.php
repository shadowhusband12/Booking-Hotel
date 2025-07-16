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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id(); // Primary key auto-increment
            
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key ke tabel users
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade'); // Foreign key ke tabel rooms
            $table->integer('number_of_rooms_booked'); // Jumlah kamar yang dipesan dari tipe tersebut
            $table->date('check_in_date'); // Tanggal check-in
            $table->date('check_out_date'); // Tanggal check-out
            $table->decimal('total_price', 10, 2); // Total harga pemesanan
            $table->string('status')->default('pending'); // Status pemesanan (pending, confirmed, cancelled, completed)
            $table->timestamps(); // `created_at` dan `updated_at`
            // Menambahkan indeks untuk mempercepat pencarian berdasarkan tanggal dan kamar
            $table->index(['room_id', 'check_in_date', 'check_out_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};