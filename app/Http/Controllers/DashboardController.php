<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\RequestLinkZoom;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search');

        // Data untuk Super Admin dan Admin Barang
        if (in_array($user->role, ['super_admin', 'admin_barang'])) {
            $totalItems = Item::count();
            $pendingRequests = ItemRequest::where('status', 'pending')->count();
            $pendingZoomRequests = RequestLinkZoom::where('status', 'pending')->count();
            
            // Hitung total barang masuk dan keluar
            $totalBarangMasuk = Transaction::where('tipe', 'masuk')->sum('jumlah');
            $totalBarangKeluar = Transaction::where('tipe', 'keluar')->sum('jumlah');

            // Data untuk grafik stock barang (dibatasi 15 item)
            $stockQuery = Item::orderBy('nama_barang');
            if ($search) {
                $stockQuery->where('nama_barang', 'like', '%' . $search . '%');
            }
            $stockItems = $stockQuery->limit(15)->get();
            $stockChartData = [
                'labels' => $stockItems->pluck('nama_barang'),
                'data' => $stockItems->pluck('jumlah'),
            ];

            // Data khusus Super Admin
            $viewData = compact('totalItems', 'pendingRequests', 'pendingZoomRequests', 'totalBarangMasuk', 'totalBarangKeluar', 'stockChartData', 'search');
            if ($user->role === 'super_admin') {
                $viewData['totalUsers'] = User::count();
            }

            return view('dashboard', $viewData);
        }

        // Data untuk User Biasa
        if ($user->role === 'user') {
            $totalItems = Item::count();
            $pendingRequests = ItemRequest::where('user_id', $user->id)->where('status', 'pending')->count();
            $availableQuery = Item::where('jumlah', '>', 0)->orderBy('nama_barang');
            if ($search) {
                $availableQuery->where('nama_barang', 'like', '%' . $search . '%');
            }
            $availableItems = $availableQuery->limit(15)->get();
            
            $chartDataUser = [
                'labels' => $availableItems->pluck('nama_barang'),
                'data' => $availableItems->pluck('jumlah'),
            ];

            return view('dashboard', compact('totalItems', 'pendingRequests', 'chartDataUser', 'search'));
        }
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        $search = $request->get('search');

        if (in_array($user->role, ['super_admin', 'admin_barang'])) {
            $stockQuery = Item::orderBy('nama_barang');
            // Only apply search filter if search term is not empty
            if (!empty($search) && trim($search) !== '') {
                $stockQuery->where('nama_barang', 'like', '%' . trim($search) . '%');
            }
            // Always limit to 15 items
            $stockItems = $stockQuery->limit(15)->get();
            
            return response()->json([
                'labels' => $stockItems->pluck('nama_barang'),
                'data' => $stockItems->pluck('jumlah'),
            ]);
        }

        if ($user->role === 'user') {
            $availableQuery = Item::where('jumlah', '>', 0)->orderBy('nama_barang');
            // Only apply search filter if search term is not empty
            if (!empty($search) && trim($search) !== '') {
                $availableQuery->where('nama_barang', 'like', '%' . trim($search) . '%');
            }
            // Always limit to 15 items
            $availableItems = $availableQuery->limit(15)->get();
            
            return response()->json([
                'labels' => $availableItems->pluck('nama_barang'),
                'data' => $availableItems->pluck('jumlah'),
            ]);
        }

        return response()->json(['labels' => [], 'data' => []]);
    }
}