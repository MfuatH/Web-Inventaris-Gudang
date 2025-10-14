<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $apiToken;
    private string $baseUrl;

    public function __construct()
    {
        // Ambil token dari config, pastikan cache sudah di-clear
        $this->apiToken = config('services.fonnte.token');
        $this->baseUrl = 'https://api.fonnte.com/send';
    }

    /**
     * Mengirim pesan dan mengembalikan hasil beserta detailnya.
     *
     * @return array ['success' => bool, 'details' => string]
     */
    public function sendMessage(string $phoneNumber, string $message): array
    {
        if (empty($this->apiToken)) {
            Log::error('Fonnte Token tidak ditemukan di konfigurasi.');
            return ['success' => false, 'details' => 'Fonnte token is not configured.'];
        }

        $response = Http::withHeaders([
            'Authorization' => $this->apiToken,
        ])->asForm()->post($this->baseUrl, [
            'target' => $phoneNumber,
            'message' => $message,
        ]);

        if ($response->successful()) {
            return ['success' => true, 'details' => 'Message sent successfully.'];
        } else {
            // Jika gagal, kembalikan detail error dari Fonnte
            $errorDetails = $response->body();
            Log::error('Fonnte API Error: ' . $errorDetails);
            return ['success' => false, 'details' => $errorDetails];
        }
    }
}

