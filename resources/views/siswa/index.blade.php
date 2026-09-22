@extends('layouts.app')

@section('title', 'Kelola Data Siswa')
@section('page-title', 'Manajemen Data Siswa')

@section('content')
<div class="space-y-6">

    <!-- Top Stats Cards for Siswa Management -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-zinc-900 p-6 rounded-3xl border border-zinc-800 shadow-xl flex items-center justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-wider text-zinc-400">Total Siswa Terdaftar</p>
                <h3 class="text-3xl font-black text-white mt-1">{{ $siswas->total() }}</h3>
                <p class="text-xs text-orange-400 font-semibold mt-1">Siswa aktif di sistem</p>
            </div>
            <div class="p-4 rounded-2xl bg-orange-500/10 text-orange-400 border border-orange-500/20">
                <i data-lucide="users" class="w-8 h-8"></i>
            </div>
        </div>

        <div class="bg-zinc-900 p-6 rounded-3xl border border-zinc-800 shadow-xl flex items-center justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-wider text-amber-400">Jurusan Terdaftar</p>
                <h3 class="text-3xl font-black text-amber-400 mt-1">{{ $totalJurusan }} Jurusan</h3>
                <p class="text-xs text-zinc-400 font-medium mt-1 truncate max-w-xs">{{ $jurusanList }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-amber-500/10 text-amber-400 border border-amber-500/20">
                <i data-lucide="graduation-cap" class="w-8 h-8"></i>
            </div>
        </div>

        <div class="bg-zinc-900 p-6 rounded-3xl border border-zinc-800 shadow-xl flex items-center justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-wider text-emerald-400">Kelas Terdaftar</p>
                <h3 class="text-3xl font-black text-emerald-400 mt-1">X-XI</h3>
                <p class="text-xs text-zinc-400 font-medium mt-1">Terverifikasi & terhubung ke Auth</p>
            </div>
            <div class="p-4 rounded-2xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                <i data-lucide="shield-check" class="w-8 h-8"></i>
            </div>
        </div>
    </div>

    <!-- Header Actions, Search & Add Button -->
    <div class="bg-zinc-900 p-6 rounded-3xl border border-zinc-800 shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('siswa.index') }}" method="GET" class="flex-1 flex items-center gap-3">
            <div class="relative flex-1">
                <input
    type="text"
    name="search"
    id="searchInputSiswa"
    value="{{ request('search') }}"
    placeholder="Cari NIS, Nama Siswa, Kelas, atau Jurusan..."
    class="w-full pl-10 pr-4 py-3 bg-zinc-950 border border-zinc-800 rounded-xl text-xs font-semibold text-white placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-orange-500"
>
            </div>
            <button type="submit" class="px-5 py-3 bg-zinc-800 hover:bg-zinc-700 text-zinc-200 font-bold rounded-xl text-xs transition-all border border-zinc-700 flex items-center gap-1.5">
                <i data-lucide="filter" class="w-4 h-4 text-orange-400"></i>
                <span>Cari</span>
            </button>
            @if(request('search'))
                <a href="{{ route('siswa.index') }}" class="px-4 py-3 bg-zinc-800/60 text-zinc-400 hover:text-white rounded-xl text-xs font-bold transition-all border border-zinc-700">
                    Reset
                </a>
            @endif
        </form>

        <button onclick="toggleForm('formAddSiswa')" class="px-5 py-3 bg-orange-500 hover:bg-orange-600 text-zinc-950 font-black rounded-xl text-xs shadow-lg shadow-orange-500/20 transition-all flex items-center justify-center gap-2 shrink-0">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>+ Tambah Siswa Baru</span>
        </button>
    </div>

    <!-- Full Width Form Card (Hidden by default, toggled with smooth animation) -->
    <div id="formAddSiswa" class="{{ $errors->any() ? '' : 'hidden' }} bg-zinc-900 p-8 rounded-3xl border border-zinc-800 shadow-2xl space-y-6">
        <div class="border-b border-zinc-800 pb-4 flex items-center justify-between">
            <div>
                <h4 class="text-lg font-black text-white">+ Tambah Data Siswa & Akun Login Baru</h4>
                <p class="text-xs text-zinc-400">Isi formulir lengkap di bawah ini untuk menambahkan siswa</p>
            </div>
            <button onclick="toggleForm('formAddSiswa')" class="text-zinc-500 hover:text-white text-xs font-bold">
                ✕ Tutup Form
            </button>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs space-y-1">
                <p class="font-black">❌ Gagal Menyimpan Data Siswa:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('siswa.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-xs">
                <div>
                    <label class="block font-black uppercase text-zinc-300 mb-2">NIS Siswa <span class="text-orange-500">*</span></label>
                    <input type="text" name="nis" value="{{ old('nis') }}" placeholder="Contoh: 20261103" required class="w-full p-3.5 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-semibold focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block font-black uppercase text-zinc-300 mb-2">Nama Lengkap <span class="text-orange-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama Lengkap Siswa" required class="w-full p-3.5 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-semibold focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block font-black uppercase text-zinc-300 mb-2">Kelas <span class="text-orange-500">*</span></label>
                    <input type="text" name="kelas" value="{{ old('kelas') }}" placeholder="Contoh: XI-3" required class="w-full p-3.5 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-semibold focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                <label class="block font-black uppercase text-zinc-300 mb-2">Jurusan <span class="text-orange-500">*</span></label>
                <select name="jurusan" required class="w-full p-3.5 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-semibold focus:ring-2 focus:ring-orange-500">
                    <option value="">Pilih Jurusan</option>
                    <option value="TKJ" {{ old('jurusan') == 'TKJ' ? 'selected' : '' }}>TKJ</option>
                    <option value="RPL" {{ old('jurusan') == 'RPL' ? 'selected' : '' }}>RPL</option>
                    <option value="DKV" {{ old('jurusan') == 'DKV' ? 'selected' : '' }}>DKV</option>
                </select>
                </div>
                <div>
                    <label class="block font-black uppercase text-zinc-300 mb-2">Email Akun Login <span class="text-orange-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Contoh: siswa@sekolah.sch.id" required class="w-full p-3.5 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-semibold focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block font-black uppercase text-zinc-300 mb-2">Password Login <span class="text-orange-500">*</span></label>
                    <input type="password" name="password" placeholder="Minimal 6 Karakter" required class="w-full p-3.5 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-semibold focus:ring-2 focus:ring-orange-500">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-zinc-800">
                <button type="button" onclick="toggleForm('formAddSiswa')" class="px-5 py-2.5 bg-zinc-800 text-zinc-300 hover:bg-zinc-700 font-bold rounded-xl text-xs">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-zinc-950 font-black rounded-xl text-xs shadow-lg shadow-orange-500/20">Simpan Data Siswa</button>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-zinc-900 rounded-3xl border border-zinc-800/80 shadow-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-zinc-950 border-b border-zinc-800 text-xs font-black text-orange-400 uppercase tracking-wider">
                        <th class="py-4 px-6">Siswa / NIS</th>
                        <th class="py-4 px-6">Kelas & Jurusan</th>
                        <th class="py-4 px-6">Email Akun</th>
                        <th class="py-4 px-6">Status Sistem</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/80 text-zinc-300">
                    @forelse($siswas as $siswa)
                        <tr class="hover:bg-zinc-800/50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-orange-500 to-amber-500 flex items-center justify-center font-black text-zinc-950 text-sm shadow-md shrink-0">
                                        {{ strtoupper(substr($siswa->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-white text-sm">{{ $siswa->nama }}</div>
                                        <div class="text-[11px] font-bold text-orange-400">NIS: {{ $siswa->nis }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-xs">
                                <span class="px-3 py-1 bg-zinc-950 rounded-lg font-extrabold text-amber-400 border border-zinc-800 shadow-sm inline-flex items-center gap-1.5">
                                    <i data-lucide="bookmark" class="w-3.5 h-3.5 text-orange-400"></i>
                                    {{ $siswa->kelas }} {{ $siswa->jurusan }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-xs text-zinc-300 font-medium">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="mail" class="w-3.5 h-3.5 text-zinc-500"></i>
                                    <span>{{ $siswa->user->email ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-xs whitespace-nowrap">
    <form action="{{ route('siswa.toggle-status', $siswa->id) }}" method="POST" class="inline">
        @csrf
        @method('PATCH')

        @if($siswa->status === 'aktif')
            <button
                type="submit"
                class="px-2.5 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 rounded-full text-[11px] font-black inline-flex items-center gap-1 hover:bg-emerald-500/20 transition-all"
                title="Klik untuk menonaktifkan siswa"
            >
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                Aktif
            </button>
        @else
            <button
                type="submit"
                class="px-2.5 py-1 bg-rose-500/10 text-rose-400 border border-rose-500/30 rounded-full text-[11px] font-black inline-flex items-center gap-1 hover:bg-rose-500/20 transition-all"
                title="Klik untuk mengaktifkan siswa"
            >
                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                Nonaktif
            </button>
        @endif
    </form>
</td>
                            <td class="py-4 px-6 text-right whitespace-nowrap space-x-1">
                                <button type="button" onclick="openEditSiswa('{{ $siswa->id }}', '{{ $siswa->nis }}', '{{ addslashes($siswa->nama) }}', '{{ addslashes($siswa->kelas) }}', '{{ addslashes($siswa->jurusan) }}', '{{ addslashes($siswa->user->email ?? '') }}')" class="px-3.5 py-2 bg-zinc-800 text-zinc-200 hover:bg-zinc-700 rounded-xl text-xs font-bold transition-all border border-zinc-700 inline-flex items-center gap-1.5 shadow-sm">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5 text-orange-400"></i> Edit
                                </button>

                                <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this)" class="px-3.5 py-2 bg-rose-500/10 text-rose-400 border border-rose-500/30 hover:bg-rose-500 hover:text-white rounded-xl text-xs font-black transition-all inline-flex items-center gap-1.5 shadow-sm">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-zinc-500">
                                <i data-lucide="users" class="w-10 h-10 mx-auto text-zinc-600 mb-2"></i>
                                <p class="font-bold text-sm">Belum ada data siswa ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-zinc-800">
            {{ $siswas->links() }}
        </div>
    </div>

</div>

<!-- Modal Edit Siswa -->
<div id="modalEditSiswa" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-8 w-full max-w-2xl space-y-6 shadow-2xl">
        <div class="border-b border-zinc-800 pb-4 flex items-center justify-between">
            <h4 class="text-lg font-black text-white">Edit Data Siswa</h4>
            <button onclick="closeEditSiswa()" class="text-zinc-500 hover:text-white">✕</button>
        </div>

        <form id="formEditSiswa" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-black text-zinc-300 mb-1 uppercase">NIS</label>
                    <input type="text" id="edit_nis" name="nis" required class="w-full p-3 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-semibold">
                </div>
                <div>
                    <label class="block font-black text-zinc-300 mb-1 uppercase">Nama</label>
                    <input type="text" id="edit_nama" name="nama" required class="w-full p-3 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-semibold">
                </div>
                <div>
                    <label class="block font-black text-zinc-300 mb-1 uppercase">Kelas</label>
                    <input type="text" id="edit_kelas" name="kelas" required class="w-full p-3 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-semibold">
                </div>
                <div>
                <label class="block font-black text-zinc-300 mb-1 uppercase">Jurusan</label>
                <select id="edit_jurusan" name="jurusan" required class="w-full p-3 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-semibold">
                    <option value="">Pilih Jurusan</option>
                    <option value="TKJ">TKJ</option>
                    <option value="RPL">RPL</option>
                    <option value="DKV">DKV</option>
                </select>
                </div>
                <div class="col-span-2">
                    <label class="block font-black text-zinc-300 mb-1 uppercase">Email Akun Login</label>
                    <input type="email" id="edit_email" name="email" required class="w-full p-3 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-semibold">
                </div>
                <div class="col-span-2">
                    <label class="block font-black text-zinc-300 mb-1 uppercase">Password Baru (Opsional, kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" placeholder="••••••••" class="w-full p-3 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-semibold">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-zinc-800">
                <button type="button" onclick="closeEditSiswa()" class="px-4 py-2 bg-zinc-800 text-zinc-300 font-bold rounded-xl text-xs">Batal</button>
                <button type="submit" class="px-5 py-2 bg-orange-500 hover:bg-orange-600 text-zinc-950 font-black rounded-xl text-xs shadow-lg">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
<script>
    function toggleForm(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    function openEditSiswa(id, nis, nama, kelas, jurusan, email) {
        document.getElementById('formEditSiswa').action = '/siswa/' + id;
        document.getElementById('edit_nis').value = nis;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_kelas').value = kelas;
        document.getElementById('edit_jurusan').value = jurusan;
        document.getElementById('edit_email').value = email;
        document.getElementById('modalEditSiswa').classList.remove('hidden');
    }

    function closeEditSiswa() {
        document.getElementById('modalEditSiswa').classList.add('hidden');
    }

    // Search otomatis ke Laravel
    const searchInput = document.getElementById('searchInputSiswa');

    if (searchInput) {
        let searchTimer;

        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimer);

            searchTimer = setTimeout(() => {
                this.form.submit();
            }, 400);
        });
    }
</script>
@endsection
