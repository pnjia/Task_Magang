# PROJECT BLUEPRINT: Hotel Booking System (MVP)

## 1. Konteks Sistem & Tujuan

Dokumen ini berfungsi sebagai **Single Source of Truth** untuk pengembangan aplikasi **Hotel Booking System**.
**Tujuan:** Membangun MVP (Minimum Viable Product) aplikasi reservasi hotel yang aman, performa tinggi, dan mampu menangani _race condition_ (pemesanan ganda) menggunakan stack modern Laravel.

**Peran AI:** Bertindak sebagai Senior Laravel Architect & Full Stack Engineer.
**Peran User:** Pilot / Lead Developer (Reviewer & Approver).

---

## 2. Technology Stack (Strict Requirements)

Agen AI **WAJIB** mematuhi spesifikasi berikut:

- **Backend:** Laravel 12 (Latest Stable).
- **Language:** PHP 8.3+ (Strict Types enabled).
- **Frontend:** Inertia.js + React (Functional Components + Hooks).
- **Styling:** Tailwind CSS + Shadcn/UI (atau Headless UI).
- **Database:** PostgreSQL (via Supabase). Driver: `pgsql`.
- **Testing:** Pest PHP (Feature & Unit Tests wajib untuk logika bisnis krusial).
- **Auth:** Laravel Breeze (React) + Laravel Socialite (Google/GitHub).
- **Payments:** Midtrans (Snap Gateway).
- **State Management:** React Hooks + Inertia Props (Minimalkan global state library yang kompleks).

---

## 3. Coding Standards & Best Practices

1.  **Security First:** Gunakan `FormRequest` untuk validasi. Jangan validasi di Controller.
2.  **Skinny Controller:** Pindahkan logika bisnis kompleks ke **Service Classes** (contoh: `BookingService`, `PaymentService`).
3.  **Atomic Transactions:** Semua operasi database yang melibatkan lebih dari 1 tabel harus dibungkus `DB::transaction()`.
4.  **Concurrency Control:** Gunakan `lockForUpdate()` saat mengecek ketersediaan kamar.
5.  **Type Hinting:** Selalu gunakan Return Type Declarations (`: void`, `: View`, `: JsonResponse`) pada method PHP.
6.  **Frontend:** Gunakan komponen kecil dan _reusable_. Hindari satu file `.jsx` raksasa.

---

## 4. Strategi Database Schema (Core)

_Agen AI harus menggunakan skema ini sebagai acuan migrasi._

- **`users`**: Standar Laravel + `role` (enum: 'admin', 'customer') + `avatar_url`.
- **`room_types`**:
  - `id`, `name` (e.g., Deluxe), `slug`, `base_price` (decimal), `capacity` (int), `description`, `total_stock` (virtual/cached count).
- **`rooms`**:
  - `id`, `room_type_id` (FK), `room_number` (string, unique), `status` (enum: 'available', 'maintenance').
- **`bookings`**:
  - `id` (UUID recommended), `user_id` (FK), `room_type_id` (FK), `check_in` (date), `check_out` (date), `total_price`, `status` (enum: 'pending', 'paid', 'cancelled'), `snap_token`.
- **`booking_room`** (Pivot Table):
  - Menghubungkan `booking_id` dan `room_id`. Diisi **HANYA SETELAH** pembayaran sukses (alokasi kamar fisik).
- **`transactions`**:
  - Log respons mentah dari Payment Gateway (Midtrans) untuk audit trail.

---

## 5. Roadmap Pengembangan (Step-by-Step Instructions)

### PHASE 1: Initialization & Infrastructure

**Goal:** Lingkungan kerja siap dan terkoneksi ke database.

1.  **Setup Laravel:** Install Laravel 12 dengan starter kit Breeze (React/Inertia).
2.  **Config Database:** Koneksikan ke Supabase PostgreSQL. Pastikan skema public bisa diakses.
3.  **Setup Tools:** Install Pest PHP, Tailwind CSS, dan setup Shadcn/UI dasar (Button, Input, Card).
4.  **Helper:** Buat helper function untuk format mata uang IDR (`Rp 1.000.000`).

### PHASE 2: User Management & Authentication

**Goal:** User bisa login, register, dan admin bisa dibedakan.

1.  **Modify User Migration:** Tambahkan kolom `role`.
2.  **Socialite Integration:** Implementasi Login Google/GitHub. Buat `SocialAuthService` untuk menangani _updateOrCreate_ user.
3.  **Middleware:** Buat middleware `IsAdmin` untuk memproteksi rute dashboard admin.

