<?php

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ClassroomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role === Role::BK;;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Mendapatkan ID ClassRoom jika ini adalah operasi update (PUT/PATCH)
        // Kita asumsikan ID Kelas ada di parameter rute, misalnya /classrooms/{classroom}
        $classRoomId = $this->route('classroom');

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                // Pastikan nama kelas unik, kecuali untuk kelas yang sedang diupdate
                Rule::unique('class_rooms', 'name')->ignore($classRoomId, 'id'),
            ],
            'description' => 'nullable|string|max:255',

            // homeroom_teacher_id (FK ke users) harus ada dan harus ber-role 'guru'
            'homeroom_teacher' => [
                'nullable',
                'uuid', // Asumsi ID di users adalah UUID (atau ubah ke 'exists:users,id' jika ID integer)
                Rule::exists('users', 'id')->where(function ($query) {
                    return $query->where('role', 'guru');
                }),
            ],
        ];
    }
}
