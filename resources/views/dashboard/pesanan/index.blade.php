<x-app-layout>
    @section('title', $title)
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Daftar Pesanan Anda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="mb-6 text-2xl font-bold">{{ __('Pesanan Saya') }}</h3>

                    @forelse ($bookings as $booking)
                        {{-- MEMANGGIL HELPER BARU DARI ENUM UNTUK MENGATUR WARNA CARD --}}
                        <div
                            class="{{ $booking->status->cardBgColor() }} {{ $booking->status->cardBorderColor() }} mb-6 rounded-lg border p-4 shadow-sm">
                            <div class="flex flex-col items-start justify-between md:flex-row md:items-center">
                                <div class="mb-4 md:mb-0">
                                    {{-- MEMPERBAIKI PEMANGGILAN RELASI DARI 'room' MENJADI 'room' --}}
                                    <h4 class="mb-1 text-xl font-semibold">
                                        {{ $booking->room ? $booking->room->name : 'Tipe Kamar Tidak Dikenal' }}
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        Check-in: <span
                                            class="font-medium">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</span>
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        Check-out: <span
                                            class="font-medium">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</span>
                                    </p>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                                        Total Harga: <span
                                            class="text-lg font-bold text-indigo-600 dark:text-indigo-400">Rp.
                                            {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                    </p>
                                </div>

                                <div class="flex flex-shrink-0 flex-col items-end">
                                    {{-- BADGE STATUS YANG SUDAH BERSIH --}}
                                    <span
                                        class="{{ $booking->status->color() }} mb-2 rounded-full px-3 py-1 text-sm font-semibold">
                                        {{-- LABEL STATUS YANG SUDAH BENAR --}}
                                        {{ $booking->status->label() }}
                                    </span>

                                    {{-- Bagian Tombol Aksi (Logika Anda di sini sudah benar) --}}
                                    @if ($booking->status === \App\Enums\BookingStatus::PENDING)
                                        <a href="{{ route('payment.show', $booking->booking_code) }}"
                                            class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-indigo-700 focus:outline-none focus:ring active:bg-indigo-900">
                                            {{ __('Lanjutkan ke Pembayaran') }}
                                        </a>
                                    @elseif (
                                        $booking->status === \App\Enums\BookingStatus::CONFIRMED ||
                                            $booking->status === \App\Enums\BookingStatus::CHECKED_IN ||
                                            $booking->status === \App\Enums\BookingStatus::CHECKED_OUT)
                                        <a href="{{ route('booking.receipt', $booking->booking_code) }}"
                                            class="inline-flex items-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-green-700 focus:outline-none focus:ring active:bg-green-900">
                                            {{ __('Lihat Tanda Terima') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-600 dark:text-gray-400">
                            <p>{{ __('Anda belum memiliki pesanan saat ini.') }}</p>
                            <p class="mt-2">
                                <a href="{{ route('home') }}"
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">
                                    {{ __('Cari kamar untuk memulai!') }}
                                </a>
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
