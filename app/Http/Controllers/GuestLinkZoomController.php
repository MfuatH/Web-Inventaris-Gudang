<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\RequestLinkZoom;
use Illuminate\Http\Request;

class GuestLinkZoomController extends Controller
{
    public function create()
    {
        $bidang = Bidang::orderBy('nama')->get();
        return view('guest.linkzoom_create', compact('bidang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'no_hp' => 'required|string|max:20',
            'bidang_id' => 'required|exists:bidang,id',
            'jadwal_mulai' => 'required|date',
            'jadwal_selesai' => 'nullable|date|after_or_equal:jadwal_mulai',
            'keterangan' => 'nullable|string',
        ]);

        RequestLinkZoom::create($validated);

        return redirect()->back()->with('success', 'Permintaan Link Zoom telah berhasil dikirim!');
    }
}


