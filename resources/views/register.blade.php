@extends('layouts.app')

@section('title', 'Daftar Akun - AtapIndonesia')
@section('hideNavbar', true)
@section('hideFooter', true)

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-slate-900 via-slate-800 to-orange-900">
    <div class="w-full max-w-md">
        <div class="flex justify-center mb-6">
            <a href="{{ route('landing') }}">
                <img src="{{ asset('img/logo-brand.svg') }}" class="h-16 w-auto drop-shadow-lg" alt="Logo AtapIndonesia">
            </a>
        </div>

        <div class="bg-slate-900/80 backdrop-blur border border-slate-800 p-8 rounded-2xl shadow-2xl space-y-6">
            <div class="text-center">
                <h2 class="text-2xl font-black text-white uppercase tracking-widest">Buat Akun Profesional</h2>
                <p class="text-xs text-slate-400 mt-2">Akses katalog material, hitung kebutuhan talang, dan kelola order dalam satu platform.</p>
            </div>

            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-xs font-bold px-3 py-2 rounded-lg">
                    @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
                </div>
            @endif

            <form action="{{ route('register.proses') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Lengkap / Nama Toko</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: CV Selalu Berkilau" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:border-orange-500 transition" required>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2.5 text-xs text-white focus:outline-none focus:border-orange-500 transition" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Password Baru (min 6 karakter)</label>
                    <div class="relative">
                        <input type="password" name="password" placeholder="••••••••" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2.5 pr-10 text-xs text-white focus:outline-none focus:border-orange-500 transition" required minlength="6" id="register-password">
                        <button type="button" onclick="togglePassword('register-password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Konfirmasi Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" placeholder="••••••••" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2.5 pr-10 text-xs text-white focus:outline-none focus:border-orange-500 transition" required minlength="6" id="register-password-confirmation">
                        <button type="button" onclick="togglePassword('register-password-confirmation', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <input type="hidden" name="role" value="pembeli">
                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-slate-950 font-black py-3 px-4 rounded-lg text-xs uppercase tracking-wider transition shadow-md cursor-pointer">
                    Daftar Sekarang
                </button>
            </form>

            <p class="text-center text-[11px] text-slate-500">
                Sudah terdaftar? <a href="{{ route('login') }}" class="text-orange-500 hover:underline font-bold">Log In di sini</a>
            </p>

            <div class="relative py-2">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-800"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="px-2 bg-slate-900 text-slate-500">Atau daftar dengan</span>
                </div>
            </div>

            <a href="{{ route('auth.google') }}" class="flex items-center justify-center gap-2 w-full bg-white hover:bg-gray-100 text-gray-700 font-bold py-2.5 px-4 rounded-lg text-xs transition border border-gray-300">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.3v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Daftar dengan Google
            </a>

        </div>
    </div>
</div>

<script>
    function togglePassword(fieldId, button) {
        const input = document.getElementById(fieldId);
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        button.innerHTML = isPassword
            ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.059 10.059 0 013.999-5.308m3.577-3.153A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.059 10.059 0 01-3.308 5.308M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.058 10.058 0 01-3.308 5.308M15.536 8.464A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.058 10.058 0 01-3.308 5.308"/></svg>'
            : '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>';
    }
</script>
@endsection
