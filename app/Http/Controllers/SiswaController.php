<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSiswaRequest;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    /**
     * Menampilkan daftar siswa.
     */
    public function index(Request $request)
    {
        $query = Siswa::with('user');

        // SEARCH SISWA
        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%")
                    ->orWhere('jurusan', 'like', "%{$search}%");
            });
        }

        // DATA SISWA
        $siswas = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // TOTAL JURUSAN
        $totalJurusan = Siswa::distinct('jurusan')
            ->count('jurusan');

        // DAFTAR JURUSAN
        $jurusanList = Siswa::select('jurusan')
            ->distinct()
            ->pluck('jurusan')
            ->implode(', ');

        return view('siswa.index', compact(
            'siswas',
            'totalJurusan',
            'jurusanList'
        ));
    }

    /**
     * Form tambah siswa.
     */
    public function create()
    {
        return view('siswa.create');
    }

    /**
     * Menyimpan siswa baru.
     */
    public function store(StoreSiswaRequest $request)
    {
        DB::transaction(function () use ($request) {

            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'siswa',
            ]);

            Siswa::create([
                'user_id' => $user->id,
                'nis' => $request->nis,
                'nama' => $request->nama,
                'kelas' => $request->kelas,
                'jurusan' => $request->jurusan,
                'status' => 'aktif',
            ]);
        });

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Form edit siswa.
     */
    public function edit(Siswa $siswa)
    {
        $siswa->load('user');

        return view('siswa.edit', compact('siswa'));
    }

    /**
     * Update data siswa.
     */
    public function update(StoreSiswaRequest $request, Siswa $siswa)
    {
        DB::transaction(function () use ($request, $siswa) {

            $userData = [
                'name' => $request->nama,
                'email' => $request->email,
            ];

            // Kalau password diisi, update password.
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            // Update data user.
            if ($siswa->user) {
                $siswa->user->update($userData);
            }

            // Update data siswa.
            $siswa->update([
                'nis' => $request->nis,
                'nama' => $request->nama,
                'kelas' => $request->kelas,
                'jurusan' => $request->jurusan,
            ]);
        });

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Mengubah status siswa:
     * aktif <-> nonaktif
     */
    public function toggleStatus(Siswa $siswa)
    {
        $siswa->status = $siswa->status === 'aktif'
            ? 'nonaktif'
            : 'aktif';

        $siswa->save();

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Status siswa berhasil diubah.');
    }

    /**
     * Hapus siswa.
     */
    public function destroy(Siswa $siswa)
    {
        DB::transaction(function () use ($siswa) {

            if ($siswa->user) {
                $siswa->user->delete();
            } else {
                $siswa->delete();
            }
        });

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }
}