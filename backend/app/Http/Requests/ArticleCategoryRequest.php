<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleCategoryRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat request ini.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Asumsikan otorisasi ditangani di tempat lain (misalnya, middleware)
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // Mendapatkan ID kategori saat ini jika ini adalah request 'update' (PUT/PATCH)
        $categoryId = $this->route('article_category');

        return [
            // Field Nama Kategori (harus diisi, string, dan maksimal 255 karakter)
            'name' => ['required', 'string', 'max:255'],

            // Field Slug (harus diisi, string, dan unik, kecuali untuk kategori yang sedang di-update)
            // 'slug' => [
            //     'required', 
            //     'string', 
            //     'max:255',
            //     Rule::unique('article_categories', 'slug')->ignore($categoryId)
            // ],

            // Field Deskripsi (optional, string)
            'description' => ['nullable', 'string'],
        ];
    }

    /**
     * Kustomisasi pesan kesalahan validasi.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'slug.unique' => 'Slug kategori ini sudah digunakan.',
        ];
    }
}