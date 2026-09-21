<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $siswaId = $this->route('siswa') ? $this->route('siswa')->id : null;

        return [
            'nis' => 'required|string|max:20|unique:siswas,nis,' . $siswaId,
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'jurusan' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users,email,' . ($this->route('siswa') ? $this->route('siswa')->user_id : 'NULL'),
            'password' => $siswaId ? 'nullable|min:6' : 'required|min:6',
        ];
    }
}
