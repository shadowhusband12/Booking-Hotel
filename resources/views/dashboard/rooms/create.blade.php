<x-app-layout>
    @section('title', $title)
    <div class="py-12">
        <div class="mx-auto max-w-[700px] space-y-6 sm:px-6 lg:px-8">
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800">
                <div class="max-w-xl">
                    <h2 class="mb-7 text-center text-2xl dark:text-white">ADD NEW CHAIND</h2>
                    <form method="post" action="{{ Route('rooms.index') }}" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="mt-1 block w-full" type="text" name="name"
                                :value="old('name')" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="slug" :value="__('Slug')" />
                            <x-text-input id="slug" class="mt-1 block w-full" type="text" name="slug"
                                :value="old('slug')" required autofocus autocomplete="slug" />
                            <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                        </div>

                        {{-- Input Deskripsi (menggunakan Summernote) --}}
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea name="description" id="description"
                                class="mb-2 mt-1 block h-52 w-full rounded-md border-2 border-gray-200 p-2 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        {{-- Input Harga per Malam --}}
                        <div class="mt-4">
                            <x-input-label for="price_per_night" :value="__('Harga per Malam')" />
                            <x-text-input id="price_per_night" class="mt-1 block w-full" type="number"
                                name="price_per_night" :value="old('price_per_night')" step="0.01" min="0" required />
                            <x-input-error :messages="$errors->get('price_per_night')" class="mt-2" />
                        </div>

                        {{-- Input Kapasitas Orang --}}
                        <div class="mt-4">
                            <x-input-label for="capacity" :value="__('Kapasitas Orang')" />
                            <x-text-input id="capacity" class="mt-1 block w-full" type="number" name="capacity"
                                :value="old('capacity')" min="1" required />
                            <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                        </div>

                        {{-- Input Jumlah Total Kamar --}}
                        <div class="mt-4">
                            <x-input-label for="total_rooms" :value="__('Jumlah Total Kamar (Tipe Ini)')" />
                            <x-text-input id="total_rooms" class="mt-1 block w-full" type="number" name="total_rooms"
                                :value="old('total_rooms')" min="1" required />
                            <x-input-error :messages="$errors->get('total_rooms')" class="mt-2" />
                        </div>

                        {{-- Input Gambar Tipe Kamar (Multiple File) --}}
                        <div class="mt-4">
                            <x-input-label for="images" :value="__('Gambar Tipe Kamar (Multiple)')" />
                            <input id="images"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm file:mr-4 file:rounded-md file:border-0 file:bg-orange-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-orange-700 hover:file:bg-orange-100 focus:border-orange-500 focus:ring-orange-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-orange-600 dark:focus:ring-orange-600"
                                type="file" name="images[]" accept="image/*" multiple />
                            <x-input-error :messages="$errors->get('images')" class="mt-2" />
                            <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
                        </div>

                        <div class="mt-4 flex items-center justify-end">
                            <x-primary-button class="ml-4">
                                {{ __('Tambah Tipe Kamar') }}
                            </x-primary-button>
                            <a href="{{ route('rooms.index') }}"
                                class="ml-4 inline-flex items-center rounded-md border border-transparent bg-gray-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:bg-gray-700">
                                {{ __('Batal') }}
                            </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        const name = document.querySelector('#name');
        const slug = document.querySelector('#slug');

        name.addEventListener('change', function() {
            fetch('/dashboard/rooms/checkSlug?name=' + name.value)
                .then(response => response.json())
                .then(data => slug.value = data.slug)
        })

        document.addEventListener('trix-file-accept', function(e) {
            e.preventDefault();
        })
    </script>
</x-app-layout>
