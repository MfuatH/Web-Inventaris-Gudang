<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\RequestLinkZoom;
use Illuminate\Http\Request;

use App\Models\User;
use App\Services\WhatsAppService;

class GuestLinkZoomController extends Controller
{
    public function create()
    {
        $bidang = Bidang::orderBy('nama')->get();
        return view('guest.linkzoom_create', compact('bidang'));
    }

    public function store(Request $request, WhatsAppService $wa)
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

        $created = RequestLinkZoom::create($validated);

        // Kirim WA ke admin sesuai bidang
        $bidang = Bidang::find($validated['bidang_id']);
        if ($bidang) {
            $adminTargets = User::where('role', 'admin_barang')
                ->where('bidang', $bidang->nama)
                ->whereNotNull('no_hp')
                ->pluck('no_hp');

            if ($adminTargets->count() > 0) {
                $message = "[Request Link Zoom]\nNama: {$validated['nama_pemohon']}\nBidang: {$bidang->nama}\nJadwal: ".date('d-m-Y H:i', strtotime($validated['jadwal_mulai']))."\nKontak: {$validated['no_hp']}";
                foreach ($adminTargets as $phone) {
                    $wa->sendMessage($phone, $message);
                }
            }
        }

        return redirect()->back()->with('success', 'Permintaan Link Zoom telah berhasil dikirim!');
    }
}


