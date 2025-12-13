<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Models\Student;
use App\Models\User;
use App\Services\OtpService;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;

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
        ]);

        $key = 'reset-password:'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return ApiResponse::error('Terlalu banyak percobaan reset password. Coba lagi nanti.', 429);
        }

        RateLimiter::hit($key, 60);

        $user = $this->findRecipient($request->email);

        if (! $user) {
            return ApiResponse::error(null, 422, [
                'email' => [
                    'Email tidak terdaftar.',
                ],
            ]);
        }

        $otp = OtpService::generate($user);

        if (is_array($otp) && isset($otp['error'])) {
            return ApiResponse::error(null, 422, [
                'otp' => [
                    $otp['message'],
                ],
            ]);
        }

        return ApiResponse::success(null, 'Berhasil mengirim otp ke email. Silahkan check email anda secara berkala.');
    }

    /**
     * Verification One Time Password (OTP)
     *
     * @unauthenticated
     */
    public function validateOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|string',
            'otp' => 'required|integer',
        ]);

        $user = $this->findRecipient($request->email);

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

        return ApiResponse::success([
            'resetToken' => $resetToken,
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
            'email' => 'required|email|string',
            'reset_token' => 'required|string',
            'password' => 'required||string|min:8|confirmed',
        ]);

        $user = $this->findRecipient($request->email);

        $isValid = Password::tokenExists($user, $request->reset_token);

        if (! $isValid) {
            return ApiResponse::error('Token Salah atau sudah expired, silahkan coba lagi.');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        Password::deleteToken($user);

        return ApiResponse::success(null, 'Berhasil mengubah password, silahkan login kembali!');
    }
}
