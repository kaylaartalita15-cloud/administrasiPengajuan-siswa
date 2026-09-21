<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use App\Http\Requests\StoreSiswaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%")
                  ->orWhere('jurusan', 'like', "%{$search}%");
        }

        $siswas = $query->latest()->paginate(10)->withQueryString();
        
        // Dynamic counts for multi-major students
        $totalJurusan = Siswa::distinct('jurusan')->count('jurusan');
        $jurusanList = Siswa::select('jurusan')->distinct()->pluck('jurusan')->implode(', ');

        return view('siswa.index', compact('siswas', 'totalJurusan', 'jurusanList'));
    }

    public function create()
    {
        return view('siswa.create');
    }

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
            ]);
        });

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit(Siswa $siswa)
    {
        $siswa->load('user');
        return view('siswa.edit', compact('siswa'));
    }

    public function update(StoreSiswaRequest $request, Siswa $siswa)
    {
        DB::transaction(function () use ($request, $siswa) {
            $userData = [
                'name' => $request->nama,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            if ($siswa->user) {
                $siswa->user->update($userData);
            }

            $siswa->update([
                'nis' => $request->nis,
                'nama' => $request->nama,
                'kelas' => $request->kelas,
                'jurusan' => $request->jurusan,
            ]);
        });

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        DB::transaction(function () use ($siswa) {
            if ($siswa->user) {
                $siswa->user->delete();
            } else {
                $siswa->delete();
            }
        });

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
