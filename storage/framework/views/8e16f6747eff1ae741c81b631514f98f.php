<?php
    $size = $size ?? 'md';
    $sizes = [
        'sm' => 'h-8',
        'md' => 'h-10',
        'lg' => 'h-12',
        'xl' => 'h-16',
    ];
    $logoHeight = $sizes[$size] ?? $sizes['md'];
?>
<a href="<?php echo e(url('/')); ?>"
   class="flex items-center gap-2 group transition-transform hover:scale-105 shrink-0 <?php echo e($containerClass ?? ''); ?>"
   title="Kembali ke Beranda AtapIndonesia">
    <img src="<?php echo e(asset('img/logo-brand.svg')); ?>?v=<?php echo e(filemtime(public_path('img/logo-brand.svg'))); ?>"
         alt="Logo AtapIndonesia"
         class="<?php echo e($logoHeight); ?> w-auto object-contain drop-shadow-sm transition group-hover:drop-shadow-md shrink-0"
         onerror="this.onerror=null;this.src='<?php echo e(asset('img/logo-brand.svg')); ?>';">
    <?php if(!empty($showText)): ?>
        <div class="leading-tight min-w-0">
            <span class="block <?php echo e($textSize ?? 'text-base'); ?> font-black tracking-wider whitespace-nowrap <?php echo e(($textColor ?? '') === 'white' ? 'text-white' : 'text-slate-900'); ?>">
                ATAP<span class="text-orange-500">INDONESIA</span>
            </span>
            <?php if(!empty($tagline)): ?>
                <span class="block text-[9px] font-bold <?php echo e(($textColor ?? '') === 'white' ? 'text-slate-300' : 'text-slate-500'); ?> uppercase tracking-widest whitespace-nowrap"><?php echo e($tagline); ?></span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</a>
<?php /**PATH C:\Users\fhazr\Downloads\AtapIndonesia\AtapIndonesia\resources\views/partials/logo.blade.php ENDPATH**/ ?>