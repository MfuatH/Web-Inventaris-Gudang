<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Request as ItemRequest;
use App\Models\Transaction;
use Illuminate\Http\Request; // Disarankan untuk ditambahkan
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

            // DITAMBAHKAN: Query untuk mengambil 5 barang paling sering masuk (7 hari)
            $topItemsIn = Transaction::with('item')
                ->where('tipe', 'masuk')
                ->where('tanggal', '>=', now()->subDays(7))
                ->select('item_id', DB::raw('SUM(jumlah) as total_jumlah'))
                ->groupBy('item_id')
                ->orderByDesc('total_jumlah')
                ->limit(5)
                ->get();

            // DITAMBAHKAN: Query untuk mengambil 5 barang paling sering keluar (7 hari)
            $topItemsOut = Transaction::with('item')
                ->where('tipe', 'keluar')
                ->where('tanggal', '>=', now()->subDays(7))
                ->select('item_id', DB::raw('SUM(jumlah) as total_jumlah'))
                ->groupBy('item_id')
                ->orderByDesc('total_jumlah')
                ->limit(5)
                ->get();

            // DIUBAH: Mengirimkan semua data ke view
            return view('dashboard', compact(
                'totalUsers', 'totalItems', 'pendingRequests', 
                'todayTransactions', 
                'labels', 'dataMasuk', 'dataKeluar',
                'topItemsIn', 'topItemsOut'
            ));
        
        // JIKA USER ADALAH USER BIASA
        } else {
            
            $items = Item::latest()->paginate(10);
            return view('dashboard', compact('items'));
        }
    }
}