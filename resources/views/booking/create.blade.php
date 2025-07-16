{{-- resources/views/booking/create.blade.php --}}

<x-app-layout>
    @section('title', $title ?? 'Form Pemesanan Kamar')

    <div class="py-12">
        <div class="mx-auto max-w-2xl space-y-6 sm:px-6 lg:px-8">
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800">
                {{-- Alpine.js data initialization --}}
                <div class="mx-auto max-w-xl" x-data="bookingForm(
                    {{ $room->id }},
                    {{ $room->price_per_night }},
                    {{ $room->total_rooms }},
                    '{{ $checkInDate }}',
                    '{{ $checkOutDate }}',
                    {{ $availableRoomsForPeriod }}
                )">
                    <h2 class="mb-7 text-center text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Form Pemesanan Kamar') }}
                    </h2>

                    @if ($errors->any())
                        <div
                            class="alert alert-danger mb-4 rounded-md bg-red-100 p-4 text-red-700 dark:bg-red-900 dark:text-red-300">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form ini akan langsung submit ke booking.store --}}
                    <form action="{{ route('booking.store') }}" method="POST">
                        @csrf

                        {{-- Input hidden untuk Room ID, Check-in, dan Check-out --}}
                        <input type="hidden" name="room_id" :value="roomId">
                        <input type="hidden" name="check_in_date" :value="checkInDate">
                        <input type="hidden" name="check_out_date" :value="checkOutDate">

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe
                                Kamar:</label>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $room->name }}</p>
                            <img src="{{ asset('storage/' . ($room->images[0] ?? 'img/room_placeholder.jpg')) }}"
                                alt="{{ $room->name }}" class="mt-2 h-48 w-full rounded-md object-cover">
                            <a href="{{ route('rooms.show', ['room' => $room->slug]) }}"
                                class="mt-1 block text-sm text-indigo-600 hover:underline dark:text-indigo-400">Lihat
                                Detail Kamar</a>
                        </div>

                        <div class="mb-4">
                            <label for="check_in_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check-in:</label>
                            <input type="date" name="check_in_date_display" id="check_in_date_display"
                                {{-- Display only --}} x-model="checkInDate" @change="validateDatesAndRooms"
                                :min="today"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <p x-show="dateError" class="mt-1 text-sm text-red-500" x-text="dateError"></p>
                        </div>

                        <div class="mb-4">
                            <label for="check_out_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Check-out:</label>
                            <input type="date" name="check_out_date_display" id="check_out_date_display"
                                {{-- Display only --}} x-model="checkOutDate" @change="validateDatesAndRooms"
                                :min="minCheckOutDate"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="mb-4">
                            <label for="number_of_rooms_booked"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah Kamar
                                Dipesan:</label>
                            <select name="number_of_rooms_booked" id="number_of_rooms_booked" required
                                x-model="numberOfRoomsBooked" @change="calculateTotalPrice"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <template x-for="i in availableRoomsForPeriod" :key="i">
                                    <option :value="i" x-text="i + ' Kamar'"></option>
                                </template>
                            </select>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tersedia: <span
                                    x-text="availableRoomsForPeriod"></span> kamar</p>
                            <p x-show="roomError" class="mt-1 text-sm text-red-500" x-text="roomError"></p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Harga
                                (Estimasi):</label>
                            <p id="totalPriceDisplay" class="text-xl font-bold text-indigo-600 dark:text-indigo-400"
                                x-text="'Rp. ' + totalFinalPrice.toLocaleString('id-ID')"></p>
                        </div>

                        <div class="mt-4 flex items-center justify-end">
                            {{-- Tombol Konfirmasi Pemesanan --}}
                            <button type="submit" {{-- type="submit" agar form disubmit langsung --}}
                                class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                x-bind:disabled="dateError || roomError || availableRoomsForPeriod <= 0 || numberOfRoomsBooked <= 0">
                                {{ __('Konfirmasi Pemesanan') }}
                            </button>
                            <a href="{{ route('home') }}"
                                class="ml-4 inline-flex items-center rounded-md border border-transparent bg-gray-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:bg-gray-700">
                                {{ __('Batal') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- KODE JAVASCRIPT DISINI --}}
    <script>
        // Inisialisasi Alpine.js Data untuk Komponen Form Booking
        document.addEventListener('alpine:init', () => {
            Alpine.data('bookingForm', (initialRoomId, initialPricePerNight, initialTotalRooms, initialCheckInDate,
                initialCheckOutDate, initialAvailableRooms) => ({
                roomId: initialRoomId,
                pricePerNight: initialPricePerNight,
                totalRooms: initialTotalRooms,
                checkInDate: initialCheckInDate, // x-model akan mengikat ini ke input date
                checkOutDate: initialCheckOutDate, // x-model akan mengikat ini ke input date
                numberOfRoomsBooked: 1,
                availableRoomsForPeriod: initialAvailableRooms,
                totalFinalPrice: 0,
                dateError: '',
                roomError: '',
                // showPaymentPopup: false, // Tidak lagi digunakan
                today: new Date().toISOString().split('T')[0],

                init() {
                    this.validateDatesAndRooms();
                    this.$watch('checkInDate', () => this.validateDatesAndRooms());
                    this.$watch('checkOutDate', () => this.validateDatesAndRooms());
                    this.$watch('numberOfRoomsBooked', () => this.calculateTotalPrice());
                },

                get minCheckOutDate() {
                    if (this.checkInDate) {
                        const date = new Date(this.checkInDate);
                        date.setDate(date.getDate() + 1);
                        return date.toISOString().split('T')[0];
                    }
                    return new Date().toISOString().split('T')[0];
                },

                async validateDatesAndRooms() {
                    this.dateError = '';
                    this.roomError = '';
                    this.availableRoomsForPeriod = 0;

                    // Validasi tanggal dasar
                    if (!this.checkInDate || !this.checkOutDate) {
                        this.dateError = 'Pilih tanggal Check-in dan Check-out.';
                        this.calculateTotalPrice();
                        return;
                    }
                    const checkIn = new Date(this.checkInDate);
                    const checkOut = new Date(this.checkOutDate);
                    const today = new Date(this.today);
                    today.setHours(0, 0, 0, 0);

                    if (checkIn.getTime() >= checkOut.getTime()) {
                        this.dateError = 'Check-out harus setelah Check-in.';
                        this.calculateTotalPrice();
                        return;
                    }
                    if (checkIn.getTime() < today.getTime()) {
                        this.dateError = 'Check-in tidak bisa di masa lalu.';
                        this.calculateTotalPrice();
                        return;
                    }

                    // Panggil AJAX untuk cek ketersediaan
                    try {
                        const response = await fetch("{{ route('rooms.check-availability') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                room_id: this.roomId,
                                check_in_date: this.checkInDate,
                                check_out_date: this.checkOutDate
                            })
                        });

                        const data = await response.json();
                        if (response.ok) {
                            this.availableRoomsForPeriod = data.available_rooms;
                            if (this.availableRoomsForPeriod <= 0) {
                                this.roomError = 'Kamar tidak tersedia untuk tanggal ini.';
                                this.numberOfRoomsBooked = 0;
                            } else {
                                if (this.numberOfRoomsBooked === 0 || this.numberOfRoomsBooked >
                                    this.availableRoomsForPeriod) {
                                    this.numberOfRoomsBooked = 1;
                                }
                            }
                        } else {
                            this.dateError = data.message || 'Gagal cek ketersediaan.';
                            this.availableRoomsForPeriod = 0;
                            this.numberOfRoomsBooked = 0;
                        }
                    } catch (error) {
                        console.error('Error fetching availability:', error);
                        this.dateError = 'Gagal cek ketersediaan. Coba lagi.';
                        this.availableRoomsForPeriod = 0;
                        this.numberOfRoomsBooked = 0;
                    }
                    this.calculateTotalPrice();
                },

                calculateTotalPrice() {
                    const numRooms = parseInt(this.numberOfRoomsBooked);
                    if (isNaN(numRooms) || numRooms < 1 || !this.checkInDate || !this.checkOutDate ||
                        this.dateError || this.roomError || this.availableRoomsForPeriod <= 0) {
                        this.totalFinalPrice = 0;
                        return;
                    }
                    const checkIn = new Date(this.checkInDate);
                    const checkOut = new Date(this.checkOutDate);
                    const diffTime = Math.abs(checkOut - checkIn);
                    const numberOfNights = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                    this.totalFinalPrice = this.pricePerNight * numRooms * numberOfNights;
                },

                // Method submitForm dan submitBookingForm TIDAK LAGI DIBUTUHKAN DI SINI
                // Karena form disubmit langsung oleh HTML
            }));
        });
    </script>
</x-app-layout>
