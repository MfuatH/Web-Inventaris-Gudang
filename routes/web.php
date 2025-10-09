<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/login');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard/search', [DashboardController::class, 'search'])
    ->middleware(['auth', 'verified'])->name('dashboard.search');

require __DIR__.'/auth.php';

// Grup untuk semua user yang sudah login
Route::middleware(['auth'])->group(function () {
    // Route Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute untuk Request Barang oleh User
    Route::get('/requests/my-requests', [RequestController::class, 'myRequests'])->name('requests.my');
    Route::put('/requests/{request}/receive', [RequestController::class, 'receive'])->name('requests.receive');
    Route::resource('requests', RequestController::class)->only(['index', 'create', 'store', 'show']);

    // Grup untuk Super Admin & Admin Barang
    Route::middleware(['role:super_admin,admin_barang'])->group(function () {
        Route::resource('items', ItemController::class)->except(['show']);
        // Route untuk menambah stok ditambahkan di sini
        Route::patch('/items/{item}/add-stock', [ItemController::class, 'addStock'])->name('items.addStock');
        // Route untuk export barang
        Route::get('/items/export', [ItemController::class, 'export'])->name('items.export');
        Route::put('/requests/{request}/approve', [RequestController::class, 'approve'])->name('requests.approve');
        
        // Route untuk Riwayat Transaksi - bisa diakses admin_barang dan super_admin
        Route::resource('transactions', TransactionController::class)->only(['index']);
        Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
    });

    // Grup khusus Super Admin
    Route::middleware(['role:super_admin'])->group(function () {
        Route::resource('users', UserController::class);
    });
});