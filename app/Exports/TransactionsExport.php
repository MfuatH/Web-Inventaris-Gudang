<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Ambil semua transaksi beserta relasi item dan user
        return Transaction::with(['item', 'request.user'])->latest()->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Definisikan judul untuk setiap kolom di file Excel
        return [
            'Tanggal',
            'Kode Barang',
            'Nama Barang',
            'Jumlah',
            'Tipe',
            'User Perequest',
        ];
    }

    /**
     * @param Transaction $transaction
     * @return array
     */
    public function map($transaction): array
    {
        // Petakan setiap baris data sesuai dengan urutan heading
        return [
            $transaction->tanggal,
            $transaction->item->kode_barang,
            $transaction->item->nama_barang,
            $transaction->jumlah,
            ucfirst($transaction->tipe),
            $transaction->request->user->name ?? '-', // Tampilkan '-' jika tidak ada user
        ];
    }
}