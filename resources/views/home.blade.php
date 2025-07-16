{{-- resources/views/welcome.blade.php --}}
@php
    use Illuminate\Support\Str;
    // Variabel ini harusnya dikirim dari HomeController
    // Saya letakkan default di sini untuk mencegah error jika variabelnya tidak ada
    $messageVersion = $messageVersion ?? '#';
    $appVersion = $appVersion ?? '1.0.0';

    // Data dummy untuk testimoni, idealnya ini dari database
    $testimonials = [
        [
            'name' => 'Sarah L.',
            'review' =>
                'Pengalaman menginap yang luar biasa! Kamarnya bersih, pemandangannya indah, dan pelayanannya sangat ramah. Pasti akan kembali lagi.',
            'avatar' => 'https://i.pravatar.cc/150?u=a042581f4e29026704d',
        ],
        [
            'name' => 'Budi S.',
            'review' =>
                'Lokasi hotel sangat strategis, dekat dengan pusat kota. Fasilitasnya lengkap dan modern. Sangat direkomendasikan untuk perjalanan bisnis.',
            'avatar' => 'https://i.pravatar.cc/150?u=a042581f4e29026705d',
        ],
        [
            'name' => 'Citra W.',
            'review' =>
                'Sempurna untuk liburan keluarga. Anak-anak sangat suka kolam renangnya. Proses booking melalui website juga sangat mudah dan cepat.',
            'avatar' => 'https://i.pravatar.cc/150?u=a042581f4e29026706d',
        ],
    ];
@endphp

