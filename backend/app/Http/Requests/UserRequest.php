<?php

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role === Role::BK;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user'); 

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Pastikan Email unik, kecuali untuk user yang sedang diupdate
                Rule::unique('users', 'email')->ignore($userId, 'id'),
            ],
            
            'nip' => [
                'required',
                'string',
                'max:18',
                // Pastikan NIP unik
                Rule::unique('users', 'nip')->ignore($userId, 'id'), 
            ],
            
            'role' => [
                'required',
                new Enum(Role::class),
            ],
            'jabatan' => 'required|string|max:255',
            
            'password' => Rule::when($this->isMethod('POST'), ['required', 'string', 'min:8', 'confirmed']),
        ];
    }
}
