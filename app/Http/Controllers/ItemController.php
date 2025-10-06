<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    /**
     * Menampilkan daftar semua barang.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $items = Item::query()
            ->when($search, function ($query, $search) {
                return $query->where('nama_barang', 'like', "%{$search}%")
                             ->orWhere('kode_barang', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);
            
        return view('items.index', compact('items'));
    }

    /**
     * Menampilkan form untuk membuat barang baru.
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Menyimpan barang baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            // Menambahkan aturan 'unique' untuk mencegah duplikasi nama barang
            'nama_barang' => 'required|string|max:255|unique:items,nama_barang',
            'satuan' => 'required|string|max:50',
            'satuan_custom' => 'nullable|required_if:satuan,Lainnya|string|max:50',
            'jumlah' => 'required|integer|min:0',
            'lokasi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);
        
        $satuanValue = $request->input('satuan') === 'Lainnya' 
                       ? $request->input('satuan_custom') 
                       : $request->input('satuan');

        DB::transaction(function () use ($request, $satuanValue) {
            $item = Item::create([
                'nama_barang' => $request->nama_barang,
                'satuan' => $satuanValue,
                'jumlah' => $request->jumlah,
                'lokasi' => $request->lokasi,
                'keterangan' => $request->keterangan,
            ]);

            Transaction::create([
                'item_id' => $item->id,
                'jumlah' => $item->jumlah,
                'tipe' => 'masuk',
                'tanggal' => now(),
            ]);
        });

        return redirect()->route('items.index')->with('success', 'Barang baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit barang.
     */
    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    /**
     * Memperbarui data barang di database.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            // Aturan unique juga diterapkan di sini, dengan pengecualian untuk item yang sedang diedit
            'nama_barang' => 'required|string|max:255|unique:items,nama_barang,'.$item->id,
            'satuan' => 'required|string|max:50',
            'satuan_custom' => 'nullable|required_if:satuan,Lainnya|string|max:50',
            'jumlah' => 'required|integer|min:0',
            'lokasi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $satuanValue = $request->input('satuan') === 'Lainnya' 
                       ? $request->input('satuan_custom') 
                       : $request->input('satuan');
        
        $itemData = $request->only(['nama_barang', 'jumlah', 'lokasi', 'keterangan']);
        $itemData['satuan'] = $satuanValue;

        $item->update($itemData);

        return redirect()->route('items.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    /**
     * Menghapus barang dari database.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * Fungsi baru untuk menambah stok barang yang sudah ada.
     */
    public function addStock(Request $request, Item $item)
    {
        $request->validate([
            'jumlah_tambahan' => 'required|integer|min:1'
        ]);
    
        DB::transaction(function () use ($request, $item) {
            // Tambah jumlah stok barang
            $item->increment('jumlah', $request->jumlah_tambahan);
    
            // Catat sebagai transaksi "masuk"
            Transaction::create([
                'item_id' => $item->id,
                'jumlah' => $request->jumlah_tambahan,
                'tipe' => 'masuk',
                'tanggal' => now(),
            ]);
        });
    
        return redirect()->route('items.index')->with('success', 'Stok barang berhasil ditambahkan.');
    }
}