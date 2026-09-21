<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusPengajuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isGuru() || auth()->user()->isAdmin());
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:disetujui,ditolak',
            'catatan' => 'required|string|min:3|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Pilih keputusan status pengajuan.',
            'status.in' => 'Status hanya boleh Disetujui atau Ditolak.',
            'catatan.required' => 'Berikan catatan/alasan keputusan pengajuan.',
        ];
    }
}
