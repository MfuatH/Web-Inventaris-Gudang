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

                // Hitung hanya jika user adalah admin
                if (in_array($user->role, ['admin_barang', 'super_admin'])) {
                    // --- LOGIKA UNTUK APPROVAL BARANG ---
                    $barangQuery = ItemRequest::where('status', 'pending');
                    if ($user->role === 'admin_barang') {
                        // Admin barang hanya melihat request dari bidangnya
                        $barangQuery->whereHas('user', function ($q) use ($user) {
                            $q->where('bidang', $user->bidang);
                        })->orWhereHas('bidang', function ($q) use ($user) {
                            // Menangani request dari tamu untuk bidang terkait
                             $q->where('nama', $user->bidang);
                        });
                    }
                    $pendingBarangCount = $barangQuery->count();


                    // --- LOGIKA UNTUK APPROVAL ZOOM ---
                    $zoomQuery = RequestLinkzoom::where('status', 'pending');
                    if ($user->role === 'admin_barang' && $user->getBidang) {
                        // Admin barang hanya melihat request zoom dari bidangnya
                        $zoomQuery->where('bidang_id', $user->getBidang->id);
                    }
                    $pendingZoomCount = $zoomQuery->count();
                }

                // Bagikan variabel ke semua view
                $view->with('pendingBarangCount', $pendingBarangCount);
                $view->with('pendingZoomCount', $pendingZoomCount);
            }
        });
    }
}
