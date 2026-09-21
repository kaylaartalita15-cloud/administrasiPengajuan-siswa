@extends('layouts.app')

@section('title', 'Kelola Jenis Surat')
@section('page-title', 'Master Jenis Surat Administrasi')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Add Button -->
    <div class="bg-zinc-900 p-6 rounded-3xl border border-zinc-800 shadow-2xl flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-black text-white">Master Jenis Surat</h3>
            <p class="text-xs text-orange-400 font-semibold mt-1">Jenis permohonan surat yang tersedia untuk diajukan oleh siswa</p>
        </div>

        <button onclick="toggleForm('formAddJenisSurat')" class="px-5 py-3 bg-orange-500 hover:bg-orange-600 text-zinc-950 font-black rounded-xl text-xs shadow-lg shadow-orange-500/20 transition-all flex items-center justify-center gap-2">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>+ Tambah Jenis Surat</span>
        </button>
    </div>

    <!-- Full Width Form Card (Hidden by default) -->
    <div id="formAddJenisSurat" class="hidden bg-zinc-900 p-8 rounded-3xl border border-zinc-800 shadow-2xl space-y-6">
        <div class="border-b border-zinc-800 pb-4 flex items-center justify-between">
            <h4 class="text-lg font-black text-white">+ Tambah Jenis Surat Permohonan Baru</h4>
            <button onclick="toggleForm('formAddJenisSurat')" class="text-zinc-500 hover:text-white text-xs font-bold">✕ Tutup Form</button>
        </div>

        <form action="{{ route('jenis-surat.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="nama_surat" class="block text-xs font-black uppercase tracking-wider text-zinc-300 mb-2">Nama Surat <span class="text-orange-500">*</span></label>
                <input type="text" name="nama_surat" id="nama_surat" required placeholder="contoh: Surat Keterangan Siswa Aktif"
                    class="w-full p-3.5 bg-zinc-950 border border-zinc-800 rounded-xl text-xs font-semibold text-white focus:ring-2 focus:ring-orange-500">
            </div>

            <div>
                <label for="keterangan" class="block text-xs font-black uppercase tracking-wider text-zinc-300 mb-2">Deskripsi / Keterangan Kegunaan Surat</label>
                <textarea name="keterangan" id="keterangan" rows="3" placeholder="Informasi kegunaan surat..."
                    class="w-full p-3.5 bg-zinc-950 border border-zinc-800 rounded-xl text-xs font-semibold text-white focus:ring-2 focus:ring-orange-500"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-zinc-800">
                <button type="button" onclick="toggleForm('formAddJenisSurat')" class="px-5 py-2.5 bg-zinc-800 text-zinc-300 font-bold rounded-xl text-xs">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-zinc-950 font-black rounded-xl text-xs shadow-lg shadow-orange-500/20">Simpan Jenis Surat</button>
            </div>
        </form>
    </div>

    <!-- Data Table Jenis Surat -->
    <div class="bg-zinc-900 rounded-3xl border border-zinc-800/80 shadow-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-zinc-950 border-b border-zinc-800 text-xs font-black text-orange-400 uppercase tracking-wider">
                        <th class="py-4 px-6">Nama Surat</th>
                        <th class="py-4 px-6">Keterangan</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/80 text-zinc-300">
                    @forelse($jenisSurats as $jenis)
                        <tr class="hover:bg-zinc-800/50 transition-colors">
                            <td class="py-4 px-6 font-bold text-white text-xs">{{ $jenis->nama_surat }}</td>
                            <td class="py-4 px-6 text-xs text-zinc-400 max-w-md">{{ $jenis->keterangan ?? '-' }}</td>
                            <td class="py-4 px-6 text-right whitespace-nowrap space-x-1">
                                <button type="button" onclick="openEditJenis('{{ $jenis->id }}', '{{ addslashes($jenis->nama_surat) }}', '{{ addslashes($jenis->keterangan ?? '') }}')" class="px-3 py-1.5 bg-zinc-800 text-zinc-200 hover:bg-zinc-700 rounded-xl text-xs font-bold transition-all border border-zinc-700 inline-flex items-center gap-1">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5 text-orange-400"></i> Edit
                                </button>

                                <form action="{{ route('jenis-surat.destroy', $jenis->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this)" class="px-3 py-1.5 bg-rose-500/10 text-rose-400 border border-rose-500/30 hover:bg-rose-500 hover:text-white rounded-xl text-xs font-black transition-all inline-flex items-center gap-1">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-zinc-500">Belum ada jenis surat ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-zinc-800">
            {{ $jenisSurats->links() }}
        </div>
    </div>

</div>

<!-- Modal Edit Jenis Surat -->
<div id="modalEditJenis" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-8 w-full max-w-lg space-y-6 shadow-2xl">
        <div class="border-b border-zinc-800 pb-4 flex items-center justify-between">
            <h4 class="text-lg font-black text-white">Edit Jenis Surat</h4>
            <button onclick="closeEditJenis()" class="text-zinc-500 hover:text-white">✕</button>
        </div>

        <form id="formEditJenis" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-black text-zinc-300 mb-1 uppercase">Nama Surat</label>
                <input type="text" id="edit_nama_surat" name="nama_surat" required class="w-full p-3 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-semibold">
            </div>
            <div>
                <label class="block font-black text-zinc-300 mb-1 uppercase">Deskripsi / Keterangan</label>
                <textarea id="edit_keterangan_surat" name="keterangan" rows="3" class="w-full p-3 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-semibold"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-zinc-800">
                <button type="button" onclick="closeEditJenis()" class="px-4 py-2 bg-zinc-800 text-zinc-300 font-bold rounded-xl text-xs">Batal</button>
                <button type="submit" class="px-5 py-2 bg-orange-500 hover:bg-orange-600 text-zinc-950 font-black rounded-xl text-xs shadow-lg">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleForm(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    function openEditJenis(id, nama, keterangan) {
        document.getElementById('formEditJenis').action = '/jenis-surat/' + id;
        document.getElementById('edit_nama_surat').value = nama;
        document.getElementById('edit_keterangan_surat').value = keterangan;
        document.getElementById('modalEditJenis').classList.remove('hidden');
    }

    function closeEditJenis() {
        document.getElementById('modalEditJenis').classList.add('hidden');
    }
</script>
@endsection
