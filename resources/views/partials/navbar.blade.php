@php
    $isLoggedIn = Auth::check() || session('is_logged_in');

    $userName = Auth::check()
        ? Auth::user()->name
        : (session('user_name') ?: 'Tamu');

    $userRole = Auth::check()
        ? Auth::user()->role
        : (session('user_role') ?: null);

    $currentRoute = Route::currentRouteName();

    // Hitung jumlah seluruh barang di keranjang
    $cartCount = collect(session('cart', []))->sum(function ($item) {
        return (int) ($item['quantity'] ?? 0);
    });
@endphp

<nav class="bg-slate-900 border-b border-slate-800 sticky top-0 z-50 px-6 py-3 shadow-lg">
    <div class="max-w-7xl mx-auto flex items-center justify-between">

        {{-- LOGO --}}
        @include('partials.logo', [
            'size' => 'md',
            'showText' => true,
            'textColor' => 'white'
        ])

        {{-- MENU TENGAH --}}
        <div class="hidden md:flex items-center gap-6 text-xs font-bold uppercase tracking-wider text-slate-300">

            <a
                href="{{ url('/') }}#simulator"
                class="hover:text-orange-500 transition">
                Sistem Pakar
            </a>

            <a
                href="{{ route('katalog') }}"
                class="hover:text-orange-500 transition {{ $currentRoute == 'katalog' ? 'text-orange-500' : '' }}">
                Katalog
            </a>

            @if($isLoggedIn)
                <a
                    href="{{ $userRole == 'admin'
                        ? route('dashboard.admin')
                        : route('dashboard.pembeli') }}"
                    class="hover:text-orange-500 transition">
                    Dashboard
                </a>
            @endif

        </div>

        {{-- TOMBOL KANAN --}}
        <div class="flex items-center gap-2">

            {{-- TOGGLE TEMA --}}
            <button
                id="theme-toggle"
                type="button"
                class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-3 py-2 rounded-lg text-xs font-bold transition"
                title="Toggle Dark/Light">
                Tema
            </button>

            {{-- =====================================================
                 KERANJANG
                 ===================================================== --}}
            <a
                href="{{ route('cart.index') }}"
                class="text-xs font-bold text-slate-300 hover:text-orange-500 transition flex items-center gap-2"
                aria-label="Keranjang">

                <span class="text-base">🛒</span>

                {{-- 
                    PENTING:
                    ID ini digunakan JavaScript katalog
                    untuk mengubah jumlah tanpa refresh.
                --}}
                <span
                    id="cart-count"
                    class="{{ $cartCount > 0 ? '' : 'hidden' }} bg-orange-500 text-white text-[10px] font-black px-2 py-1 rounded-full">
                    {{ $cartCount }}
                </span>

            </a>

            {{-- USER --}}
            @if($isLoggedIn)

                <div class="hidden sm:flex items-center gap-2 bg-slate-800 px-3 py-1.5 rounded-lg">

                    <div
                        class="w-7 h-7 rounded-full bg-orange-500 flex items-center justify-center text-[11px] font-black text-white">
                        {{ strtoupper(substr($userName, 0, 2)) }}
                    </div>

                    <span class="text-xs font-bold text-slate-200">
                        {{ ucfirst($userName) }}
                    </span>

                </div>

                {{-- KELUAR --}}
                <a
                    href="{{ route('logout') }}"
                    class="bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white text-xs font-bold px-3 py-2 rounded-lg transition border border-red-500/30">
                    Keluar
                </a>

            @else

                {{-- MASUK --}}
                <a
                    href="{{ route('login') }}"
                    class="text-xs font-bold text-slate-300 hover:text-white px-3 py-2 transition {{ $currentRoute == 'login' ? 'text-orange-500' : '' }}">
                    Masuk
                </a>

                {{-- DAFTAR --}}
                <a
                    href="{{ route('register') }}"
                    class="bg-orange-500 hover:bg-orange-600 text-slate-950 text-xs font-black uppercase px-4 py-2 rounded-lg transition tracking-wider shadow-md">
                    Daftar
                </a>

            @endif

        </div>

    </div>
</nav>