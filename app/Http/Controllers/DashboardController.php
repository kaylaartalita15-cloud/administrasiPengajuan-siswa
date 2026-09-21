<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Siswa;
use App\Models\JenisSurat;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isSiswa()) {
            $siswa = $user->siswa;
            $siswaId = $siswa ? $siswa->id : null;

            $totalPengajuan = $siswaId ? Pengajuan::where('siswa_id', $siswaId)->count() : 0;
            $pendingCount = $siswaId ? Pengajuan::where('siswa_id', $siswaId)->where('status', 'pending')->count() : 0;
            $disetujuiCount = $siswaId ? Pengajuan::where('siswa_id', $siswaId)->where('status', 'disetujui')->count() : 0;
            $ditolakCount = $siswaId ? Pengajuan::where('siswa_id', $siswaId)->where('status', 'ditolak')->count() : 0;
            
            $recentPengajuans = $siswaId ? Pengajuan::with(['jenisSurat'])
                ->where('siswa_id', $siswaId)
                ->latest()
                ->take(5)
                ->get() : collect();

            return view('dashboard.index', compact('totalPengajuan', 'pendingCount', 'disetujuiCount', 'ditolakCount', 'recentPengajuans'));
        }

        // Admin & Guru / Staff
        $totalPengajuan = Pengajuan::count();
        $pendingCount = Pengajuan::where('status', 'pending')->count();
        $disetujuiCount = Pengajuan::where('status', 'disetujui')->count();
        $ditolakCount = Pengajuan::where('status', 'ditolak')->count();
        $totalSiswa = Siswa::count();
        $totalJenisSurat = JenisSurat::count();
        $totalUser = User::count();

        $recentPengajuans = Pengajuan::with(['siswa', 'jenisSurat'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalPengajuan', 'pendingCount', 'disetujuiCount', 'ditolakCount',
            'totalSiswa', 'totalJenisSurat', 'totalUser', 'recentPengajuans'
        ));
    }
}
