<?php

namespace App\Http\Controllers;

use App\Models\RequestLinkZoom;
use App\Models\Bidang;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ZoomController extends Controller
{
    /**
     * Menampilkan daftar request link zoom untuk approval
     */
    public function approval()
    {
        $user = auth()->user();
        $requestsQuery = RequestLinkZoom::with('bidang')->latest();

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
    public function approve(RequestLinkZoom $zoomRequest)
    {
        $admin = auth()->user();

        if ($admin->role === 'admin_barang') {
            $reqBidangNama = optional($zoomRequest->bidang)->nama;
            if ($reqBidangNama === null || $admin->bidang !== $reqBidangNama) {
                abort(403, 'ANDA TIDAK BERHAK MENYETUJUI REQUEST INI.');
            }
        }

        if (!$zoomRequest->link_zoom) {
            return redirect()->route('zoom.approval')->with('error', 'Link Zoom harus diisi terlebih dahulu.');
        }

        $zoomRequest->update([
            'status' => 'approved'
        ]);

        $template = optional($zoomRequest->bidang)->pesan_template;
        $pesanFinal = null;

        if ($template) {
            $placeholders = ['@nama', '@kegiatan', '@tanggal', '@link'];
            $values = [
                $zoomRequest->nama_pemohon,
                $zoomRequest->keterangan,
                Carbon::parse($zoomRequest->jadwal_mulai)->isoFormat('dddd, D MMMM YYYY'),
                $zoomRequest->link_zoom
            ];
            $pesanFinal = str_replace($placeholders, $values, $template);
        }

        //  dd([
        //     'Template Pesan dari DB:' => $template,
        //     'Pesan Final (setelah replace):' => $pesanFinal,
        //     'Nomor HP Guest (Asli):' => $zoomRequest->no_hp,
        //     'Nomor HP Guest (Setelah Format):' => $this->formatPhoneNumber($zoomRequest->no_hp),
        //     'Token Fonnte dari Config:' => config('services.fonnte.token')
        // ]);

        // --- KIRIM WHATSAPP DENGAN NOMOR YANG SUDAH DIBERSIHKAN ---
        if ($pesanFinal && $zoomRequest->no_hp) {
            try {
                // Ambil nomor HP guest dan format ke standar internasional
                $nomorTujuan = $this->formatPhoneNumber($zoomRequest->no_hp);

                $whatsAppService = new WhatsAppService();
                $hasilKirim = $whatsAppService->sendMessage($nomorTujuan, $pesanFinal); // Gunakan $nomorTujuan

                if ($hasilKirim['success']) {
                    Log::info('Berhasil mengirim notifikasi WhatsApp ke: ' . $nomorTujuan);
                } else {
                    Log::error('Gagal mengirim WhatsApp ke ' . $nomorTujuan . '. Alasan: ' . ($hasilKirim['details'] ?? 'Unknown Error'));
                }

            } catch (\Exception $e) {
                Log::error('Terjadi Exception saat mengirim WhatsApp: ' . $e->getMessage());
            }
        }

        // --- RESPON KE PENGGUNA ---
        $redirect = redirect()->route('zoom.approval')->with('success', 'Request Link Zoom berhasil disetujui.');

        if ($pesanFinal) {
            $redirect->with('info', 'Pesan Notifikasi: ' . $pesanFinal);
        }

        return $redirect;
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
            'pesan' => 'required|string|max:2000',
        ]);

        $bidang = Bidang::findOrFail($validated['bidang_id']);

        $bidang->update([
            'pesan_template' => $validated['pesan']
        ]);

        return redirect()->route('zoom.master_pesan')->with('success', 'Master pesan berhasil disimpan.');
    }
    
    /**
     * Membersihkan dan memformat nomor HP ke format internasional (62xxx).
     */
    private function formatPhoneNumber($number)
    {
        // 1. Hapus semua karakter selain angka
        $cleaned = preg_replace('/[^0-9]/', '', $number);

        // 2. Jika nomor diawali '0', ganti dengan '62'
        if (substr($cleaned, 0, 1) == '0') {
            return '62' . substr($cleaned, 1);
        }

        // 3. Jika nomor sudah diawali '62', biarkan
        if (substr($cleaned, 0, 2) == '62') {
            return $cleaned;
        }

        // 4. Untuk kasus lain (misal: hanya mengetik 812...), tambahkan '62'
        return '62' . $cleaned;
    }
}

