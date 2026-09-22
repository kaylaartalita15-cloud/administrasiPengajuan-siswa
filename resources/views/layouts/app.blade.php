<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administrasi Sekolah')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        orange: {
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #09090b; }
        .gradient-orange {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #9a3412 100%);
        }
        .orange-glow {
            box-shadow: 0 0 35px -5px rgba(249, 115, 22, 0.35);
        }
        /* SweetAlert Dark Orange Theme Customization */
        .swal2-popup {
            background: #121215 !important;
            border: 1px solid #27272a !important;
            border-radius: 1.5rem !important;
            color: #f4f4f5 !important;
        }
        .swal2-title {
            color: #ffffff !important;
            font-weight: 800 !important;
        }
        .swal2-html-container {
            color: #a1a1aa !important;
        }
        .swal2-confirm {
            background-color: #f97316 !important;
            color: #09090b !important;
            font-weight: 800 !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 15px -3px rgba(249, 115, 22, 0.3) !important;
        }
        .swal2-cancel {
            background-color: #27272a !important;
            color: #f4f4f5 !important;
            font-weight: 700 !important;
            border-radius: 0.75rem !important;
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-100 antialiased min-h-screen flex flex-col">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-zinc-950 border-r border-zinc-800/80 text-white flex flex-col z-20 shrink-0">
            <!-- Brand Header -->
            <div class="px-6 py-5 flex items-center gap-3 border-b border-zinc-800/80 bg-zinc-950">
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-2.5 rounded-xl text-zinc-950 font-black shadow-lg shadow-orange-500/20 shrink-0">
                    <i data-lucide="graduation-cap" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="font-black text-base tracking-tight text-white leading-tight">E-Administrasi</h1>
                    <p class="text-[10px] text-orange-400 font-bold tracking-wide">Sistem Informasi Sekolah</p>
                </div>
            </div>

            <!-- Profile Info Card -->
            <!-- <div class="p-3 mx-3 my-4 bg-zinc-900/90 rounded-xl border border-zinc-800/80 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-tr from-orange-500 to-amber-500 flex items-center justify-center font-black text-xs text-zinc-950 shadow-md shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <h4 class="font-bold text-xs text-zinc-100 truncate leading-tight">{{ auth()->user()->name ?? 'Pengguna' }}</h4>
                    <span class="inline-block mt-0.5 px-2 py-0.5 text-[9px] font-black rounded-md uppercase tracking-wider bg-orange-500/10 text-orange-400 border border-orange-500/30">
                        {{ auth()->user()->role }}
                    </span>
                </div>
            </div> -->

            <!-- Navigation Links -->
            <nav class="flex-1 px-3 space-y-1.5 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-orange-500 text-zinc-950 shadow-lg shadow-orange-500/30' : 'text-zinc-400 hover:bg-zinc-900 hover:text-orange-400' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('pengajuan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all duration-200 {{ request()->routeIs('pengajuan.index*') ? 'bg-orange-500 text-zinc-950 shadow-lg shadow-orange-500/30' : 'text-zinc-400 hover:bg-zinc-900 hover:text-orange-400' }}">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    <span>{{ auth()->user()->isSiswa() ? 'Pengajuan Saya' : 'Daftar Pengajuan' }}</span>
                </a>

                @if(auth()->user()->isSiswa())
                    <a href="{{ route('pengajuan.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all duration-200 {{ request()->routeIs('pengajuan.create') ? 'bg-orange-500 text-zinc-950 shadow-lg shadow-orange-500/30' : 'text-zinc-400 hover:bg-zinc-900 hover:text-orange-400' }}">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i>
                        <span>Buat Surat Baru</span>
                    </a>
                @endif

                @if(auth()->user()->isAdmin())
                    <div class="pt-4 pb-1 px-4 text-[11px] font-black tracking-widest text-zinc-500 uppercase">Administrator</div>

                    <a href="{{ route('siswa.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all duration-200 {{ request()->routeIs('siswa.*') ? 'bg-orange-500 text-zinc-950 shadow-lg shadow-orange-500/30' : 'text-zinc-400 hover:bg-zinc-900 hover:text-orange-400' }}">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        <span>Kelola Siswa</span>
                    </a>

                    <a href="{{ route('jenis-surat.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all duration-200 {{ request()->routeIs('jenis-surat.*') ? 'bg-orange-500 text-zinc-950 shadow-lg shadow-orange-500/30' : 'text-zinc-400 hover:bg-zinc-900 hover:text-orange-400' }}">
                        <i data-lucide="folder-kanban" class="w-5 h-5"></i>
                        <span>Jenis Surat</span>
                    </a>

                    <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-sm transition-all duration-200 {{ request()->routeIs('users.*') ? 'bg-orange-500 text-zinc-950 shadow-lg shadow-orange-500/30' : 'text-zinc-400 hover:bg-zinc-900 hover:text-orange-400' }}">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                @endif
            </nav>

            <!-- Bottom Logout button -->
            <div class="p-4 border-t border-zinc-800/80">
                <form action="{{ route('logout') }}" method="POST" class="btn-delete-confirm">
                    @csrf
                    <button type="button" onclick="confirmLogout(this)" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold text-sm text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all border border-red-500/20">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        <span>Keluar System</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-y-auto bg-zinc-950">
            <!-- Top Header -->
            <header class="px-8 py-5 bg-zinc-950/95 backdrop-blur-md border-b border-zinc-800/80 sticky top-0 z-30 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-extrabold text-white leading-tight">@yield('page-title', 'Dashboard')</h2>
                    <p class="text-[10px] text-orange-400 font-bold tracking-wide mt-0.5">Sistem Pengajuan Administrasi Surat Online</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-xs font-bold text-zinc-300 bg-zinc-900/80 px-3.5 py-1.5 rounded-xl border border-zinc-800/80">
                        <i data-lucide="calendar" class="inline w-3.5 h-3.5 mr-1 text-orange-400"></i>
                        {{ now()->format('d M Y') }}
                    </span>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 p-8">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-zinc-950 border-t border-zinc-800/80 py-4 px-8 text-center text-xs text-zinc-500">
                &copy; {{ date('Y') }} <strong class="text-orange-400">Administrasi Sekolah</strong> • Dikembangkan oleh <strong class="text-white">KAYLA ARTALITA (XI-3 RPL)</strong>.
            </footer>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // Global SweetAlert Flash Notifications
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false,
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops... Error',
                text: "{{ session('error') }}",
                confirmButtonText: 'Mengerti'
            });
        @endif

        // SweetAlert Confirmation helper
        function confirmDelete(button) {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus Data',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            });
        }

        function confirmLogout(button) {
            Swal.fire({
                title: 'Keluar Dari Sistem?',
                text: "Sesi Anda akan diakhiri.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
