<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth routes for API (no CSRF)
Route::get('/login', [AuthenticatedSessionController::class, 'create']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::get('/register', [RegisteredUserController::class, 'create']);
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');

// API routes for resources
Route::middleware('auth:sanctum')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('api.dashboard');

    // Products
    Route::apiResource('products', ProductController::class);

    // Categories
    Route::apiResource('categories', CategoryController::class);

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('api.transactions.index');
    Route::get('/transactions/history', [TransactionController::class, 'history'])->name('api.transactions.history');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('api.transactions.show');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('api.transactions.store');
    Route::put('/transactions/{transaction}/status', [TransactionController::class, 'updateStatus'])->name('api.transactions.updateStatus');
    Route::put('/transactions/{transaction}/confirm-payment', [TransactionController::class, 'confirmPayment'])->name('api.transactions.confirmPayment');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('api.transactions.update');

    // Users
    Route::apiResource('users', UserController::class);
    Route::put('/users/{user}/role', [UserController::class, 'updateRole'])->name('api.users.updateRole');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('api.profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('api.profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('api.profile.updatePassword');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('api.profile.destroy');
});