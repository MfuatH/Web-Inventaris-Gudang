<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestController extends Controller
{
    public function index()
    {
        return view('guest.dashboard');
    }

    public function stock()
    {
        $items = Item::where('jumlah', '>', 0)
            ->orderBy('nama_barang')
            ->paginate(15);

        return view('guest.stock', compact('items'));
    }

    public function createRequest()
    {
        $items = Item::where('jumlah', '>', 0)
            ->orderBy('nama_barang')
            ->get();
        $bidang = Bidang::orderBy('nama')->get();
        return view('guest.request_create', compact('items', 'bidang'));
    }

    public function storeRequest(Request $request)
    {
        $validated = $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'no_hp' => 'required|string|max:20',
            'bidang_id' => 'required|exists:bidang,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.jumlah_request' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                foreach ($validated['items'] as $reqItem) {
                    $item = Item::findOrFail($reqItem['item_id']);

                    if ($reqItem['jumlah_request'] > $item->jumlah) {
                        throw new \Exception('Stok untuk barang "' . $item->nama_barang . '" tidak mencukupi.');
                    }

                    ItemRequest::create([
                        'user_id' => null,
                        'bidang_id' => $validated['bidang_id'],
                        'nama_pemohon' => $validated['nama_pemohon'],
                        'nip' => $validated['nip'] ?? null,
                        'no_hp' => $validated['no_hp'],
                        'item_id' => $reqItem['item_id'],
                        'jumlah_request' => $reqItem['jumlah_request'],
                        'status' => 'pending',
                    ]);
                }
            });
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }

        return redirect()->back()->with('success', 'Permintaan berhasil dikirim.');
    }
}


