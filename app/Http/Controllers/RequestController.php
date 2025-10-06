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
    // Tampilan untuk Admin: melihat semua request (dengan filter)
    public function index()
    {
        $user = Auth::user();
        $requestsQuery = ItemRequest::with(['user', 'item'])->latest();

        // JIKA BUKAN SUPER ADMIN, FILTER BERDASARKAN BIDANG
        if ($user->role === 'admin_barang') {
            $requestsQuery->whereHas('user', function ($query) use ($user) {
                $query->where('bidang', $user->bidang);
            });
        }

        $requests = $requestsQuery->paginate(10);
        return view('requests.index', compact('requests'));
    }

    // Aksi untuk menyetujui request (dengan validasi hak akses)
    public function approve(ItemRequest $request)
    {
        $admin = Auth::user();

        // JIKA ADMIN BARANG, PASTIKAN BIDANGNYA SAMA DENGAN PEMBUAT REQUEST
        if ($admin->role === 'admin_barang') {
            if ($admin->bidang !== $request->user->bidang) {
                abort(403, 'ANDA TIDAK BERHAK MENYETUJUI REQUEST INI.');
            }
        }

        DB::transaction(function () use ($request) {
            $item = $request->item;

            if ($request->jumlah_request > $item->jumlah) {
                throw new \Exception('Stok tidak mencukupi untuk menyetujui permintaan ini.');
            }

            $item->decrement('jumlah', $request->jumlah_request);

            $request->update(['status' => 'approved']);
            
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
    
    // ... fungsi-fungsi lain (myRequests, create, store, receive) tidak berubah ...
    public function myRequests()
    {
        $requests = ItemRequest::with('item')->where('user_id', Auth::id())->latest()->paginate(10);
        return view('requests.my_requests', compact('requests'));
    }
    
    public function create()
    {
        $items = Item::where('jumlah', '>', 0)->get();
        return view('requests.create', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([ 'item_id' => 'required|exists:items,id', 'jumlah_request' => 'required|integer|min:1', ]);
        $item = Item::findOrFail($request->item_id);
        if ($request->jumlah_request > $item->jumlah) { return back()->withErrors(['jumlah_request' => 'Jumlah permintaan melebihi stok.'])->withInput(); }
        ItemRequest::create([ 'user_id' => Auth::id(), 'item_id' => $request->item_id, 'jumlah_request' => $request->jumlah_request, ]);
        return redirect()->route('requests.my')->with('success', 'Permintaan barang berhasil dikirim.');
    }

    public function receive(ItemRequest $request)
    {
        if ($request->user_id !== Auth::id()) { abort(403); }
        $request->update(['status' => 'received']);
        return redirect()->route('requests.my')->with('success', 'Barang telah dikonfirmasi diterima.');
    }
}