<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CounselingFormRequest extends FormRequest
{
   /**
     * Tentukan apakah pengguna berwenang untuk membuat permintaan ini.
     * Pastikan siswa sudah login.
     */
    public function authorize(): bool
    {
        // Pastikan siswa sudah terautentikasi
        return Auth::guard('student')->check();
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk permintaan.
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'min:50'], // Minimal 50 karakter
            'urgency' => ['required', 'string', 'in:low,medium,high'],
        ];
    }

    /**
     * Sesuaikan pesan kesalahan untuk validasi.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul masalah wajib diisi.',
            'description.required' => 'Deskripsi masalah wajib diisi.',
            'description.min' => 'Deskripsi harus minimal 50 karakter.',
            'urgency.required' => 'Tingkat urgensi wajib dipilih.',
            'urgency.in' => 'Tingkat urgensi tidak valid.',
        ];
    }
}
