<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Exports\TransactionsExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $transactionsQuery = Transaction::with(['item', 'request.bidang']);

        if ($user->role === 'admin_barang') {
            $transactionsQuery->whereHas('request', function ($requestQuery) use ($user) {
                $requestQuery->whereHas('bidang', function ($bidangQuery) use ($user) {
                    $bidangQuery->where('nama', $user->bidang);
                });
            });
        }

        $transactions = $transactionsQuery->latest()->paginate(15);
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Fungsi untuk mengekspor data ke Excel dengan filter role.
     */
    public function export() 
    {
        $user = Auth::user();
        
        // Buat query dasar yang sama seperti di fungsi index()
        $transactionsQuery = Transaction::with(['item', 'request.bidang', 'request.item']);

        // Terapkan filter yang sama jika rolenya admin_barang
        if ($user->role === 'admin_barang') {
            $transactionsQuery->whereHas('request', function ($requestQuery) use ($user) {
                $requestQuery->whereHas('bidang', function ($bidangQuery) use ($user) {
                    $bidangQuery->where('nama', $user->bidang);
                });
            });
        }
        
        // Kirim query yang sudah difilter ke class export
        return Excel::download(new TransactionsExport($transactionsQuery), 'riwayat_transaksi.xlsx');
    }
}