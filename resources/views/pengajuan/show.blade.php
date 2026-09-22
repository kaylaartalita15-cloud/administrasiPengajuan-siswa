@extends('layouts.app')

@section('title', 'Detail Pengajuan Surat')
@section('page-title', 'Detail & Log Riwayat Administrasi')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">

    <!-- Top Card Detail -->
    <div class="bg-zinc-900 p-8 rounded-3xl border border-zinc-800 shadow-2xl space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-zinc-800 pb-6">
            <div>
                <span class="text-xs font-black text-orange-400 uppercase tracking-wider">No. Registrasi</span>
                <h2 class="text-2xl font-black text-white">#SRT-{{ str_pad($pengajuan->id, 4, '0', STR_PAD_LEFT) }}</h2>
                <p class="text-xs text-zinc-400 mt-1 font-medium">Diajukan pada tanggal {{ \Carbon\Carbon::parse($pengajuan->tanggal)->format('d F Y') }}</p>
            </div>
            <div>
                @if($pengajuan->status === 'pending')
                    <span class="px-4 py-2 bg-amber-500/10 text-amber-400 border border-amber-500/30 rounded-full text-sm font-black inline-flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse"></span> Pending (Menunggu Verifikasi)
                    </span>
                @elseif($pengajuan->status === 'disetujui')
                    <span class="px-4 py-2 bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 rounded-full text-sm font-black inline-flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span> Pengajuan Disetujui
                    </span>
                @else
                    <span class="px-4 py-2 bg-rose-500/10 text-rose-400 border border-rose-500/30 rounded-full text-sm font-black inline-flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span> Pengajuan Ditolak
                    </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div class="bg-zinc-950 p-5 rounded-2xl border border-zinc-800 space-y-2">
                <span class="text-xs font-black uppercase tracking-wider text-orange-400 block">Informasi Siswa</span>
                <h4 class="font-black text-white text-base">{{ $pengajuan->siswa->nama ?? 'Siswa N/A' }}</h4>
                <div class="text-xs text-zinc-300 space-y-1 font-medium">
                    <p><strong class="text-zinc-400">NIS:</strong> {{ $pengajuan->siswa->nis ?? '-' }}</p>
                    <p><strong class="text-zinc-400">Kelas / Jurusan:</strong> {{ $pengajuan->siswa->kelas ?? '-' }} {{ $pengajuan->siswa->jurusan ?? '' }}</p>
                    <p><strong class="text-zinc-400">Email Akun:</strong> {{ $pengajuan->siswa->user->email ?? '-' }}</p>
                </div>
            </div>

            <div class="bg-zinc-950 p-5 rounded-2xl border border-zinc-800 space-y-2">
                <span class="text-xs font-black uppercase tracking-wider text-orange-400 block">Jenis Surat Permohonan</span>
                <h4 class="font-black text-orange-400 text-base">{{ $pengajuan->jenisSurat->nama_surat ?? '-' }}</h4>
                <p class="text-xs text-zinc-400 leading-relaxed font-medium">{{ $pengajuan->jenisSurat->keterangan ?? '-' }}</p>
            </div>
        </div>

        <div class="bg-zinc-950 p-5 rounded-2xl border border-zinc-800">
            <span class="text-xs font-black uppercase tracking-wider text-orange-400 block mb-2">Keterangan / Keperluan Lengkap</span>
            <p class="text-sm text-zinc-200 leading-relaxed font-semibold bg-zinc-900 p-4 rounded-xl border border-zinc-800">
                "{{ $pengajuan->keterangan }}"
            </p>
        </div>

        <!-- Action Box for Guru / Staff / Admin (Only shown for Pending Status) -->
        @if((auth()->user()->isGuru() || auth()->user()->isAdmin()) && $pengajuan->status === 'pending')
            <div class="p-6 gradient-orange text-white rounded-3xl space-y-4 shadow-xl orange-glow">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-black/30 rounded-xl border border-orange-400/30">
                        <i data-lucide="shield-alert" class="w-6 h-6 text-white"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-base">Proses Keputusan Verifikasi Administrasi</h4>
                        <p class="text-xs text-orange-100 font-medium">Sebagai Staff/Guru/Admin, tentukan keputusan permohonan surat ini (Hanya dapat diproses 1 kali)</p>
                    </div>
                </div>

                <form action="{{ route('pengajuan.update-status', $pengajuan->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-2 gap-3">
                        <label class="p-3 bg-black/40 border border-orange-400/30 rounded-xl cursor-pointer flex items-center gap-2 hover:bg-black/60 transition-all">
                            <input type="radio" name="status" value="disetujui" required class="text-orange-500 focus:ring-0">
                            <span class="text-xs font-black text-white">Setujui Pengajuan</span>
                        </label>
                        <label class="p-3 bg-black/40 border border-orange-400/30 rounded-xl cursor-pointer flex items-center gap-2 hover:bg-black/60 transition-all">
                            <input type="radio" name="status" value="ditolak" required class="text-orange-500 focus:ring-0">
                            <span class="text-xs font-black text-white">Tolak Pengajuan</span>
                        </label>
                    </div>

                    <div>
                        <label for="catatan" class="block text-xs font-black text-white uppercase tracking-wider mb-1">Catatan / Alasan Keputusan <span class="text-white">*</span></label>
                        <textarea name="catatan" id="catatan" rows="2" required placeholder="Tuliskan catatan verifikasi (contoh: Berkas lengkap dan ditandatangani / Berkas tidak valid)..."
                            class="w-full p-3 bg-black/40 border border-orange-400/30 text-white placeholder-orange-200/70 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-orange-400"></textarea>
                    </div>

                    <button type="button" onclick="confirmProcess(this)" class="w-full py-3 bg-zinc-950 hover:bg-zinc-900 text-orange-400 font-black rounded-xl text-xs shadow-lg transition-all border border-orange-500/40 flex items-center justify-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-orange-500"></i>
                        <span>Simpan Keputusan Administrasi</span>
                    </button>
                </form>
            </div>
        @elseif(auth()->user()->isGuru() || auth()->user()->isAdmin())
            <div class="p-5 bg-zinc-950 rounded-2xl border border-zinc-800 flex items-center justify-between text-xs text-zinc-400">
                <div class="flex items-center gap-2.5">
                    <i data-lucide="lock" class="w-4 h-4 text-orange-400"></i>
                    <span class="font-semibold">Keputusan permohonan ini telah final (<strong>{{ ucfirst($pengajuan->status) }}</strong>) dan tidak dapat diubah lagi.</span>
                </div>
                <span class="px-3 py-1 bg-zinc-900 rounded-lg border border-zinc-800 text-[10px] font-black uppercase text-zinc-500">Permanen</span>
            </div>
        @endif
    </div>

    <script>
        function confirmProcess(button) {
            const form = button.closest('form');
            const statusRadio = form.querySelector('input[name="status"]:checked');
            const catatan = form.querySelector('textarea[name="catatan"]').value.trim();

            if (!statusRadio) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Keputusan!',
                    text: 'Pilih salah satu keputusan (Setujui / Tolak) terlebih dahulu.',
                    confirmButtonText: 'Mengerti'
                });
                return;
            }

            if (!catatan) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Isi Catatan Keputusan!',
                    text: 'Catatan / Alasan keputusan wajib diisi.',
                    confirmButtonText: 'Mengerti'
                });
                return;
            }

            const statusText = statusRadio.value === 'disetujui' ? 'Menyetujui' : 'Menolak';

            Swal.fire({
                title: 'Konfirmasi Keputusan?',
                text: `Apakah Anda yakin ingin ${statusText} pengajuan surat ini?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan Keputusan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>

    <!-- Riwayat Audit Timeline -->
    <div class="bg-zinc-900 p-8 rounded-3xl border border-zinc-800 shadow-2xl space-y-6">
        <div class="border-b border-zinc-800 pb-4">
            <h3 class="text-lg font-black text-white flex items-center gap-2">
                <i data-lucide="history" class="w-5 h-5 text-orange-400"></i>
                <span>Riwayat & Timeline Perubahan Status</span>
            </h3>
            <p class="text-xs text-zinc-400 mt-1 font-medium">Audit log proses persetujuan administrasi secara kronologis</p>
        </div>

        <div class="space-y-6 relative before:absolute before:inset-0 before:left-3.5 before:w-0.5 before:bg-zinc-800 pl-8">
            @foreach($pengajuan->riwayats as $riwayat)
                <div class="relative">
                    <div class="absolute -left-8 top-1.5 w-3.5 h-3.5 rounded-full border-2 border-zinc-900
                        {{ $riwayat->status === 'pending' ? 'bg-amber-400 ring-4 ring-amber-500/20' : '' }}
                        {{ $riwayat->status === 'disetujui' ? 'bg-emerald-400 ring-4 ring-emerald-500/20' : '' }}
                        {{ $riwayat->status === 'ditolak' ? 'bg-rose-400 ring-4 ring-rose-500/20' : '' }}
                    "></div>

                    <div class="bg-zinc-950 p-4 rounded-2xl border border-zinc-800 space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-black uppercase tracking-wider
                                {{ $riwayat->status === 'pending' ? 'text-amber-400' : '' }}
                                {{ $riwayat->status === 'disetujui' ? 'text-emerald-400' : '' }}
                                {{ $riwayat->status === 'ditolak' ? 'text-rose-400' : '' }}
                            ">
                                Status: {{ $riwayat->status }}
                            </span>
                            <span class="text-zinc-500 font-semibold">{{ \Carbon\Carbon::parse($riwayat->created_at)->format('d M Y H:i') }}</span>
                        </div>
                        <p class="text-xs text-zinc-200 font-semibold">"{{ $riwayat->catatan }}"</p>
                        <div class="text-[11px] text-zinc-400 pt-1 font-medium">
                            Oleh: <strong class="text-orange-400 font-bold">{{ $riwayat->user->name ?? 'Sistem' }}</strong> ({{ $riwayat->user->role ?? 'System' }})
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
