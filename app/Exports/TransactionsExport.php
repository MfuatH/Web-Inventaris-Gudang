<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Untuk lebar kolom otomatis
use Maatwebsite\Excel\Concerns\WithEvents;     // Untuk styling
use Maatwebsite\Excel\Events\AfterSheet;        // Event setelah sheet dibuat

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    public function collection()
    {
        return Transaction::with(['item', 'request.user'])->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal', 'Kode Barang', 'Nama Barang', 'Jumlah', 'Tipe', 'User Perequest',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->tanggal,
            $transaction->item->kode_barang,
            $transaction->item->nama_barang,
            $transaction->jumlah,
            ucfirst($transaction->tipe),
            $transaction->request->user->name ?? '-',
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Style untuk header
                $event->sheet->getStyle('A1:F1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'FF333333', // Warna abu-abu gelap
                        ],
                    ],
                    'font' => [
                        'color' => [
                            'argb' => 'FFFFFFFF', // Warna font putih
                        ]
                    ]
                ]);

                // Menambahkan garis tepi (border) ke semua sel yang terisi
                $cellRange = 'A1:F' . ($event->sheet->getHighestRow());
                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                // Pewarnaan kondisional untuk kolom 'Tipe' (Kolom E)
                foreach ($event->sheet->getRowIterator() as $row) {
                    // Lewati baris header
                    if ($row->getRowIndex() == 1) {
                        continue;
                    }

                    $cell = $event->sheet->getCell('E' . $row->getRowIndex());
                    $value = $cell->getValue();
                    
                    if ($value == 'Masuk') {
                        $cell->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFC6EFCE'); // Warna hijau muda
                    } elseif ($value == 'Keluar') {
                        $cell->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFFFC7CE'); // Warna merah muda
                    }
                }
            },
        ];
    }
}