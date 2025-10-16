<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;      // PERUBAHAN: Dari FromCollection menjadi FromQuery
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Database\Eloquent\Builder;      // <-- WAJIB TAMBAHKAN INI

class TransactionsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    /**
     * @var Builder
     */
    protected $query;

    /**
     * PERUBAHAN: Buat constructor untuk menerima query yang sudah difilter dari Controller.
     */
    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    /**
     * PERUBAHAN: Gunakan method query() untuk menjalankan query yang diterima.
     * Method collection() dihapus.
     */
    public function query()
    {
        return $this->query->latest();
    }

    /**
     * PERUBAHAN: Sesuaikan header dengan data tamu.
     */
    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Tanggal',
            'Tipe',
            'Kode Barang',
            'Nama Barang',
            'Jumlah',
            'Nama Pemohon',
            'Bidang Pemohon',
        ];
    }

    /**
     * PERUBAHAN: Sesuaikan pemetaan data dengan data tamu dari request.
     */
    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->tanggal,
            ucfirst($transaction->tipe),
            $transaction->item->kode_barang ?? 'N/A',
            $transaction->item->nama_barang ?? 'N/A',
            $transaction->jumlah,
            $transaction->request->nama_pemohon ?? 'N/A', // Ambil nama pemohon dari request
            $transaction->request->bidang->nama ?? 'N/A',   // Ambil nama bidang dari request
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // PERUBAHAN: Sesuaikan range cell dari F menjadi H karena kolom bertambah
                $headerRange = 'A1:H1';
                $fullRange = 'A1:H' . ($event->sheet->getHighestRow());

                // Style untuk header
                $event->sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFFFF'], // Font putih
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF333333'], // Latar abu-abu gelap
                    ],
                ]);

                // Menambahkan garis tepi (border) ke semua sel
                $event->sheet->getStyle($fullRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                // Pewarnaan kondisional untuk kolom 'Tipe' (sekarang Kolom C)
                foreach ($event->sheet->getRowIterator() as $row) {
                    if ($row->getRowIndex() == 1) continue; // Lewati header

                    $cell = $event->sheet->getCell('C' . $row->getRowIndex());
                    $value = $cell->getValue();
                    
                    if ($value == 'Masuk') {
                        $cell->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFC6EFCE'); // Hijau muda
                    } elseif ($value == 'Keluar') {
                        $cell->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFFFC7CE'); // Merah muda
                    }
                }
            },
        ];
    }
}