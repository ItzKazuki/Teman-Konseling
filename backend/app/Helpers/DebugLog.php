<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class DebugLog
{
    public static function log($message = null, $data = null): void
    {
        $logEntry = [
            'timestamp' => now()->toISOString(),
            'message' => $message,
            'data' => $data,
        ];

        Log::debug('Debug Log Entry:', $logEntry);
    }

    public static function info($message = null, $data = null): void
    {
        $logEntry = [
            'timestamp' => now()->toISOString(),
            'message' => $message,
            'data' => $data,
        ];

        Log::info('Info Log Entry:', $logEntry);
    }

    public static function error($message = null, $data = null): void
    {
        $logEntry = [
            'timestamp' => now()->toISOString(),
            'message' => $message,
            'data' => $data,
        ];

        Log::error('Error Log Entry:', $logEntry);
    }
}
