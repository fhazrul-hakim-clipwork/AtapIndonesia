

<?php $__env->startSection('title', 'Keranjang Belanja - AtapIndonesia'); ?>

<?php $__env->startSection('content'); ?>
<section class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
    <div class="mb-6 sm:mb-8 flex flex-col gap-4">
        <div>
            <span class="text-xs font-black text-orange-500 uppercase tracking-widest">Keranjang</span>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 dark:text-white mt-2">Ringkasan Pesanan Anda</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Kelola produk, jumlah, dan lihat total sebelum checkout.</p>
        </div>
        <a href="<?php echo e(route('katalog')); ?>" class="w-full sm:w-auto text-center text-xs font-black uppercase tracking-widest px-4 py-3 rounded-full bg-slate-900 text-white hover:bg-slate-800 transition">
            Lanjut Belanja
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-4 sm:p-6 shadow-sm">
            <?php if(count($cartItems) === 0): ?>
                <div class="text-center py-20">
                    <p class="text-lg font-black text-slate-900 dark:text-white">Keranjang Anda masih kosong.</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-3">Tambahkan produk untuk melihat ringkasan pesanan dan detail harga.</p>
                    <a href="<?php echo e(route('katalog')); ?>" class="mt-6 inline-block px-6 py-3 rounded-full bg-orange-500 text-slate-950 font-black uppercase text-xs tracking-wider transition hover:bg-orange-600">Jelajahi Katalog</a>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="rounded-3xl border border-slate-200 dark:border-slate-800 p-3 sm:p-4 bg-slate-50 dark:bg-slate-950">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <div>
                                    <h2 class="text-sm font-black text-slate-900 dark:text-white"><?php echo e($item['nama']); ?></h2>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Harga satuan: Rp <?php echo e(number_format($item['harga'], 0, ',', '.')); ?></p>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Stok tersedia: <?php echo e($item['stok']); ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-black uppercase text-slate-400 tracking-widest">Subtotal</p>
                                    <p class="text-base font-black text-slate-900 dark:text-white mt-1">Rp <?php echo e(number_format($item['subtotal'], 0, ',', '.')); ?></p>
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-1 gap-3 items-end">
                                <div class="flex flex-col gap-3">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                        <label class="text-[11px] font-bold text-slate-500 dark:text-slate-400">Jumlah</label>
                                        <div class="flex items-center gap-2">
                                            <form method="POST" action="<?php echo e(route('cart.update')); ?>">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="product_id" value="<?php echo e($item['id']); ?>">
                                                <input type="hidden" name="delta" value="-1">
                                                <button type="submit" class="w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 text-lg font-black">−</button>
                                            </form>
                                            <span class="min-w-12 text-center rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 px-3 py-2 text-sm font-black text-slate-900 dark:text-slate-100"><?php echo e($item['quantity']); ?></span>
                                            <form method="POST" action="<?php echo e(route('cart.update')); ?>">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="product_id" value="<?php echo e($item['id']); ?>">
                                                <input type="hidden" name="delta" value="1">
                                                <button type="submit" class="w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 text-lg font-black">+</button>
                                            </form>
                                        </div>
                                    </div>

                                    <form method="POST" action="<?php echo e(route('cart.update')); ?>" class="flex flex-col sm:flex-row gap-2">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="product_id" value="<?php echo e($item['id']); ?>">
                                        <input type="number" name="quantity" min="1" max="<?php echo e($item['stok']); ?>" value="<?php echo e($item['quantity']); ?>" class="w-full rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 px-3 py-2 text-sm" />
                                        <button type="submit" class="w-full sm:w-auto rounded-xl bg-slate-900 text-white px-4 py-2 text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition">Perbarui</button>
                                    </form>
                                </div>

                                <form method="POST" action="<?php echo e(route('cart.remove')); ?>" class="w-full sm:text-right">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="product_id" value="<?php echo e($item['id']); ?>">
                                    <button type="submit" class="w-full sm:w-auto rounded-xl bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white px-4 py-3 text-xs font-black uppercase tracking-widest transition">Hapus</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>

        <aside class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-4 sm:p-6 shadow-sm mt-2 lg:mt-0">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Ringkasan</p>
            <div class="mt-4 space-y-4">
                <div class="flex items-center justify-between text-sm text-slate-500 dark:text-slate-400">
                    <span>Total item</span>
                    <span><?php echo e($totalItems); ?></span>
                </div>
                <div class="flex items-center justify-between text-sm text-slate-500 dark:text-slate-400">
                    <span>Subtotal</span>
                    <span>Rp <?php echo e(number_format($total, 0, ',', '.')); ?></span>
                </div>
                <div class="border-t border-slate-200 dark:border-slate-800 pt-4">
                    <div class="flex items-center justify-between text-base font-black text-slate-900 dark:text-white">
                        <span>Total</span>
                        <span>Rp <?php echo e(number_format($total, 0, ',', '.')); ?></span>
                    </div>
                </div>
                <div class="space-y-3">
                    <?php if(count($cartItems) > 0): ?>
                        <a href="<?php echo e(route('checkout.index')); ?>" class="block w-full rounded-3xl bg-orange-500 text-slate-950 text-sm font-black uppercase tracking-widest py-3 text-center hover:bg-orange-600 transition">Lanjut ke Checkout</a>
                    <?php else: ?>
                        <button type="button" class="w-full rounded-3xl bg-orange-500/60 text-slate-950 text-sm font-black uppercase tracking-widest py-3 cursor-not-allowed" disabled>Lanjut ke Checkout</button>
                    <?php endif; ?>
                    <?php if(count($cartItems) > 0): ?>
                        <form method="POST" action="<?php echo e(route('cart.clear')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full rounded-3xl border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-slate-100 text-sm font-black uppercase tracking-widest py-3 hover:bg-slate-100 dark:hover:bg-slate-800 transition">Bersihkan Keranjang</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </aside>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\fhazr\Downloads\AtapIndonesia\AtapIndonesia\resources\views/cart.blade.php ENDPATH**/ ?>