<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success($data = null, string | null $message = null, int $code = 200): JsonResponse
    {
        $response = [
            'status' => true,
        ];

        if (!is_null($message) && $message !== [] && $message !== '') {
            $response['message'] = $message;
        }

        // hanya tambahkan "data" jika tidak null / tidak kosong
        if (!is_null($data) && $data !== [] && $data !== '') {
            $response['data'] = $data;
        }

        $response['meta'] = [
            'status_code' => $code,
            'timestamp' => now()->toISOString(),
        ];

        return response()->json($response, $code);
    }

    public static function error(string | null $message = null, int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'status' => false,
        ];

        if (!is_null($message) && $message !== [] && $message !== '') {
            $response['message'] = $message;
        }

        // hanya tambahkan "errors" jika ada isinya (misal error detail)
        if (!is_null($errors) && $errors !== [] && $errors !== '') {
            $response['errors'] = $errors;
        }

        $response['meta'] = [
            'status_code' => $code,
            'timestamp' => now()->toISOString(),
        ];

        return response()->json($response, $code);
    }
}
