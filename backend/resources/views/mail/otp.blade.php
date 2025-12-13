<x-mail::message>
# Halo {{ $name }},

Kami menerima permintaan **reset password** untuk akun Anda.

## Kode OTP Anda
<x-mail::panel>
{{ $otp }}
</x-mail::panel>

Kode ini berlaku selama **5 menit**  
⚠️ **Jangan bagikan kode ini kepada siapa pun**

@if (!empty($callback_url))
---

<x-mail::button :url="$callback_url">
Reset Password
</x-mail::button>

Jika tombol di atas tidak berfungsi, salin dan buka tautan berikut di browser Anda:

{{ $callback_url }}
@endif

---

Jika Anda **tidak merasa** meminta reset password, abaikan email ini.

Terima kasih,  
{{ config('app.name') }}
</x-mail::message>
