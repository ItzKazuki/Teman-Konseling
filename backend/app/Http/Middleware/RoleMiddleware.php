<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // Cek apakah user login dan memiliki property role
        // $user->role di sini sudah otomatis menjadi instance Enum Role karena di-cast di model
        if (! $user || ! in_array($user->role->value, $roles)) {
            return response()->json([
                'status' => false,
                'message' => 'Akses ditolak. Role Anda tidak memiliki izin.',
                'required_roles' => $roles,
            ], 403);
        }

        return $next($request);
    }
}
