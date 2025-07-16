<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Enums\BookingStatus;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan dengan paginasi.
     * Ini adalah halaman utama untuk manajemen booking oleh admin.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil semua data booking dengan relasi 'user' dan 'room'
        // untuk menghindari N+1 query problem (Eager Loading).
        // Diurutkan dari yang terbaru dan menggunakan paginasi.
        $bookings = Booking::with('user', 'room')->latest()->paginate(15);

        // Mengirim data bookings ke view
        return view('dashboard.bookings.index', compact('bookings'));
    }

    /**
     * Menampilkan detail dari satu pesanan spesifik.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\View\View
     */
    public function show(Booking $booking)
    {
        // Berkat Route Model Binding, kita tidak perlu mencari booking manual.
        // Laravel sudah otomatis melakukannya.
        return view('dashboard.bookings.show', compact('booking'));
    }

    /**
     * Mencari pesanan berdasarkan ID dan langsung mengarahkan (redirect)
     * ke halaman detail jika ditemukan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function findAndShow(Request $request)
    {
        // 1. Validasi input dari form pencarian cepat.
        $request->validate([
            'booking_id' => 'required|integer|exists:bookings,id',
        ], [
            // Pesan error kustom agar lebih ramah
            'booking_id.required' => 'Silakan masukkan ID Pesanan.',
            'booking_id.integer' => 'ID Pesanan harus berupa angka.',
            'booking_id.exists' => 'ID Pesanan tidak ditemukan atau tidak valid.',
        ]);

        // 2. Jika validasi lolos (ID ada di database),
        //    langsung redirect ke halaman detail booking.
        return redirect()->route('admin.bookings.show', ['booking' => $request->booking_id]);
    }

    /**
     * Mengubah status pesanan menjadi 'Checked-In'.
     * Ini adalah aksi untuk tombol di halaman detail.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsCheckedIn(Booking $booking)
    {
        // 1. Cek kondisi: Aksi ini hanya boleh dilakukan jika status saat ini adalah 'Confirmed'.
        //    Ini mencegah status diubah dari 'Cancelled' atau 'Checked-Out' menjadi 'Checked-In'.
        if ($booking->status === BookingStatus::CONFIRMED) {

            // 2. Ubah status menggunakan Enum untuk keamanan dan konsistensi.
            $booking->status = BookingStatus::CHECKED_IN;
            $booking->save(); // Simpan perubahan ke database.

            // 3. Kembalikan ke halaman sebelumnya dengan pesan sukses.
            return redirect()->back()->with('success', 'Status pesanan berhasil diubah menjadi Checked-In.');
        }

        // Jika kondisi tidak terpenuhi, kembalikan dengan pesan error.
        return redirect()->back()->with('error', 'Pesanan ini tidak dapat di-check-in kan (mungkin sudah check-in atau dibatalkan).');
    }
}
