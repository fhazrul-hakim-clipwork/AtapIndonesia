<?php $__env->startSection('title', 'Katalog Material Atap - AtapIndonesia'); ?>

<?php $__env->startSection('content'); ?>

<section class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12">

    

    <div class="mb-6">

        <span class="text-xs font-black text-orange-500 uppercase tracking-widest">
            Katalog
        </span>

        <h1 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mt-2">
            Katalog Material Atap
        </h1>

        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            Pilih material berkualitas untuk kebutuhan atap dan talang Anda.
        </p>

    </div>


    

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4">

        <?php $__currentLoopData = $data['produk']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <div
                class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition group overflow-hidden"
            >

                

                <div class="aspect-square bg-slate-50 dark:bg-slate-950 relative overflow-hidden">

                    <?php if(!empty($produk['image'])): ?>

                        <?php if(\Illuminate\Support\Str::startsWith($produk['image'], 'http')): ?>

                            <img
                                src="<?php echo e($produk['image']); ?>"
                                alt="<?php echo e($produk['nama']); ?>"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                            >

                        <?php else: ?>

                            <img
                                src="<?php echo e(asset('storage/' . $produk['image'])); ?>"
                                alt="<?php echo e($produk['nama']); ?>"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                            >

                        <?php endif; ?>

                    <?php else: ?>

                        <div
                            class="w-full h-full flex items-center justify-center text-4xl text-slate-200 dark:text-slate-700"
                        >
                            📦
                        </div>

                    <?php endif; ?>


                    

                    <?php if(!empty($produk['promo'])): ?>

                        <div class="absolute top-2 left-2">

                            <span
                                class="text-[9px] font-black uppercase tracking-wider bg-red-500 text-white px-1.5 py-0.5 rounded-md"
                            >
                                <?php echo e($produk['promo']); ?>

                            </span>

                        </div>

                    <?php endif; ?>


                    

                    <?php if(!empty($produk['best'])): ?>

                        <div class="absolute top-2 right-2">

                            <span
                                class="text-[9px] font-black uppercase tracking-wider bg-amber-500 text-white px-1.5 py-0.5 rounded-md"
                            >
                                Best
                            </span>

                        </div>

                    <?php endif; ?>

                </div>


                

                <div class="p-3">

                    

                    <div class="flex items-center gap-1 mb-1">

                        <span class="text-amber-500 text-[10px]">
                            ⭐
                        </span>

                        <span class="text-[10px] font-bold text-slate-600 dark:text-slate-300">
                            <?php echo e($produk['rating']); ?>

                        </span>

                        <span class="text-[10px] text-slate-400">
                            •
                        </span>

                        <span class="text-[10px] text-slate-500 dark:text-slate-400">
                            Stok <?php echo e($produk['stok']); ?>

                        </span>

                    </div>


                    

                    <h3
                        class="text-xs font-black text-slate-900 dark:text-white leading-tight mb-2 line-clamp-2"
                        title="<?php echo e($produk['nama']); ?>"
                    >
                        <?php echo e($produk['nama']); ?>

                    </h3>


                    

                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mb-2">
                        <?php echo e($produk['material']); ?>

                    </p>


                    

                    <div class="flex items-center justify-between">

                        <p class="text-sm font-black text-orange-600">
                            Rp <?php echo e(number_format($produk['harga'], 0, ',', '.')); ?>

                        </p>

                    </div>


                    

                    <div class="mt-2 grid grid-cols-2 gap-1.5">


                        

                        <form
                            method="POST"
                            action="<?php echo e(route('cart.buy_now')); ?>"
                            class="inline"
                        >

                            <?php echo csrf_field(); ?>

                            <input
                                type="hidden"
                                name="product_id"
                                value="<?php echo e($produk['id']); ?>"
                            >

                            <input
                                type="hidden"
                                name="quantity"
                                value="1"
                            >

                            <button
                                type="submit"
                                class="w-full rounded-lg bg-orange-500 text-white text-[10px] font-black py-1.5 hover:bg-orange-600 transition"
                            >
                                Beli
                            </button>

                        </form>


                        

                        <form
                            method="POST"
                            action="<?php echo e(route('cart.add')); ?>"
                            class="inline add-to-cart-form"
                        >

                            <?php echo csrf_field(); ?>

                            <input
                                type="hidden"
                                name="product_id"
                                value="<?php echo e($produk['id']); ?>"
                            >

                            <input
                                type="hidden"
                                name="quantity"
                                value="1"
                            >

                            <button
                                type="submit"
                                class="w-full rounded-lg bg-slate-900 text-white text-[10px] font-black py-1.5 hover:bg-slate-800 transition"
                            >
                                + Keranjang
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>

</section>




<script>

