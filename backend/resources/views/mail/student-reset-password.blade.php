<x-mail::message>
# Halo {{ $student->name }},

Admin telah mengatur ulang kata sandi akun Anda di **{{ config('app.name') }}**. 
Berikut adalah detail login terbaru yang dapat Anda gunakan:

<x-mail::panel>
**Email:** {{ $student->email }}  
**Kata Sandi Baru:** `{{ $newPassword }}`
</x-mail::panel>

⚠️ **Demi keamanan, harap segera masuk dan ganti kata sandi Anda melalui menu Pengaturan Akun setelah login.**

<x-mail::button :url="config('app.siswa') . '/auth/login'">
Masuk ke Dashboard
</x-mail::button>

---

Jika Anda merasa tidak meminta pengaturan ulang ini atau mengalami kendala saat masuk, silakan hubungi tim IT atau Administrator sekolah.

Terima kasih,  
**{{ config('app.name') }}**
</x-mail::message>