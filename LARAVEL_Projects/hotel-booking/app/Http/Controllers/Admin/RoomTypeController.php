<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoomTypeRequest;
use App\Models\RoomType;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class RoomTypeController extends Controller
{
    // Menampilkan daftar tipe kamar (Read)
    public function index(): Response
    {
        // Ambil data tipe kamar terbaru, dan hitung jumlah kamar fisiknya (withCount)
        $roomTypes = RoomType::withCount('rooms')->latest()->paginate(10);

        // Render komponen React (resources/js/Pages/Admin/RoomTypes/Index.jsx)
        return Inertia::render('Admin/RoomTypes/Index', [
            'roomTypes' => $roomTypes
        ]);
    }

    // Menampilkan form tambah (Create)
    public function create(): Response
    {
        return Inertia::render('Admin/RoomTypes/Create');
    }

    // Menyimpan data ke database (Store)
    public function store(StoreRoomTypeRequest $request): RedirectResponse
    {
        // $request->validated() hanya mengambil data yang LOLOS validasi saja (mencegah mass-assignment vulnerability)
        RoomType::create($request->validated());

        // Redirect kembali ke halaman index dengan pesan sukses (Flash Message)
        return redirect()->route('admin.room-types.index')
            ->with('success', 'Tipe kamar berhasil ditambahkan.');
    }

// ... (Fungsi edit, update, dan destroy bisa Anda lengkapi dengan pola yang sama)
}