<?php

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role === Role::BK || $this->user()->role === Role::GURU;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Mendapatkan ID Siswa jika ini adalah operasi update (PUT/PATCH)
        // Kita asumsikan ID Siswa ada di parameter rute, misalnya /students/{student}
        $studentId = $this->route('student'); 

        return [
            'nis' => [
                'required',
                'string',
                'max:10',
                Rule::unique('students', 'nis')->ignore($studentId), 
            ],
            'nisn' => [
                'required',
                'string',
                'max:15',
                // Pastikan NISN unik, kecuali untuk siswa yang sedang diupdate
                Rule::unique('students', 'nisn')->ignore($studentId), 
            ],
            'name' => 'required|string|max:255',
            
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Pastikan Email unik, kecuali untuk siswa yang sedang diupdate
                Rule::unique('students', 'email')->ignore($studentId),
            ],
            
            'password' => Rule::when($this->isMethod('POST'), ['required', 'string', 'min:8']),
            
            'class_room_id' => 'required|uuid|exists:class_rooms,id',
        ];
    }
}