document.addEventListener('DOMContentLoaded', function () {

    'use strict';


    /*
    |--------------------------------------------------------------------------
    | UPDATE COUNTER KERANJANG
    |--------------------------------------------------------------------------
    */

    function updateCartCount(count) {

        count = Number(count);

        if (!Number.isFinite(count)) {
            return;
        }

        const cartCount =
            document.getElementById('cart-count');


        /*
        |--------------------------------------------------------------------------
        | COUNTER TIDAK DITEMUKAN
        |--------------------------------------------------------------------------
        */

        if (!cartCount) {

            console.warn(
                'Elemen #cart-count tidak ditemukan.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE ANGKA
        |--------------------------------------------------------------------------
        */

        cartCount.textContent = count;


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN / SEMBUNYIKAN
        |--------------------------------------------------------------------------
        */

        if (count > 0) {

            cartCount.classList.remove('hidden');

        } else {

            cartCount.classList.add('hidden');

        }


        /*
        |--------------------------------------------------------------------------
        | ANIMASI
        |--------------------------------------------------------------------------
        */

        cartCount.classList.remove('scale-125');

        void cartCount.offsetWidth;

        cartCount.classList.add('scale-125');


        setTimeout(function () {

            cartCount.classList.remove(
                'scale-125'
            );

        }, 300);

    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL SEMUA FORM + KERANJANG
    |--------------------------------------------------------------------------
    */

    const cartForms =
        document.querySelectorAll(
            '.add-to-cart-form'
        );


    /*
    |--------------------------------------------------------------------------
    | LOOP FORM
    |--------------------------------------------------------------------------
    */

    cartForms.forEach(function (form) {

        form.addEventListener(
            'submit',
            async function (event) {

                /*
                |--------------------------------------------------------------
                | CEGAH REFRESH
                |--------------------------------------------------------------
                */

                event.preventDefault();


                /*
                |--------------------------------------------------------------
                | AMBIL BUTTON
                |--------------------------------------------------------------
                */

                const button =
                    form.querySelector(
                        'button[type="submit"]'
                    );


                if (!button) {
                    return;
                }


                /*
                |--------------------------------------------------------------
                | SIMPAN TEXT ASLI
                |--------------------------------------------------------------
                */

                const originalText =
                    button.innerHTML;


                /*
                |--------------------------------------------------------------
                | DISABLE BUTTON
                |--------------------------------------------------------------
                */

                button.disabled = true;

                button.innerHTML = `
                    <span class="inline-flex items-center gap-1">
                        <span class="animate-spin">⟳</span>
                        Menambahkan
                    </span>
                `;


                try {

                    /*
                    |--------------------------------------------------------------------------
                    | FORM DATA
                    |--------------------------------------------------------------------------
                    */

                    const formData =
                        new FormData(form);


                    /*
                    |--------------------------------------------------------------------------
                    | AJAX REQUEST
                    |--------------------------------------------------------------------------
                    */

                    const response =
                        await fetch(
                            form.action,
                            {
                                method: 'POST',

                                headers: {

                                    'X-Requested-With':
                                        'XMLHttpRequest',

                                    'Accept':
                                        'application/json'

                                },

                                body:
                                    formData
                            }
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | CEK RESPONSE
                    |--------------------------------------------------------------------------
                    */

                    const contentType =
                        response.headers.get(
                            'content-type'
                        ) || '';


                    if (
                        !contentType.includes(
                            'application/json'
                        )
                    ) {

                        throw new Error(
                            'Server tidak mengembalikan response JSON.'
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | BACA JSON
                    |--------------------------------------------------------------------------
                    */

                    const data =
                        await response.json();


                    /*
                    |--------------------------------------------------------------------------
                    | CEK STATUS
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !response.ok ||
                        !data.success
                    ) {

                        throw new Error(
                            data.message ||
                            'Gagal menambahkan produk ke keranjang.'
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | ==========================================================
                    | UPDATE COUNTER TANPA REFRESH
                    | ==========================================================
                    */

                    if (
                        data.cart_count !== undefined &&
                        data.cart_count !== null
                    ) {

                        updateCartCount(
                            data.cart_count
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | SWEETALERT
                    |--------------------------------------------------------------------------
                    */

                    if (
                        typeof Swal !== 'undefined'
                    ) {

                        await Swal.fire({

                            icon: 'success',

                            title: 'Berhasil!',

                            text:
                                data.message ||
                                'Produk berhasil dimasukkan ke keranjang.',

                            confirmButtonText:
                                'Lanjut Belanja',

                            confirmButtonColor:
                                '#f97316',

                        });

                    }


                } catch (error) {

                    /*
                    |--------------------------------------------------------------------------
                    | LOG ERROR
                    |--------------------------------------------------------------------------
                    */

                    console.error(
                        'Add to cart error:',
                        error
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | SWEETALERT ERROR
                    |--------------------------------------------------------------------------
                    */

                    if (
                        typeof Swal !== 'undefined'
                    ) {

                        Swal.fire({

                            icon: 'error',

                            title: 'Gagal!',

                            text:
                                error.message ||
                                'Gagal menambahkan produk ke keranjang.',

                            confirmButtonText:
                                'OK',

                            confirmButtonColor:
                                '#f97316',

                        });

                    }

                } finally {

                    /*
                    |--------------------------------------------------------------------------
                    | KEMBALIKAN BUTTON
                    |--------------------------------------------------------------------------
                    */

                    button.disabled = false;

                    button.innerHTML =
                        originalText;

                }

            }
        );

    });

});

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\fhazr\Downloads\AtapIndonesia\AtapIndonesia\resources\views/katalog.blade.php ENDPATH**/ ?>