@extends('layouts.app')

@section('title', 'Dashboard Utama')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-8">

    <!-- Hero Orange & Black Banner -->
    <div class="gradient-orange rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden orange-glow">
        <div class="absolute -right-10 -bottom-10 opacity-20 text-black">
            <i data-lucide="zap" class="w-72 h-72"></i>
        </div>
        <div class="relative z-10 max-w-2xl">
            <span class="px-3 py-1 bg-black/40 backdrop-blur-md text-xs font-black rounded-full uppercase tracking-wider text-orange-200 border border-orange-400/30">
                Sistem Informasi Administrasi Sekolah
            </span>
            <h1 class="text-3xl font-black mt-3 tracking-tight text-white drop-shadow-md">Selamat Datang, {{ auth()->user()->name }}!</h1>
            <p class="text-orange-100 text-sm mt-2 leading-relaxed font-medium">
                Anda masuk sebagai <strong class="text-orange-300 uppercase tracking-widest px-3 py-1 bg-black/50 border border-orange-400/40 font-black rounded-lg backdrop-blur-md shadow-inner">{{ auth()->user()->role }}</strong>.
                Kelola permohonan surat siswa secara efisien, modern, cepat, dan transparan.
            </p>
            @if(auth()->user()->isSiswa())
                <div class="mt-6 flex items-center gap-3">
                    <a href="{{ route('pengajuan.create') }}" class="px-6 py-3 bg-zinc-950 hover:bg-zinc-900 text-orange-400 font-extrabold rounded-xl text-sm shadow-xl transition-all flex items-center gap-2 border border-orange-500/40 hover:scale-105">
                        <i data-lucide="plus-circle" class="w-5 h-5 text-orange-500"></i>
                        <span>Ajukan Surat Baru Sekarang</span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Stats Grid (Vibrant Orange & Sleek Dark Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card Total -->
        <div class="bg-zinc-900 p-6 rounded-2xl border border-zinc-800 shadow-xl flex items-center justify-between hover:border-orange-500/50 transition-all">
            <div>
                <p class="text-xs font-black uppercase tracking-wider text-zinc-400">Total Pengajuan</p>
                <h3 class="text-3xl font-black text-white mt-1">{{ $totalPengajuan }}</h3>
                <p class="text-xs text-orange-400 font-semibold mt-1">Seluruh berkas pengajuan</p>
            </div>
            <div class="p-4 rounded-2xl bg-orange-500/10 text-orange-500 border border-orange-500/20">
                <i data-lucide="file-text" class="w-8 h-8"></i>
            </div>
        </div>

        <!-- Card Pending -->
        <div class="bg-zinc-900 p-6 rounded-2xl border border-zinc-800 shadow-xl flex items-center justify-between hover:border-amber-500/50 transition-all">
            <div>
                <p class="text-xs font-black uppercase tracking-wider text-amber-400">Menunggu (Pending)</p>
                <h3 class="text-3xl font-black text-amber-400 mt-1">{{ $pendingCount }}</h3>
                <p class="text-xs text-zinc-400 font-medium mt-1">Perlu tindakan verifikasi</p>
            </div>
            <div class="p-4 rounded-2xl bg-amber-500/10 text-amber-400 border border-amber-500/20">
                <i data-lucide="clock" class="w-8 h-8"></i>
            </div>
        </div>

        <!-- Card Disetujui -->
        <div class="bg-zinc-900 p-6 rounded-2xl border border-zinc-800 shadow-xl flex items-center justify-between hover:border-emerald-500/50 transition-all">
            <div>
                <p class="text-xs font-black uppercase tracking-wider text-emerald-400">Disetujui</p>
                <h3 class="text-3xl font-black text-emerald-400 mt-1">{{ $disetujuiCount }}</h3>
                <p class="text-xs text-zinc-400 font-medium mt-1">Selesai diverifikasi</p>
            </div>
            <div class="p-4 rounded-2xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                <i data-lucide="check-circle-2" class="w-8 h-8"></i>
            </div>
        </div>

        <!-- Card Ditolak -->
        <div class="bg-zinc-900 p-6 rounded-2xl border border-zinc-800 shadow-xl flex items-center justify-between hover:border-rose-500/50 transition-all">
            <div>
                <p class="text-xs font-black uppercase tracking-wider text-rose-400">Ditolak</p>
                <h3 class="text-3xl font-black text-rose-400 mt-1">{{ $ditolakCount }}</h3>
                <p class="text-xs text-zinc-400 font-medium mt-1">Tidak memenuhi syarat</p>
            </div>
            <div class="p-4 rounded-2xl bg-rose-500/10 text-rose-400 border border-rose-500/20">
                <i data-lucide="x-circle" class="w-8 h-8"></i>
            </div>
        </div>
    </div>

    <!-- Dark Table Section -->
    <div class="bg-zinc-900 rounded-3xl border border-zinc-800/80 shadow-2xl overflow-hidden">
        <div class="p-6 border-b border-zinc-800/80 flex items-center justify-between bg-zinc-950/60">
            <div>
                <h3 class="font-black text-lg text-white">Pengajuan Terbaru</h3>
                <p class="text-xs text-zinc-400">Daftar transaksi permohonan administrasi surat terbaru</p>
            </div>
            <a href="{{ route('pengajuan.index') }}" class="text-xs font-bold text-orange-400 hover:text-orange-300 flex items-center gap-1 bg-orange-500/10 px-3.5 py-2 rounded-xl border border-orange-500/20 transition-all">
                <span>Lihat Semua Data</span>
                <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-zinc-950 border-b border-zinc-800 text-xs font-black text-orange-400/90 uppercase tracking-wider">
                        <th class="py-4 px-6">Tanggal</th>
                        @if(!auth()->user()->isSiswa())
                            <th class="py-4 px-6">Siswa</th>
                        @endif
                        <th class="py-4 px-6">Jenis Surat</th>
                        <th class="py-4 px-6">Keterangan</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/80 text-zinc-300">
                    @forelse($recentPengajuans as $item)
                        <tr class="hover:bg-zinc-800/50 transition-colors">
                            <td class="py-4 px-6 text-xs text-zinc-400 whitespace-nowrap font-medium">
                                {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d M Y') : '-' }}
                            </td>
                            @if(!auth()->user()->isSiswa())
                                <td class="py-4 px-6 font-bold text-white">
                                    {{ $item->siswa ? $item->siswa->nama : 'Siswa N/A' }}
                                    <span class="block text-[11px] font-normal text-zinc-400">NIS: {{ $item->siswa->nis ?? '-' }}</span>
                                </td>
                            @endif
                            <td class="py-4 px-6 font-semibold text-orange-400">
                                {{ $item->jenisSurat ? $item->jenisSurat->nama_surat : '-' }}
                            </td>
                            <td class="py-4 px-6 text-xs text-zinc-300 max-w-xs truncate">
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
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <a href="{{ route('pengajuan.show', $item->id) }}" class="px-3.5 py-1.5 bg-orange-500/10 text-orange-400 hover:bg-orange-500 hover:text-zinc-950 rounded-xl text-xs font-black transition-all border border-orange-500/30 inline-flex items-center gap-1">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-zinc-500 text-sm font-medium">
                                Belum ada data pengajuan surat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
