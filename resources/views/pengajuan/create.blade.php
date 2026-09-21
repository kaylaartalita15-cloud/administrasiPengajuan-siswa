@extends('layouts.app')

@section('title', 'Buat Pengajuan Surat Baru')
@section('page-title', 'Form Permohonan Surat')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-zinc-900 p-8 rounded-3xl border border-zinc-800 shadow-2xl space-y-6">
        <div class="border-b border-zinc-800 pb-4">
            <h3 class="text-xl font-black text-white">Form Pengajuan Surat Administrasi</h3>
            <p class="text-xs text-orange-400 font-medium mt-1">Lengkapi data berikut untuk mengirimkan berkas pengajuan ke pihak sekolah</p>
        </div>

        <!-- Student Data Summary -->
        <div class="p-4 bg-zinc-950 rounded-2xl border border-zinc-800 flex items-center justify-between text-xs text-zinc-300">
            <div>
                <span class="font-extrabold block text-white text-sm">{{ $siswa->nama }}</span>
                <span class="text-zinc-400 font-medium">NIS: {{ $siswa->nis }} • Kelas: {{ $siswa->kelas }} {{ $siswa->jurusan }}</span>
            </div>
            <span class="px-3 py-1 bg-orange-500/20 text-orange-400 font-black rounded-lg uppercase tracking-wider text-[10px] border border-orange-500/30">Data Terverifikasi</span>
        </div>

        <form action="{{ route('pengajuan.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Jenis Surat Select -->
            <div>
                <label for="jenis_surat_id" class="block text-xs font-black uppercase tracking-wider text-zinc-300 mb-2">Pilih Jenis Surat <span class="text-orange-500">*</span></label>
                <select name="jenis_surat_id" id="jenis_surat_id" required class="w-full p-3.5 bg-zinc-950 border border-zinc-800 rounded-xl text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">-- Pilih Jenis Surat --</option>
                    @foreach($jenisSurats as $jenis)
                        <option value="{{ $jenis->id }}" {{ old('jenis_surat_id') == $jenis->id ? 'selected' : '' }}>
                            {{ $jenis->nama_surat }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_surat_id')
                    <p class="text-xs text-rose-400 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Pengajuan -->
            <div>
                <label for="tanggal" class="block text-xs font-black uppercase tracking-wider text-zinc-300 mb-2">Tanggal Pengajuan <span class="text-orange-500">*</span></label>
                <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                    class="w-full p-3.5 bg-zinc-950 border border-zinc-800 rounded-xl text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                @error('tanggal')
                    <p class="text-xs text-rose-400 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Keterangan -->
            <div>
                <label for="keterangan" class="block text-xs font-black uppercase tracking-wider text-zinc-300 mb-2">Keperluan / Keterangan Pengajuan <span class="text-orange-500">*</span></label>
                <textarea name="keterangan" id="keterangan" rows="4" required placeholder="Jelaskan secara detail alasan dan keperluan pembuatan surat ini..."
                    class="w-full p-3.5 bg-zinc-950 border border-zinc-800 rounded-xl text-sm font-semibold text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-orange-500">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="text-xs text-rose-400 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-zinc-800 flex items-center justify-end gap-3">
                <a href="{{ route('pengajuan.index') }}" class="px-5 py-2.5 bg-zinc-800 text-zinc-300 hover:bg-zinc-700 font-bold rounded-xl text-xs transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-zinc-950 font-black rounded-xl text-xs shadow-lg shadow-orange-500/20 transition-all flex items-center gap-2">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Kirim Pengajuan Surat</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
