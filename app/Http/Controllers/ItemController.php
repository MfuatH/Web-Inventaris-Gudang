<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    /**
     * Menampilkan daftar barang dengan fitur pencarian.
     */
    public function index(Request $request)
    {
        $query = Item::query();

        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                  ->orWhere('nama_barang', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('satuan', 'like', "%{$search}%") // Ditambahkan
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $items = $query->latest()->paginate(10);
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
     * Menyimpan barang baru dan mencatatnya sebagai transaksi masuk.
     */
    public function store(Request $request)
    {
        // DIUBAH: Validasi untuk 'satuan' ditambahkan
        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'satuan' => 'nullable|string|max:255',
            'lokasi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            $item = Item::create($validatedData);

            if ($item->jumlah > 0) {
                Transaction::create([
                    'item_id' => $item->id,
                    'jumlah' => $item->jumlah,
                    'tipe' => 'masuk',
                    'tanggal' => now(),
                    'keterangan' => 'Stok awal barang baru.',
                ]);
            }
            
            DB::commit();
            return redirect()->route('items.index')->with('success', 'Barang baru berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    /**
     * Menampilkan form untuk mengedit informasi barang.
     */
    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    /**
     * Memperbarui data barang.
     */
    public function update(Request $request, Item $item)
    {
        // DIUBAH: Validasi untuk 'satuan' ditambahkan
        $validatedData = $request->validate([
            'kode_barang' => ['required', 'string', 'max:255', Rule::unique('items')->ignore($item->id)],
            'nama_barang' => 'required|string|max:255',
            'satuan' => 'nullable|string|max:255',
            'lokasi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $item->update($validatedData);

        return redirect()->route('items.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    /**
     * Menghapus barang jika tidak memiliki riwayat transaksi.
     */
    public function destroy(Item $item)
    {
        if ($item->transactions()->exists() || $item->requests()->exists()) {
            return back()->withErrors(['error' => 'Barang tidak dapat dihapus karena memiliki riwayat transaksi atau request.']);
        }

        $item->delete();
        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus.');
    }

    // --- FUNGSI TAMBAH STOK ---

    public function addStockForm(Item $item)
    {
        return view('items.add-stock', compact('item'));
    }

    public function storeStock(Request $request, Item $item)
    {
        $request->validate([
            'jumlah_masuk' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            $item->increment('jumlah', $request->jumlah_masuk);
            Transaction::create([
                'item_id' => $item->id,
                'jumlah' => $request->jumlah_masuk,
                'tipe' => 'masuk',
                'tanggal' => now(),
                'keterangan' => $request->keterangan ?? 'Penambahan stok manual.',
            ]);

            DB::commit();
            return redirect()->route('items.index')->with('success', 'Stok barang berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menambahkan stok. Silakan coba lagi.']);
        }
    }
}

