{{-- resources/views/admin/room_types/index.blade.php --}}

<x-app-layout>
    @section('title', $title ?? 'Manajemen Tipe Kamar') {{-- Menambahkan default title --}}

    {{-- Header dengan tombol "New Rooms" --}}
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{-- Menggunakan route helper untuk link create --}}
            <a href="{{ route('rooms.create') }}"
                class="rounded-md bg-orange-600 px-5 py-2 text-white hover:bg-orange-500">
                {{ __('Tambah Tipe Kamar Baru') }}
            </a>
        </h2>
    </x-slot>

    {{-- Alert Sukses --}}
    @if (session()->has('success'))
        <div id="alert" class="relative m-4 flex justify-between rounded bg-green-500 p-4 text-white shadow-lg">
            <p>{{ session('success') }}</p>
            <button type="button" id="closeBtn" class="close transition" data-dismiss="alert">
                <i class="text-white" data-feather="x"></i>
            </button>
        </div>
    @endif

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="w-full overflow-hidden bg-white p-4 shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800">
                <div class="relative max-w-full overflow-x-auto"> {{-- Tambahkan overflow-x-auto untuk tabel responsif --}}
                    <table class="w-full text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">No</th>
                                <th scope="col" class="px-6 py-3">Nama Tipe Kamar</th>
                                <th scope="col" class="px-6 py-3">Harga per Malam</th>
                                <th scope="col" class="px-6 py-3">Kapasitas</th>
                                <th scope="col" class="px-6 py-3">Total Kamar</th>
                                <th scope="col" class="px-6 py-3">Tersedia</th>
                                <th scope="col" class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rooms as $room)
                                {{-- Loop melalui $rooms --}}
                                <tr class="border-b bg-white dark:border-gray-700 dark:bg-gray-800">
                                    <th scope="row"
                                        class="whitespace-nowrap px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $loop->iteration }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{-- Link ke halaman detail publik (rooms.show) --}}
                                        <a class="hover:underline hover:underline-offset-1"
                                            href="{{ route('rooms.show', $room->slug) }}">
                                            {{ $room->name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4">
                                        Rp. {{ number_format($room->price_per_night, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $room->capacity }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $room->total_rooms }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $room->available_rooms }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-start space-x-2 text-white"> {{-- Tombol Aksi --}}
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('rooms.edit', ['room' => $room->slug]) }}"
                                                class="bg-warning-600 group relative inline-block h-8 w-12 rounded-md bg-yellow-500 px-3 py-1 hover:bg-yellow-600">
                                                {{-- Warna kuning --}}
                                                <i data-feather="edit"></i>
                                                <span
                                                    class="absolute left-7 top-8 z-50 border border-black bg-slate-100 px-0.5 py-0.5 text-xs text-black opacity-0 transition duration-300 group-hover:opacity-100">Edit</span>
                                            </a>
                                            {{-- Tombol Delete --}}
                                            <form action="{{ route('rooms.destroy', $room->slug) }}" method="post"
                                                class="inline-block"> {{-- Hilangkan d-inline karena sudah flex --}}
                                                @method('delete')
                                                @csrf
                                                <button type="submit"
                                                    class="bg-danger-600 group relative h-8 w-12 rounded-md bg-red-600 px-3 py-1 hover:bg-red-700"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus tipe kamar ini: {{ $room->name }}?')">
                                                    <i data-feather="trash-2"></i> {{-- Icon delete --}}
                                                    <span
                                                        class="absolute left-7 top-8 z-50 border border-black bg-slate-100 px-0.5 py-0.5 text-xs text-black opacity-0 transition duration-300 group-hover:opacity-100">Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-600 dark:text-gray-400">
                                        Belum ada tipe kamar yang ditambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Paginasi --}}
                <div class="mt-4">
                    {{ $rooms->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Tambahkan script feather.replace() dan alert di sini --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace(); // Inisialisasi ikon Feather

            // Logic untuk menutup alert
            const alertElement = document.getElementById('alert');
            const closeBtn = document.getElementById('closeBtn');

            if (closeBtn && alertElement) {
                closeBtn.addEventListener('click', function() {
                    alertElement.classList.add('hidden'); // Sembunyikan alert
                });
                // Opsional: sembunyikan alert setelah beberapa detik
                setTimeout(() => {
                    alertElement.classList.add('hidden');
                }, 5000); // Sembunyikan setelah 5 detik
            }
        });
    </script>
@endpush
