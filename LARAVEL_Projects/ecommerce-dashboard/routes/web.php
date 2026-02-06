<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// --- AREA ADMIN (LOGIN REQUIRED) ---
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. AREA UMUM (Owner & Cashier Bisa Akses)

    // POS (Kasir) - Perhatikan nama routenya saya ubah jadi 'transactions.create'
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');

    // Riwayat Transaksi
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // 2. AREA KHUSUS OWNER (DILARANG MASUK SELAIN OWNER)
    Route::middleware(['role:owner'])->group(function () {

        // Dashboard (Gunakan Controller, HAPUS route default di atas tadi)
        // PENTING: Namanya harus 'dashboard', bukan 'dashboard.index'
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Manajemen Produk & Kategori
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);

        // Konfirmasi Pembayaran
        Route::post('/transactions/{transaction}/confirm', [TransactionController::class, 'confirmPayment'])->name('transactions.confirm');

        // Manajemen User (Staff) - Pindahkan ke sini agar aman
        Route::resource('users', UserController::class)->except(['show', 'edit', 'update']);
        Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');

    });

});

// --- AREA PUBLIK (TOKO ONLINE) ---
Route::get('/store/{slug}', [StoreController::class, 'index'])->name('store.index');
Route::get('/store/{slug}/product/{productSlug}', [StoreController::class, 'show'])->name('store.product');

// Keranjang
// Route Tambah Keranjang (Sudah ada atau sesuaikan)
Route::get('/store/{slug}/cart/{productId}', [CartController::class, 'addToCart'])->name('cart.add');

// Route Hapus Item
Route::get('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');

// --- BARU: Route Keranjang Global ---
Route::get('/keranjang', [CartController::class, 'globalCart'])->name('cart.global');

// Checkout
Route::get('/store/{slug}/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'store'])->name('checkout.process');

// Route untuk tombol Tambah/Kurang Quantity
Route::get('/cart/change/{key}/{operation}', [CartController::class, 'changeQuantity'])->name('cart.change');

require __DIR__ . '/auth.php';