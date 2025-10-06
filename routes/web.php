<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BidangController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/login');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

// Grup untuk semua user yang sudah login
Route::middleware(['auth', 'verified'])->group(function () {
    // Route Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Rute untuk User Biasa ---
    Route::middleware(['can:user'])->group(function () {
        Route::get('/requests/my-requests', [RequestController::class, 'myRequests'])->name('requests.my');
        Route::put('/requests/{request}/receive', [RequestController::class, 'receive'])->name('requests.receive');
        Route::resource('requests', RequestController::class)->only(['create', 'store', 'show']);
    });

    // --- Grup untuk Super Admin & Admin Barang ---
    Route::middleware(['can:manage_items'])->group(function () {
        // CRUD Barang
        Route::resource('items', ItemController::class);
        
        // Fitur Tambah Stok
        Route::get('/items/{item}/add-stock', [ItemController::class, 'addStockForm'])->name('items.addStockForm');
        Route::post('/items/{item}/add-stock', [ItemController::class, 'storeStock'])->name('items.storeStock');

        // Approval & Daftar Request
        Route::put('/requests/{request}/approve', [RequestController::class, 'approve'])->name('requests.approve');
        Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');
    });

    // --- Grup khusus Super Admin ---
    Route::middleware(['can:super_admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('bidang', BidangController::class);
        Route::resource('transactions', TransactionController::class)->only(['index']);
        
        // Route untuk Export Excel
        Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
    });
});