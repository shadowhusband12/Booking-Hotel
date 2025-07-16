<x-app-layout>
    @section('title', $title)
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200 print:hidden">
            {{-- print:hidden agar header ini tidak ikut tercetak --}}
            {{ __('Tanda Terima Pesanan') }}
        </h2>
    </x-slot>

    {{-- KITA TIDAK MEMBUTUHKAN BLOK <style> YANG PANJANG LAGI --}}
    <style>
        @media print {
            /* ======================================================= */
            /* BAGIAN 1: SEMBUNYIKAN SEMUA ELEMEN YANG TIDAK DICETAK   */
            /* ======================================================= */

            /* Sembunyikan Navigasi berdasarkan ID yang Anda berikan */
            nav#header,

            /* Sembunyikan Header Halaman (tag <header> dari slot) */
            header,

            /* Sembunyikan div spacer di bawah navigasi */
            .py-1.pt-16,

            /* Sembunyikan Tombol Scroll to Top */
            #scroll-to-top,

            /* Sembunyikan Footer berdasarkan ID yang Anda berikan */
            div#footer,

            /* Sembunyikan Tombol Cetak itu sendiri */
            .print-button {
                display: none !important;
            }

            /* ======================================================= */
            /* BAGIAN 2: ATUR ULANG LAYOUT & FOKUS PADA <main>         */
            /* ======================================================= */

            /* Hapus semua padding/margin dari pembungkus utama */
            body,
            div.min-h-screen {
                margin: 0 !important;
                padding: 0 !important;
                background-color: #fff !important;
                /* Latar belakang putih */
            }

            /* Jadikan <main> sebagai satu-satunya konten yang terlihat */
            main {
                /* Mengisolasi <main> dari sisa layout */
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                padding: 1rem !important;
                /* Padding untuk margin cetak */
            }

            /* ======================================================= */
            /* BAGIAN 3: PASTIKAN TAMPILAN CETAK BERSIH                */
            /* ======================================================= */
            * {
                color: #000 !important;
                background: transparent !important;
                box-shadow: none !important;
                text-shadow: none !important;
            }
        }
    </style>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            {{-- Menambahkan kelas print:* untuk mengatur tampilan saat dicetak --}}
            <div
                class="overflow-hidden bg-white shadow-xl sm:rounded-lg dark:bg-gray-800 print:border print:border-gray-300 print:shadow-none">
                <div
                    class="border-b border-gray-200 bg-white p-6 sm:px-20 dark:border-gray-700 dark:bg-gray-800 print:border-b-2 print:border-gray-500">
                    <div class="mb-6 text-center">
                        {{-- Tambahkan logo hotel jika ada, akan terlihat bagus di nota --}}
                        {{-- <img src="{{ asset('img/logo.jpg') }}" alt="Logo Hotel" class="mx-auto mb-4 h-16 w-auto"> --}}
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 print:text-black">
                            {{ __('Nota Pembayaran Pesanan') }}
                        </h3>
                    </div>

                    <div
                        class="grid grid-cols-1 gap-4 text-sm text-gray-700 md:grid-cols-2 dark:text-gray-300 print:text-black">
                        <div>
                            <p class="font-semibold">Nomor Pesanan:</p>
                            <p class="mb-2 font-mono text-base">{{ $booking->booking_code }}</p>

                            <p class="font-semibold">Tanggal Pesanan:</p>
                            <p class="mb-2">{{ \Carbon\Carbon::parse($booking->created_at)->format('d F Y, H:i') }}
                            </p>
                        </div>
                        <div class="md:text-right">
                            <p class="font-semibold">Pelanggan:</p>
                            <p class="mb-2">{{ $booking->user->name ?? 'N/A' }}</p>

                            <p class="font-semibold">Email:</p>
                            <p class="mb-2">{{ $booking->user->email ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h4 class="mb-4 text-xl font-semibold text-gray-900 dark:text-gray-100 print:text-black">
                            Rincian Pesanan
                        </h4>
                        <div class="overflow-x-auto rounded-lg border dark:border-gray-600">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="p-3 text-left font-semibold dark:text-white">Deskripsi</th>
                                        <th class="p-3 text-center font-semibold dark:text-white">Durasi</th>
                                        <th class="p-3 text-right font-semibold dark:text-white">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b dark:border-gray-600">
                                        <td class="p-3">
                                            {{-- PERBAIKAN RELASI: room -> roomType --}}
                                            <p class="font-semibold dark:text-white">{{ $booking->room->name ?? 'N/A' }}
                                            </p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                Check-in:
                                                {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}<br>
                                                Check-out:
                                                {{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}
                                            </p>
                                        </td>
                                        <td class="p-3 text-center dark:text-white">
                                            @php
                                                $duration = \Carbon\Carbon::parse($booking->check_in_date)->diffInDays(
                                                    $booking->check_out_date,
                                                );
                                            @endphp
                                            {{ $duration }} Malam
                                        </td>
                                        <td class="p-3 text-right dark:text-white">Rp.
                                            {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                                <tfoot class="font-bold">
                                    <tr class="dark:bg-gray-700">
                                        <td colspan="2" class="p-3 text-right dark:text-white">Grand Total</td>
                                        <td
                                            class="p-3 text-right text-lg text-indigo-600 dark:text-indigo-400 print:text-black">
                                            Rp. {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div
                        class="mt-6 flex items-center justify-between border-t border-gray-200 pt-4 dark:border-gray-700">
                        <div>
                            <p class="font-semibold dark:text-white"><span
                                    class="{{ $booking->status->color() }} rounded-full px-3 py-1 text-sm font-semibold">
                                    {{ $booking->status->label() }}
                                </span></p>
                            {{-- PERBAIKAN STATUS: Menggunakan badge dari Enum --}}

                        </div>
                        <div class="text-center">
                            {{-- Tombol cetak ini akan otomatis hilang saat dicetak --}}
                            <button onclick="window.print()"
                                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 dark:border-gray-500 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 print:hidden">
                                <i class="fas fa-print mr-2"></i> {{ __('Cetak') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
