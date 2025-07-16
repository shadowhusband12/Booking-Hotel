<?php

namespace App\Http\Controllers;

use App\Models\Room; // Import model Room
use App\Models\Booking; // Import model Booking
use Illuminate\Http\Request;
use Carbon\Carbon; // Import Carbon untuk manipulasi tanggal
use Illuminate\Support\Facades\Log; // Untuk debugging di log Laravel

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::query();

        $checkInDate = $request->input('check_in_date');
        $checkOutDate = $request->input('check_out_date');
        $capacity = $request->input('capacity');

        // Filter berdasarkan kapasitas
        if ($capacity) {
            $query->where('capacity', '<=', $capacity);
        }

        // --- Logika Ketersediaan Berdasarkan Tanggal ---
        if ($checkInDate && $checkOutDate) {
            try {
                $checkIn = Carbon::parse($checkInDate)->startOfDay();
                $checkOut = Carbon::parse($checkOutDate)->startOfDay();

                if ($checkIn->greaterThanOrEqualTo($checkOut)) {
                    return redirect()->back()->withErrors(['check_out_date' => 'Tanggal Check-out harus setelah Tanggal Check-in.'])->withInput();
                }
                if ($checkIn->isBefore(Carbon::today()->startOfDay())) {
                    return redirect()->back()->withErrors(['check_in_date' => 'Tanggal Check-in tidak bisa di masa lalu.'])->withInput();
                }
            } catch (\Exception $e) {
                Log::error('Date parsing error in HomeController:', ['error' => $e->getMessage(), 'request' => $request->all()]);
                return redirect()->back()->withErrors(['dates' => 'Format tanggal tidak valid.'])->withInput();
            }

            $bookedRoomsSubquery = Booking::selectRaw('room_id, SUM(number_of_rooms_booked) as total_booked')
                ->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in_date', '<', $checkOut)
                        ->where('check_out_date', '>', $checkIn);
                })
                ->whereIn('status', ['pending', 'confirmed'])
                ->groupBy('room_id');

            // Gabungkan dengan query utama untuk Room
            // HAPUS HAVING available_for_period > 0
            $roomTypes = $query->leftJoinSub($bookedRoomsSubquery, 'booked_rooms', function ($join) {
                $join->on('rooms.id', '=', 'booked_rooms.room_id');
            })
                ->selectRaw('rooms.*, (rooms.total_rooms - IFNULL(booked_rooms.total_booked, 0)) as available_for_period')
                ->orderBy('name')
                ->get();

            $selectedCheckInDate = $checkInDate;
            $selectedCheckOutDate = $checkOutDate;
            $selectedCapacity = $capacity;
        } else {
            // Jika tanggal tidak diberikan, tampilkan semua kamar (tanpa available_for_period dihitung)
            $roomTypes = $query->orderBy('name')->get();
            $selectedCheckInDate = null;
            $selectedCheckOutDate = null;
            $selectedCapacity = null;
        }

        // KOREKSI: Gunakan view('welcome') yang benar
        return view('home', compact('roomTypes', 'selectedCheckInDate', 'selectedCheckOutDate', 'selectedCapacity'));
    }

    // ... (showRoom method jika ada) ...
}
