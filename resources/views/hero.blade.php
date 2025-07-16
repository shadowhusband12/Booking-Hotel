<div class="relative z-10 flex min-h-[600px] items-center bg-cover bg-center pt-12 md:min-h-screen"
    style="background-image: url('img/hotel.jpg');">
    {{-- Overlay untuk membuat teks lebih mudah dibaca di atas gambar --}}
    <div class="absolute inset-0 bg-black opacity-50"></div>

    <div class="container relative z-10 mx-auto flex flex-col flex-wrap items-center px-5 text-white md:flex-row">
        <!--Left Col (Konten Teks)-->
        <div
            class="flex w-full flex-col items-start justify-center text-center md:w-full md:text-left lg:w-2/3 xl:w-2/5">
            <p class="tracking-loose w-full text-sm uppercase opacity-90 md:text-base">Temukan Penginapan Sempurna Anda
            </p>
            <h1 class="my-4 text-3xl font-bold leading-tight drop-shadow-md md:text-5xl">
                Rasakan Kenyamanan dan Kemudahan Memesan Kamar Hotel!
            </h1>
            <p class="mb-8 text-base leading-normal opacity-90 drop-shadow-sm md:text-2xl">
                Jelajahi berbagai pilihan kamar yang sesuai dengan kebutuhan Anda. Dari liburan keluarga hingga
                perjalanan bisnis, kami punya yang Anda cari.
            </p>
            <a href="#temukan-kamar-ideal" {{-- Mengarahkan ke bagian pencarian kamar di halaman utama --}}
                class="focus:shadow-outline z-20 mx-auto my-6 transform scroll-auto rounded-full bg-white px-8 py-4 font-bold text-gray-800 shadow-lg transition duration-300 ease-in-out hover:scale-105 focus:outline-none lg:mx-0">
                Cari Kamar Sekarang
            </a>
        </div>
    </div>
</div>
