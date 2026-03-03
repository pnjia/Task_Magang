<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\RoomType;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Carbon\Carbon;

use Midtrans\Config;
use Midtrans\Snap;

class BookingService
{
    public function createBooking(array $data, int $userId): Booking
    {
        // DB::transaction memastikan jika di tengah jalan ada error, 
        // semua perubahan di database akan dibatalkan (rollback).
        return DB::transaction(function () use ($data, $userId) {

            // 1. KUNCI BARIS TIPE KAMAR INI (Pessimistic Locking)
            // Selama proses ini berjalan, request lain yang mencoba mem-booking tipe kamar yang sama akan dipaksa mengantri!
            $roomType = RoomType::where('id', $data['room_type_id'])
                ->lockForUpdate()
                ->firstOrFail();

            // 2. Hitung total kamar fisik yang dimiliki tipe ini
            $totalPhysicalRooms = $roomType->rooms()->count();

            // 3. Cari jumlah booking yang tanggalnya "Bertabrakan" (Overlapping)
            $overlappingBookingsCount = Booking::where('room_type_id', $roomType->id)
                ->whereIn('status', ['paid', 'pending']) // Asumsi: 'pending' juga menahan stok sementara
                ->where(function ($query) use ($data) {
                // Logika Overlapping Date standar industri
                $query->where('check_in', '<', $data['check_out'])
                    ->where('check_out', '>', $data['check_in']);
            }
            )
                ->count();

            // 4. Kalkulasi Ketersediaan
            $availableRooms = $totalPhysicalRooms - $overlappingBookingsCount;

            if ($availableRooms <= 0) {
                // Lempar error jika habis. DB::transaction otomatis di-rollback!
                throw ValidationException::withMessages([
                    'room_type_id' => 'Maaf, tipe kamar ini penuh pada tanggal tersebut.'
                ]);
            }

            // 5. Kalkulasi Harga
            $checkIn = Carbon::parse($data['check_in']);
            $checkOut = Carbon::parse($data['check_out']);
            $nights = $checkIn->diffInDays($checkOut);
            $totalPrice = $nights * $roomType->base_price;

            // 6. Buat Booking
            return Booking::create([
                'id' => Str::uuid(), // Generate UUID
                'user_id' => $userId,
                'room_type_id' => $roomType->id,
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);
            // --- TAMBAHAN INTEGRASI MIDTRANS ---
// Konfigurasi Midtrans
            Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            Config::$isSanitized = true;
            Config::$is3ds = true;

            // Buat Payload untuk Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => $booking->id, // Menggunakan UUID booking sebagai Order ID
                    'gross_amount' => (int)$booking->total_price,
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                ],
            ];

            // Minta Token ke Server Midtrans
            $snapToken = Snap::getSnapToken($params);

            // Simpan token ke database agar bisa dipanggil ulang jika user menutup browser
            $booking->update(['snap_token' => $snapToken]);

            return $booking;
        });
    }
}