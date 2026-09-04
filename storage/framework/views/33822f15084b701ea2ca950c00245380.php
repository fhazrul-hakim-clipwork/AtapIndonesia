<?php
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
?>

<nav class="bg-slate-900 border-b border-slate-800 sticky top-0 z-50 px-6 py-3 shadow-lg">
    <div class="max-w-7xl mx-auto flex items-center justify-between">

        
        <?php echo $__env->make('partials.logo', [
            'size' => 'md',
            'showText' => true,
            'textColor' => 'white'
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <div class="hidden md:flex items-center gap-6 text-xs font-bold uppercase tracking-wider text-slate-300">

            <a
                href="<?php echo e(url('/')); ?>#simulator"
                class="hover:text-orange-500 transition">
                Sistem Pakar
            </a>

            <a
                href="<?php echo e(route('katalog')); ?>"
                class="hover:text-orange-500 transition <?php echo e($currentRoute == 'katalog' ? 'text-orange-500' : ''); ?>">
                Katalog
            </a>

            <?php if($isLoggedIn): ?>
                <a
                    href="<?php echo e($userRole == 'admin'
                        ? route('dashboard.admin')
                        : route('dashboard.pembeli')); ?>"
                    class="hover:text-orange-500 transition">
                    Dashboard
                </a>
            <?php endif; ?>

        </div>

        
        <div class="flex items-center gap-2">

            
            <button
                id="theme-toggle"
                type="button"
                class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-3 py-2 rounded-lg text-xs font-bold transition"
                title="Toggle Dark/Light">
                Tema
            </button>

            
            <a
                href="<?php echo e(route('cart.index')); ?>"
                class="text-xs font-bold text-slate-300 hover:text-orange-500 transition flex items-center gap-2"
                aria-label="Keranjang">

                <span class="text-base">🛒</span>

                
                <span
                    id="cart-count"
                    class="<?php echo e($cartCount > 0 ? '' : 'hidden'); ?> bg-orange-500 text-white text-[10px] font-black px-2 py-1 rounded-full">
                    <?php echo e($cartCount); ?>

                </span>

            </a>

            
            <?php if($isLoggedIn): ?>

                <div class="hidden sm:flex items-center gap-2 bg-slate-800 px-3 py-1.5 rounded-lg">

                    <div
                        class="w-7 h-7 rounded-full bg-orange-500 flex items-center justify-center text-[11px] font-black text-white">
                        <?php echo e(strtoupper(substr($userName, 0, 2))); ?>

                    </div>

                    <span class="text-xs font-bold text-slate-200">
                        <?php echo e(ucfirst($userName)); ?>

                    </span>

                </div>

                
                <a
                    href="<?php echo e(route('logout')); ?>"
                    class="bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white text-xs font-bold px-3 py-2 rounded-lg transition border border-red-500/30">
                    Keluar
                </a>

            <?php else: ?>

                
                <a
                    href="<?php echo e(route('login')); ?>"
                    class="text-xs font-bold text-slate-300 hover:text-white px-3 py-2 transition <?php echo e($currentRoute == 'login' ? 'text-orange-500' : ''); ?>">
                    Masuk
                </a>

                
                <a
                    href="<?php echo e(route('register')); ?>"
                    class="bg-orange-500 hover:bg-orange-600 text-slate-950 text-xs font-black uppercase px-4 py-2 rounded-lg transition tracking-wider shadow-md">
                    Daftar
                </a>

            <?php endif; ?>

        </div>

    </div>
</nav><?php /**PATH C:\Users\fhazr\Downloads\AtapIndonesia\AtapIndonesia\resources\views/partials/navbar.blade.php ENDPATH**/ ?>