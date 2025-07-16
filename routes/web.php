<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;

use App\Http\Controllers\BookingController;

use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\AdminBookingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('home');
// });


Route::get('/', [HomeController::class, 'index'])->name('home');



Route::get('/generate-sitemap', [SitemapController::class, 'generate']);





Route::get('/dashboard', function () {
    return view('dashboard', [
        'title' => "Dashboard"
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/payment/{booking}', [BookingController::class, 'showPaymentForm'])
    ->middleware(['auth']) // Hanya user yang login dan pemilik booking/admin
    ->name('payment.show');

// Rute untuk memproses pembayaran (dari form di halaman pembayaran)
Route::post('/payment/{booking}/process', [BookingController::class, 'processPayment'])
    ->middleware(['auth']) // Hanya user yang login dan pemilik booking/admin
    ->name('payment.process');
Route::post('/rooms/check-availability', [BookingController::class, 'checkAvailability'])->name('rooms.check-availability');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard/pesanan/{booking}/receipt', [BookingController::class, 'receipt'])->name('booking.receipt');
    Route::get('/dashboard/pesanan', [PesananController::class, 'index'])->name('dashboard.pesanan');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('/dashboard/users', UserController::class)->names([
        'users.index' => 'users.index',
        'users.create' => 'users.create',
        'users.store' => 'users.store',
        'users.show' => 'users.show',
        'users.edit' => 'users.edit',
        'users.update' => 'users.update',
        'users.destroy' => 'users.destroy',
    ]);


    Route::get('/booking/{room}', [BookingController::class, 'create'])
        ->name('booking.create');

    Route::post('/booking', [BookingController::class, 'store'])
        ->name('booking.store');
});
Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/dashboard/rooms/checkSlug', [RoomController::class, 'checkSlug'])->name('rooms.checkSlug');
    Route::post('/rooms/delete-image', [RoomController::class, 'deleteImage'])->name('rooms.delete-image');
    Route::resource('/dashboard/rooms', RoomController::class);

    // Routes untuk Admin mengelola SEMUA booking
    Route::get('/dashboard/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings.index');
    Route::post('/dashboard/bookings/find', [AdminBookingController::class, 'findAndShow'])->name('admin.bookings.find');
    Route::get('/dashboard/bookings/{booking}', [AdminBookingController::class, 'show'])->name('admin.bookings.show');
    Route::patch('/dashboard/bookings/{booking}/check-in', [AdminBookingController::class, 'markAsCheckedIn'])->name('admin.bookings.check-in');
});


require __DIR__ . '/auth.php';
