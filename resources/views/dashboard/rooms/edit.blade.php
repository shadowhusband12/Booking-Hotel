{{-- resources/views/dashboard/rooms/edit.blade.php --}}

<x-app-layout>
    @section('title', $title ?? 'Edit Tipe Kamar')

    <div class="py-12">
        <div class="mx-auto max-w-[700px] space-y-6 sm:px-6 lg:px-8">
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800">
                <div class="max-w-xl">
                    <h2 class="mb-7 text-center text-2xl dark:text-white">
                        {{ __('EDIT TIPE KAMAR') }}
                    </h2>

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="post" action="{{ route('rooms.update', $room) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Input fields (Nama, Slug, Deskripsi, Harga, Kapasitas, Total Kamar) --}}
                        <div>
                            <x-input-label for="name" :value="__('Nama Tipe Kamar')" />
                            <x-text-input id="name" class="mt-1 block w-full" type="text" name="name"
                                :value="old('name', $room->name)" required autofocus autocomplete="off" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="slug" :value="__('Slug')" />
                            <x-text-input id="slug" class="mt-1 block w-full" type="text" name="slug"
                                :value="old('slug', $room->slug)" required readonly />
                            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea name="description" id="description"
                                class="mb-2 mt-1 block h-52 w-full rounded-md border-2 border-gray-200 p-2 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ old('description', $room->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="price_per_night" :value="__('Harga per Malam')" />
                            <x-text-input id="price_per_night" class="mt-1 block w-full" type="number"
                                name="price_per_night" :value="old('price_per_night', $room->price_per_night)" step="0.01" min="0" required />
                            <x-input-error :messages="$errors->get('price_per_night')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="capacity" :value="__('Kapasitas Orang')" />
                            <x-text-input id="capacity" class="mt-1 block w-full" type="number" name="capacity"
                                :value="old('capacity', $room->capacity)" min="1" required />
                            <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="total_rooms" :value="__('Jumlah Total Kamar (Tipe Ini)')" />
                            <x-text-input id="total_rooms" class="mt-1 block w-full" type="number" name="total_rooms"
                                :value="old('total_rooms', $room->total_rooms)" min="1" required />
                            <x-input-error :messages="$errors->get('total_rooms')" class="mt-2" />
                        </div>

                        {{-- Input Gambar Tipe Kamar (Multiple File) --}}
                        <div class="mt-4">
                            <x-input-label for="images" :value="__('Upload Gambar Baru')" />
                            <input id="images"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm file:mr-4 file:rounded-md file:border-0 file:bg-orange-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-orange-700 hover:file:bg-orange-100 focus:border-orange-500 focus:ring-orange-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-orange-600 dark:focus:ring-orange-600"
                                type="file" name="images[]" accept="image/*" multiple />
                            <x-input-error :messages="$errors->get('images')" class="mt-2" />
                            <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
                        </div>

                        {{-- Menampilkan Gambar yang Sudah Ada --}}
                        <div class="mt-6">
                            <x-input-label :value="__('Gambar yang Sudah Ada:')" />
                            <div id="existing-images-container" class="mt-2 flex flex-wrap gap-3">
                                @if ($room->images && is_array($room->images) && count($room->images) > 0)
                                    @foreach ($room->images as $imagePath)
                                        <div
                                            class="image-item group relative inline-block rounded-md border border-gray-300 p-1 dark:border-gray-600">
                                            <img src="{{ asset('storage/' . $imagePath) }}" alt="Room Image"
                                                class="h-24 w-24 rounded object-cover">
                                            {{-- Tombol Hapus: Panggil fungsi baru deleteImageFrontend --}}
                                            <button type="button"
                                                class="absolute -right-2 -top-2 rounded-full bg-red-600 p-1 text-xs text-white opacity-0 transition-opacity group-hover:opacity-100"
                                                onclick="deleteImageFrontend('{{ $room->id }}', '{{ $imagePath }}', this)">
                                                <i data-feather="x" class="h-3 w-3"></i>
                                            </button>
                                            {{-- Input hidden ini TIDAK lagi dibutuhkan untuk retained_images[] --}}
                                            {{-- Karena kita akan update JSON di DB via AJAX secara langsung --}}
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-gray-500 dark:text-gray-400">Tidak ada gambar saat ini.</p>
                                @endif
                            </div>
                            {{-- Input hidden removed_images_input juga TIDAK dibutuhkan lagi --}}
                        </div>

                        {{-- Tombol Submit dan Batal --}}
                        <div class="mt-6 flex items-center justify-end">
                            <x-primary-button class="ml-4">
                                {{ __('Update Tipe Kamar') }}
                            </x-primary-button>
                            <a href="{{ route('rooms.index') }}"
                                class="ml-4 inline-flex items-center rounded-md border border-transparent bg-gray-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:bg-gray-700">
                                {{ __('Batal') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Inisialisasi Summernote --}}
    {{-- <script>
        $(document).ready(function() {
            $('#description').summernote({
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onPaste: function(e) {
                        e.preventDefault();
                        var text = (e.originalEvent.clipboardData || window.clipboardData).getData(
                            'text/plain');
                        var cleanText = text.replace(/<\/?[^>]+(>|$)/g, "");
                        document.execCommand('insertText', false, cleanText);
                    }
                },
            });
        });
    </script> --}}

    {{-- Script untuk menghasilkan slug secara otomatis --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.querySelector('#name');
            const slugInput = document.querySelector('#slug');

            if (nameInput && slugInput) {
                nameInput.addEventListener('keyup', function() {
                    console.log('Keyup event on name input. Value:', nameInput.value);

                    if (nameInput.value.length === 0) {
                        slugInput.value = '';
                        return;
                    }

                    const url = "{{ route('rooms.checkSlug') }}?name=" + encodeURIComponent(nameInput
                        .value);
                    console.log('Fetching slug from URL:', url);

                    fetch(url)
                        .then(response => {
                            console.log('Fetch response received. Status:', response.status);
                            if (!response.ok) {
                                throw new Error('Network response was not ok: ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(data => {
                            slugInput.value = data.slug;
                        })
                        .catch(error => {
                            console.error('Error fetching slug:', error);
                            slugInput.value = '';
                        });
                });
            } else {
                console.error("Name or Slug input not found in DOM.");
            }

            feather.replace();

            const alertElement = document.getElementById('alert');
            const closeBtn = document.getElementById('closeBtn');
            if (closeBtn && alertElement) {
                closeBtn.addEventListener('click', function() {
                    alertElement.classList.add('hidden');
                });
                setTimeout(() => {
                    alertElement.classList.add('hidden');
                }, 5000);
            }
        });
    </script>

    {{-- Script BARU untuk menghapus gambar via AJAX --}}
    <script>
        function deleteImageFrontend(roomId, imagePath, buttonElement) {
            console.log('Attempting to delete image via AJAX.');
            console.log('Room ID:', roomId);
            console.log('Image Path:', imagePath);

            if (confirm('Apakah Anda yakin ingin menghapus gambar ini secara permanen?')) {
                // Dapatkan CSRF token dari meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch("{{ route('rooms.delete-image') }}", { // Panggil rute baru yang kita buat
                        method: 'POST', // Gunakan POST
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken // Kirim CSRF token
                        },
                        body: JSON.stringify({
                            room_id: roomId,
                            image_path: imagePath
                        })
                    })
                    .then(response => {
                        console.log('AJAX response status:', response.status);
                        if (!response.ok) {
                            // Jika ada error HTTP (misal 404, 500), lempar error
                            return response.json().then(err => {
                                throw new Error(err.message || 'Server error');
                            });
                        }
                        return response.json(); // Parse respons JSON
                    })
                    .then(data => {
                        if (data.success) {
                            console.log('Image deletion successful:', data.message);
                            // Hapus elemen gambar dari DOM setelah sukses dari backend
                            const imageItem = buttonElement.closest('.image-item');
                            if (imageItem) {
                                imageItem.remove();
                                console.log('Image element removed from DOM.');
                            }
                            // Opsional: tampilkan pesan sukses ke user
                            alert(data.message);
                        } else {
                            console.error('Image deletion failed:', data.message);
                            alert('Gagal menghapus gambar: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error during image deletion fetch:', error);
                        alert('Terjadi kesalahan saat menghubungi server: ' + error.message);
                    });
            }
        }
    </script>

</x-app-layout>
