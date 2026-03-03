<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;

class BookingController extends Controller
{
    // Dependency Injection: Laravel otomatis membuatkan instance dari BookingService
    public function __construct(protected BookingService $bookingService)
    {
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        // Data sudah dipastikan valid oleh StoreBookingRequest

        // Serahkan logika ke Service
        $booking = $this->bookingService->createBooking(
            $request->validated(),
            auth()->id()
        );

        // Nanti di Phase 5, kita arahkan ke halaman pembayaran (Midtrans).
        // Sementara ini, kita kembalikan dengan pesan sukses.
        return redirect()->route('dashboard')->with('success', 'Booking berhasil dibuat! Menunggu pembayaran.');
    }
}