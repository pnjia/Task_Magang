<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\ProviderController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Middleware\EnsureUserIsAdmin;

Route::get('/', function () {
    return Inertia::render('Welcome', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');
});

// Blok Route Khusus Admin
Route::middleware(['auth', EnsureUserIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {

    // Ini akan otomatis membuat rute CRUD: index, create, store, edit, update, destroy
    Route::resource('room-types', RoomTypeController::class);
    Route::resource('rooms', RoomController::class);

});

Route::get('/auth/{provider}/redirect', [ProviderController::class , 'redirect'])->name('oauth.redirect');
Route::get('/auth/{provider}/callback', [ProviderController::class , 'callback'])->name('oauth.callback');

Route::post('/payment/callback', [\App\Http\Controllers\Api\PaymentCallbackController::class , 'handle']);

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';