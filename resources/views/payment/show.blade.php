{{-- resources/views/payment/show.blade.php --}}

<x-app-layout>
    @section('title', $title ?? 'Halaman Pembayaran')

    <div class="py-12">
        <div class="mx-auto max-w-md space-y-6 sm:px-6 lg:px-8">
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800">
                <div class="mx-auto max-w-md text-center">
                    <h2 class="mb-4 text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Lanjutkan Pembayaran') }}
                    </h2>

                    {{-- @if (session('info'))
                        <div
                            class="alert alert-info mb-4 rounded-md bg-blue-100 p-4 text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                            <p>{{ session('info') }}</p>
                        </div>
                    @endif --}}
                    @if ($errors->any())
                        <div
                            class="alert alert-danger mb-4 rounded-md bg-red-100 p-4 text-red-700 dark:bg-red-900 dark:text-red-300">
                            <p class="font-bold">Terjadi Kesalahan:</p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <p class="mb-4 text-gray-700 dark:text-gray-300">Pemesanan Anda #{{ $booking->booking_code }} telah
                        dibuat
                        dengan status <span class="text-yellow-300">pending</span>.</p>
                    <p class="mb-6 text-gray-700 dark:text-gray-300">Silakan lakukan pembayaran sejumlah:</p>

                    <p class="mb-6 text-3xl font-bold text-indigo-600 dark:text-indigo-400">
                        Rp. {{ number_format($booking->total_price, 0, ',', '.') }}
                    </p>

                    <h3 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">{{ __('Scan QRIS untuk Bayar') }}
                    </h3>

                    <img src="{{ asset('img/fake-qris.png') }}" alt="QRIS Code"
                        class="mx-auto mb-6 h-48 w-48 rounded-md border border-gray-300 dark:border-gray-600">
                    <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">Simulasi Pembayaran (Tidak Memotong Saldo
                        Asli)</p>

                    {{-- Form untuk mengkonfirmasi pembayaran --}}
                    {{-- Ini akan memicu BookingController@processPayment --}}
                    <form action="{{ route('payment.process', ['booking' => $booking->booking_code]) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center rounded-md border border-transparent bg-green-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            {{ __('Pembayaran Sudah Dilakukan') }}
                        </button>
                    </form>

                    <a href="{{ route('dashboard') }}"
                        class="mt-4 inline-block text-sm text-gray-600 hover:underline dark:text-gray-400">
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
