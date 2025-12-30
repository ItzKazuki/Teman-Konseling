<?php

namespace App\Services\Whatsapp;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService implements WhatsappNotificationInterface
{
    protected $baseUrl;

    protected $auth;

    const ENDPOINTS = [
        'send_message' => '/send',
    ];

    public function __construct()
    {
        $this->baseUrl = config('whatsapp.fonnte.api');
        $this->auth = config('whatsapp.fonnte.token');
    }

    protected function makeRequest($endpoint, $params = [])
    {
        $token = $this->auth ?? null;

        if (! $token) {
            return ['status' => false, 'error' => 'API token or device token is required.'];
        }

        $response = Http::withHeaders([
            'Authorization' => $token,
            'Content-Type' => 'application/json', // Tambahkan header
        ])->post($this->baseUrl.$endpoint, $params);

        $json = $response->json();

        Log::info('WhatsApp Gateway API Response', [
            'endpoint' => $this->baseUrl.$endpoint,
            'response' => $json,
        ]);

        if ($response->failed()) {
            return [
                'status' => false,
                'error' => $response->json()['reason'] ?? 'Unknown error occurred',
            ];
        }

        return [
            'status' => true,
            'data' => $response->json(),
        ];
    }

    public function sendWhatsAppMessage(string $phoneNumber, string $message): array
    {
        return $this->makeRequest(self::ENDPOINTS['send_message'], [
            'target' => $phoneNumber,
            'message' => $message,
        ]);
    }
}
