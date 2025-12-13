<x-mail::message>
# Halo {{ $name }},

Kode OTP untuk reset password Anda adalah:

# {{ $otp }}

Berlaku selama **5 menit**.

Jika Anda tidak meminta reset password, abaikan email ini.

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
