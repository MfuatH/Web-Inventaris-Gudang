<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    private string $apiToken;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiToken = config('services.fonnte.token');
        $this->baseUrl = 'https://api.fonnte.com/send';
    }

    public function sendMessage(string $phoneNumber, string $message): bool
    {
        if (empty($this->apiToken)) {
            return false;
        }

        $response = Http::withHeaders([
            'Authorization' => $this->apiToken,
        ])->asForm()->post($this->baseUrl, [
            'target' => $phoneNumber,
            'message' => $message,
        ]);

        return $response->successful();
    }
}


