@extends('layouts.app')

@section('title', 'Daftar Pengajuan Surat')
@section('page-title', auth()->user()->isSiswa() ? 'Pengajuan Surat Saya' : 'Kelola & Verifikasi Pengajuan Surat')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Search Toolbar -->
    <div class="bg-zinc-900 p-6 rounded-3xl border border-zinc-800 shadow-2xl space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-black text-white">Filter & Pencarian Data</h3>
                <p class="text-xs text-zinc-400 font-medium">Cari berdasarkan nama siswa, NIS, atau jenis pengajuan surat</p>
            </div>
            @if(auth()->user()->isSiswa())
                <a href="{{ route('pengajuan.create') }}" class="px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-zinc-950 font-black rounded-xl text-xs shadow-lg shadow-orange-500/20 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    <span>Ajukan Surat Baru</span>
                </a>
            @endif
        </div>

        <!-- Filter Form -->
        <form action="{{ route('pengajuan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-2 relative">
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Cari NIS, Nama Siswa, atau Keterangan..."
                    class="w-full pl-10 pr-4 py-2.5 bg-zinc-950 border border-zinc-800 rounded-xl text-xs font-semibold text-white placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-orange-500"
                    onkeyup="filterTableInstant()">
                <i data-lucide="search" class="w-4 h-4 text-zinc-500 absolute left-3.5 top-3"></i>
            </div>

            <div>
                <select name="status" id="statusSelect" onchange="filterTableInstant()" class="w-full py-2.5 px-3 bg-zinc-950 border border-zinc-800 rounded-xl text-xs font-semibold text-white focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <option value="">-- Semua Status --</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 py-2.5 bg-orange-500 text-zinc-950 hover:bg-orange-600 rounded-xl text-xs font-black transition-all flex items-center justify-center gap-1.5 shadow-md shadow-orange-500/10">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Terapkan</span>
                </button>
                @if(request()->hasAny(['search', 'status', 'jenis_surat_id']))
                    <a href="{{ route('pengajuan.index') }}" class="py-2.5 px-3 bg-zinc-800 text-zinc-400 hover:text-white hover:bg-zinc-700 rounded-xl text-xs font-bold transition-all border border-zinc-700">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-zinc-900 rounded-3xl border border-zinc-800/80 shadow-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-zinc-950 border-b border-zinc-800 text-xs font-black text-orange-400 uppercase tracking-wider">
                        <th class="py-4 px-6">ID / Tanggal</th>
                        @if(!auth()->user()->isSiswa())
                            <th class="py-4 px-6">Siswa</th>
                        @endif
                        <th class="py-4 px-6">Jenis Surat</th>
                        <th class="py-4 px-6">Keterangan / Keperluan</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/80 text-zinc-300">
                    @forelse($pengajuans as $item)
                        <tr class="hover:bg-zinc-800/50 transition-colors">
                            <td class="py-4 px-6">
                                <span class="font-black text-white text-xs">#SRT-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</span>
                                <span class="block text-[11px] text-zinc-400 mt-0.5">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                </span>
                            </td>
                            @if(!auth()->user()->isSiswa())
                                <td class="py-4 px-6">
                                    <div class="font-bold text-white">{{ $item->siswa->nama ?? '-' }}</div>
                                    <div class="text-[11px] text-zinc-400">NIS: {{ $item->siswa->nis ?? '-' }} • {{ $item->siswa->kelas ?? '' }} {{ $item->siswa->jurusan ?? '' }}</div>
                                </td>
                            @endif
                            <td class="py-4 px-6 font-bold text-orange-400">
                                {{ $item->jenisSurat->nama_surat ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-xs text-zinc-300 max-w-xs truncate font-medium">
                                {{ $item->keterangan }}
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                @if($item->status === 'pending')
                                    <span class="px-3 py-1 bg-amber-500/10 text-amber-400 border border-amber-500/30 rounded-full text-xs font-black inline-flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-amber-400"></span> Pending
                                    </span>
                                @elseif($item->status === 'disetujui')
                                    <span class="px-3 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 rounded-full text-xs font-black inline-flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Disetujui
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-rose-500/10 text-rose-400 border border-rose-500/30 rounded-full text-xs font-black inline-flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-rose-400"></span> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap space-x-1">
                                <a href="{{ route('pengajuan.show', $item->id) }}" class="px-3.5 py-1.5 bg-orange-500/10 text-orange-400 hover:bg-orange-500 hover:text-zinc-950 rounded-xl text-xs font-black transition-all border border-orange-500/30 inline-flex items-center gap-1">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i> Detail & Process
                                </a>

                                @if(auth()->user()->isAdmin() || (auth()->user()->isSiswa() && $item->status === 'pending'))
                                    <a href="{{ route('pengajuan.edit', $item->id) }}" class="px-3 py-1.5 bg-zinc-800 text-zinc-200 hover:bg-zinc-700 rounded-xl text-xs font-bold transition-all inline-flex items-center gap-1 border border-zinc-700">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5 text-orange-400"></i> Edit
                                    </a>

                                    <form action="{{ route('pengajuan.destroy', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete(this)" class="px-3 py-1.5 bg-rose-500/10 text-rose-400 border border-rose-500/30 hover:bg-rose-500 hover:text-white rounded-xl text-xs font-black transition-all inline-flex items-center gap-1">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-zinc-500">
                                <i data-lucide="inbox" class="w-10 h-10 mx-auto text-zinc-600 mb-2"></i>
                                <p class="font-bold text-sm">Tidak ada data pengajuan surat ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-zinc-800">
            {{ $pengajuans->links() }}
        </div>
    </div>

</div>

<script>
    function filterTableInstant() {
        const searchInput = document.getElementById('searchInput');
        const statusSelect = document.getElementById('statusSelect');
        
        const filterText = searchInput ? searchInput.value.toLowerCase() : '';
        const filterStatus = statusSelect ? statusSelect.value.toLowerCase() : '';

        const tbody = document.querySelector('tbody');
        if (!tbody) return;
        
        const rows = tbody.getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
            const row = rows[i];
            const rowText = (row.textContent || row.innerText).toLowerCase();
            
            // Check text search filter
            const matchesText = !filterText || rowText.indexOf(filterText) > -1;
            
            // Check status filter
            let matchesStatus = true;
            if (filterStatus) {
                // Check if row contains the selected status text
                matchesStatus = rowText.indexOf(filterStatus) > -1;
            }

            if (matchesText && matchesStatus) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        }
    }
</script>
@endsection
