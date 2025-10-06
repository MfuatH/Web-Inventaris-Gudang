<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Exports\TransactionsExport; // <-- Tambahkan ini
use Maatwebsite\Excel\Facades\Excel; // <-- Tambahkan ini

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['item', 'request.user'])
            ->latest()
            ->paginate(15);
            
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Fungsi untuk mengekspor data ke Excel.
     */
    public function export() 
    {
        // Panggil class export dan tentukan nama file yang akan diunduh
        return Excel::download(new TransactionsExport, 'riwayat_transaksi.xlsx');
    }
}