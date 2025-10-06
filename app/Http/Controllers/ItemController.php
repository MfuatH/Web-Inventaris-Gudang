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
        $request->validate([
            'kode_barang' => 'required|string|max:255|unique:items,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'lokasi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            // 1. Buat item baru
            $item = Item::create($request->all());

            // 2. Jika jumlah awal lebih dari 0, catat sebagai transaksi masuk
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
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.']);
        }
    }

    /**
     * Menampilkan form untuk mengedit informasi barang (bukan stok).
     */
    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    /**
     * Memperbarui data barang (nama, lokasi, dll.), BUKAN jumlah stok.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'kode_barang' => ['required', 'string', 'max:255', Rule::unique('items')->ignore($item->id)],
            'nama_barang' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            // Kolom 'jumlah' sengaja dihilangkan dari validasi ini
        ]);

        $item->update($request->except('jumlah')); // Update semua kecuali 'jumlah'

        return redirect()->route('items.index')->with('success', 'Data barang berhasil diperbarui.');
    }

    /**
     * Menghapus barang jika tidak memiliki riwayat transaksi.
     */
    public function destroy(Item $item)
    {
        // Pengecekan keamanan: jangan hapus item jika sudah ada transaksi
        if ($item->transactions()->exists() || $item->requests()->exists()) {
            return back()->withErrors(['error' => 'Barang tidak dapat dihapus karena memiliki riwayat transaksi atau request.']);
        }

        $item->delete();
        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus.');
    }

    // --- FUNGSI TAMBAH STOK ---

    /**
     * Menampilkan form untuk menambah stok.
     */
    public function addStockForm(Item $item)
    {
        return view('items.add-stock', compact('item'));
    }

    /**
     * Menyimpan penambahan stok dan mencatatnya sebagai transaksi masuk.
     */
    public function storeStock(Request $request, Item $item)
    {
        $request->validate([
            'jumlah_masuk' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            // 1. Tambah jumlah di tabel items
            $item->increment('jumlah', $request->jumlah_masuk);

            // 2. Buat catatan di tabel transactions
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