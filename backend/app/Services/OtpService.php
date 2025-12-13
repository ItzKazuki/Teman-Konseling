<?php

namespace App\Services;

use App\Mail\Otp;
use App\Models\Student;
use App\Models\User;
use App\Services\Whatsapp\WhatsappNotificationInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Throwable;

class OtpService
{
    /**
     * Generate OTP baru untuk user/student dan mengirimkannya.
     *
     * * @param User|Student $recipient Model user/student
     * @param  string  $deliveryMethod  'email' atau 'whatsapp'
     * @return array|int Hasil OTP atau array error
     */
    public static function generate(User|Student $recipient, array $options = [], string $deliveryMethod = 'email')
    {
        $otpKey = self::getCacheKey($recipient->id);
        $cooldownKey = self::getCooldownKey($recipient->id);

        $callbackUrl = $options['callback_url'] ?? null;

        if (Cache::has($cooldownKey)) {
            return [
                'error' => true,
                'message' => 'Tunggu 1 menit sebelum meminta OTP baru.',
            ];
        }

        $otp = random_int(100000, 999999);

        Cache::put($otpKey, [
            'otp' => $otp,
            'expired_at' => Carbon::now()->addMinutes(5),
            'callback_url' => $callbackUrl,
        ], now()->addMinutes(5));

        try {
            $message = self::buildMessage($otp, $callbackUrl);

            if ($deliveryMethod === 'whatsapp') {
                if (empty($recipient->phone_number)) {
                    throw new \Exception('Nomor telepon pengguna tidak tersedia.');
                }

                $waService = App::make(WhatsappNotificationInterface::class);

                $result = $waService->sendWhatsAppMessage(
                    $recipient->phone_number,
                    $message
                );

                if (! ($result['status'] ?? false)) {
                    throw new \Exception($result['message'] ?? 'Gagal mengirim OTP via WhatsApp.');
                }

            } else {
                if (empty($recipient->email)) {
                    throw new \Exception('Alamat email pengguna tidak tersedia.');
                }

                Mail::to($recipient->email)
                ->send(new Otp($recipient->name, $otp, $callbackUrl));
            }

        } catch (Throwable $e) {
            Cache::forget($otpKey);

            return [
                'error' => true,
                'message' => 'Gagal mengirim OTP via '.$deliveryMethod.': '.$e->getMessage(),
            ];
        }

        Cache::put($cooldownKey, true, now()->addSeconds(60));

        return $otp;
    }

    /**
     * Validasi OTP
     */
    public static function validate(int $otp, string $userId)
    {
        $otpKey = self::getCacheKey($userId);
        $cached = Cache::get($otpKey);

        if (! $cached) {
            return false; // Tidak ada OTP tersimpan
        }

        if (Carbon::now()->greaterThan($cached['expired_at'])) {
            Cache::forget($otpKey);

            return false;
        }

        return intval($otp) === intval($cached['otp']);
    }

    /**
     * Hapus OTP setelah digunakan
     */
    public static function clear(string $userId, int $otp)
    {
        $otpKey = self::getCacheKey($userId);
        $cached = Cache::get($otpKey);

        if ($cached && intval($cached['otp']) === intval($otp)) {
            Cache::forget($otpKey);

            return true;
        }

        return false;
    }

    /**
     * Key cache utama per user.
     */
    private static function getCacheKey(string $userId): string
    {
        return "otp:user:{$userId}";
    }

    /**
     * Key cooldown agar user tidak spam generate OTP.
     */
    private static function getCooldownKey(string $userId): string
    {
        return "otp:cooldown:user:{$userId}";
    }

    private static function buildMessage(int $otp, ?string $callbackUrl): string
    {
        $message = "Kode OTP Teman Konseling Anda adalah *{$otp}*.\n";
        $message .= "Berlaku selama 5 menit.\n";
        $message .= 'Jangan berikan kode ini kepada siapapun.';

        if ($callbackUrl) {
            $message .= "\n\nReset password di:\n{$callbackUrl}";
        }

        return $message;
    }
}
