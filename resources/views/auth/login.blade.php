<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Administrasi Sekolah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #09090b; }
        .gradient-orange {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #9a3412 100%);
        }
    </style>
</head>
<body class="bg-zinc-950 min-h-screen flex items-center justify-center p-6 text-zinc-100">

    <div class="w-full max-w-md bg-zinc-900 border border-zinc-800 rounded-3xl p-8 shadow-2xl relative overflow-hidden">
        <!-- Orange Glow Accents -->
        <div class="absolute -top-24 -right-24 w-56 h-56 bg-orange-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-56 h-56 bg-amber-500/15 rounded-full blur-3xl"></div>

        <!-- Header -->
        <div class="text-center mb-8 relative z-10">
            <div class="inline-flex p-3 rounded-2xl bg-orange-500/10 border border-orange-500/30 text-orange-400 mb-3 shadow-inner">
                <i data-lucide="graduation-cap" class="w-9 h-9"></i>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">Administrasi Sekolah</h1>
            <p class="text-xs text-orange-400 mt-1 font-bold">Sistem Pengajuan Surat Online • SMK XI RPL</p>
        </div>

        <!-- Flash Message -->
        @if(session('error'))
            <div class="mb-4 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs font-semibold flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-4 h-4 shrink-0 text-rose-400"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if(session('success'))
            <div class="mb-4 p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs font-semibold flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 shrink-0 text-emerald-400"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('login') }}" method="POST" class="space-y-5 relative z-10">
            @csrf
            <div>
                <label for="email" class="block text-xs font-black uppercase tracking-wider text-zinc-300 mb-2">Email Akun</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-500">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                    </div>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="contoh: kayla@sekolah.sch.id"
                        class="w-full pl-10 pr-4 py-3 bg-zinc-950 border border-zinc-800 rounded-xl text-sm text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all">
                </div>
                @error('email')
                    <p class="text-rose-400 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-black uppercase tracking-wider text-zinc-300 mb-2">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-500">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                    </div>
                    <input type="password" name="password" id="password" required placeholder="••••••••"
                        class="w-full pl-10 pr-4 py-3 bg-zinc-950 border border-zinc-800 rounded-xl text-sm text-white placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all">
                </div>
                @error('password')
                    <p class="text-rose-400 text-xs mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full py-3.5 px-4 bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-zinc-950 font-black rounded-xl text-sm shadow-xl shadow-orange-500/20 transition-all flex items-center justify-center gap-2">
                <span>Masuk Ke Sistem</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </button>
        </form>

        <!-- Quick Credentials Hint for LKPD Testing -->
        <div class="mt-8 pt-6 border-t border-zinc-800 text-xs text-zinc-400 relative z-10 space-y-2">
            <p class="font-black text-orange-400 uppercase tracking-wider text-[10px]">Akun Uji Coba (Password: password123):</p>
            <div class="grid grid-cols-3 gap-2 text-center text-[11px]">
                <div class="p-2 rounded-lg bg-zinc-950 border border-zinc-800">
                    <span class="block text-amber-400 font-black">Admin</span>
                    <span class="text-[10px] text-zinc-500 truncate block">admin@sekolah.sch.id</span>
                </div>
                <div class="p-2 rounded-lg bg-zinc-950 border border-zinc-800">
                    <span class="block text-orange-400 font-black">Guru/Staff</span>
                    <span class="text-[10px] text-zinc-500 truncate block">guru@sekolah.sch.id</span>
                </div>
                <div class="p-2 rounded-lg bg-zinc-950 border border-zinc-800">
                    <span class="block text-emerald-400 font-black">Siswa Sample</span>
                    <span class="text-[10px] text-zinc-500 truncate block">siti / budi / kayla</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
