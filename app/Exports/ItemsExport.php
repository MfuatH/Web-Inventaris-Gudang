<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ItemsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    public function collection()
    {
        return Item::orderBy('nama_barang')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Barang',
            'Nama Barang',
            'Satuan',
            'Jumlah Stok',
            'Lokasi',
            'Keterangan',
            'Tanggal Dibuat',
            'Tanggal Diperbarui'
        ];
    }

    public function map($item): array
    {
        static $rowNumber = 0;
        $rowNumber++;
        
        return [
            $rowNumber,
            $item->kode_barang,
            $item->nama_barang,
            $item->satuan,
            $item->jumlah,
            $item->lokasi,
            $item->keterangan ?? '-',
            $item->created_at->format('d/m/Y H:i'),
            $item->updated_at->format('d/m/Y H:i')
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
                $event->sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 12
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'FF4472C4', // Warna biru
                        ],
                    ],
                    'font' => [
                        'color' => [
                            'argb' => 'FFFFFFFF', // Warna font putih
                        ]
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ]
                ]);

                // Menambahkan garis tepi (border) ke semua sel yang terisi
                $cellRange = 'A1:I' . ($event->sheet->getHighestRow());
                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);

                // Pewarnaan kondisional untuk kolom 'Jumlah Stok' (Kolom E)
                foreach ($event->sheet->getRowIterator() as $row) {
                    // Lewati baris header
                    if ($row->getRowIndex() == 1) {
                        continue;
                    }

                    $cell = $event->sheet->getCell('E' . $row->getRowIndex());
                    $value = $cell->getValue();
                    
                    if ($value == 0) {
                        // Stok habis - warna merah
                        $cell->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFFFC7CE');
                    } elseif ($value < 10) {
                        // Stok rendah - warna kuning
                        $cell->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFFFF2CC');
                    } else {
                        // Stok normal - warna hijau
                        $cell->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFC6EFCE');
                    }
                }

                // Set tinggi baris header
                $event->sheet->getRowDimension('1')->setRowHeight(25);
            },
        ];
    }
}