<x-app-layout> {{-- Tetap gunakan <x-app-layout> untuk membungkus konten --}}

    {{-- Meta tags khusus untuk home (jika tidak global di app.blade.php) --}}
    @section('description', 'Jelajahi hotel-hotel terbaik dan pesan kamar dengan mudah untuk liburan impian Anda.')
    @section('og:title', 'Aplikasi Booking Hotel - Temukan Penginapan Sempurna Anda')
    @section('og:image', asset('img/tumbnail.PNG'))
    @section('og:description', 'Jelajahi hotel-hotel terbaik dan pesan kamar dengan mudah untuk liburan impian Anda.')
    @section('title', 'Home - Aplikasi Booking Hotel') {{-- Untuk judul halaman --}}

    {{-- Custom CSS for slider and other enhancements --}}
    <style>
        .hero-section {
            min-height: 75vh;
        }

        .search-card {
            margin-top: -100px;
            z-index: 20;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .dark .search-card {
            background: rgba(17, 24, 39, 0.9);
            /* gray-900 with transparency */
            border: 1px solid rgba(55, 65, 81, 0.5);
            /* gray-700 with transparency */
        }

        .room-image-slider {
            position: relative;
            overflow: hidden;
        }

        .slider-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.4);
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 50%;
            z-index: 10;
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .slider-button:hover {
            background: rgba(0, 0, 0, 0.7);
            transform: translateY(-50%) scale(1.1);
        }

        .slider-button.left {
            left: 10px;
        }

        .slider-button.right {
            right: 10px;
        }

        .current-slider-image {
            transition: opacity 0.5s ease-in-out;
        }
    </style>

    {{-- Hero Section --}}
    <div class="hero-section relative flex items-center justify-center bg-cover bg-center pt-12"
        style="background-image: url('img/hotel.jpg');">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/40 to-transparent"></div>
        <div class="container relative z-10 mx-auto flex flex-col items-center px-5 text-center text-white">
            <p class="tracking-loose w-full text-sm uppercase opacity-90 md:text-base">Temukan Penginapan Sempurna Anda
            </p>
            <h1 class="my-4 text-4xl font-extrabold leading-tight drop-shadow-lg md:text-6xl">
                Kenyamanan & Kemudahan dalam Genggaman
            </h1>
            <p class="mb-8 max-w-3xl text-lg leading-normal opacity-90 drop-shadow-md md:text-xl">
                Jelajahi berbagai pilihan kamar yang sesuai dengan kebutuhan Anda. Dari liburan keluarga hingga
                perjalanan bisnis, kami punya yang Anda cari.
            </p>
        </div>
    </div>

    {{-- Search / Filter Section (Floating Card) --}}
    <div id="temukan-kamar-ideal" class="relative z-20 -mt-24 pb-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="search-card overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-700">
                <div class="px-6 py-8">
                    <h2 class="mb-6 text-center text-3xl font-bold text-gray-900 dark:text-gray-100">
                        {{ __('Temukan Kamar Ideal Anda') }}
                    </h2>

                    <form action="{{ route('home') }}" method="GET" class="mx-auto w-full max-w-4xl space-y-4">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            {{-- Capacity --}}
                            <div class="relative">
                                <label for="capacity"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah
                                    Tamu</label>
                                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center pt-6">
                                    <i class="fas fa-users text-gray-400"></i>
                                </div>
                                <select name="capacity" id="capacity"
                                    class="mt-1 block w-full appearance-none rounded-md border-gray-300 py-2 pl-3 pr-10 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    <option value="">Semua</option>
                                    <option value="1"
                                        {{ old('capacity', $selectedCapacity) == '1' ? 'selected' : '' }}>1 Orang
                                    </option>
                                    <option value="2"
                                        {{ old('capacity', $selectedCapacity) == '2' ? 'selected' : '' }}>2 Orang
                                    </option>
                                    <option value="3"
                                        {{ old('capacity', $selectedCapacity) == '3' ? 'selected' : '' }}>3 Orang
                                    </option>
                                    <option value="4" {{ request('capacity') == '4' ? 'selected' : '' }}>4+ Orang
                                    </option>
                                </select>
                            </div>

                            {{-- Check-in --}}
                            <div class="relative">
                                <label for="check_in_date"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check-in</label>
                                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center pt-6">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                                <input type="date" name="check_in_date" id="check_in_date"
                                    value="{{ old('check_in_date', $selectedCheckInDate) }}"
                                    min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>

                            {{-- Check-out --}}
                            <div class="relative">
                                <label for="check_out_date"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check-out</label>
                                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center pt-6">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                                <input type="date" name="check_out_date" id="check_out_date"
                                    value="{{ old('check_out_date', $selectedCheckOutDate) }}"
                                    min="{{ \Carbon\Carbon::now()->addDay()->format('Y-m-d') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>

                            {{-- Submit Button --}}
                            <div class="flex items-end">
                                <button type="submit"
                                    class="w-full transform rounded-md bg-indigo-600 px-6 py-2.5 text-base font-medium text-white shadow-md transition hover:-translate-y-1 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Cari Kamar
                                </button>
                            </div>
                        </div>
                    </form>

                    @if ($errors->any())
                        <div
                            class="mt-4 rounded-md bg-red-100 p-4 text-center text-sm text-red-700 dark:bg-red-900 dark:text-red-300">
                            <ul class="list-inside list-disc">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Featured Rooms Section --}}
    <div class="bg-gray-50 py-16 dark:bg-gray-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl dark:text-white">Pilihan Kamar Unggulan
                    Kami</h2>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Setiap kamar dirancang untuk kenyamanan
                    maksimal Anda.</p>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                @forelse ($roomTypes as $room)
                    <div
                        class="flex transform flex-col overflow-hidden rounded-lg bg-white shadow-lg transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl dark:bg-gray-800">
                        {{-- Image Slider --}}
                        <div class="room-image-slider relative flex-shrink-0"
                            data-images="{{ json_encode(array_map(fn($img) => asset('storage/' . $img), $room->images ?? [])) }}"
                            data-current-index="0">
                            @if ($room->images && count($room->images) > 0)
                                <img src="{{ asset('storage/' . $room->images[0]) }}" alt="{{ $room->name }}"
                                    class="current-slider-image h-64 w-full object-cover">
                                @if (count($room->images) > 1)
                                    <button class="slider-button left" type="button" onclick="prevSlide(this)"><i
                                            class="fas fa-chevron-left"></i></button>
                                    <button class="slider-button right" type="button" onclick="nextSlide(this)"><i
                                            class="fas fa-chevron-right"></i></button>
                                    <div class="absolute bottom-4 left-1/2 flex -translate-x-1/2 transform space-x-2">
                                        @foreach ($room->images as $index => $image)
                                            <div class="slider-dot {{ $index == 0 ? 'opacity-100' : 'opacity-50' }} h-2 w-2 rounded-full bg-white transition-opacity duration-300"
                                                data-index="{{ $index }}"></div>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <img src="{{ asset('img/room_placeholder.jpg') }}" alt="Gambar Tidak Tersedia"
                                    class="h-64 w-full object-cover">
                            @endif
                        </div>

                        {{-- Room Details --}}
                        <div class="flex flex-1 flex-col justify-between p-6">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $room->name }}</h3>
                                <p class="mt-3 text-base text-gray-600 dark:text-gray-300">
                                    {{ Str::limit($room->description, 150) }}</p>
                                <div
                                    class="mt-4 flex items-center justify-between text-sm text-gray-700 dark:text-gray-300">
                                    <span class="flex items-center"><i class="fas fa-users mr-2 text-indigo-500"></i>
                                        Kapasitas: {{ $room->capacity }} Orang</span>
                                    @if (isset($selectedCheckInDate) && isset($selectedCheckOutDate))
                                        @if ($room->available_for_period > 0)
                                            <span class="flex items-center font-semibold text-green-500"><i
                                                    class="fas fa-check-circle mr-2"></i>
                                                {{ $room->available_for_period }} dari {{ $room->available_rooms }}
                                                Kamar Tersedia</span>
                                        @else
                                            <span class="flex items-center font-semibold text-red-500"><i
                                                    class="fas fa-times-circle mr-2"></i>
                                                {{ $room->available_for_period }} dari {{ $room->available_rooms }}
                                                Kamar Tersedia</span>
                                        @endif
                                    @else
                                        <span class="flex items-center font-semibold text-blue-500"><i
                                                class="fas fa-door-open mr-2"></i> Total Kamar:
                                            {{ $room->total_rooms }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-6 flex items-center justify-between">
                                <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">Rp.
                                    {{ number_format($room->price_per_night, 0, ',', '.') }}<span
                                        class="text-sm font-normal text-gray-500">/malam</span></p>
                                @if (isset($selectedCheckInDate) && isset($selectedCheckOutDate) && $room->available_for_period > 0)
                                    <a href="{{ route('booking.create', ['room' => $room->slug, 'check_in' => $selectedCheckInDate, 'check_out' => $selectedCheckOutDate]) }}"
                                        class="transform rounded-md bg-blue-600 px-5 py-2 text-base font-medium text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Booking</a>
                                @else
                                    <button type="button" disabled
                                        class="cursor-not-allowed rounded-md bg-gray-400 px-5 py-2 text-base font-medium text-white dark:bg-gray-600">Pilih
                                        Tanggal</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-1 rounded-lg bg-white p-12 text-center text-gray-600 shadow-md lg:col-span-2 dark:bg-gray-800 dark:text-gray-400">
                        <i class="fas fa-bed fa-3x mb-4 text-gray-400"></i>
                        <p class="text-xl font-semibold">Kamar Tidak Ditemukan</p>
                        <p class="mt-2">Tidak ada tipe kamar yang sesuai dengan kriteria pencarian Anda atau belum
                            ada kamar yang tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- NEW: Why Choose Us Section --}}
    <div class="bg-white py-16 dark:bg-gray-800">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl dark:text-white">Kenapa Memilih Kami?</h2>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Kami menawarkan lebih dari sekadar tempat
                    menginap.</p>
            </div>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                <div class="text-center">
                    <div
                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400">
                        <i class="fas fa-wifi fa-2x"></i>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">WiFi Kecepatan Tinggi</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Tetap terhubung dengan internet super cepat di
                        seluruh area hotel.</p>
                </div>
                <div class="text-center">
                    <div
                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400">
                        <i class="fas fa-swimming-pool fa-2x"></i>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Kolam Renang Mewah</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Bersantai dan segarkan diri di kolam renang kami
                        yang bersih dan nyaman.</p>
                </div>
                <div class="text-center">
                    <div
                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400">
                        <i class="fas fa-concierge-bell fa-2x"></i>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Layanan 24 Jam</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Tim kami siap membantu Anda kapan saja untuk
                        memastikan kenyamanan Anda.</p>
                </div>
                <div class="text-center">
                    <div
                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400">
                        <i class="fas fa-parking fa-2x"></i>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Parkir Aman & Luas</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Area parkir yang aman dan luas tersedia gratis
                        untuk semua tamu.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- NEW: Testimonials Section --}}
    <div class="bg-gray-50 py-16 dark:bg-gray-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl dark:text-white">Apa Kata Mereka?</h2>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Pengalaman nyata dari para tamu kami yang
                    berharga.</p>
            </div>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($testimonials as $testimonial)
                    <div class="rounded-lg bg-white p-8 shadow-md dark:bg-gray-800">
                        <div class="flex items-center">
                            <img class="h-12 w-12 rounded-full" src="{{ $testimonial['avatar'] }}"
                                alt="{{ $testimonial['name'] }}">
                            <div class="ml-4">
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $testimonial['name'] }}
                                </h4>
                            </div>
                        </div>
                        <p class="mt-4 text-gray-600 dark:text-gray-300">"{{ $testimonial['review'] }}"</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        // --- JavaScript untuk Inline Image Slider (VERSI BARU DENGAN FADE) ---
        function changeSlide(sliderElement, direction) {
            const currentImageElement = sliderElement.querySelector('.current-slider-image');
            if (!currentImageElement) return;

            const imagesDataAttr = sliderElement.getAttribute('data-images');
            if (!imagesDataAttr) return;

            const imagesData = JSON.parse(imagesDataAttr);
            if (!imagesData || imagesData.length <= 1) return;

            let currentIndex = parseInt(sliderElement.getAttribute('data-current-index')) || 0;
            const totalImages = imagesData.length;

            let newIndex;
            if (direction === 'next') {
                newIndex = (currentIndex + 1) % totalImages;
            } else {
                newIndex = (currentIndex - 1 + totalImages) % totalImages;
            }

            // 1. Fade out the current image
            currentImageElement.style.opacity = '0';

            // 2. Wait for the fade out to finish, then change src and fade in
            setTimeout(() => {
                currentImageElement.src = imagesData[newIndex];
                currentImageElement.style.opacity = '1';
                sliderElement.setAttribute('data-current-index', newIndex);

                // Update indicator dots
                const dots = sliderElement.querySelectorAll('.slider-dot');
                dots.forEach((dot, index) => {
                    dot.classList.toggle('opacity-100', index === newIndex);
                    dot.classList.toggle('opacity-50', index !== newIndex);
                });
            }, 500); // Must match the transition duration in CSS
        }

        function prevSlide(button) {
            const sliderElement = button.closest('.room-image-slider');
            if (sliderElement) {
                changeSlide(sliderElement, 'prev');
            }
        }

        function nextSlide(button) {
            const sliderElement = button.closest('.room-image-slider');
            if (sliderElement) {
                changeSlide(sliderElement, 'next');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Logic untuk scroll-to-top (jika ada di app.blade.php, ini bisa dihapus)
            // Logic untuk menutup alert (jika ada di app.blade.php, ini bisa dihapus)

            // Dinamika tanggal check-out
            const checkInInput = document.getElementById('check_in_date');
            const checkOutInput = document.getElementById('check_out_date');

            if (checkInInput && checkOutInput) {
                checkInInput.addEventListener('change', function() {
                    const checkInDate = new Date(this.value);
                    checkInDate.setDate(checkInDate.getDate() + 1); // Tambah 1 hari
                    const minCheckOutDate = checkInDate.toISOString().split('T')[0];
                    checkOutInput.min = minCheckOutDate;

                    // Jika tgl check-out lebih awal dari tgl check-in baru, update tgl check-out
                    if (checkOutInput.value < minCheckOutDate) {
                        checkOutInput.value = minCheckOutDate;
                    }
                });
            }
        });
    </script>
</x-app-layout>
