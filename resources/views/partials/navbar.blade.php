@php
    /*
    |--------------------------------------------------------------------------
    | DETEKSI LOGIN
    |--------------------------------------------------------------------------
    |
    | Aplikasi menggunakan dua guard:
    |
    | admin
    | pembeli
    |
    | Karena kedua guard dapat aktif dalam session yang sama,
    | kita menggunakan session('active_role') untuk menentukan
    | akun yang sedang digunakan oleh navbar.
    |
    */

    $adminUser = Auth::guard('admin')->user();
    $pembeliUser = Auth::guard('pembeli')->user();

    /*
    |--------------------------------------------------------------------------
    | ROLE AKTIF
    |--------------------------------------------------------------------------
    */

    $activeRole = session('active_role');

    /*
    |--------------------------------------------------------------------------
    | TENTUKAN USER YANG DIGUNAKAN NAVBAR
    |--------------------------------------------------------------------------
    */

    if ($activeRole === 'admin' && $adminUser) {

        $currentUser = $adminUser;
        $userRole = 'admin';

        $logoutRoute = route('admin.logout');
        $dashboardRoute = route('dashboard.admin');

    } elseif ($activeRole === 'pembeli' && $pembeliUser) {

        $currentUser = $pembeliUser;
        $userRole = 'pembeli';

        $logoutRoute = route('logout');
        $dashboardRoute = route('dashboard.pembeli');

    } elseif ($adminUser && !$pembeliUser) {

        /*
        |--------------------------------------------------------------------------
        | FALLBACK ADMIN
        |--------------------------------------------------------------------------
        |
        | Jika hanya guard admin yang aktif.
        |
        */

        $currentUser = $adminUser;
        $userRole = 'admin';

        $logoutRoute = route('admin.logout');
        $dashboardRoute = route('dashboard.admin');

    } elseif ($pembeliUser && !$adminUser) {

        /*
        |--------------------------------------------------------------------------
        | FALLBACK PEMBELI
        |--------------------------------------------------------------------------
        |
        | Jika hanya guard pembeli yang aktif.
        |
        */

        $currentUser = $pembeliUser;
        $userRole = 'pembeli';

        $logoutRoute = route('logout');
        $dashboardRoute = route('dashboard.pembeli');

    } else {

        /*
        |--------------------------------------------------------------------------
        | BELUM LOGIN
        |--------------------------------------------------------------------------
        */

        $currentUser = null;
        $userRole = null;

        $logoutRoute = null;
        $dashboardRoute = null;
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS LOGIN
    |--------------------------------------------------------------------------
    */

    $isLoggedIn = $currentUser !== null;

    /*
    |--------------------------------------------------------------------------
    | NAMA USER
    |--------------------------------------------------------------------------
    */

    $userName = $currentUser?->name
        ?? session('user_name', 'Tamu');

    /*
    |--------------------------------------------------------------------------
    | ROUTE AKTIF
    |--------------------------------------------------------------------------
    */

    $currentRoute = Route::currentRouteName();

    /*
    |--------------------------------------------------------------------------
    | HITUNG JUMLAH BARANG DI KERANJANG
    |--------------------------------------------------------------------------
    */

    $cartCount = collect(session('cart', []))->sum(function ($item) {
        return (int) ($item['quantity'] ?? 0);
    });
@endphp


<nav class="bg-slate-900 border-b border-slate-800 sticky top-0 z-50 px-6 py-3 shadow-lg">

    <div class="max-w-7xl mx-auto flex items-center justify-between">

        {{-- =====================================================
             LOGO
        ====================================================== --}}

        @include('partials.logo', [
            'size' => 'md',
            'showText' => true,
            'textColor' => 'white'
        ])


        {{-- =====================================================
             MENU TENGAH
        ====================================================== --}}

        <div class="hidden md:flex items-center gap-6 text-xs font-bold uppercase tracking-wider text-slate-300">

            {{-- SISTEM PAKAR --}}

            <a
                href="{{ url('/') }}#simulator"
                class="hover:text-orange-500 transition"
            >
                Sistem Pakar
            </a>


            {{-- KATALOG --}}

            <a
                href="{{ route('katalog') }}"
                class="hover:text-orange-500 transition
                    {{ $currentRoute === 'katalog' ? 'text-orange-500' : '' }}"
            >
                Katalog
            </a>


            {{-- DASHBOARD --}}

            @if($isLoggedIn)

                <a
                    href="{{ $dashboardRoute }}"
                    class="hover:text-orange-500 transition
                        {{ str_starts_with($currentRoute ?? '', 'dashboard.') ? 'text-orange-500' : '' }}"
                >
                    Dashboard
                </a>

            @endif

        </div>


        {{-- =====================================================
             TOMBOL KANAN
        ====================================================== --}}

        <div class="flex items-center gap-2">


            {{-- =================================================
                 TOGGLE TEMA
            ================================================== --}}

            <button
                id="theme-toggle"
                type="button"
                class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-3 py-2 rounded-lg text-xs font-bold transition"
                title="Toggle Dark/Light"
            >
                Tema
            </button>


            {{-- =================================================
                 KERANJANG
            ================================================== --}}

            <a
                href="{{ route('cart.index') }}"
                class="text-xs font-bold text-slate-300 hover:text-orange-500 transition flex items-center gap-2"
                aria-label="Keranjang"
            >

                <span class="text-base">
                    🛒
                </span>

                <span
                    id="cart-count"
                    class="
                        {{ $cartCount > 0 ? '' : 'hidden' }}
                        bg-orange-500
                        text-white
                        text-[10px]
                        font-black
                        px-2
                        py-1
                        rounded-full
                    "
                >
                    {{ $cartCount }}
                </span>

            </a>


            {{-- =================================================
                 USER SUDAH LOGIN
            ================================================== --}}

            @if($isLoggedIn)

                {{-- =================================================
                     USER INFO
                ================================================== --}}

                <div
                    class="hidden sm:flex items-center gap-2 bg-slate-800 px-3 py-1.5 rounded-lg"
                >

                    {{-- AVATAR --}}

                    <div
                        class="w-7 h-7 rounded-full bg-orange-500 flex items-center justify-center text-[11px] font-black text-white"
                    >
                        {{ strtoupper(substr($userName, 0, 2)) }}
                    </div>


                    {{-- INFORMASI USER --}}

                    <div class="flex flex-col">

                        <span class="text-xs font-bold text-slate-200">
                            {{ ucfirst($userName) }}
                        </span>

                        <span class="text-[9px] uppercase tracking-wider text-slate-400">

                            {{ $userRole === 'admin' ? 'Admin' : 'Pembeli' }}

                        </span>

                    </div>

                </div>


                {{-- =================================================
                     LOGOUT
                ================================================== --}}

                <a
                    href="{{ $logoutRoute }}"
                    class="bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white text-xs font-bold px-3 py-2 rounded-lg transition border border-red-500/30"
                >
                    Keluar
                </a>


            {{-- =================================================
                 BELUM LOGIN
            ================================================== --}}

            @else

                {{-- =================================================
                     MASUK
                ================================================== --}}

                <a
                    href="{{ route('login') }}"
                    class="text-xs font-bold text-slate-300 hover:text-white px-3 py-2 transition
                        {{ $currentRoute === 'login' ? 'text-orange-500' : '' }}"
                >
                    Masuk
                </a>


                {{-- =================================================
                     DAFTAR
                ================================================== --}}

                <a
                    href="{{ route('register') }}"
                    class="bg-orange-500 hover:bg-orange-600 text-slate-950 text-xs font-black uppercase px-4 py-2 rounded-lg transition tracking-wider shadow-md"
                >
                    Daftar
                </a>

            @endif

        </div>

    </div>

</nav>