<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJenisSuratRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'nama_surat' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ];
    }
}
