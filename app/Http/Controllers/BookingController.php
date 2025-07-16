<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Booking;
use App\Enums\BookingStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class BookingController extends Controller
{
    /**
     * Tampilkan form pemesanan, isi otomatis data dari parameter URL.
     */
    public function create(Room $room, Request $request)
    {
        $checkInDate = $request->input('check_in');
        $checkOutDate = $request->input('check_out');

        // Validasi dasar tanggal dari URL
        if (!$checkInDate || !$checkOutDate) {
            return redirect()->route('home')->withErrors(['dates' => 'Tanggal Check-in dan Check-out diperlukan untuk pemesanan.']);
        }

        // Parsing tanggal Carbon (validasi lebih lanjut di JS frontend)
        try {
            $checkIn = Carbon::parse($checkInDate)->startOfDay();
            $checkOut = Carbon::parse($checkOutDate)->startOfDay();
        } catch (\Exception $e) {
            Log::error('Booking create date parsing error from URL:', ['error' => $e->getMessage(), 'request' => $request->all()]);
            return redirect()->route('home')->withErrors(['dates' => 'Format tanggal tidak valid dari URL.']);
        }

        // Kirim semua data yang diperlukan ke view, termasuk yang dihitung Alpine.js nanti
        return view('booking.create', [
            'room' => $room,
            'checkInDate' => $checkIn->toDateString(),
            'checkOutDate' => $checkOut->toDateString(),
            'availableRoomsForPeriod' => 0, // Akan dihitung ulang oleh AJAX di frontend
            'title' => 'Form Pemesanan: ' . $room->name
        ]);
    }

    /**
     * Endpoint AJAX untuk mengecek ketersediaan kamar pada tanggal tertentu.
     * Dipanggil oleh Alpine.js saat tanggal diubah.
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);

        $room = Room::find($request->room_id);
        if (!$room) {
            return Response::json(['success' => false, 'message' => 'Tipe kamar tidak ditemukan.'], 404);
        }

        $checkIn = Carbon::parse($request->check_in_date)->startOfDay();
        $checkOut = Carbon::parse($request->check_out_date)->startOfDay();

        $bookedRoomsCount = Booking::where('room_id', $room->id)
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->where('check_in_date', '<', $checkOut)
                    ->where('check_out_date', '>', $checkIn);
            })
            ->whereIn('status', ['pending', 'confirmed'])
            ->sum('number_of_rooms_booked');

        $availableRoomsForPeriod = $room->total_rooms - $bookedRoomsCount;

        return Response::json(['success' => true, 'available_rooms' => $availableRoomsForPeriod]);
    }

    /**
     * Simpan pemesanan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_rooms_booked' => 'required|integer|min:1',
        ]);

        $room = Room::find($request->room_id);

        $bookingCode = null;
        do {
            $randomNumber = mt_rand(1000000000, 9999999999);
            $isExist = Booking::where('booking_code', $randomNumber)->exists();

            if (!$isExist) {
                $bookingCode = $randomNumber;
            }
        } while (is_null($bookingCode));

        if (!$room) {
            Log::error('Booking store: Room not found during final validation.', ['room_id' => $request->room_id]);
            return redirect()->back()->withErrors(['room' => 'Tipe kamar tidak ditemukan.'])->withInput();
        }

        $checkIn = Carbon::parse($request->check_in_date)->startOfDay();
        $checkOut = Carbon::parse($request->check_out_date)->startOfDay();

        // Validasi ketersediaan terakhir sebelum menyimpan (race condition check)
        $bookedRoomsCount = Booking::where('room_id', $room->id)
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->where('check_in_date', '<', $checkOut)
                    ->where('check_out_date', '>', $checkIn);
            })
            ->whereIn('status', ['pending', 'confirmed'])
            ->sum('number_of_rooms_booked');

        $availableRoomsForPeriod = $room->total_rooms - $bookedRoomsCount;

        if ($request->number_of_rooms_booked > $availableRoomsForPeriod) {
            Log::warning('Booking store: Requested rooms exceed actual availability.', ['room_id' => $room->id, 'requested' => $request->number_of_rooms_booked, 'available' => $availableRoomsForPeriod]);
            return redirect()->back()->withErrors(['number_of_rooms_booked' => 'Jumlah kamar yang diminta melebihi ketersediaan (' . $availableRoomsForPeriod . ').'])->withInput();
        }

        // Hitung total harga
        $numberOfNights = $checkOut->diffInDays($checkIn);
        $totalPrice = $room->price_per_night * $request->number_of_rooms_booked * $numberOfNights;

        // Buat entri pemesanan baru dengan status 'pending'
        $booking = Booking::create([ // Ambil instance booking yang baru dibuat
            'booking_code' => $bookingCode,
            'user_id' => Auth::id(),
            'room_id' => $room->id,
            'number_of_rooms_booked' => $request->number_of_rooms_booked,
            'check_in_date' => $checkIn->toDateString(),
            'check_out_date' => $checkOut->toDateString(),
            'total_price' => $totalPrice,
            'status' => 'pending', // Status awal 'pending'
        ]);

        Log::info('New booking created with pending status.', ['booking_code' => $booking->booking_code, 'user_id' => Auth::id(), 'room_id' => $room->id]);

        // REDIRECT KE HALAMAN PEMBAYARAN DENGAN BOOKING ID
        return redirect()->route('payment.show', ['booking' => $booking->booking_code])->with('info', 'Pemesanan Anda telah dibuat (pending). Silakan lanjutkan pembayaran.');
    }

    public function showPaymentForm(Booking $booking) // Nama method yang akan kita gunakan
    {
        // Pastikan hanya user yang memiliki booking atau admin yang bisa melihat form pembayaran
        if (Auth::id() !== $booking->user_id && !Auth::user()->isAdmin) { // Asumsi isAdmin method/attribute di User model
            abort(403, 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        // Jika status sudah confirmed/cancelled/completed, tidak perlu bayar lagi
        if ($booking->status !== BookingStatus::PENDING) {
            return redirect()->route('dashboard')->with('info', 'Pemesanan ini sudah ' . $booking->status . '.');
        }

        return view('payment.show', compact('booking'))->with('title', 'Pembayaran Pemesanan #' . $booking->booking_code);
    }

    /**
     * Proses pembayaran (simulasi) dan ubah status booking menjadi 'confirmed'.
     *
     * @param Request $request
     * @param Booking $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processPayment(Request $request, Booking $booking) // Nama method yang akan kita gunakan
    {
        // Pastikan hanya user yang memiliki booking atau admin yang bisa memproses pembayaran
        if (Auth::id() !== $booking->user_id && !Auth::user()->isAdmin) {
            Log::warning('Unauthorized attempt to process payment.', ['user_id' => Auth::id(), 'booking_id' => $booking->booking_code]);
            abort(403, 'Anda tidak memiliki izin untuk memproses pembayaran ini.');
        }

        // Jika status sudah confirmed/cancelled/completed, tidak bisa bayar lagi
        if ($booking->status !== BookingStatus::PENDING) {
            // Gunakan ->label() untuk menampilkan nama status yang ramah untuk user
            return redirect()->route('dashboard.pesanan')->with('info', 'Pesanan ini sudah ' . $booking->status->label() . '.');
        }

        // --- SIMULASI LOGIKA PEMBAYARAN QRIS ---
        // Dalam aplikasi nyata, ini akan berinteraksi dengan Payment Gateway API (misal Midtrans, Xendit, Doku)
        // Setelah pembayaran berhasil diverifikasi oleh Payment Gateway:
        $booking->status = 'confirmed';
        $booking->save();

        Log::info('Booking payment processed and status changed to confirmed.', ['booking_id' => $booking->booking_code]);
        return redirect()->route('dashboard.pesanan')->with('success', 'Pembayaran berhasil! Pemesanan Anda telah dikonfirmasi.');
    }

    public function receipt(Booking $booking)
    {
        // Pastikan hanya pemilik booking yang bisa melihat tanda terima
        if ($booking->user_id !== Auth::id()) {
            // Jika bukan pemilik, arahkan kembali atau tampilkan error 403
            return abort(403, 'Anda tidak memiliki akses ke tanda terima ini.');
        }

        // Muat relasi 'room' dan 'user' untuk detail yang diperlukan di tanda terima
        // Pastikan relasi ini sudah didefinisikan di model Booking
        $booking->load('room', 'user');

        return view('dashboard.pesanan.receipt', [
            'title' => "receipt"
        ], compact('booking'));
    }
}
