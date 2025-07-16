<?php

namespace App\Http\Controllers;

use App\Models\Booking; // Asumsi model Booking Anda sudah ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    /**
     * Menampilkan daftar pesanan untuk pengguna yang sedang login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login'); // Arahkan ke halaman login jika belum
        }

        // Ambil semua pesanan (booking) yang terkait dengan pengguna yang sedang login
        // Muat relasi 'room' (sesuai dengan model Booking yang Anda berikan)
        // Urutkan berdasarkan tanggal check-in terbaru atau ID
        $bookings = Booking::where('user_id', Auth::id())
            ->with('room') // <-- Diubah dari 'roomType' menjadi 'room'
            ->orderBy('check_in_date', 'desc')
            ->get();

        return view('dashboard.pesanan.index', [
            'title' => "pesanan saya"
        ], compact('bookings'));
    }

    // Anda bisa menambahkan method lain di sini (misalnya untuk melihat detail pesanan)
    // public function show(Booking $booking)
    // {
    //     // Pastikan hanya pemilik booking yang bisa melihatnya
    //     if ($booking->user_id !== Auth::id()) {
    //         abort(403, 'Unauthorized action.');
    //     }
    //     return view('dashboard.pesanan.show', compact('booking'));
    // }
}
