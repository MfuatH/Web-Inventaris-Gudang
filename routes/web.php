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
        Route::resource('items', ItemController::class);
        // Route untuk menambah stok ditambahkan di sini
        Route::patch('/items/{item}/add-stock', [ItemController::class, 'addStock'])->name('items.addStock');
        Route::put('/requests/{request}/approve', [RequestController::class, 'approve'])->name('requests.approve');
    });

    // Grup khusus Super Admin
    Route::middleware(['role:super_admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('transactions', TransactionController::class)->only(['index']);
        
        // Route untuk Export Excel
        Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
    });
});