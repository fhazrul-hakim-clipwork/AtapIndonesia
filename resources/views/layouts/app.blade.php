<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'AtapIndonesia — Sistem Pakar Talang Air & Distribusi Material')
    </title>

    <meta
        name="description"
        content="@yield('description', 'Platform distribusi material atap & sistem pakar talang air untuk Indonesia')"
    >

    {{-- =========================================================
         TAILWIND
    ========================================================== --}}

    <script src="https://cdn.tailwindcss.com"></script>

    {{-- =========================================================
         SWEETALERT
    ========================================================== --}}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            darkMode: 'class'
        };
    </script>

    {{-- =========================================================
         FAVICON
    ========================================================== --}}

    <link
        rel="icon"
        type="image/svg+xml"
        href="{{ asset('img/logo-brand.svg') }}"
    >

    @stack('styles')
    @stack('head-scripts')
</head>

<body
    class="@yield('body-class', 'bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100')
    font-sans min-h-screen flex flex-col transition-colors duration-300"
>

    {{-- =========================================================
         NAVBAR
    ========================================================== --}}

    @if(!isset($hideNavbar) || !$hideNavbar)

        @yield('navbar', '')

        @include('partials.navbar')

    @endif


    {{-- =========================================================
         MAIN CONTENT
    ========================================================== --}}

    <main class="flex-1">

        @yield('content')

    </main>


    {{-- =========================================================
         FOOTER
    ========================================================== --}}

    @if(!isset($hideFooter) || !$hideFooter)

        @include('partials.footer')

    @endif


    {{-- =========================================================
         GLOBAL JAVASCRIPT
    ========================================================== --}}

    <script>

        // =========================================================
        // DARK MODE
        // =========================================================

        const themeBtn = document.getElementById('theme-toggle');

        function applyTheme(theme) {

            if (theme === 'dark') {

                document.documentElement.classList.add('dark');

            } else {

                document.documentElement.classList.remove('dark');

            }

        }


        // Terapkan tema saat halaman pertama kali dibuka
        applyTheme(
            localStorage.getItem('theme') || 'light'
        );


        // Toggle tema
        if (themeBtn) {

            themeBtn.addEventListener('click', () => {

                const nextTheme =
                    document.documentElement.classList.contains('dark')
                        ? 'light'
                        : 'dark';

                localStorage.setItem('theme', nextTheme);

                applyTheme(nextTheme);

            });

        }


        // =========================================================
        // GLOBAL TOAST
        // =========================================================
        //
        // SATU konfigurasi untuk seluruh website.
        //
        // Digunakan oleh:
        //
        // - session('sukses')
        // - session('success')
        // - session('warning')
        // - session('info')
        // - session('error')
        // - validation error
        // - JavaScript showToast()
        //
        // =========================================================

        const Toast = Swal.mixin({

            toast: true,

            position: 'top-end',

            showConfirmButton: false,

            showCloseButton: true,

            timer: 2000,

            timerProgressBar: true,

            width: '360px',

            padding: '12px',

            background:
                document.documentElement.classList.contains('dark')
                    ? '#0f172a'
                    : '#ffffff',

            color:
                document.documentElement.classList.contains('dark')
                    ? '#f8fafc'
                    : '#0f172a',

            customClass: {

                popup:
                    'rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700',

                title:
                    'text-sm font-bold',

                htmlContainer:
                    'text-xs leading-relaxed'

            },

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


        // =========================================================
        // GLOBAL SHOW TOAST
        // =========================================================
        //
        // Contoh penggunaan dari JavaScript:
        //
        // showToast('success', 'Produk berhasil ditambahkan');
        //
        // showToast('error', 'Terjadi kesalahan');
        //
        // showToast('warning', 'Stok hampir habis');
        //
        // showToast('info', 'Silakan login terlebih dahulu');
        //
        // Bisa juga menggunakan title:
        //
        // showToast(
        //     'success',
        //     'Data berhasil disimpan',
        //     'Berhasil'
        // );
        //
        // =========================================================

        function showToast(
            type = 'info',
            message = '',
            title = null
        ) {

            const titles = {

                success: 'Berhasil',

                error: 'Terjadi Kesalahan',

                warning: 'Perhatian',

                info: 'Informasi'

            };


            // Pastikan tipe icon valid
            const validTypes = [
                'success',
                'error',
                'warning',
                'info'
            ];


            if (!validTypes.includes(type)) {

                type = 'info';

            }


            Toast.fire({

                icon: type,

                title:
                    title ||
                    titles[type] ||
                    'Informasi',

                text: message

            });

        }


        // =========================================================
        // GLOBAL SESSION NOTIFICATION
        // =========================================================
        //
        // Semua flash message Laravel ditampilkan melalui
        // showToast().
        //
        // =========================================================


        // ---------------------------------------------------------
        // SUCCESS
        // ---------------------------------------------------------

        @if(session('sukses'))

            showToast(
                'success',
                @json(session('sukses'))
            );

        @elseif(session('success'))

            showToast(
                'success',
                @json(session('success'))
            );

        @endif


        // ---------------------------------------------------------
        // WARNING
        // ---------------------------------------------------------

        @if(session('warning'))

            showToast(
                'warning',
                @json(session('warning'))
            );

        @endif


        // ---------------------------------------------------------
        // INFO
        // ---------------------------------------------------------

        @if(session('info'))

            showToast(
                'info',
                @json(session('info'))
            );

        @endif


        // ---------------------------------------------------------
        // ERROR
        // ---------------------------------------------------------

        @if(session('error'))

            showToast(
                'error',
                @json(session('error'))
            );

        @endif


        // =========================================================
        // VALIDATION ERROR
        // =========================================================
        //
        // Jika Laravel memiliki validation error, cukup tampilkan
        // error pertama sebagai toast.
        //
        // =========================================================

        @if($errors->any())

            showToast(
                'error',
                @json($errors->first()),
                'Terjadi Kesalahan'
            );

        @endif

    </script>


    {{-- =========================================================
         PAGE-SPECIFIC JAVASCRIPT
    ========================================================== --}}

    @stack('scripts')

</body>

</html>