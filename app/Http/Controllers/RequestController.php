<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    /**
     * Menampilkan daftar request untuk Admin (dengan filter per bidang).
     */
    public function index()
    {
        $user = Auth::user();
        $requestsQuery = ItemRequest::with(['user', 'item'])->latest();

        // JIKA BUKAN SUPER ADMIN, FILTER BERDASARKAN BIDANG
        if ($user->role === 'admin_barang') {
            // Filter berdasarkan bidang_id jika ada; request tamu harus memiliki bidang_id terisi
            $requestsQuery->where(function ($q) use ($user) {
                $q->whereHas('user', function ($query) use ($user) {
                        $query->where('bidang', $user->bidang);
                    })
                  ->orWhereHas('bidang', function ($query) use ($user) {
                        $query->where('nama', $user->bidang);
                    });
            });
        }

        $requests = $requestsQuery->paginate(10);
        return view('requests.index', compact('requests'));
    }

    /**
     * Menampilkan riwayat request milik user yang sedang login.
     */
    public function myRequests()
    {
        $requests = ItemRequest::with('item')->where('user_id', Auth::id())->latest()->paginate(10);
        return view('requests.my_requests', compact('requests'));
    }
    
    /**
     * Menampilkan form untuk membuat request baru.
     */
    public function create()
    {
        $items = Item::where('jumlah', '>', 0)->orderBy('nama_barang')->get();
        return view('requests.create', compact('items'));
    }

    /**
     * Menyimpan satu atau lebih request baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi untuk array input
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.jumlah_request' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                foreach ($validated['items'] as $reqItem) {
                    $item = Item::findOrFail($reqItem['item_id']);

                    // Cek stok untuk setiap barang dalam loop
                    if ($reqItem['jumlah_request'] > $item->jumlah) {
                        // Jika stok tidak cukup, batalkan semua transaksi
                        throw new \Exception('Stok untuk barang "' . $item->nama_barang . '" tidak mencukupi.');
                    }

                    ItemRequest::create([
                        'user_id' => Auth::id(),
                        'item_id' => $reqItem['item_id'],
                        'jumlah_request' => $reqItem['jumlah_request'],
                        'status' => 'pending',
                    ]);
                }
            });
        } catch (\Exception $e) {
            // Kembali ke halaman form dengan pesan error
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }

        return redirect()->route('requests.my')->with('success', 'Semua permintaan barang berhasil dikirim.');
    }

    /**
     * Menyetujui sebuah request (dengan validasi hak akses).
     */
    public function approve(ItemRequest $request)
    {
        $admin = Auth::user();

        // JIKA ADMIN BARANG, PASTIKAN BIDANGNYA SAMA DENGAN PEMBUAT REQUEST
        if ($admin->role === 'admin_barang') {
            // Bila request dari tamu (tanpa user), lewati pengecekan bidang
            if ($request->user && $admin->bidang !== $request->user->bidang) {
                abort(403, 'ANDA TIDAK BERHAK MENYETUJUI REQUEST INI.');
            }
        }

        DB::transaction(function () use ($request) {
            $item = $request->item;

            if ($request->jumlah_request > $item->jumlah) {
                throw new \Exception('Stok tidak mencukupi untuk menyetujui permintaan ini.');
            }

            // Kurangi stok barang
            $item->decrement('jumlah', $request->jumlah_request);

            // Ubah status request
            $request->update(['status' => 'approved']);
            
            // Catat transaksi
            Transaction::create([
                'request_id' => $request->id,
                'item_id' => $item->id,
                'jumlah' => $request->jumlah_request,
                'tipe' => 'keluar',
                'tanggal' => now(),
            ]);
        });

        return redirect()->route('requests.index')->with('success', 'Permintaan berhasil disetujui.');
    }

    /**
     * Konfirmasi penerimaan barang oleh user.
     */
    public function receive(ItemRequest $request)
    {
        if ($request->user_id !== Auth::id()) {
            abort(403);
        }
        $request->update(['status' => 'received']);
        return redirect()->route('requests.my')->with('success', 'Barang telah dikonfirmasi diterima.');
    }
}