<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Detail Pesanan #{{ $booking->booking_code }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            {{-- Menampilkan Notifikasi Sukses/Error --}}
            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-gray-800 dark:text-green-400"
                    role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-gray-800 dark:text-red-400"
                    role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Bagian Status dan Tombol Aksi --}}
                    <div
                        class="mb-6 flex flex-col items-start justify-between border-b border-gray-200 pb-4 sm:flex-row sm:items-center dark:border-gray-700">
                        <div>
                            <h3 class="text-lg font-medium">Status Pesanan:</h3>
                            <span
                                class="{{ $booking->status->color() }} inline-block rounded-full px-3 py-1 text-sm font-semibold uppercase">
                                {{ $booking->status->label() }}
                            </span>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            {{-- Tampilkan tombol ini HANYA JIKA statusnya masih 'Confirmed' --}}
                            @if ($booking->status === \App\Enums\BookingStatus::CONFIRMED)
                                <form action="{{ route('admin.bookings.check-in', $booking) }}" method="POST"
                                    onsubmit="return confirm('Anda yakin ingin mengubah status menjadi Check-In?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="inline-flex items-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-green-700 active:bg-green-800">
                                        <i class="fas fa-check-circle mr-2"></i> Tandai Sudah Check-In
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    {{-- Detail Informasi Booking --}}
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <h4 class="mb-2 font-semibold">Informasi Tamu</h4>
                            <p><strong>Nama:</strong> {{ $booking->user->name }}</p>
                            <p><strong>Email:</strong> {{ $booking->user->email }}</p>
                        </div>
                        <div>
                            <h4 class="mb-2 font-semibold">Informasi Kamar</h4>
                            <p><strong>Tipe Kamar:</strong> {{ $booking->room->name }}</p>
                            <p><strong>Harga per Malam:</strong> Rp.
                                {{ number_format($booking->room->price_per_night, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <h4 class="mb-2 font-semibold">Detail Menginap</h4>
                            <p><strong>Tanggal Check-In:</strong> {{ $booking->check_in_date->format('l, d F Y') }}</p>
                            <p><strong>Tanggal Check-Out:</strong> {{ $booking->check_out_date->format('l, d F Y') }}
                            </p>
                        </div>
                        <div>
                            <h4 class="mb-2 font-semibold">Detail Pembayaran</h4>
                            <p><strong>Total Harga:</strong> Rp.
                                {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                            <p><strong>Tanggal Pesan:</strong> {{ $booking->created_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>

                    {{-- Link kembali --}}
                    <div class="mt-8 border-t border-gray-200 pt-4 dark:border-gray-700">
                        <a href="{{ route('admin.bookings.index') }}"
                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">
                            &larr; Kembali ke Daftar Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
