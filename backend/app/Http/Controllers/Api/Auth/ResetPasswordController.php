<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Models\Student;
use App\Models\User;
use App\Services\OtpService;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

#[Group('Auth')]
class ResetPasswordController
{
    protected function findRecipient(string $email): User|Student
    {
        return User::where('email', $email)->first()
            ?? Student::where('email', $email)->first();
    }

    /**
     * Request Reset Password
     *
     * @unauthenticated
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|string',
            'callback_url' => 'nullable|url|max:2048',
        ]);

        $key = 'reset-password:'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return ApiResponse::error('Terlalu banyak percobaan reset password.', 429);
        }

        RateLimiter::hit($key, 60);

        $user = $this->findRecipient($request->email);

        if (! $user) {
            return ApiResponse::error(null, 422, [
                'email' => ['Email tidak terdaftar.'],
            ]);
        }

        $requestId = (string) Str::uuid();

        Cache::put(
            "password_reset:$requestId",
            [
                'user_id' => $user->id,
                'user_type' => $user instanceof User ? 'user' : 'student',
                'email' => $user->email,
            ],
            now()->addMinutes(10)
        );

        $otp = OtpService::generate($user);

        if (is_array($otp) && isset($otp['error'])) {
            return ApiResponse::error(null, 422, [
                'otp' => [$otp['message']],
            ]);
        }

        return ApiResponse::success([
            'request_id' => $requestId,
        ], 'OTP berhasil dikirim ke email.');
    }

    /**
     * Verification One Time Password (OTP)
     *
     * @unauthenticated
     */
    public function validateOtp(Request $request)
    {
        $request->validate([
            'request_id' => 'required|uuid',
            'otp' => 'required|integer',
        ]);

        $data = Cache::get("password_reset:{$request->request_id}");

        if (! $data) {
            return ApiResponse::error('Session reset password expired.', 410);
        }

        $user = $this->findRecipient($data['email']);

        if (! $user) {
            return ApiResponse::error('Pengguna tidak ditemukan.', 404);
        }

        $statusOtp = OtpService::validate($request->otp, $user->id);

        if (! $statusOtp) {
            return ApiResponse::error(null, 422, [
                'otp' => [
                    'Otp salah atau sudah expired.',
                ],
            ]);
        }

        // generate reset token via Laravel built-in
        $resetToken = Password::createToken($user);

        Cache::put(
            "password_reset:{$request->request_id}:token",
            $resetToken,
            now()->addMinutes(10)
        );

        return ApiResponse::success([
            'request_id' => $request->request_id,
        ], 'Otp berhasil divalidasi.');
    }

    /**
     * Change Password
     *
     * @unauthenticated
     */
    public function change(Request $request)
    {
        $request->validate([
            'request_id' => 'required|uuid',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data = Cache::get("password_reset:{$request->request_id}");
        $resetToken = Cache::get("password_reset:{$request->request_id}:token");

        if (! $data || ! $resetToken) {
            return ApiResponse::error('Session reset password expired.', 410);
        }

        $user = $this->findRecipient($data['email']);

        if (! $user) {
            return ApiResponse::error('User tidak ditemukan.', 404);
        }

        if (! Password::tokenExists($user, $resetToken)) {
            return ApiResponse::error('Token tidak valid atau expired.');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        Password::deleteToken($user);

        Cache::forget("password_reset:{$request->request_id}");
        Cache::forget("password_reset:{$request->request_id}:token");

        return ApiResponse::success(null, 'Password berhasil diubah.');
    }

    private function isTrustedCallback(?string $url): bool
    {
        if (! $url) {
            return true; // optional → aman
        }

        // pastikan URL valid
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $host = parse_url($url, PHP_URL_HOST);

        if (! $host) {
            return false;
        }

        $trustedDomains = config('auth.trusted_callback_domains');

        foreach ($trustedDomains as $domain) {
            // exact match atau subdomain
            if ($host === $domain || Str::endsWith($host, '.'.$domain)) {
                return true;
            }
        }

        return false;
    }
}
