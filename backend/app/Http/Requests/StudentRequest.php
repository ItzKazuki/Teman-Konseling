<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role->isManagement();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $studentId = $this->route('student');

        return [
            // Identitas & Akun
            'nis' => [
                'required',
                'string',
                'max:255', // Sesuai tipe varchar(255) di tabel
                Rule::unique('students', 'nis')->ignore($studentId),
            ],
            'nisn' => [
                'required',
                'string',
                'max:255', // Sesuai tipe varchar(255) di tabel
                Rule::unique('students', 'nisn')->ignore($studentId),
            ],
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('students', 'email')->ignore($studentId),
            ],
            'password' => Rule::when($this->isMethod('POST'), ['required', 'string', 'min:8']),
            'class_room_id' => 'required|string|size:36|exists:class_rooms,id', // char(36) untuk UUID

            // Kontak & Alamat
            'phone_number' => 'required|string|max:20',
            'postal_code' => 'nullable|string|max:255',
            'address' => 'nullable|string', // longtext di tabel
            'village' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',

            // Data Orang Tua
            'parent_name' => 'nullable|string|max:255',
            'parent_phone_number' => 'nullable|string|max:255',
        ];
    }

    /**
     * Custom attributes untuk pesan error yang lebih user-friendly.
     */
    public function attributes(): array
    {
        return [
            'nis' => 'Nomor Induk Siswa',
            'nisn' => 'NISN',
            'class_room_id' => 'Kelas',
            'parent_name' => 'Nama Orang Tua',
            'parent_phone_number' => 'Nomor Telepon Orang Tua',
        ];
    }
}
