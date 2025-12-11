<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Http\Resources\UserResource;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

#[Group('Auth')]
class LoginController extends Controller
{
    /**
     * Login Guru/Bk
     *
     * @unauthenticated
     */
    public function index(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
            'remember_me' => 'nullable|boolean',
        ]);

        $rememberMe = $request->boolean('remember_me');

        $key = 'login:'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return ApiResponse::error('Terlalu banyak percobaan login. Coba lagi nanti.', 429);
        }

        RateLimiter::hit($key, 60);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return ApiResponse::error(null, 422, [
                'email' => [
                    'Email atau password salah.',
                ],
            ]);
        }

        $expiresAt = $rememberMe
            ? Carbon::now()->addWeeks(1) // Token berlaku 7 hari
            : Carbon::now()->addHour();

        $token = $user->createToken('auth_token', ['*'], $expiresAt)->plainTextToken;

        return ApiResponse::success([
            'token' => $token,
            'type' => 'Bearer',
            'user' => new UserResource($user),
        ], "Login berhasil, selamat datang {$user->name}");
    }

    /**
     * Login Siswa
     *
     * @unauthenticated
     */
    public function student(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
            'remember_me' => 'nullable|boolean',
        ]);

        $rememberMe = $request->boolean('remember_me');

        $key = 'login:'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return ApiResponse::error('Terlalu banyak percobaan login. Coba lagi nanti.', 429);
        }

        RateLimiter::hit($key, 60);

        $user = Student::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return ApiResponse::error(null, 422, [
                'email' => [
                    'Email atau password salah.',
                ],
            ]);
        }

        $expiresAt = $rememberMe
            ? Carbon::now()->addWeeks(1) // Token berlaku 7 hari
            : Carbon::now()->addHour();

        $token = $user->createToken('auth_token', ['*'], $expiresAt)->plainTextToken;

        return ApiResponse::success([
            'token' => $token,
            'type' => 'Bearer',
            'user' => new StudentResource($user),
        ], "Login berhasil, selamat datang {$user->name}");
    }
}
