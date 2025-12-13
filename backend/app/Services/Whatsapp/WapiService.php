<?php

namespace App\Services\Whatsapp;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WapiService implements WhatsappNotificationInterface
{
    protected $baseUrl;

    protected $auth;

    const ENDPOINTS = [
        'send_message' => '/send/message',
    ];

    public function __construct()
    {
        $this->baseUrl = config('whatsapp.wapi.api');
        $this->auth = config('whatsapp.wapi.auth'); // format: user:password
    }

    protected function formatPhoneNumber(string $phoneNumber): string
    {
        $defaultCode = config('whatsapp.wapi.country_code');

        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

        if (str_starts_with($phoneNumber, '0')) {
            $phoneNumber = $defaultCode.substr($phoneNumber, 1);
        }

        if (str_starts_with($phoneNumber, $defaultCode)) {
            return $phoneNumber.'@s.whatsapp.net';
        }

        return $phoneNumber.'@s.whatsapp.net';
    }

    protected function makeRequest($endpoint, $params = [])
    {
        if (! $this->auth) {
            return ['status' => false, 'error' => 'Token belum disetting, kontak admin untuk error di bagian whatsapp token'];
        }

        [$user, $password] = explode(':', $this->auth);

        $response = Http::withBasicAuth($user, $password)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($this->baseUrl.$endpoint, $params);

        $json = $response->json();

        Log::info('WhatsApp Gateway API Response', [
            'endpoint' => $this->baseUrl.$endpoint,
            'response' => $json,
        ]);

        if ($response->failed()) {
            return [
                'status' => false,
                'code' => $json['code'] ?? $response->status(),
                'error' => $json['message'] ?? 'Unknown error occurred',
            ];
        }

        if (isset($json['code']) && $json['code'] === 'SUCCESS') {
            return [
                'status' => true,
                'code' => $json['code'],
                'message' => $json['message'],
                'data' => $json['results'],
            ];
        }

        return [
            'status' => false,
            'code' => $json['code'] ?? $response->status(),
            'error' => $json['message'] ?? 'Unknown error occurred',
        ];
    }

    public function sendWhatsAppMessage(string $phoneNumber, string $message): array
    {
        return $this->makeRequest(self::ENDPOINTS['send_message'], [
            'phone' => $this->formatPhoneNumber($phoneNumber),
            'message' => $message,
            'is_forwarded' => false,
            'duration' => 0,
        ]);
    }
}
