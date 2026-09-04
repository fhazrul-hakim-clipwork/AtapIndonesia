<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AtapIndonesia — Sistem Pakar Talang Air & Distribusi Material')</title>
    <meta name="description" content="@yield('description', 'Platform distribusi material atap & sistem pakar talang air untuk Indonesia')">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>

    <link rel="icon" type="image/svg+xml" href="{{ asset('img/logo-brand.svg') }}">

    @stack('styles')
    @stack('head-scripts')
</head>
<body class="@yield('body-class', 'bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100') font-sans min-h-screen flex flex-col transition-colors duration-300">

    @if(!isset($hideNavbar) || !$hideNavbar)
        @yield('navbar', '')
        @include('partials.navbar')
    @endif

    <main class="flex-1">
        @if(session('sukses'))
            <div class="max-w-7xl mx-auto px-6 pt-4">
                <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold px-4 py-3 rounded-xl">
                    {{ session('sukses') }}
                </div>
            </div>
        @endif
        @yield('content')
    </main>

    @if(!isset($hideFooter) || !$hideFooter)
        @include('partials.footer')
    @endif

    <script>
        // === DARK MODE TOGGLE ===
        const themeBtn = document.getElementById('theme-toggle');
        function applyTheme(t) {
            if (t === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        applyTheme(localStorage.getItem('theme') || 'light');
        if (themeBtn) themeBtn.addEventListener('click', () => {
            const next = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
            localStorage.setItem('theme', next);
            applyTheme(next);
        });

        // === GLOBAL SWEETALERT ALERTS ===
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terdapat Kesalahan',
                html: `
                    <ul class="text-left text-sm text-red-500 mt-2 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                confirmButtonColor: '#f97316'
            });
        @elseif(session('sukses'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('sukses') }}',
                confirmButtonColor: '#f97316'
            });
        @endif
    </script>
    @stack('scripts')
</body>
</html>
