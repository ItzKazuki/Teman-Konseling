<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MoodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'emotion_name' => 'required|string|max:50',
            'magnitude'    => 'required|integer|min:1|max:4',
            'story'         => 'nullable|string|max:500',
            'is_custom'    => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'emotion_name.required' => 'Nama emosi harus diisi.',
            'magnitude.required'    => 'Tingkat intensitas harus dipilih.',
            'magnitude.min'         => 'Skala minimal adalah 1.',
            'magnitude.max'         => 'Skala maksimal adalah 4.',
            'story.max'             => 'Cerita maksimal 500 karakter.',
        ];
    }
}
