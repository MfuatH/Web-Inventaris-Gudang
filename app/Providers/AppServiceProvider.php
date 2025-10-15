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

                    // Filter hanya diterapkan untuk admin_barang, super_admin melihat semua
                    if ($user->role === 'admin_barang') {
                        // KUNCI PERBAIKAN: Mengelompokkan kondisi WHERE
                        $barangQuery->where(function ($query) use ($user) {
                            // Kondisi 1: Request dari user terdaftar di bidang yang sama
                            $query->whereHas('user', function ($q_user) use ($user) {
                                $q_user->where('bidang', $user->bidang);
                            });
                            // ATAU
                            // Kondisi 2: Request dari tamu untuk bidang yang sama
                            $query->orWhereHas('bidang', function ($q_bidang) use ($user) {
                                $q_bidang->where('nama', $user->bidang);
                            });
                        });
                    }
                    $pendingBarangCount = $barangQuery->count();


                    // --- LOGIKA UNTUK APPROVAL ZOOM (Tidak Diubah, sudah benar) ---
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
