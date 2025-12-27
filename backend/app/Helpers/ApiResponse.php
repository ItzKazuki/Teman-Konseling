<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\AbstractPaginator;

class ApiResponse
{
    public static function success($data = null, ?string $message = null, int $code = 200): JsonResponse
    {
        $response = [
            'status' => true,
        ];

        if (! is_null($message) && $message !== [] && $message !== '') {
            $response['message'] = $message;
        }

        // Jika data adalah paginator
        if ($data instanceof AbstractPaginator) {

            $response['data'] = $data->items();

            $response['pagination'] = [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'has_more_pages' => $data->hasMorePages(),
            ];
        } else {
            // Normal (non-pagination)
            if (! is_null($data)) {
                $response['data'] = $data;
            }
        }

        $response['meta'] = [
            'status_code' => $code,
            'timestamp' => now()->toISOString(),
        ];

        return response()->json($response, $code);
    }

    public static function error(?string $message = null, int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'status' => false,
        ];

        if (! is_null($message) && $message !== [] && $message !== '') {
            $response['message'] = $message;
        }

        if (! is_null($errors) && $errors !== [] && $errors !== '') {
            $response['errors'] = $errors;
        }

        $response['meta'] = [
            'status_code' => $code,
            'timestamp' => now()->toISOString(),
        ];

        return response()->json($response, $code);
    }
}
