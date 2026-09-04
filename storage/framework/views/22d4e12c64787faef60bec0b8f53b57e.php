<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'AtapIndonesia — Sistem Pakar Talang Air & Distribusi Material'); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('description', 'Platform distribusi material atap & sistem pakar talang air untuk Indonesia'); ?>">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>

    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('img/logo-brand.svg')); ?>">

    <?php echo $__env->yieldPushContent('styles'); ?>
    <?php echo $__env->yieldPushContent('head-scripts'); ?>
</head>
<body class="<?php echo $__env->yieldContent('body-class', 'bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100'); ?> font-sans min-h-screen flex flex-col transition-colors duration-300">

    <?php if(!isset($hideNavbar) || !$hideNavbar): ?>
        <?php echo $__env->yieldContent('navbar', ''); ?>
        <?php echo $__env->make('partials.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>

    <main class="flex-1">
        <?php if(session('sukses')): ?>
            <div class="max-w-7xl mx-auto px-6 pt-4">
                <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold px-4 py-3 rounded-xl">
                    <?php echo e(session('sukses')); ?>

                </div>
            </div>
        <?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php if(!isset($hideFooter) || !$hideFooter): ?>
        <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>

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
        <?php if($errors->any()): ?>
            Swal.fire({
                icon: 'error',
                title: 'Terdapat Kesalahan',
                html: `
                    <ul class="text-left text-sm text-red-500 mt-2 space-y-1">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>- <?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                `,
                confirmButtonColor: '#f97316'
            });
        <?php elseif(session('sukses')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?php echo e(session('sukses')); ?>',
                confirmButtonColor: '#f97316'
            });
        <?php endif; ?>
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\fhazr\Downloads\AtapIndonesia\AtapIndonesia\resources\views/layouts/app.blade.php ENDPATH**/ ?>