<?php

use App\Models\RoomType;
use App\Models\Room;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Validation\ValidationException;

// Menggunakan RefreshDatabase agar database kembali bersih setelah tes berjalan
uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('menolak booking jika kamar sudah penuh pada tanggal yang diminta', function () {
    // 1. SETUP: Buat 1 Tipe Kamar dengan HANYA 1 Kamar Fisik
    $roomType = RoomType::factory()->create();
    Room::factory()->create(['room_type_id' => $roomType->id]);

    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $service = app(BookingService::class);

    $bookingData = [
        'room_type_id' => $roomType->id,
        'check_in' => now()->addDays(1)->format('Y-m-d'),
        'check_out' => now()->addDays(3)->format('Y-m-d'),
    ];

    // 2. ACTION 1: User A melakukan booking (Harus Sukses)
    $bookingA = $service->createBooking($bookingData, $userA->id);
    expect($bookingA->id)->not->toBeNull(); // Memastikan booking terbuat

    // 3. ACTION 2: User B mencoba booking tipe kamar dan tanggal yang sama
    // Karena sisa kamar 1 dan sudah dibooking User A, ini HARUS melempar ValidationException
    expect(fn() => $service->createBooking($bookingData, $userB->id))
        ->toThrow(ValidationException::class , 'Maaf, tipe kamar ini penuh pada tanggal tersebut.');
});