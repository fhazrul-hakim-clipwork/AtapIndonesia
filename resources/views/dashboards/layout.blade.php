@php
    /*
    |--------------------------------------------------------------------------
    | AMBIL USER BERDASARKAN GUARD
    |--------------------------------------------------------------------------
    | Admin dan pembeli bisa login bersamaan dalam session yang sama.
    |
    | active_role digunakan untuk menentukan user yang sedang aktif.
    */

    $adminUser = Auth::guard('admin')->user();
    $pembeliUser = Auth::guard('pembeli')->user();

    $activeRole = session('active_role');

    if ($activeRole === 'admin' && $adminUser) {

        $currentUser = $adminUser;
        $userRole = 'admin';

    } elseif ($activeRole === 'pembeli' && $pembeliUser) {

        $currentUser = $pembeliUser;
        $userRole = 'pembeli';

    } elseif ($adminUser && !$pembeliUser) {

        $currentUser = $adminUser;
        $userRole = 'admin';

    } elseif ($pembeliUser && !$adminUser) {

        $currentUser = $pembeliUser;
        $userRole = 'pembeli';

    } elseif ($adminUser && $pembeliUser) {

        /*
        | Jika kedua guard aktif tetapi active_role belum tersedia,
        | gunakan pembeli sebagai fallback.
        */

        $currentUser = $pembeliUser;
        $userRole = 'pembeli';

    } else {

        $currentUser = null;
        $userRole = session('user_role', 'pembeli');

    }


    /*
    |--------------------------------------------------------------------------
    | INFORMASI USER
    |--------------------------------------------------------------------------
    */

    $userName = $currentUser?->name
        ?? session('user_name', 'User');

    $userRole = $currentUser?->role
        ?? $userRole
        ?? session('user_role', 'pembeli');


    /*
    |--------------------------------------------------------------------------
    | LABEL ROLE
    |--------------------------------------------------------------------------
    */

    $roleLabel = match ($userRole) {

        'toko' => 'Mitra Toko',

        'pembeli' => 'Pembeli',

        'admin' => 'Admin',

        default => 'User',

    };


    /*
    |--------------------------------------------------------------------------
    | INISIAL USER
    |--------------------------------------------------------------------------
    */

    $initials = strtoupper(
        substr(
            trim($userName),
            0,
            2
        )
    );


    /*
    |--------------------------------------------------------------------------
    | LOGOUT ROUTE
    |--------------------------------------------------------------------------
    | Admin   -> admin.logout
    | Pembeli -> logout
    */

    $logoutRoute = $userRole === 'admin'
        ? route('admin.logout')
        : route('logout');

@endphp

<!DOCTYPE html>

<html lang="id" class="scroll-smooth">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Dashboard - AtapIndonesia')
    </title>


    {{-- =========================================================
         TAILWIND CSS
    ========================================================== --}}

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>


    {{-- =========================================================
         SWEETALERT2
    ========================================================== --}}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    {{-- =========================================================
         FAVICON
    ========================================================== --}}

    <link
        rel="icon"
        type="image/svg+xml"
        href="{{ asset('img/logo-brand.svg') }}"
    >


    @stack('styles')

</head>


