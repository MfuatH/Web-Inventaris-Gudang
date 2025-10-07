<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Request as ItemRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // JIKA USER ADALAH ADMIN ATAU SUPER ADMIN
        if ($user->role === 'super_admin' || $user->role === 'admin_barang') {
            
            // Data untuk Card Statistik
            $totalUsers = User::count();
            $totalItems = Item::count();
            $pendingRequests = ItemRequest::where('status', 'pending')->count();
            
            // Data untuk Tabel Histori Hari Ini
            $todayTransactions = Transaction::with('item')
                ->whereDate('tanggal', today())
                ->latest()->take(5)->get();

            // Data untuk Grafik Tren 7 Hari
            $chartData = Transaction::query()
                ->select(DB::raw('DATE(tanggal) as date'), DB::raw("SUM(CASE WHEN tipe = 'masuk' THEN jumlah ELSE 0 END) as total_masuk"), DB::raw("SUM(CASE WHEN tipe = 'keluar' THEN jumlah ELSE 0 END) as total_keluar"))
                ->where('tanggal', '>=', now()->subDays(7))->groupBy('date')->orderBy('date', 'ASC')->get();
            
            $labels = $chartData->pluck('date')->map(fn ($date) => \Carbon\Carbon::parse($date)->format('d M'));
            $dataMasuk = $chartData->pluck('total_masuk');
            $dataKeluar = $chartData->pluck('total_keluar');

            // DIPERBAIKI: Menggunakan kolom 'jumlah' yang benar
            $lowStockItems = Item::where('jumlah', '<=', 5)
                                 ->orderBy('jumlah', 'asc') // Diurutkan dari stok paling sedikit
                                 ->limit(5) // Mengambil 5 teratas saja
                                 ->get();

            // Mengirimkan semua data ke view
            return view('dashboard', compact(
                'totalUsers', 'totalItems', 'pendingRequests', 
                'todayTransactions', 
                'labels', 'dataMasuk', 'dataKeluar',
                'lowStockItems' // Mengirim data barang stok tipis
            ));
        
        // JIKA USER ADALAH USER BIASA
        } else {
            
            $items = Item::latest()->paginate(10);
            return view('dashboard', compact('items'));
        }
    }
}
