<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\RequestLinkzoom;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\ItemRequest;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $pendingBarangCount = 0;
                $pendingZoomCount = 0;

                if (in_array($user->role, ['admin_barang', 'super_admin'])) {
                    
                    // --- LOGIKA UNTUK APPROVAL BARANG ---
                    $barangQuery = ItemRequest::where('status', 'pending');

                    // Filter HANYA diterapkan untuk admin_barang
                    if ($user->role === 'admin_barang') {
                        // KODE BARU: Filter berdasarkan relasi 'bidang'
                        $barangQuery->whereHas('bidang', function ($q_bidang) use ($user) {
                            // Cocokkan 'nama' di tabel bidang dengan 'bidang' milik admin
                            $q_bidang->where('nama', $user->bidang);
                        });
                    }
                    
                    $pendingBarangCount = $barangQuery->count();

                    // --- LOGIKA UNTUK APPROVAL ZOOM (Tidak perlu diubah) ---
                    $zoomQuery = RequestLinkzoom::where('status', 'pending');
                    if ($user->role === 'admin_barang' && $user->getBidang) {
                        $zoomQuery->where('bidang_id', $user->getBidang->id);
                    }
                    $pendingZoomCount = $zoomQuery->count();
                }

                $view->with('pendingBarangCount', $pendingBarangCount);
                $view->with('pendingZoomCount', $pendingZoomCount);
            }
        });
    }
}
