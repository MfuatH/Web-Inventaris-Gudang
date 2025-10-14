<?php

namespace App\Http\Controllers;

use App\Models\RequestLinkZoom;
use App\Models\Bidang;
use Illuminate\Http\Request;

class ZoomController extends Controller
{
    /**
     * Menampilkan daftar request link zoom untuk approval
     */
    public function approval()
    {
        $user = auth()->user();
        $requestsQuery = RequestLinkZoom::with('bidang')->latest();

        // Filter berdasarkan bidang jika admin_barang
        if ($user->role === 'admin_barang') {
            $requestsQuery->whereHas('bidang', function ($query) use ($user) {
                $query->where('nama', $user->bidang);
            });
        }

        $requests = $requestsQuery->paginate(10);
        return view('zoom.approval', compact('requests'));
    }

    /**
     * Menambahkan link zoom ke request
     */
    public function addLink(Request $request, RequestLinkZoom $zoomRequest)
    {
        $admin = auth()->user();

        // Validasi hak akses untuk admin_barang
        if ($admin->role === 'admin_barang') {
            $reqBidangNama = optional($zoomRequest->bidang)->nama;
            if ($reqBidangNama === null || $admin->bidang !== $reqBidangNama) {
                abort(403, 'ANDA TIDAK BERHAK MENYETUJUI REQUEST INI.');
            }
        }

        $validated = $request->validate([
            'link_zoom' => 'required|url|max:500',
        ]);

        $zoomRequest->update([
            'link_zoom' => $validated['link_zoom']
        ]);

        return redirect()->route('zoom.approval')->with('success', 'Link Zoom berhasil ditambahkan.');
    }

    /**
     * Menyetujui request link zoom
     */
    public function approve(RequestLinkZoom $request)
    {
        $admin = auth()->user();

        // Validasi hak akses untuk admin_barang
        if ($admin->role === 'admin_barang') {
            $reqBidangNama = optional($request->bidang)->nama;
            if ($reqBidangNama === null || $admin->bidang !== $reqBidangNama) {
                abort(403, 'ANDA TIDAK BERHAK MENYETUJUI REQUEST INI.');
            }
        }

        // Pastikan link zoom sudah ada
        if (!$request->link_zoom) {
            return redirect()->route('zoom.approval')->with('error', 'Link Zoom harus diisi terlebih dahulu.');
        }

        $request->update([
            'status' => 'approved'
        ]);

        return redirect()->route('zoom.approval')->with('success', 'Request Link Zoom berhasil disetujui.');
    }

    /**
     * Menampilkan halaman master pesan
     */
    public function masterPesan()
    {
        $bidang = Bidang::orderBy('nama')->get();
        return view('zoom.master_pesan', compact('bidang'));
    }

    /**
     * Menyimpan master pesan
     */
    public function storeMasterPesan(Request $request)
    {
        $validated = $request->validate([
            'bidang_id' => 'required|exists:bidang,id',
            'pesan' => 'required|string|max:1000',
        ]);

        // Simpan ke database atau file
        // Untuk contoh, kita simpan ke session
        session()->put('master_pesan_' . $validated['bidang_id'], $validated['pesan']);

        return redirect()->route('zoom.master_pesan')->with('success', 'Master pesan berhasil disimpan.');
    }
}
