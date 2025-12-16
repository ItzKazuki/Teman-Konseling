<?php

namespace App\Http\Requests;

use App\Models\RequestCounseling;
use App\Models\ScheduleCounseling;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CounselingScheduleRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna berwenang untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        // Pastikan siswa sudah login
        return Auth::guard('student')->check();
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk permintaan.
     */
    public function rules(): array
    {
        // Dapatkan ID siswa yang sedang login
        $studentId = Auth::guard('student')->user()->id ?? null;

        return [
            'counselor_id' => [
                'required',
                'uuid',
                // Pastikan konselor ID valid dan tersedia (is_available = true)
                function ($attribute, $value, $fail) {
                    if (!User::where('id', $value)->where('is_available', true)->exists()) {
                        $fail('Guru BK yang dipilih tidak valid atau sedang tidak tersedia.');
                    }
                },
            ],
            'method' => ['required', 'string', 'in:chat,face-to-face'],
            'schedule_date' => ['required', 'date', 'after_or_equal:today'], // Tidak boleh tanggal lampau
            'time_slot' => [
                'required', 
                'date_format:H:i',
                // Validasi custom: Pastikan slot waktu belum dipesan oleh konselor ini pada tanggal ini
                function ($attribute, $value, $fail) {
                    $isBooked = ScheduleCounseling::where('counselor_id', $this->counselor_id)
                                        ->where('schedule_date', $this->schedule_date)
                                        ->where('time_slot', $value)
                                        ->where('status', '!=', 'canceled') // Abaikan yang dibatalkan
                                        ->exists();
                    if ($isBooked) {
                        $fail("Slot jam $value pada tanggal {$this->schedule_date} sudah terisi oleh Guru BK ini.");
                    }
                },
            ],
        ];
    }
}
