# 🏫 Project Administrasi Sekolah (LKPD Sumatif Tengah Semester)

**Nama**: KAYLA ARTALITA  
**Kelas**: XI-3 RPL  
**Mata Pelajaran**: Pemrograman Web & Perangkat Bergerak (Laravel)  
**Nama Project**: AdministrasiPengajuan-siswa

---

## 📌 1. Deskripsi Project

**Administrasi Sekolah** adalah aplikasi sistem informasi berbasis web yang dikembangkan menggunakan framework **Laravel 12 (MVC)**. Sistem ini dirancang untuk mengatasi permasalahan tata kelola administrasi surat di sekolah yang sebelumnya tersebar secara manual di berbagai berkas fisik.

Dengan aplikasi ini, proses pengajuan permohonan surat administrasi siswa (seperti *Surat Keterangan Aktif*, *Surat Pengantar PKL*, *Surat Dispensasi*, dll.) dapat dilakukan secara online, aman, cepat, serta terpantau status dan riwayat persetujuannya secara transparan.

---

## ✨ 2. Fitur Utama

- 🔑 **Multi-Role Authentication & Custom Authorization Middleware**  
  Pengamanan hak akses halaman berdasarkan 3 role (`admin`, `guru`, `siswa`) menggunakan `RoleMiddleware` custom.
- 📄 **Pengajuan Surat Online & Realtime Status Tracking**  
  Siswa dapat mengajukan permohonan surat baru serta memantau status pengajuan secara realtime (`pending`, `disetujui`, `ditolak`).
- ✍️ **Verifikasi & Keputusan Approval (Guru / Staff)**  
  Guru dan Staff dapat memproses pengajuan siswa dengan memilih keputusan (Setujui / Tolak) dan memberikan catatan verifikasi.
- 🕒 **Timeline Audit Log & Riwayat Perubahan Status**  
  Setiap proses permohonan dan perubahan status dicatat secara kronologis lengkap dengan nama verifikator dan waktu kejadian.
- ✏️ **Fitur CRUD & Edit di Seluruh Modul**  
  Siswa dapat mengedit permohonan yang berstatus pending; Admin dapat mengelola penuh data siswa, jenis surat, dan pengguna sistem.
- 🔍 **Search, Filter Status, & Pagination Terpadu**  
  Pencarian multi-kolom (Nama, NIS, Keterangan), penyaringan data berdasarkan status, dan pagination yang tetap mempertahankan query URL (`withQueryString()`).
- 🍭 **Notifikasi Interaktif SweetAlert2**  
  Penggunaan popup SweetAlert2 untuk feedback sukses/error dan konfirmasi tindakan sensitif (hapus data & logout).
- 🎨 **Desain Ultra Modern Orange & Dark Mode Theme**  
  Antarmuka visual modern berbasis Tailwind CSS dengan nuansa *Vibrant Orange & Black*, responsive layout, dan dynamic stats cards.

---

## 👥 3. Role & Hak Akses Pengguna

| Role | Deskripsi Role | Hak Akses & Tanggung Jawab |
| :--- | :--- | :--- |
| **Admin** | Administrator Utama Sistem | Memiliki akses penuh ke seluruh fitur dan halaman. Mengelola Master Data Siswa, Master Jenis Surat, dan Pengguna Sistem. |
| **Guru / Staff** | Verifikator Administrasi | Mengelola & memproses pengajuan surat dari seluruh siswa (Setujui/Tolak + Catatan) serta memantau audit log riwayat. |
| **Siswa** | Pemohon Surat Administrasi | Melihat dashboard pribadi, membuat pengajuan surat baru, mengedit permohonan pending, dan melacak status & riwayat surat sendiri. |

---

## 🛠️ 4. Teknologi yang Digunakan

- **Framework Core**: Laravel 12.x (PHP 8.5)
- **Database ORM**: MySQL / SQLite via Eloquent ORM (Eager Loading `with(['siswa', 'jenisSurat', 'riwayats.user'])`)
- **Template Engine**: Blade Templating (Layouts, Components, Directive)
- **Frontend & Styling**: Tailwind CSS + Lucide Icons + SweetAlert2 (CDN)
- **Security & Validation**: Laravel Auth, Custom Middleware (`RoleMiddleware`), FormRequest Validations

---

## 🚀 5. Cara Menjalankan Project

### Prerequisites
Pastikan pada komputer Anda telah terinstall **PHP >= 8.2**, **Composer**, dan **Node.js**.

### Langkah Setup & Instalasi:

1. **Clone Repository & Masuk ke Direktori Project**
   ```bash
   git clone <url-repository-github-anda>
   cd AdministrasiPengajuan-siswa
   ```

2. **Install Dependencies Project**
   ```bash
   composer install
   npm install
   ```

3. **Pengaturan Environment File `.env`**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrasi & Seeding Database Bawaan**  
   Jalankan perintah berikut untuk membuat seluruh tabel database dan mengisinya dengan data awal (Admin, Guru, 10 Sampel Siswa dari 3 Jurusan: RPL, TKJ, DKV, Jenis Surat, dan Dummy Pengajuan):
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Jalankan Server Lokal**
   ```bash
   php artisan serve
   ```
   Buka peramban (browser) Anda dan akses alamat:  
   👉 **`http://127.0.0.1:8000`**

---

## 🔑 6. Akun Uji Coba Sistem (Default Password: `password123`)

| Role | Email Login | Password | Keterangan |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@sekolah.sch.id` | `password123` | Akses penuh seluruh master data |
| **Guru / Staff** | `guru@sekolah.sch.id` | `password123` | Akses verifikasi pengajuan siswa |
| **Siswa (RPL)** | `kayla@sekolah.sch.id` | `password123` | Siswa XI-3 RPL |
| **Siswa (DKV)** | `siti@sekolah.sch.id` | `password123` | Siswa XI-1 DKV |
| **Siswa (TKJ)** | `budi@sekolah.sch.id` | `password123` | Siswa XI-2 TKJ |