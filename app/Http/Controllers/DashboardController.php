<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Data untuk Super Admin dan Admin Barang
        if (in_array($user->role, ['super_admin', 'admin_barang'])) {
            $totalItems = Item::count();
            $pendingRequests = ItemRequest::where('status', 'pending')->count();
            $transactionsIn = Transaction::where('tipe', 'masuk')->count();
            $transactionsOut = Transaction::where('tipe', 'keluar')->count();

            $chartData = [
                'in' => $transactionsIn,
                'out' => $transactionsOut,
            ];

            // Data khusus Super Admin
            $viewData = compact('totalItems', 'pendingRequests', 'chartData');
            if ($user->role === 'super_admin') {
                $viewData['totalUsers'] = User::count();
            }

            return view('dashboard', $viewData);
        }

        // Data untuk User Biasa
        if ($user->role === 'user') {
            $totalItems = Item::count();
            $pendingRequests = ItemRequest::where('user_id', $user->id)->where('status', 'pending')->count();
            $availableItems = Item::where('jumlah', '>', 0)->orderBy('nama_barang')->get();
            
            $chartDataUser = [
                'labels' => $availableItems->pluck('nama_barang'),
                'data' => $availableItems->pluck('jumlah'),
            ];

            return view('dashboard', compact('totalItems', 'pendingRequests', 'chartDataUser'));
        }
    }
}