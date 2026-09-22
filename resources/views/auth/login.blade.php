<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Administrasi Sekolah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #09090b; }
        .gradient-orange { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); }
    </style>
</head>
<body class="bg-zinc-950 min-h-screen flex items-center justify-center p-6 text-zinc-100">

    <div class="w-full max-w-md bg-zinc-900 border border-zinc-800 rounded-3xl p-8 shadow-2xl space-y-2 relative overflow-hidden">
        
        <!-- Subtle Glowing Gradient Background Pill -->
        <div class="absolute -right-20 -top-20 w-44 h-44 bg-orange-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Header Portal Sekolah -->
        <div class="text-center mb-8">
            <div class="inline-flex p-4 rounded-2xl bg-orange-500/10 border border-orange-500/30 text-orange-400 mb-3 shadow-inner">
                <i data-lucide="graduation-cap" class="w-8 h-8"></i>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">E-Administrasi Sekolah</h1>
            <p class="text-xs text-orange-400 font-semibold mt-1">Portal Layanan Surat Pengajuan Online</p>
        </div>

        <!-- Flash Alert Message -->
        @if(session('error'))
            <div class="mb-5 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs font-semibold flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-rose-400"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if(session('success'))
            <div class="mb-5 p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-semibold flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 shrink-0 text-emerald-400"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Form Login Realistis -->
        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="block text-xs font-black uppercase tracking-wider text-zinc-300 mb-2">Alamat Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-500">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                    </div>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="masukkan email anda"
                        class="w-full pl-10 pr-4 py-3 bg-zinc-950 border border-zinc-800 rounded-xl text-xs text-white placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all font-semibold">
                </div>
                @error('email')
                    <p class="text-rose-400 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-xs font-black uppercase tracking-wider text-zinc-300 mb-2">Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-500">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                    </div>
                    <input type="password" name="password" id="password" required placeholder="masukkan kata sandi"
                        class="w-full pl-10 pr-4 py-3 bg-zinc-950 border border-zinc-800 rounded-xl text-xs text-white placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all font-semibold">
                </div>
                @error('password')
                    <p class="text-rose-400 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between text-xs text-zinc-400 font-medium">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded bg-zinc-950 border-zinc-800 text-orange-500 focus:ring-0">
                    <span>Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-zinc-950 font-black rounded-xl text-xs transition-all flex items-center justify-center gap-2 shadow-xl shadow-orange-500/20">
                <span>Masuk Sistem</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </button>
        </form>

        <!-- Footnote Resmi Sekolah -->
        <div class="mt-8 pt-6 border-t border-zinc-800/80 text-center text-xs text-zinc-500 font-medium">
            <p>&copy; {{ date('Y') }} Sistem Informasi E-Administrasi Sekolah</p>
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
