<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Siswa;
use App\Models\JenisSurat;
use App\Models\Pengajuan;
use App\Models\RiwayatPengajuan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Staff & Admin Users
        $admin = User::create([
            'name' => 'Administrator Utama',
            'email' => 'admin@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $guru = User::create([
            'name' => 'Bapak Staff Administrasi / Guru',
            'email' => 'guru@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'guru',
        ]);

        // 2. Sample Students for 3 Majors (RPL, TKJ, DKV)
        $studentsData = [
            ['nis' => '20261101', 'nama' => 'KAYLA ARTALITA', 'kelas' => 'XI-3', 'jurusan' => 'RPL', 'email' => 'kayla@sekolah.sch.id'],
            ['nis' => '20261102', 'nama' => 'Ahmad Rizky', 'kelas' => 'XI-3', 'jurusan' => 'RPL', 'email' => 'ahmad@sekolah.sch.id'],
            ['nis' => '20261103', 'nama' => 'Siti Nurhaliza', 'kelas' => 'XI-1', 'jurusan' => 'DKV', 'email' => 'siti@sekolah.sch.id'],
            ['nis' => '20261104', 'nama' => 'Budi Santoso', 'kelas' => 'XI-2', 'jurusan' => 'TKJ', 'email' => 'budi@sekolah.sch.id'],
            ['nis' => '20261105', 'nama' => 'Anisa Rahmawati', 'kelas' => 'XI-1', 'jurusan' => 'DKV', 'email' => 'anisa@sekolah.sch.id'],
            ['nis' => '20261106', 'nama' => 'Dimas Prasetyo', 'kelas' => 'XI-2', 'jurusan' => 'DKV', 'email' => 'dimas@sekolah.sch.id'],
            ['nis' => '20261107', 'nama' => 'Fadhil Muhammad', 'kelas' => 'XI-3', 'jurusan' => 'TKJ', 'email' => 'fadhil@sekolah.sch.id'],
            ['nis' => '20261108', 'nama' => 'Gita Gutawa', 'kelas' => 'XI-1', 'jurusan' => 'RPL', 'email' => 'gita@sekolah.sch.id'],
            ['nis' => '20261109', 'nama' => 'Hendra Setiawan', 'kelas' => 'XI-2', 'jurusan' => 'TKJ', 'email' => 'hendra@sekolah.sch.id'],
            ['nis' => '20261110', 'nama' => 'Indah Permata', 'kelas' => 'XI-3', 'jurusan' => 'DKV', 'email' => 'indah@sekolah.sch.id'],
        ];

        $createdSiswas = [];
        foreach ($studentsData as $st) {
            $user = User::create([
                'name' => $st['nama'],
                'email' => $st['email'],
                'password' => Hash::make('password123'),
                'role' => 'siswa',
            ]);

            $siswa = Siswa::create([
                'user_id' => $user->id,
                'nis' => $st['nis'],
                'nama' => $st['nama'],
                'kelas' => $st['kelas'],
                'jurusan' => $st['jurusan'],
            ]);

            $createdSiswas[] = ['user' => $user, 'siswa' => $siswa];
        }

        // 3. Seed Jenis Surats
        $surat1 = JenisSurat::create([
            'nama_surat' => 'Surat Keterangan Siswa Aktif',
            'keterangan' => 'Digunakan untuk keperluan pencairan beasiswa, BPJS, atau tunjangan orang tua.',
            'is_active' => true,
        ]);

        $surat2 = JenisSurat::create([
            'nama_surat' => 'Surat Pengantar Praktik Kerja Lapangan (PKL)',
            'keterangan' => 'Digunakan untuk pengajuan magang/PKL ke perusahaan atau instansi mitra.',
            'is_active' => true,
        ]);

        $surat3 = JenisSurat::create([
            'nama_surat' => 'Surat Izin Tidak Masuk / Dispensasi',
            'keterangan' => 'Digunakan untuk keperluan perlombaan, kedinasan, atau perizinan khusus.',
            'is_active' => true,
        ]);

        $surat4 = JenisSurat::create([
            'nama_surat' => 'Surat Bebas Pinjam Perpustakaan & Laboratorium',
            'keterangan' => 'Digunakan sebagai syarat pendaftaran kelulusan / mutasi.',
            'is_active' => true,
        ]);

        // 4. Seed Dummy Pengajuans & Riwayats
        // Kayla (RPL)
        $p1 = Pengajuan::create([
            'siswa_id' => $createdSiswas[0]['siswa']->id,
            'jenis_surat_id' => $surat1->id,
            'tanggal' => now()->subDays(3)->toDateString(),
            'keterangan' => 'Keperluan pengurusan tunjangan pendidikan orang tua bulan September 2026.',
            'status' => 'disetujui',
        ]);
        RiwayatPengajuan::create([
            'pengajuan_id' => $p1->id,
            'user_id' => $createdSiswas[0]['user']->id,
            'status' => 'pending',
            'catatan' => 'Pengajuan surat baru dibuat oleh siswa.',
            'created_at' => now()->subDays(3),
        ]);
        RiwayatPengajuan::create([
            'pengajuan_id' => $p1->id,
            'user_id' => $guru->id,
            'status' => 'disetujui',
            'catatan' => 'Berkas valid dan telah ditandatangani Kepala Sekolah.',
            'created_at' => now()->subDays(2),
        ]);

        // Ahmad (RPL)
        $p2 = Pengajuan::create([
            'siswa_id' => $createdSiswas[1]['siswa']->id,
            'jenis_surat_id' => $surat3->id,
            'tanggal' => now()->subDays(1)->toDateString(),
            'keterangan' => 'Pengajuan izin mengikuti Lomba LKS Tingkat Provinsi.',
            'status' => 'pending',
        ]);
        RiwayatPengajuan::create([
            'pengajuan_id' => $p2->id,
            'user_id' => $createdSiswas[1]['user']->id,
            'status' => 'pending',
            'catatan' => 'Pengajuan surat baru dibuat oleh siswa.',
            'created_at' => now()->subDays(1),
        ]);

        // Siti (DKV)
        $p3 = Pengajuan::create([
            'siswa_id' => $createdSiswas[2]['siswa']->id,
            'jenis_surat_id' => $surat2->id,
            'tanggal' => now()->toDateString(),
            'keterangan' => 'Pengajuan surat pengantar PKL ke Studio Desain Animasi.',
            'status' => 'pending',
        ]);
        RiwayatPengajuan::create([
            'pengajuan_id' => $p3->id,
            'user_id' => $createdSiswas[2]['user']->id,
            'status' => 'pending',
            'catatan' => 'Pengajuan surat baru dibuat oleh siswa.',
            'created_at' => now(),
        ]);

        // Budi (TKJ)
        $p4 = Pengajuan::create([
            'siswa_id' => $createdSiswas[3]['siswa']->id,
            'jenis_surat_id' => $surat1->id,
            'tanggal' => now()->subDays(2)->toDateString(),
            'keterangan' => 'Pengurusan beasiswa Indonesia Pintar (PIP).',
            'status' => 'ditolak',
        ]);
        RiwayatPengajuan::create([
            'pengajuan_id' => $p4->id,
            'user_id' => $createdSiswas[3]['user']->id,
            'status' => 'pending',
            'catatan' => 'Pengajuan surat baru dibuat oleh siswa.',
            'created_at' => now()->subDays(2),
        ]);
        RiwayatPengajuan::create([
            'pengajuan_id' => $p4->id,
            'user_id' => $guru->id,
            'status' => 'ditolak',
            'catatan' => 'Berkas kelengkapan rapor semester lalu belum dilampirkan.',
            'created_at' => now()->subDays(1),
        ]);
    }
}