### PHASE 3: Inventory Management (Master Data)

**Goal:** Admin bisa mengelola kamar yang akan dijual.

1.  **Models & Migrations:** Buat untuk `RoomType` dan `Room`.
2.  **Admin CRUD Pages:**
    - Page List Room Types (Table).
    - Page Create/Edit Room Type (Form upload gambar & harga).
    - Page Manage Rooms (Generate room numbers secara bulk/banyak sekaligus).
3.  **Testing:** Buat Feature Test untuk memastikan Admin bisa menambah kamar, tapi Customer tidak bisa.

### PHASE 4: The Booking Engine (CORE LOGIC) ⚠️

**Goal:** Mencegah _Double Booking_ secara absolut.

1.  **Availability Logic:**
    - Buat `BookingService`. Logic: Kamar tipe X tersedia JIKA (Total Kamar X) > (Jumlah Booking tipe X pada tanggal Y yang statusnya 'paid' atau 'confirmed').
2.  **Locking Mechanism:**
    - Implementasi `DB::transaction` dengan `lockForUpdate()` saat user menekan tombol "Book" atau "Pay".
    - Jika stok habis saat detik transaksi, lempar `RoomUnavailableException`.
3.  **Pest Testing (CRITICAL):**
    - Buat _stress test_ simulasi: 2 user booking kamar terakhir yang sama di waktu bersamaan. Satu harus sukses, satu harus gagal.

### PHASE 5: Payment Gateway Integration

**Goal:** Transaksi nyata menggunakan uang.

1.  **Midtrans Setup:** Install library/SDK Midtrans atau buat Service Wrapper sederhana.
2.  **Snap Token:** Generate token saat booking dibuat (status: pending).
3.  **Frontend Payment:** Trigger Midtrans Snap Popup di React saat tombol "Pay Now" diklik.
4.  **Webhook Handler:**
    - Buat Controller `PaymentCallbackController`.
    - **Disable CSRF** untuk route webhook ini di `bootstrap/app.php`.
    - Validasi Signature Key.
    - Update status booking -> 'paid'.
    - **Auto-Assign Room:** Saat status 'paid', sistem otomatis memilih satu `room_id` yang kosong di tanggal tersebut dan menyimpannya ke pivot `booking_room`.

### PHASE 6: Frontend Experience (SPA)

**Goal:** UI yang halus untuk tamu.

1.  **Search Widget:** Input Check-in/Check-out dengan validasi tanggal (tidak boleh tanggal lampau).
2.  **Room List:** Tampilkan kamar yang tersedia saja beserta harga total durasi menginap.
3.  **Dashboard Customer:** List riwayat booking dengan badge status warna-warni.

### PHASE 7: Final Polish

1.  **Queue:** Pindahkan pengiriman email (Invoice) ke Queue Job agar tidak memperlambat webhook.
2.  **Optimization:** Gunakan `Eager Loading` (`with('roomType')`) pada semua query list.
3.  **Deployment Prep:** Pastikan `APP_URL` dan config HTTPS benar untuk production (Inertia requirement).

---

## 6. Example Snippet Style (For AI Reference)

**Preferred Service Pattern:**

```php
// App/Services/BookingService.php

public function createBooking(CreateBookingRequest $request): Booking
{
    return DB::transaction(function () use ($request) {
        // 1. Check Availability with Lock
        $available = RoomType::where('id', $request->room_type_id)
            ->lockForUpdate()
            ->first();

        $currentBookings = $available->bookings()
            ->whereOverlapping($request->check_in, $request->check_out)
            ->count();

        if (($available->total_rooms - $currentBookings) <= 0) {
            throw ValidationException::withMessages(['room' => 'Kamar penuh!']);
        }

        // 2. Create Booking Logic...
        return Booking::create([
            'user_id' => auth()->id(),
            'status' => BookingStatus::PENDING,
            // ... fields lain
        ]);
    });
}
```

**Preferred Rect/Inertia Pattern:**

```
// Pages/Booking/Create.jsx
import { useForm } from '@inertiajs/react';

export default function CreateBooking({ roomType }) {
const { data, setData, post, processing, errors } = useForm({
check_in: '',
check_out: '',
});

    const submit = (e) => {
        e.preventDefault();
        post(route('bookings.store'));
    };

    return (
        <form onSubmit={submit}>
            {/* UI Components here */}
            <Button disabled={processing}>Book Now</Button>
        </form>
    );

}
```
