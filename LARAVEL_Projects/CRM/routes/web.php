<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InteractionController;
use Illuminate\Support\Facades\Route;

// 1. Halaman Depan
Route::get('/', function () {
    return view('welcome');
});

// 2. Dashboard (Hanya bisa diakses jika login)
Route::get('/dashboard', [CustomerController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// 3. Group Middleware Auth (Semua di sini butuh Login)
Route::middleware('auth')->group(function () {
    
    // --- Route Profile (Bawaan Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Route CRM ---
    
    // Pastikan urutannya begini:
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create'); // <--- TAMBAHAN BARU
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');         // <--- TAMBAHAN BARU
    
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');

    // Route Dashboard (Index) yang tadi kita ubah
    // Route::get('/customers', ...) <- Ini sudah diwakili oleh route /dashboard di atas, jadi opsional.
    
    // Route Detail (Simpan di paling bawah blok ini)
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::post('/customers/{customer}/interactions', [InteractionController::class, 'store'])->name('customers.interactions.store');

    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');

});

// 4. LOAD ROUTE OTENTIKASI (PENTING! JANGAN DIHAPUS)
// Baris ini yang memuat route 'login', 'register', 'logout'
require __DIR__.'/auth.php';