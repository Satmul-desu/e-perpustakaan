<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(\+62|62|0)8[1-9][0-9]{6,10}$/',
            ],
            'address' => [
                'nullable',
                'string',
                'max:500',
            ],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Format nomor telepon tidak valid. Gunakan format 08xx atau +628xx.',
            'avatar.dimensions' => 'Dimensi foto harus antara 100x100 hingga 4000x4000 pixel.',
            'avatar.uploaded' => 'Gagal mengunggah foto. Hal ini mungkin karena file terlalu besar bagi sistem server.',
            'email.unique' => 'Email ini sudah digunakan oleh pengguna lain.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'email' => 'alamat email',
            'phone' => 'nomor telepon',
            'address' => 'alamat domisili',
            'avatar' => 'foto profil',
        ];
    }
}
