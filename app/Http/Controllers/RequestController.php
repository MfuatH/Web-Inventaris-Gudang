<?php

namespace App\Http\Controllers;

use App\Models\Bidang; // Pastikan model Bidang di-import
use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception; // Import Exception class

class RequestController extends Controller
{
    /**
     * Menampilkan daftar semua request barang.
     * Admin Barang hanya bisa melihat request untuk bidangnya.
     * Super Admin bisa melihat semua request.
     */
    public function index()
    {
        $user = Auth::user();
        // PERBAIKAN: Eager load relasi 'item' dan 'bidang'
        $requestsQuery = ItemRequest::with(['item', 'bidang'])->latest();

        // JIKA BUKAN SUPER ADMIN, FILTER BERDASARKAN BIDANG
        if ($user->role === 'admin_barang') {
            // PERBAIKAN: Hapus pencarian relasi 'user'.
            // Cukup filter berdasarkan relasi 'bidang' yang dimiliki request.
            $requestsQuery->whereHas('bidang', function ($query) use ($user) {
                $query->where('nama', $user->bidang);
            });
        }

        $requests = $requestsQuery->paginate(10);
        return view('requests.index', compact('requests'));
    }

    /**
     * Menampilkan form untuk membuat request baru oleh tamu.
     */
    public function create()
    {
        $items = Item::where('jumlah', '>', 0)->orderBy('nama_barang')->get();
        $bidang = Bidang::orderBy('nama')->get(); // Ambil data bidang untuk dropdown
        return view('requests.create', compact('items', 'bidang'));
    }

    /**
     * Menyimpan request baru dari tamu ke database.
     */
    public function store(Request $request)
    {
        // PERBAIKAN: Validasi disesuaikan untuk input dari tamu
        $validated = $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
            'no_hp' => 'required|string|max:25',
            'bidang_id' => 'required|exists:bidang,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.jumlah_request' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                foreach ($validated['items'] as $reqItem) {
                    $item = Item::findOrFail($reqItem['item_id']);

                    // Cek stok untuk setiap barang
                    if ($reqItem['jumlah_request'] > $item->jumlah) {
                        throw new Exception('Stok untuk barang "' . $item->nama_barang . '" tidak mencukupi.');
                    }

                    // PERBAIKAN: Hapus 'user_id' dan tambahkan data pemohon tamu
                    ItemRequest::create([
                        'nama_pemohon'   => $validated['nama_pemohon'],
                        'nip'            => $validated['nip'],
                        'no_hp'          => $validated['no_hp'],
                        'bidang_id'      => $validated['bidang_id'],
                        'item_id'        => $reqItem['item_id'],
                        'jumlah_request' => $reqItem['jumlah_request'],
                        'status'         => 'pending',
                    ]);
                }
            });
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }

        // PERBAIKAN: Arahkan ke halaman publik atau halaman sukses
        return redirect()->route('requests.create')->with('success', 'Permintaan barang berhasil dikirim.');
    }

    /**
     * Menyetujui sebuah request (dengan validasi hak akses).
     */
    public function approve(ItemRequest $request)
    {
        $admin = Auth::user();

        // PERBAIKAN: Validasi hak akses admin barang disesuaikan
        // Cek bidang admin dengan bidang yang ada pada request
        if ($admin->role === 'admin_barang') {
            if (!$request->bidang || $admin->bidang !== $request->bidang->nama) {
                abort(403, 'ANDA TIDAK BERHAK MENYETUJUI REQUEST DARI BIDANG INI.');
            }
        }

        try {
            DB::transaction(function () use ($request) {
                $item = $request->item;

                if ($request->jumlah_request > $item->jumlah) {
                    throw new Exception('Stok tidak mencukupi untuk menyetujui permintaan ini.');
                }

                $item->decrement('jumlah', $request->jumlah_request);
                $request->update(['status' => 'approved']);
                
                Transaction::create([
                    'request_id' => $request->id,
                    'item_id'    => $item->id,
                    'jumlah'     => $request->jumlah_request,
                    'tipe'       => 'keluar',
                    'tanggal'    => now(),
                ]);
            });
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('requests.index')->with('success', 'Permintaan berhasil disetujui.');
    }
    
    // --- FUNGSI DI BAWAH INI TIDAK LAGI DIGUNAKAN UNTUK SISTEM TAMU ---
    // --- Hapus route dan tombol yang mengarah ke fungsi-fungsi ini ---

    /*
    * NONAKTIF: Fungsi ini hanya untuk user yang login, tidak relevan untuk tamu.
    public function myRequests()
    {
        // $requests = ItemRequest::with('item')->where('user_id', Auth::id())->latest()->paginate(10);
        // return view('requests.my_requests', compact('requests'));
        
        // Redirect ke halaman utama atau tampilkan pesan error
        return redirect('/')->with('error', 'Halaman ini tidak tersedia.');
    }
    */

    /*
    * NONAKTIF: Fungsi ini hanya untuk user yang login, tidak relevan untuk tamu.
    public function receive(ItemRequest $request)
    {
        // if ($request->user_id !== Auth::id()) {
        //     abort(403);
        // }
        // $request->update(['status' => 'received']);
        // return redirect()->route('requests.my')->with('success', 'Barang telah dikonfirmasi diterima.');

        // Redirect ke halaman utama atau tampilkan pesan error
        return redirect('/')->with('error', 'Aksi ini tidak tersedia.');
    }
    */
}