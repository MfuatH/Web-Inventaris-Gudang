<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('kode_barang', 'like', "%{$search}%")
                  ->orWhere('nama_barang', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%"); // <-- TAMBAHKAN INI
        }

        $items = $query->latest()->paginate(10);

        return view('items.index', compact('items'));
    }

    // Menampilkan form untuk membuat barang baru
    public function create()
    {
        return view('items.create');
    }

    // Menyimpan barang baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'lokasi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);
        
        // Menggunakan transaction agar jika salah satu gagal, semua dibatalkan
        DB::transaction(function () use ($request) {
            $item = Item::create($request->only('nama_barang', 'jumlah', 'lokasi', 'keterangan'));

            // Otomatis mencatat transaksi "masuk"
            Transaction::create([
                'item_id' => $item->id,
                'jumlah' => $item->jumlah,
                'tipe' => 'masuk',
                'tanggal' => now(),
            ]);
        });

        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit barang
    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    // Memperbarui data barang di database
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'lokasi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $item->update($request->all());

        return redirect()->route('items.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    // Menghapus barang dari database
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus.');
    }
}