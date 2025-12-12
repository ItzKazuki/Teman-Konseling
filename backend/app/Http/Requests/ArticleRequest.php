<?php

namespace App\Http\Requests;

use App\Enums\ArticleStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ArticleRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat request ini.
     * Dalam kasus aplikasi admin/panel, ini biasanya diatur ke true
     * setelah otorisasi di middleware.
     */
    public function authorize(): bool
    {
        return true; // Atau gunakan gate/policy di sini, e.g., $this->user()->can('manage-articles');
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // Mendapatkan ID artikel saat ini jika ini adalah request 'update' (PUT/PATCH)
        $articleId = $this->route('article');

        return [
            'article_category_id' => ['required', 'string', Rule::exists('article_categories', 'id')],

            'title' => ['required', 'string', 'max:255'],

            'excerpt' => ['nullable', 'string'],

            'thumbnail_file_id' => ['nullable', 'string', Rule::exists('files', 'id')],

            'content' => ['required', 'string'],

            'status' => ['required', 'string', new Enum(ArticleStatus::class)],

            'published_at' => ['nullable', 'date'],
        ];
    }

    /**
     * Kustomisasi pesan kesalahan validasi.
     */
    public function messages(): array
    {
        return [
            'author_id.exists' => 'ID penulis yang dipilih tidak valid.',
            'article_category_id.exists' => 'ID kategori yang dipilih tidak valid.',
            'slug.unique' => 'Slug artikel ini sudah digunakan.',
            'status.in' => 'Status yang dipilih tidak valid.',
        ];
    }
}
