<?php

namespace App\Http\Controllers; // Pastikan namespace ini benar jika controller ada di folder dashboard

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Room; // Import model Room
use Illuminate\Support\Str; // Untuk Str::slug()
use Illuminate\Support\Facades\Storage; // Untuk operasi file Storage
use Cviebrock\EloquentSluggable\Services\SlugService; // Untuk SlugService
use Illuminate\Validation\Rule; // Diperlukan untuk Rule::unique saat update
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller; // Pastikan ini tetap meng-extend Controller dasar

class RoomController extends Controller // Nama kelas controller Anda
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::orderBy('name')->paginate(10);
        // Mengirimkan 'title' ke view, seperti yang Anda gunakan di index.blade.php
        return view('dashboard.rooms.index', compact('rooms'))->with('title', 'Manajemen Tipe Kamar');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mengirimkan 'title' ke view create.blade.php
        return view('dashboard.rooms.create')->with('title', 'Tambah Tipe Kamar Baru');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:rooms,name',
            'description' => 'nullable|string',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'total_rooms' => 'required|integer|min:1',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi untuk multiple files
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('room_images', 'public'); // Simpan setiap gambar
            }
        }

        Room::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Gunakan Str::slug untuk membuat slug
            'description' => $request->description,
            'price_per_night' => $request->price_per_night,
            'capacity' => $request->capacity,
            'total_rooms' => $request->total_rooms,
            'available_rooms' => $request->total_rooms, // Saat dibuat, semua kamar tersedia
            'images' => $imagePaths, // Simpan array path sebagai JSON
        ]);

        return redirect()->route('rooms.index')->with('success', 'Tipe kamar berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     * (Untuk dashboard, biasanya tidak ada halaman show terpisah, langsung ke edit)
     */
    public function show(Room $room)
    {
        // Jika Anda memutuskan untuk punya halaman detail dashboard:
        // return view('dashboard.rooms.show', compact('room'))->with('title', $room->name);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $room) // UBAH INI: Ganti Room $room menjadi string $room
    {
        // dd("Parameter yang diterima di controller: " . $room); // Debugging: Cek apa yang diterima dari URL

        // Coba cari Room secara manual berdasarkan slug
        $room = Room::where('slug', $room)->first();

        // dd($room); // Debugging: Sekarang cek apakah Room berhasil ditemukan

        if (!$room) {
            // Jika model tidak ditemukan, bisa return 404 atau redirect
            abort(404, 'Tipe Kamar tidak ditemukan.');
        }

        return view('dashboard.rooms.edit', [
            'room' => $room, // Mengirimkan objek Room yang sudah ditemukan
            'title' => "Edit Room: " . $room->name
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        // Debugging: Cek semua input yang diterima dari form
        // dd($request->all());

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rooms')->ignore($room->id), // Pastikan tabel adalah 'rooms'
            ],
            'description' => 'nullable|string',
            'price_per_night' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'total_rooms' => 'required|integer|min:1',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'retained_images' => 'nullable|array', // Validasi untuk gambar yang dipertahankan
        ]);

        $finalImagePaths = $request->input('retained_images', []); // Ambil gambar yang dipertahankan

        // Debugging: Cek gambar yang dipertahankan


        // Hapus gambar lama yang TIDAK ada di 'retained_images'
        // Bandingkan gambar yang ada di DB ($room->images) dengan $finalImagePaths
        if ($room->images) { // Pastikan $room->images tidak null
            $imagesToDelete = array_diff($room->images, $finalImagePaths);
            foreach ($imagesToDelete as $imagePath) {
                Storage::disk('public')->delete($imagePath);
                // \Log::info('Image deleted from storage:', ['path' => $imagePath]);
            }
        }

        // Tambahkan gambar baru (upload)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $finalImagePaths[] = $image->store('room_images', 'public');
            }
        }
        // Debugging: Cek array gambar setelah penambahan baru



        // Hitung ulang available_rooms jika total_rooms berubah
        $bookedRooms = $room->total_rooms - $room->available_rooms;
        $newAvailableRooms = $request->total_rooms - $bookedRooms;
        if ($newAvailableRooms < 0) {
            $newAvailableRooms = 0;
        }

        $room->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price_per_night' => $request->price_per_night,
            'capacity' => $request->capacity,
            'total_rooms' => $request->total_rooms,
            'available_rooms' => $newAvailableRooms,
            'images' => $finalImagePaths, // Simpan array path yang diperbarui
        ]);

        return redirect()->route('rooms.index')->with('success', 'Tipe kamar berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room) // Menggunakan Route Model Binding
    {
        $imagePaths = $room->images ?? []; // Gunakan null coalesce operator untuk menghindari error jika $room->images null

        // Hapus semua gambar terkait dari storage
        foreach ($imagePaths as $imagePath) {
            if (Storage::disk('public')->exists($imagePath)) {
                if (Storage::disk('public')->delete($imagePath)) {
                    Log::info('Room Delete: Successfully deleted image.', ['path' => $imagePath, 'room_id' => $room->id]);
                } else {
                    Log::error('Room Delete: Failed to delete image.', ['path' => $imagePath, 'room_id' => $room->id]);
                }
            } else {
                Log::warning('Room Delete: Attempted to delete non-existent image.', ['path' => $imagePath, 'room_id' => $room->id]);
            }
        }

        $room->delete();

        Log::info('Room Delete: Room and associated images deleted successfully.', ['room_id' => $room->id]);
        return redirect()->route('rooms.index')->with('success', 'Tipe kamar berhasil dihapus!');
    }

    public function deleteImage(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id', // ID room yang gambarnya akan dihapus
            'image_path' => 'required|string',       // Path gambar yang akan dihapus
        ]);

        $room = Room::find($request->room_id);

        if (!$room) {
            return Response::json(['success' => false, 'message' => 'Room not found.'], 404);
        }

        $imagePathToDelete = $request->image_path;
        $currentImages = $room->images ?? []; // Dapatkan array gambar saat ini dari DB

        // Pastikan gambar yang diminta untuk dihapus benar-benar ada di daftar gambar room ini
        if (in_array($imagePathToDelete, $currentImages)) {
            // Hapus file fisik dari storage
            if (Storage::disk('public')->exists($imagePathToDelete)) {
                Storage::disk('public')->delete($imagePathToDelete);
                Log::info('Deleted image from storage via AJAX:', ['path' => $imagePathToDelete, 'room_id' => $room->id]);
            } else {
                Log::warning('Attempted to delete non-existent file via AJAX:', ['path' => $imagePathToDelete, 'room_id' => $room->id]);
            }

            // Hapus path gambar dari array JSON di database
            $updatedImages = array_filter($currentImages, fn($path) => $path !== $imagePathToDelete);
            $room->images = array_values($updatedImages); // Re-index array
            $room->save(); // Simpan perubahan ke database

            return Response::json(['success' => true, 'message' => 'Image deleted successfully.']);
        } else {
            return Response::json(['success' => false, 'message' => 'Image not found in room\'s list or already deleted.'], 400);
        }
    }
    /**
     * Handle slug generation for frontend via AJAX.
     */
    public function checkSlug(Request $request)
    {
        $slug = SlugService::createSlug(Room::class, 'slug', $request->name);
        return response()->json(['slug' => $slug]);
    }
}
