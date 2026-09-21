<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Http\Requests\StoreJenisSuratRequest;
use Illuminate\Http\Request;

class JenisSuratController extends Controller
{
    public function index()
    {
        $jenisSurats = JenisSurat::latest()->paginate(10);
        return view('jenis_surat.index', compact('jenisSurats'));
    }

    public function store(StoreJenisSuratRequest $request)
    {
        JenisSurat::create($request->validated());
        return redirect()->route('jenis-surat.index')->with('success', 'Jenis surat berhasil ditambahkan.');
    }

    public function update(StoreJenisSuratRequest $request, JenisSurat $jenisSurat)
    {
        $jenisSurat->update($request->validated());
        return redirect()->route('jenis-surat.index')->with('success', 'Jenis surat berhasil diperbarui.');
    }

    public function destroy(JenisSurat $jenisSurat)
    {
        $jenisSurat->delete();
        return redirect()->route('jenis-surat.index')->with('success', 'Jenis surat berhasil dihapus.');
    }
}
