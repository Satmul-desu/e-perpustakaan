<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // category_id harus ada di tabel categories kolom id
            'category_id'    => ['required', 'exists:categories,id'],

            'name'           => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],

            // Stok buku (opsional untuk library)
            'stock'          => ['nullable', 'integer', 'min:0'],

            'is_active'      => ['boolean'],

            // Validasi Array Gambar
            'images'         => ['nullable', 'array', 'max:10'],
            'images.*'       => [
                'image',
                'mimes:jpg,png,webp',
                'max:2048',
            ],
        ];
    }

    /**
     * Persiapkan data sebelum validasi dijalankan.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active'   => $this->boolean('is_active'),
        ]);
    }
}