<body class="bg-slate-100 font-sans text-slate-800 flex min-h-screen">


    {{-- =========================================================
         SIDEBAR
    ========================================================== --}}

    <aside class="w-64 bg-slate-900 text-white shrink-0 flex flex-col justify-between hidden md:flex">

        <div>

            {{-- =====================================================
                 LOGO
            ====================================================== --}}

            <div class="h-16 flex items-center px-4 border-b border-slate-800 overflow-hidden">

                @include(
                    'partials.logo',
                    [
                        'size' => 'sm',
                        'showText' => true,
                        'textColor' => 'white',
                        'textSize' => 'text-[13px]'
                    ]
                )

            </div>


            {{-- =====================================================
                 USER PROFILE
            ====================================================== --}}

            <div class="p-4 border-b border-slate-800 flex items-center gap-3 bg-slate-950/40">

                <div class="w-10 h-10 rounded-full bg-orange-500 flex items-center justify-center font-bold text-white shadow-md">

                    {{ $initials }}

                </div>


                <div>

                    <h4 class="text-xs font-bold tracking-wide">

                        {{ ucfirst($userName) }}

                    </h4>


                    <p class="text-[10px] text-emerald-400 font-medium flex items-center gap-1">

                        <span class="h-1.5 w-1.5 bg-emerald-400 rounded-full animate-pulse"></span>

                        {{ $roleLabel }}

                    </p>

                </div>

            </div>


            {{-- =====================================================
                 NAVIGATION
            ====================================================== --}}

            <nav class="p-4 space-y-1.5 text-xs font-semibold">

                @if($userRole === 'admin')

                    {{-- =================================================
                         MENU ADMIN
                    ================================================== --}}

                    <a
                        href="{{ route('dashboard.admin') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg
                        {{ request()->routeIs('dashboard.admin')
                            ? 'bg-slate-800 text-white'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                    >

                        <span>📊</span>

                        Dashboard

                    </a>


                    <a
                        href="{{ route('admin.orders.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg
                        {{ request()->routeIs('admin.orders.*')
                            ? 'bg-slate-800 text-white'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                    >

                        <span>📦</span>

                        Kelola Pesanan

                    </a>


                    <a
                        href="{{ route('admin.products.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg
                        {{ request()->routeIs('admin.products.*')
                            ? 'bg-slate-800 text-white'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                    >

                        <span>🏷️</span>

                        Kelola Produk

                    </a>


                    <a
                        href="{{ route('admin.invoices.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg
                        {{ request()->routeIs('admin.invoices.*')
                            ? 'bg-slate-800 text-white'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                    >

                        <span>🧾</span>

                        Kelola Invoice

                    </a>


                    <a
                        href="{{ route('admin.customers.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg
                        {{ request()->routeIs('admin.customers.*')
                            ? 'bg-slate-800 text-white'
                            : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
                    >

                        <span>👥</span>

                        Kelola Customer

                    </a>

                @else

                    {{-- =================================================
                         MENU PEMBELI
                    ================================================== --}}

                    @yield('sidebar')

                @endif

            </nav>

        </div>


        {{-- =========================================================
             SIDEBAR FOOTER
        ========================================================== --}}

        <div class="p-4 border-t border-slate-800 space-y-2">


            {{-- =====================================================
                 KATALOG PUBLIK
            ====================================================== --}}

            <a
                href="{{ route('katalog') }}"
                target="_blank"
                class="flex items-center justify-center w-full bg-orange-500/10 hover:bg-orange-500 text-orange-400 hover:text-white text-xs font-bold py-2 px-4 rounded-lg transition border border-orange-500/30"
            >

                Katalog Publik

            </a>


            {{-- =====================================================
                 LOGOUT
            ====================================================== --}}

            <a
                href="{{ $logoutRoute }}"
                class="flex items-center justify-center w-full bg-slate-800 hover:bg-red-600 hover:text-white text-slate-300 text-xs font-bold py-2 px-4 rounded-lg transition"
            >

                Keluar Sistem

            </a>

        </div>

    </aside>


    {{-- =========================================================
         MAIN
    ========================================================== --}}

    <main class="flex-1 flex flex-col overflow-x-hidden">


        {{-- =========================================================
             HEADER
        ========================================================== --}}

        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 shrink-0">

            <div class="flex items-center gap-3">

                <a
                    href="{{ url('/') }}"
                    class="md:hidden"
                >

                    <img
                        src="{{ asset('img/logo-brand.svg') }}"
                        class="h-8 w-auto"
                        alt="Logo"
                    >

                </a>


                <h1 class="text-sm font-bold text-slate-800">

                    @yield('header-title', 'Dashboard')

                </h1>

            </div>


            {{-- =====================================================
                 LIVE CLOCK
            ====================================================== --}}

            <div class="text-xs text-slate-500 font-medium hidden sm:flex items-center gap-2">

                <span>🕐</span>

                <span
                    id="live-clock"
                    class="font-mono font-bold text-slate-700"
                >
                    --:--:-- WIB
                </span>

            </div>

        </header>


        {{-- =========================================================
             CONTENT
        ========================================================== --}}

        <div class="p-6 flex-1 space-y-6 overflow-y-auto">

            @yield('dashboard-content')

        </div>

    </main>


    {{-- =========================================================
         LIVE CLOCK
    ========================================================== --}}

    <script>

        (function () {

            const el = document.getElementById('live-clock');

            if (!el) {
                return;
            }

            const tick = () => {

                const d = new Date();

                const h = String(d.getHours()).padStart(2, '0');
                const m = String(d.getMinutes()).padStart(2, '0');
                const s = String(d.getSeconds()).padStart(2, '0');

                el.textContent = `${h}:${m}:${s} WIB`;

            };

            tick();

            setInterval(tick, 1000);

        })();

    </script>


    {{-- =========================================================
         GLOBAL DASHBOARD NOTIFICATION
    ========================================================== --}}

    <script>

        /*
        |--------------------------------------------------------------------------
        | KONFIGURASI TOAST
        |--------------------------------------------------------------------------
        | Dibuat sama dengan sistem popup pada layouts/app.blade.php.
        */

        const Toast = Swal.mixin({

            toast: true,

            position: 'top-end',

            showConfirmButton: false,

            showCloseButton: true,

            timer: 2000,

            timerProgressBar: true,

            width: 360,

            didOpen: (toast) => {

                toast.addEventListener(
                    'mouseenter',
                    Swal.stopTimer
                );

                toast.addEventListener(
                    'mouseleave',
                    Swal.resumeTimer
                );

            }

        });


        /*
        |--------------------------------------------------------------------------
        | FUNGSI SHOW TOAST
        |--------------------------------------------------------------------------
        */

        function showToast(
            type,
            message,
            title = ''
        ) {

            Toast.fire({

                icon: type,

                title: title || undefined,

                text: message

            });

        }


        /*
        |--------------------------------------------------------------------------
        | SESSION SUCCESS
        |--------------------------------------------------------------------------
        */

        @if(session('sukses'))

            showToast(
                'success',
                @json(session('sukses'))
            );

        @endif


        /*
        |--------------------------------------------------------------------------
        | SESSION SUCCESS - ENGLISH KEY
        |--------------------------------------------------------------------------
        */

        @if(session('success'))

            showToast(
                'success',
                @json(session('success'))
            );

        @endif


        /*
        |--------------------------------------------------------------------------
        | SESSION WARNING
        |--------------------------------------------------------------------------
        */

        @if(session('warning'))

            showToast(
                'warning',
                @json(session('warning'))
            );

        @endif


        /*
        |--------------------------------------------------------------------------
        | SESSION INFO
        |--------------------------------------------------------------------------
        */

        @if(session('info'))

            showToast(
                'info',
                @json(session('info'))
            );

        @endif


        /*
        |--------------------------------------------------------------------------
        | SESSION ERROR
        |--------------------------------------------------------------------------
        */

        @if(session('error'))

            showToast(
                'error',
                @json(session('error'))
            );

        @endif


        /*
        |--------------------------------------------------------------------------
        | VALIDATION ERROR
        |--------------------------------------------------------------------------
        | Jika controller menggunakan:
        |
        | $request->validate(...)
        |
        | Laravel otomatis mengirim $errors ke view.
        */

        @if($errors->any())

            showToast(
                'error',
                @json($errors->first())
            );

        @endif

    </script>


    @stack('scripts')

</body>

</html>