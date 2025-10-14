<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GuestLinkZoomController;
use App\Http\Controllers\ZoomController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\GuestController;

Route::view('/', 'welcome')->name('welcome');
Route::get('/guest/stock', [GuestController::class, 'stock'])->name('guest.stock');
Route::get('/guest/requests/create', [GuestController::class, 'createRequest'])->name('guest.requests.create');
Route::post('/guest/requests', [GuestController::class, 'storeRequest'])->name('guest.requests.store');
Route::get('/guest/linkzoom/create', [GuestLinkZoomController::class, 'create'])->name('guest.linkzoom.create');
Route::post('/guest/linkzoom', [GuestLinkZoomController::class, 'store'])->name('guest.linkzoom.store');

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
        
        // Routes untuk Zoom
        Route::get('/zoom/approval', [ZoomController::class, 'approval'])->name('zoom.approval');
        // Route::put('/zoom/{request}/add-link', [ZoomController::class, 'addLink'])->name('zoom.add_link');
        // Route::put('/zoom/{request}/approve', [ZoomController::class, 'approve'])->name('zoom.approve');
        Route::put('/zoom/{zoomRequest}/add-link', [ZoomController::class, 'addLink'])->name('zoom.addLink');
Route::put('/zoom/{zoomRequest}/approve', [ZoomController::class, 'approve'])->name('zoom.approve');
        Route::get('/zoom/master-pesan', [ZoomController::class, 'masterPesan'])->name('zoom.master_pesan');
        Route::post('/zoom/master-pesan', [ZoomController::class, 'storeMasterPesan'])->name('zoom.master_pesan.store');
        
        // Route untuk Riwayat Transaksi - bisa diakses admin_barang dan super_admin
        Route::resource('transactions', TransactionController::class)->only(['index']);
        Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
    });

    // Grup khusus Super Admin
    Route::middleware(['role:super_admin'])->group(function () {
        Route::resource('users', UserController::class);
    });
});