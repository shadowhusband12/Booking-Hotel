# Proyek Aplikasi Booking Hotel

Aplikasi web untuk manajemen pemesanan kamar hotel, dibangun menggunakan framework Laravel dengan fitur panel admin dan portal pengguna.

Proyek ini mencakup fitur-fitur seperti pencarian kamar, proses booking, pembayaran (simulasi), manajemen pesanan oleh admin, dan tanda terima untuk pengguna.

## Prasyarat (Requirements)

Sebelum memulai, pastikan Anda sudah memiliki perangkat lunak berikut terinstal di sistem Anda:

-   PHP (versi ^8.1 atau lebih tinggi)
-   Composer (Manajer dependensi PHP)
-   Node.js (versi ^16.0 atau lebih tinggi)
-   NPM atau Yarn (Manajer paket Node.js)
-   Database Server (contoh: MySQL, MariaDB, atau PostgreSQL)
-   Git

## Langkah-langkah Instalasi

Ikuti langkah-langkah di bawah ini untuk menjalankan proyek ini di lingkungan lokal Anda.

### 1. Clone Repository

Langkah pertama adalah men-clone repository ini ke mesin lokal Anda menggunakan Git.

```bash
git clone https://github.com/caraka15/booking-hotel.git
cd booking-hotel
```

### 2. Instal Dependensi PHP

Instal semua package PHP yang dibutuhkan oleh proyek menggunakan Composer.

```bash
composer install
```

### 3. Instal Dependensi JavaScript

Instal semua package JavaScript yang dibutuhkan menggunakan NPM.

```bash
npm install
```

### 4. Konfigurasi Environment

Langkah ini sangat penting untuk menghubungkan aplikasi dengan database dan konfigurasi lainnya.

#### a. Salin File Environment

Salin file `.env.example` menjadi file `.env`. File `.env` adalah tempat Anda menyimpan semua variabel konfigurasi lokal.

Untuk pengguna Linux/macOS:

```bash
cp .env.example .env
```

Untuk pengguna Windows:

```bash
copy .env.example .env
```

#### b. Buat Application Key

Setiap aplikasi Laravel membutuhkan kunci enkripsi yang unik. Generate kunci ini dengan perintah Artisan berikut:

```bash
php artisan key:generate
```

#### c. Konfigurasi Database

Buka file `.env` yang baru saja Anda buat dengan editor teks. Cari bagian konfigurasi database (`DB_`) dan sesuaikan dengan pengaturan database lokal Anda.

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<nama_database_anda>
DB_USERNAME=<user_database_anda>
DB_PASSWORD=<password_database_anda>
```

**Penting:** Pastikan Anda sudah membuat database kosong dengan nama yang sesuai (contoh: `booking_hotel_db`) di server database Anda (misalnya melalui phpMyAdmin atau command line).

### 5. Migrasi dan Seeding Database

Jalankan migrasi untuk membuat semua tabel yang dibutuhkan oleh aplikasi di dalam database Anda.

```bash
php artisan migrate
```

Jika proyek Anda memiliki data awal (seeder) untuk diisi (misalnya data peran admin, tipe kamar), jalankan perintah berikut. Jika tidak ada, lewati langkah ini.

```bash
php artisan db:seed
```

### 6. Buat Storage Link

Aplikasi ini mungkin menyimpan file yang di-upload (seperti gambar kamar). Buat symbolic link agar file-file ini bisa diakses dari web.

```bash
php artisan storage:link
```

### 7. Kompilasi Aset Frontend

Kompilasi file CSS dan JavaScript menggunakan Vite.

```bash
npm run build
```

## Menjalankan Aplikasi

Sekarang proyek Anda sudah siap dijalankan!

1. **Jalankan Development Server Laravel:**
   Buka terminal pertama dan jalankan perintah:

    ```bash
    php artisan serve
    ```

    Aplikasi Anda akan berjalan di `http://127.0.0.1:8000`.

2. **Jalankan Vite Server (jika belum berjalan):**
   Biarkan terminal pertama tetap berjalan. Buka terminal kedua dan jalankan perintah:

    ```bash
    npm run dev
    ```

    Ini akan mengawasi perubahan pada file frontend (CSS/JS) dan meng-compile ulang secara otomatis.

Sekarang buka browser Anda dan kunjungi `http://127.0.0.1:8000` untuk melihat aplikasi berjalan.

## Akun Default

Jika Anda menggunakan seeder, Anda bisa login dengan akun default berikut:

**Admin**

-   Email: admin@hotel.com
-   Password: admin123


## Troubleshooting

Jika Anda mengalami masalah:

1. Pastikan semua prasyarat terinstal dengan versi yang benar
2. Periksa koneksi database di file `.env`
3. Jalankan `composer dump-autoload` jika ada masalah autoloading
4. Untuk masalah frontend, coba `npm run build`
5. Periksa log Laravel di `storage/logs/laravel.log` untuk error detail
