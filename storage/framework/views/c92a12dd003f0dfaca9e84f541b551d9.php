<footer class="bg-slate-950 border-t border-slate-900 mt-auto">
    <div class="max-w-7xl mx-auto px-6 py-10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div class="md:col-span-2">
                <?php echo $__env->make('partials.logo', ['size' => 'md', 'showText' => true, 'textColor' => 'white'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <p class="text-xs text-slate-400 mt-3 max-w-md leading-relaxed">
                    Platform distribusi material atap & sistem pakar mitigasi talang air pertama di Indonesia yang
                    mengotomatisasi rantai pasok dari toko bangunan ke konsumen.
                </p>
            </div>
            <div>
                <h4 class="text-xs font-black text-white uppercase tracking-widest mb-3">Platform</h4>
                <ul class="space-y-2 text-xs text-slate-400">
                    <li><a href="<?php echo e(route('katalog')); ?>" class="hover:text-orange-500">Katalog Produk</a></li>
                    <li><a href="<?php echo e(url('/')); ?>#simulator" class="hover:text-orange-500">Sistem Pakar</a></li>
                    <li><a href="<?php echo e(route('register')); ?>" class="hover:text-orange-500">Daftar</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-xs font-black text-white uppercase tracking-widest mb-3">Hubungi Kami</h4>
                <ul class="space-y-2 text-xs text-slate-400">
                    <li><a href="mailto:atap.idn@gmail.com" class="hover:text-orange-500">atap.idn@gmail.com</a></li>
                    <li>+62 812-3456-7890</li>
                    <li class="leading-relaxed">Jalan Karang Duren, RT 002 RW 003, Sokaraja Lor, Sokaraja (Gerbang Hitam), Sokaraja, Kab. Banyumas, Jawa Tengah</li>
                </ul>
            </div>
        </div>
        <div class="border-t border-slate-900 pt-4 text-center text-[10px] text-slate-500 font-mono">
            &copy; 2026 ATAP INDONESIA — All rights reserved.
        </div>
    </div>
</footer><?php /**PATH C:\Users\fhazr\Downloads\AtapIndonesia\AtapIndonesia\resources\views/partials/footer.blade.php ENDPATH**/ ?>