<?php

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClassroomRequest extends FormRequest
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
                Rule::unique('class_rooms', 'name')->ignore($classRoomId, 'id'),
            ],

            'description' => 'nullable|string|max:255',

            'level' => 'required|in:10,11,12',

            'homeroom_teacher' => [
                'nullable',
                'uuid',
                Rule::exists('users', 'id')->where(function ($query) {
                    return $query->where('role', 'guru');
                }),
            ],
        ];
    }
}
