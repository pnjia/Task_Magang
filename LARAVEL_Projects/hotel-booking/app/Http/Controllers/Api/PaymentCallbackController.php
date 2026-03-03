<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');

        // 1. Keamanan: Validasi Signature Key (Mencegah Hacker memalsukan lunas)
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            abort(403, 'Invalid Signature!');
        }

        // 2. Cari Data Booking berdasarkan Order ID
        $booking = Booking::findOrFail($request->order_id);

        // 3. Update Status Berdasarkan Balasan Midtrans
        if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
            $booking->update(['status' => 'paid']);

        // TODO: (Langkah selanjutnya) Alokasikan $room_id fisik ke tabel booking_room

        }
        elseif (in_array($request->transaction_status, ['cancel', 'deny', 'expire'])) {
            $booking->update(['status' => 'cancelled']);
        }

        // Midtrans mewajibkan kita membalas dengan status 200 OK
        return response()->json(['message' => 'Callback received']);
    }
}