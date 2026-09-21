@extends('layouts.app')

@section('title', 'Kelola Pengguna')
@section('page-title', 'Manajemen Pengguna & Role System')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Add Button -->
    <div class="bg-zinc-900 p-6 rounded-3xl border border-zinc-800 shadow-2xl flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-xl font-black text-white">Daftar Akun Pengguna</h3>
            <p class="text-xs text-orange-400 font-semibold mt-1">Kelola akun dan hak akses role (Admin, Guru/Staff, Siswa)</p>
        </div>

        <button onclick="toggleForm('formAddUser')" class="px-5 py-3 bg-orange-500 hover:bg-orange-600 text-zinc-950 font-black rounded-xl text-xs shadow-lg shadow-orange-500/20 transition-all flex items-center justify-center gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>+ Tambah Akun Pengguna</span>
        </button>
    </div>

    <!-- Full Width Form Card (Hidden by default) -->
    <div id="formAddUser" class="hidden bg-zinc-900 p-8 rounded-3xl border border-zinc-800 shadow-2xl space-y-6">
        <div class="border-b border-zinc-800 pb-4 flex items-center justify-between">
            <h4 class="text-lg font-black text-white">+ Tambah Akun Pengguna Baru</h4>
            <button onclick="toggleForm('formAddUser')" class="text-zinc-500 hover:text-white text-xs font-bold">✕ Tutup Form</button>
        </div>

        <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div>
                    <label class="block font-black uppercase text-zinc-300 mb-2">Nama Pengguna <span class="text-orange-500">*</span></label>
                    <input type="text" name="name" placeholder="Nama Lengkap" required class="w-full p-3.5 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-semibold focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block font-black uppercase text-zinc-300 mb-2">Email Login <span class="text-orange-500">*</span></label>
                    <input type="email" name="email" placeholder="email@sekolah.sch.id" required class="w-full p-3.5 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-semibold focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block font-black uppercase text-zinc-300 mb-2">Password Login <span class="text-orange-500">*</span></label>
                    <input type="password" name="password" placeholder="Minimal 6 Karakter" required class="w-full p-3.5 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-semibold focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block font-black uppercase text-zinc-300 mb-2">Role Hak Akses <span class="text-orange-500">*</span></label>
                    <select name="role" required class="w-full p-3.5 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-bold focus:ring-2 focus:ring-orange-500">
                        <option value="admin">Role: Admin</option>
                        <option value="guru">Role: Guru / Staff</option>
                        <option value="siswa">Role: Siswa</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-zinc-800">
                <button type="button" onclick="toggleForm('formAddUser')" class="px-5 py-2.5 bg-zinc-800 text-zinc-300 font-bold rounded-xl text-xs">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-zinc-950 font-black rounded-xl text-xs shadow-lg shadow-orange-500/20">Simpan Akun</button>
            </div>
        </form>
    </div>

    <!-- Table Users -->
    <div class="bg-zinc-900 rounded-3xl border border-zinc-800/80 shadow-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-zinc-950 border-b border-zinc-800 text-xs font-black text-orange-400 uppercase tracking-wider">
                        <th class="py-4 px-6">Nama Pengguna</th>
                        <th class="py-4 px-6">Email</th>
                        <th class="py-4 px-6">Role / Hak Akses</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/80 text-zinc-300">
                    @forelse($users as $user)
                        <tr class="hover:bg-zinc-800/50 transition-colors">
                            <td class="py-4 px-6 font-bold text-white text-xs">{{ $user->name }}</td>
                            <td class="py-4 px-6 text-xs text-zinc-400">{{ $user->email }}</td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                <span class="px-3 py-1 text-xs font-black rounded-full uppercase tracking-wider bg-orange-500/10 text-orange-400 border border-orange-500/30">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap space-x-1">
                                <button type="button" onclick="openEditUser('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ $user->role }}')" class="px-3 py-1.5 bg-zinc-800 text-zinc-200 hover:bg-zinc-700 rounded-xl text-xs font-bold transition-all border border-zinc-700 inline-flex items-center gap-1">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5 text-orange-400"></i> Edit
                                </button>

                                @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete(this)" class="px-3 py-1.5 bg-rose-500/10 text-rose-400 border border-rose-500/30 hover:bg-rose-500 hover:text-white rounded-xl text-xs font-black transition-all inline-flex items-center gap-1">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-zinc-500 italic font-semibold px-2">Akun Anda</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-zinc-500">Belum ada pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-zinc-800">
            {{ $users->links() }}
        </div>
    </div>

</div>

<!-- Modal Edit User -->
<div id="modalEditUser" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-8 w-full max-w-lg space-y-6 shadow-2xl">
        <div class="border-b border-zinc-800 pb-4 flex items-center justify-between">
            <h4 class="text-lg font-black text-white">Edit Akun Pengguna</h4>
            <button onclick="closeEditUser()" class="text-zinc-500 hover:text-white">✕</button>
        </div>

        <form id="formEditUser" method="POST" class="space-y-4 text-xs">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-black text-zinc-300 mb-1 uppercase">Nama Pengguna</label>
                <input type="text" id="edit_user_name" name="name" required class="w-full p-3 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-semibold">
            </div>
            <div>
                <label class="block font-black text-zinc-300 mb-1 uppercase">Email Login</label>
                <input type="email" id="edit_user_email" name="email" required class="w-full p-3 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-semibold">
            </div>
            <div>
                <label class="block font-black text-zinc-300 mb-1 uppercase">Role Hak Akses</label>
                <select id="edit_user_role" name="role" required class="w-full p-3 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-bold">
                    <option value="admin">Role: Admin</option>
                    <option value="guru">Role: Guru / Staff</option>
                    <option value="siswa">Role: Siswa</option>
                </select>
            </div>
            <div>
                <label class="block font-black text-zinc-300 mb-1 uppercase">Password Baru (Opsional, kosongkan jika tidak diubah)</label>
                <input type="password" name="password" placeholder="••••••••" class="w-full p-3 bg-zinc-950 border border-zinc-800 rounded-xl text-white font-semibold">
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-zinc-800">
                <button type="button" onclick="closeEditUser()" class="px-4 py-2 bg-zinc-800 text-zinc-300 font-bold rounded-xl text-xs">Batal</button>
                <button type="submit" class="px-5 py-2 bg-orange-500 hover:bg-orange-600 text-zinc-950 font-black rounded-xl text-xs shadow-lg">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleForm(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    function openEditUser(id, name, email, role) {
        document.getElementById('formEditUser').action = '/users/' + id;
        document.getElementById('edit_user_name').value = name;
        document.getElementById('edit_user_email').value = email;
        document.getElementById('edit_user_role').value = role;
        document.getElementById('modalEditUser').classList.remove('hidden');
    }

    function closeEditUser() {
        document.getElementById('modalEditUser').classList.add('hidden');
    }
</script>
@endsection
