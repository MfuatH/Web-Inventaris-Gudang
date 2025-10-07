<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'nama_barang' => 'Laptop Dell Inspiron 15',
                'satuan' => 'Unit',
                'jumlah' => 5,
                'lokasi' => 'Gudang A - Rak 1',
                'keterangan' => 'Laptop untuk keperluan administrasi kantor'
            ],
            [
                'nama_barang' => 'Printer HP LaserJet Pro',
                'satuan' => 'Unit',
                'jumlah' => 3,
                'lokasi' => 'Gudang A - Rak 2',
                'keterangan' => 'Printer untuk keperluan cetak dokumen'
            ],
            [
                'nama_barang' => 'Kertas A4 70gr',
                'satuan' => 'Rim',
                'jumlah' => 50,
                'lokasi' => 'Gudang B - Rak 3',
                'keterangan' => 'Kertas untuk keperluan cetak dokumen'
            ],
            [
                'nama_barang' => 'Tinta Printer Hitam',
                'satuan' => 'Botol',
                'jumlah' => 20,
                'lokasi' => 'Gudang B - Rak 4',
                'keterangan' => 'Tinta printer untuk HP LaserJet'
            ],
            [
                'nama_barang' => 'Mouse Wireless Logitech',
                'satuan' => 'Unit',
                'jumlah' => 15,
                'lokasi' => 'Gudang A - Rak 5',
                'keterangan' => 'Mouse wireless untuk komputer'
            ],
            [
                'nama_barang' => 'Keyboard Mechanical',
                'satuan' => 'Unit',
                'jumlah' => 10,
                'lokasi' => 'Gudang A - Rak 6',
                'keterangan' => 'Keyboard mechanical untuk gaming dan kerja'
            ],
            [
                'nama_barang' => 'Monitor LED 24 inch',
                'satuan' => 'Unit',
                'jumlah' => 8,
                'lokasi' => 'Gudang A - Rak 7',
                'keterangan' => 'Monitor untuk workstation'
            ],
            [
                'nama_barang' => 'Kabel HDMI 2 meter',
                'satuan' => 'Pcs',
                'jumlah' => 25,
                'lokasi' => 'Gudang B - Rak 8',
                'keterangan' => 'Kabel HDMI untuk koneksi monitor'
            ],
            [
                'nama_barang' => 'Stapler Besar',
                'satuan' => 'Unit',
                'jumlah' => 12,
                'lokasi' => 'Gudang B - Rak 9',
                'keterangan' => 'Stapler untuk keperluan kantor'
            ],
            [
                'nama_barang' => 'Isi Stapler No. 10',
                'satuan' => 'Box',
                'jumlah' => 30,
                'lokasi' => 'Gudang B - Rak 10',
                'keterangan' => 'Isi stapler untuk stapler besar'
            ],
            [
                'nama_barang' => 'Map Folder',
                'satuan' => 'Pcs',
                'jumlah' => 100,
                'lokasi' => 'Gudang B - Rak 11',
                'keterangan' => 'Map folder untuk arsip dokumen'
            ],
            [
                'nama_barang' => 'Penghapus Papan Tulis',
                'satuan' => 'Unit',
                'jumlah' => 6,
                'lokasi' => 'Gudang B - Rak 12',
                'keterangan' => 'Penghapus untuk papan tulis ruang meeting'
            ],
            [
                'nama_barang' => 'Spidol Whiteboard Hitam',
                'satuan' => 'Pcs',
                'jumlah' => 20,
                'lokasi' => 'Gudang B - Rak 13',
                'keterangan' => 'Spidol untuk papan tulis'
            ],
            [
                'nama_barang' => 'Spidol Whiteboard Merah',
                'satuan' => 'Pcs',
                'jumlah' => 15,
                'lokasi' => 'Gudang B - Rak 13',
                'keterangan' => 'Spidol merah untuk papan tulis'
            ],
            [
                'nama_barang' => 'Spidol Whiteboard Biru',
                'satuan' => 'Pcs',
                'jumlah' => 15,
                'lokasi' => 'Gudang B - Rak 13',
                'keterangan' => 'Spidol biru untuk papan tulis'
            ],
            [
                'nama_barang' => 'Meja Kantor Kayu',
                'satuan' => 'Unit',
                'jumlah' => 20,
                'lokasi' => 'Gudang C - Rak 1',
                'keterangan' => 'Meja kantor untuk ruang kerja'
            ],
            [
                'nama_barang' => 'Kursi Kantor Ergonomic',
                'satuan' => 'Unit',
                'jumlah' => 25,
                'lokasi' => 'Gudang C - Rak 2',
                'keterangan' => 'Kursi ergonomic untuk kenyamanan kerja'
            ],
            [
                'nama_barang' => 'Lemari Arsip 4 Laci',
                'satuan' => 'Unit',
                'jumlah' => 8,
                'lokasi' => 'Gudang C - Rak 3',
                'keterangan' => 'Lemari arsip untuk penyimpanan dokumen'
            ],
            [
                'nama_barang' => 'AC Split 1.5 PK',
                'satuan' => 'Unit',
                'jumlah' => 4,
                'lokasi' => 'Gudang C - Rak 4',
                'keterangan' => 'AC untuk ruang meeting dan kantor'
            ],
            [
                'nama_barang' => 'Proyektor Epson',
                'satuan' => 'Unit',
                'jumlah' => 2,
                'lokasi' => 'Gudang A - Rak 8',
                'keterangan' => 'Proyektor untuk presentasi meeting'
            ]
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
