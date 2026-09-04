@php
    if (Auth::check()) {
        $userName = Auth::user()->name;
        $userRole = Auth::user()->role;
    } else {
        $userName = session('user_name', 'User');
        $userRole = session('user_role', 'pembeli');
    }
    $roleLabel = match($userRole) {
        'toko' => 'Mitra Toko',
        'pembeli' => 'Pembeli',
        'admin' => 'Admin',
        default => 'User',
    };
    $initials = strtoupper(substr($userName, 0, 2));
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - AtapIndonesia')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>

    <link rel="icon" type="image/svg+xml" href="{{ asset('img/logo-brand.svg') }}">
    @stack('styles')
</head>
<body class="bg-slate-100 font-sans text-slate-800 flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-slate-900 text-white shrink-0 flex flex-col justify-between hidden md:flex">
        <div>
            <div class="h-16 flex items-center px-4 border-b border-slate-800 overflow-hidden">
                @include('partials.logo', ['size' => 'sm', 'showText' => true, 'textColor' => 'white', 'textSize' => 'text-[13px]'])
            </div>
            <div class="p-4 border-b border-slate-800 flex items-center gap-3 bg-slate-950/40">
                <div class="w-10 h-10 rounded-full bg-orange-500 flex items-center justify-center font-bold text-white shadow-md">
                    {{ $initials }}
                </div>
                <div>
                    <h4 class="text-xs font-bold tracking-wide">{{ ucfirst($userName) }}</h4>
                    <p class="text-[10px] text-emerald-400 font-medium flex items-center gap-1">
                        <span class="h-1.5 w-1.5 bg-emerald-400 rounded-full animate-pulse"></span> {{ $roleLabel }}
                    </p>
                </div>
            </div>
            <nav class="p-4 space-y-1.5 text-xs font-semibold">
                @if($userRole === 'admin')
                    <a href="{{ route('dashboard.admin') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.admin') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span>📊</span> Dashboard
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.orders.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span>📦</span> Kelola Pesanan
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span>🏷️</span> Kelola Produk
                    </a>
                    <a href="{{ route('admin.invoices.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.invoices.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span>🧾</span> Kelola Invoice
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.customers.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <span>👥</span> Kelola Customer
                    </a>
                @else
                    @yield('sidebar')
                @endif
            </nav>
        </div>
        <div class="p-4 border-t border-slate-800 space-y-2">
            <a href="{{ route('katalog') }}" target="_blank"
               class="flex items-center justify-center w-full bg-orange-500/10 hover:bg-orange-500 text-orange-400 hover:text-white text-xs font-bold py-2 px-4 rounded-lg transition border border-orange-500/30">
                Katalog Publik
            </a>
            <a href="{{ route('logout') }}"
               class="flex items-center justify-center w-full bg-slate-800 hover:bg-red-600 hover:text-white text-slate-300 text-xs font-bold py-2 px-4 rounded-lg transition">
                Keluar Sistem
            </a>
        </div>
    </aside>

    {{-- MAIN --}}
    <main class="flex-1 flex flex-col overflow-x-hidden">
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 shrink-0">
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="md:hidden">
                    <img src="{{ asset('img/logo-brand.svg') }}" class="h-8 w-auto" alt="Logo">
                </a>
                <h1 class="text-sm font-bold text-slate-800">@yield('header-title', 'Dashboard')</h1>
            </div>
            <div class="text-xs text-slate-500 font-medium hidden sm:flex items-center gap-2">
                <span>🕐</span>
                <span id="live-clock" class="font-mono font-bold text-slate-700">--:--:-- WIB</span>
            </div>
        </header>
        <div class="p-6 flex-1 space-y-6 overflow-y-auto">
            @if(session('sukses'))
                <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 text-xs font-bold px-4 py-3 rounded-xl">
                    ✅ {{ session('sukses') }}
                </div>
            @endif
            @yield('dashboard-content')
        </div>
    </main>

    <script>
        // Live clock
        (function(){
            const el = document.getElementById('live-clock');
            if (!el) return;
            const tick = () => {
                const d = new Date();
                const h = String(d.getHours()).padStart(2,'0');
                const m = String(d.getMinutes()).padStart(2,'0');
                const s = String(d.getSeconds()).padStart(2,'0');
                el.textContent = `${h}:${m}:${s} WIB`;
            };
            tick(); setInterval(tick, 1000);
        })();
    </script>
    @stack('scripts')
</body>
</html>
