<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\JenisSurat;
use App\Models\RiwayatPengajuan;
use App\Http\Requests\StorePengajuanRequest;
use App\Http\Requests\UpdateStatusPengajuanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Pengajuan::with(['siswa', 'jenisSurat', 'riwayats.user']);

        // Restrictions for Siswa
        if ($user->isSiswa()) {
            $siswaId = $user->siswa ? $user->siswa->id : 0;
            $query->where('siswa_id', $siswaId);
        }

        // Search Filter (Keterangan, Nama Siswa, NIS, Nama Surat)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('keterangan', 'like', "%{$search}%")
                  ->orWhereHas('siswa', function ($qSiswa) use ($search) {
                      $qSiswa->where('nama', 'like', "%{$search}%")
                             ->orWhere('nis', 'like', "%{$search}%");
                  })
                  ->orWhereHas('jenisSurat', function ($qSurat) use ($search) {
                      $qSurat->where('nama_surat', 'like', "%{$search}%");
                  });
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Jenis Surat Filter
        if ($request->filled('jenis_surat_id')) {
            $query->where('jenis_surat_id', $request->input('jenis_surat_id'));
        }

        $pengajuans = $query->latest()->paginate(10)->withQueryString();
        $jenisSurats = JenisSurat::where('is_active', true)->get();

        return view('pengajuan.index', compact('pengajuans', 'jenisSurats'));
    }

    public function create()
    {
        if (! auth()->user()->isSiswa()) {
            return redirect()->route('pengajuan.index')->with('error', 'Hanya siswa yang dapat membuat pengajuan.');
        }

        $siswa = auth()->user()->siswa;
        if (! $siswa) {
            return redirect()->route('dashboard')->with('error', 'Data siswa belum terhubung dengan akun Anda.');
        }

        $jenisSurats = JenisSurat::where('is_active', true)->get();
        return view('pengajuan.create', compact('jenisSurats', 'siswa'));
    }

    public function store(StorePengajuanRequest $request)
    {
        $siswa = auth()->user()->siswa;

        DB::transaction(function () use ($request, $siswa) {
            $pengajuan = Pengajuan::create([
                'siswa_id' => $siswa->id,
                'jenis_surat_id' => $request->jenis_surat_id,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'status' => 'pending',
            ]);

            RiwayatPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => auth()->id(),
                'status' => 'pending',
                'catatan' => 'Pengajuan surat baru dibuat oleh siswa.',
            ]);
        });

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan surat berhasil dikirim.');
    }

    public function show(Pengajuan $pengajuan)
    {
        $user = auth()->user();
        if ($user->isSiswa() && ($pengajuan->siswa_id !== optional($user->siswa)->id)) {
            abort(403, 'Anda tidak berhak melihat pengajuan ini.');
        }

        $pengajuan->load(['siswa', 'jenisSurat', 'riwayats.user']);

        return view('pengajuan.show', compact('pengajuan'));
    }

    public function edit(Pengajuan $pengajuan)
    {
        $user = auth()->user();

        // Siswa can edit only if status is pending & owned
        if ($user->isSiswa() && ($pengajuan->siswa_id !== optional($user->siswa)->id || $pengajuan->status !== 'pending')) {
            return redirect()->route('pengajuan.index')->with('error', 'Pengajuan ini tidak dapat diedit lagi.');
        }

        $jenisSurats = JenisSurat::where('is_active', true)->get();
        $siswa = $pengajuan->siswa;

        return view('pengajuan.edit', compact('pengajuan', 'jenisSurats', 'siswa'));
    }

    public function update(Request $request, Pengajuan $pengajuan)
    {
        $user = auth()->user();

        if ($user->isSiswa() && ($pengajuan->siswa_id !== optional($user->siswa)->id || $pengajuan->status !== 'pending')) {
            return redirect()->route('pengajuan.index')->with('error', 'Pengajuan ini tidak dapat diedit lagi.');
        }

        $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|min:5|max:1000',
        ]);

        DB::transaction(function () use ($request, $pengajuan) {
            $pengajuan->update([
                'jenis_surat_id' => $request->jenis_surat_id,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
            ]);

            RiwayatPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => auth()->id(),
                'status' => $pengajuan->status,
                'catatan' => 'Pengajuan surat diperbarui oleh pengaju.',
            ]);
        });

        return redirect()->route('pengajuan.show', $pengajuan->id)->with('success', 'Pengajuan surat berhasil diperbarui.');
    }

    public function destroy(Pengajuan $pengajuan)
    {
        $user = auth()->user();

        if ($user->isSiswa() && ($pengajuan->siswa_id !== optional($user->siswa)->id || $pengajuan->status !== 'pending')) {
            return redirect()->route('pengajuan.index')->with('error', 'Pengajuan yang sudah diproses tidak dapat dihapus.');
        }

        $pengajuan->delete();

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan surat berhasil dihapus.');
    }

    public function updateStatus(UpdateStatusPengajuanRequest $request, Pengajuan $pengajuan)
    {
        if ($pengajuan->status !== 'pending') {
            return redirect()->route('pengajuan.show', $pengajuan->id)
                ->with('error', 'Status pengajuan ini sudah diproses dan tidak dapat diubah lagi.');
        }

        DB::transaction(function () use ($request, $pengajuan) {
            $pengajuan->update([
                'status' => $request->status,
            ]);

            RiwayatPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => auth()->id(),
                'status' => $request->status,
                'catatan' => $request->catatan,
            ]);
        });

        return redirect()->route('pengajuan.show', $pengajuan->id)
            ->with('success', 'Status pengajuan berhasil diperbarui menjadi ' . ucfirst($request->status));
    }
}
