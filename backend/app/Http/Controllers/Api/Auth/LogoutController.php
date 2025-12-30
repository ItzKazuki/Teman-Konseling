<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[Group('Auth', weight: 0)]
class LogoutController extends Controller
{
    /**
     * Logout
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            $user->timestamps = false;
            $user->update([
                'is_online' => false,
                'last_seen_at' => now(),
            ]);

            $user->tokens()->delete();
        }

        return ApiResponse::success(null, 'Logout berhasil, Selamat tinggal');
    }
}
