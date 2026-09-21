<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengajuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isSiswa();
    }

    public function rules(): array
    {
        return [
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|min:5|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'jenis_surat_id.required' => 'Pilih jenis surat yang akan diajukan.',
            'jenis_surat_id.exists' => 'Jenis surat tidak valid.',
            'tanggal.required' => 'Tanggal pengajuan wajib diisi.',
            'keterangan.required' => 'Keterangan atau keperluan pengajuan surat wajib diisi.',
            'keterangan.min' => 'Keterangan minimal 5 karakter.',
        ];
    }
}
