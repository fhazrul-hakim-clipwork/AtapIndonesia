<?php $__env->startSection('title', 'Checkout - AtapIndonesia'); ?>

<?php $__env->startSection('content'); ?>
<section class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12">

    
    <div class="mb-8">
        <span class="text-xs font-black text-orange-500 uppercase tracking-widest">
            Checkout
        </span>

        <h1 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mt-2">
            Lengkapi Data Pesanan
        </h1>

        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
            Masukkan data penerima. Ongkir akan dihitung otomatis berdasarkan alamat dan ekspedisi.
        </p>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr] gap-6">

        
        <form
            method="POST"
            action="<?php echo e(route('checkout.process')); ?>"
            class="space-y-6 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-4 sm:p-6 shadow-sm"
            id="checkout-form"
        >

            <?php echo csrf_field(); ?>


            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        name="nama"
                        value="<?php echo e(old('nama', $userName)); ?>"
                        required
                        class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-4 py-3 text-sm"
                    />
                </div>


                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="<?php echo e(old('email')); ?>"
                        required
                        class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-4 py-3 text-sm"
                    />
                </div>

            </div>


            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                        Nomor Telepon
                    </label>

                    <input
                        type="text"
                        name="telepon"
                        value="<?php echo e(old('telepon')); ?>"
                        required
                        class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-4 py-3 text-sm"
                    />
                </div>


                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                        Alamat Pengiriman
                    </label>

                    <input
                        type="text"
                        name="alamat"
                        value="<?php echo e(old('alamat')); ?>"
                        required
                        class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-4 py-3 text-sm"
                        id="alamat-input"
                        placeholder="Contoh: Purwokerto, Banyumas"
                    />

                    <p class="text-[10px] text-slate-400 mt-2">
                        Masukkan minimal nama kota/kabupaten agar ongkir dapat dihitung.
                    </p>
                </div>

            </div>


            
            <div>

                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">
                    Ekspedisi Pengiriman
                </label>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                    
                    <label class="rounded-2xl border border-slate-200 dark:border-slate-800 p-3 cursor-pointer hover:border-orange-500 transition flex items-center gap-3">

                        <input
                            type="radio"
                            name="shipping_provider"
                            value="lalamove"
                            checked
                            class="mr-2"
                        />

                        <img
                            src="<?php echo e(asset('img/logos/lalamove.svg')); ?>"
                            alt="Lalamove"
                            class="h-6 w-auto rounded"
                        />

                        <div>
                            <span class="font-semibold text-sm">
                                Lalamove
                            </span>

                            <p class="text-[10px] text-slate-500">
                                Motor / Van / Truck
                            </p>
                        </div>

                    </label>


                    
                    <label class="rounded-2xl border border-slate-200 dark:border-slate-800 p-3 cursor-pointer hover:border-orange-500 transition flex items-center gap-3">

                        <input
                            type="radio"
                            name="shipping_provider"
                            value="deliveree"
                            class="mr-2"
                        />

                        <img
                            src="<?php echo e(asset('img/logos/deliveree.svg')); ?>"
                            alt="Deliveree"
                            class="h-6 w-auto rounded"
                        />

                        <div>
                            <span class="font-semibold text-sm">
                                Deliveree
                            </span>

                            <p class="text-[10px] text-slate-500">
                                Box truck / Van
                            </p>
                        </div>

                    </label>

                </div>

            </div>


            
            <div>

                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">
                    Metode Pembayaran
                </label>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                    
                    <label class="rounded-2xl border border-slate-200 dark:border-slate-800 p-3 cursor-pointer hover:border-orange-500 transition flex items-center gap-3">

                        <input
                            type="radio"
                            name="metode_pembayaran"
                            value="va"
                            checked
                            class="mr-2"
                        />

                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center">
                            <img
                                src="<?php echo e(asset('img/logos/bca.svg')); ?>"
                                alt="BCA"
                                class="h-5 w-auto rounded"
                            />
                        </div>

                        <div>
                            <span class="font-semibold text-sm">
                                Virtual Account
                            </span>

                            <p class="text-[10px] text-slate-500">
                                BCA VA
                            </p>
                        </div>

                    </label>


                    
                    <label class="rounded-2xl border border-slate-200 dark:border-slate-800 p-3 cursor-pointer hover:border-orange-500 transition flex items-center gap-3">

                        <input
                            type="radio"
                            name="metode_pembayaran"
                            value="qris"
                            class="mr-2"
                        />

                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-lg">
                            📱
                        </div>

                        <div>
                            <span class="font-semibold text-sm">
                                QRIS
                            </span>

                            <p class="text-[10px] text-slate-500">
                                Scan QR Code
                            </p>
                        </div>

                    </label>


                    
                    <label class="rounded-2xl border border-slate-200 dark:border-slate-800 p-3 cursor-pointer hover:border-orange-500 transition flex items-center gap-3">

                        <input
                            type="radio"
                            name="metode_pembayaran"
                            value="bank"
                            class="mr-2"
                        />

                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center">

                            <img
                                src="<?php echo e(asset('img/logos/bca.svg')); ?>"
                                alt="BCA"
                                class="h-5 w-auto rounded"
                            />

                        </div>

                        <div>
                            <span class="font-semibold text-sm">
                                Transfer Bank
                            </span>

                            <p class="text-[10px] text-slate-500">
                                BCA only
                            </p>
                        </div>

                    </label>

                </div>

            </div>


            
            <button
                type="submit"
                class="w-full rounded-3xl bg-orange-500 text-slate-950 text-sm font-black uppercase tracking-widest py-3 hover:bg-orange-600 transition"
            >
                Buat Pesanan
            </button>

        </form>


        
        <aside class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-4 sm:p-6 shadow-sm h-fit mt-2 lg:mt-0">

            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">
                Ringkasan Pesanan
            </p>


            
            <div class="mt-4 space-y-3">

                <?php $__currentLoopData = $summary['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <div class="flex items-center justify-between text-sm gap-4">

                        <span class="text-slate-700 dark:text-slate-300">
                            <?php echo e($item['nama']); ?> × <?php echo e($item['quantity']); ?>

                        </span>

                        <span class="font-semibold text-slate-900 dark:text-white whitespace-nowrap">
                            Rp <?php echo e(number_format($item['subtotal'], 0, ',', '.')); ?>

                        </span>

                    </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>


            
            <div class="mt-6 border-t border-slate-200 dark:border-slate-800 pt-4 space-y-3 text-sm">

                
                <div class="flex justify-between text-slate-500 dark:text-slate-400">

                    <span>
                        Subtotal
                    </span>

                    <span class="font-semibold text-slate-900 dark:text-white">
                        Rp <?php echo e(number_format($summary['subtotal'], 0, ',', '.')); ?>

                    </span>

                </div>


                
                <div class="flex justify-between text-slate-500 dark:text-slate-400">

                    <span>
                        Ekspedisi
                    </span>

                    <span
                        id="shipping-provider-display"
                        class="font-semibold text-slate-900 dark:text-white"
                    >
                        Lalamove
                    </span>

                </div>


                
                <div class="flex justify-between text-slate-500 dark:text-slate-400">

                    <span>
                        Jarak Estimasi
                    </span>

                    <span
                        id="shipping-distance-display"
                        class="font-semibold text-slate-900 dark:text-white"
                    >
                        -
                    </span>

                </div>


                
                <div class="flex justify-between text-slate-500 dark:text-slate-400">

                    <span>
                        Ongkir
                    </span>

                    <span
                        id="shipping-fee-display"
                        class="font-bold text-orange-500"
                    >
                        Masukkan alamat
                    </span>

                </div>


                
                <div class="flex justify-between text-base font-black text-slate-900 dark:text-white border-t border-slate-200 dark:border-slate-800 pt-3 mt-3">

                    <span>
                        Total Pembayaran
                    </span>

                    <span
                        id="total-display"
                        class="text-orange-500"
                    >
                        Rp <?php echo e(number_format($summary['subtotal'], 0, ',', '.')); ?>

                    </span>

                </div>

            </div>


            
            <div
                id="shipping-loading"
                class="mt-3 text-[10px] text-slate-400 hidden"
            >
                Menghitung ongkir...
            </div>


            
            <div class="mt-5 rounded-2xl bg-orange-500/10 border border-orange-500/20 p-3">

                <p class="text-[11px] font-bold text-orange-600">
                    Informasi Ongkir
                </p>

                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                    Ongkir dihitung berdasarkan estimasi jarak dari gudang AtapIndonesia
                    ke alamat tujuan dan ekspedisi yang dipilih.
                </p>

            </div>

        </aside>

    </div>
</section>



<script>
(function () {

    'use strict';

    const alamatInput =
        document.getElementById('alamat-input');

    const shippingDisplay =
        document.getElementById('shipping-fee-display');

    const distanceDisplay =
        document.getElementById('shipping-distance-display');

    const totalDisplay =
        document.getElementById('total-display');

    const providerDisplay =
        document.getElementById('shipping-provider-display');

    const loading =
        document.getElementById('shipping-loading');

    const checkoutForm =
        document.getElementById('checkout-form');

    const subtotal =
        Number(<?php echo e($summary['subtotal']); ?>);

    let debounceTimer = null;


    /*
    |--------------------------------------------------------------------------
    | FORMAT RUPIAH
    |--------------------------------------------------------------------------
    */

    function formatRupiah(number) {

        return 'Rp ' +
            Number(number || 0).toLocaleString('id-ID');

    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT JARAK
    |--------------------------------------------------------------------------
    */

    function formatDistance(distance) {

        const value = Number(distance || 0);

        if (!Number.isFinite(value) || value <= 0) {
            return '-';
        }

        return value.toLocaleString(
            'id-ID',
            {
                minimumFractionDigits: 1,
                maximumFractionDigits: 1
            }
        ) + ' km';

    }


    /*
    |--------------------------------------------------------------------------
    | PROVIDER
    |--------------------------------------------------------------------------
    */

    function getProvider() {

        const selected =
            document.querySelector(
                'input[name="shipping_provider"]:checked'
            );

        return selected
            ? selected.value
            : 'lalamove';

    }


    /*
    |--------------------------------------------------------------------------
    | HITUNG ONGKIR
    |--------------------------------------------------------------------------
    */

    window.calculateShipping = function () {

        if (!alamatInput) {
            return;
        }


        const address =
            alamatInput.value.trim();

        const provider =
            getProvider();


        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN PROVIDER
        |--------------------------------------------------------------------------
        */

        providerDisplay.textContent =
            provider === 'lalamove'
                ? 'Lalamove'
                : 'Deliveree';


        /*
        |--------------------------------------------------------------------------
        | RESET JIKA ALAMAT KURANG
        |--------------------------------------------------------------------------
        */

        if (address.length < 8) {

            shippingDisplay.textContent =
                'Masukkan alamat';

            distanceDisplay.textContent =
                '-';

            totalDisplay.textContent =
                formatRupiah(subtotal);

            return;

        }


        clearTimeout(debounceTimer);


        debounceTimer =
            setTimeout(async function () {

                loading.classList.remove('hidden');

                shippingDisplay.textContent =
                    'Menghitung...';

                distanceDisplay.textContent =
                    'Menghitung...';


                try {

                    const formData =
                        new FormData();

                    formData.append(
                        'alamat',
                        address
                    );

                    formData.append(
                        'shipping_provider',
                        provider
                    );

                    formData.append(
                        '_token',
                        '<?php echo e(csrf_token()); ?>'
                    );


                    const response =
                        await fetch(
                            '<?php echo e(route('api.shipping.calculate')); ?>',
                            {
                                method: 'POST',

                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },

                                body: formData
                            }
                        );


                    const contentType =
                        response.headers.get('content-type') || '';


                    if (!contentType.includes('application/json')) {

                        throw new Error(
                            'Server tidak mengembalikan JSON.'
                        );

                    }


                    const data =
                        await response.json();


                    if (!response.ok) {

                        throw new Error(
                            data.message ||
                            'Gagal menghitung ongkir.'
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | HASIL BERHASIL
                    |--------------------------------------------------------------------------
                    */

                    if (
                        data.success &&
                        data.data
                    ) {

                        const fee =
                            Number(
                                data.data.fee || 0
                            );

                        const distance =
                            Number(
                                data.data.distance_km || 0
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | ONGKIR
                        |--------------------------------------------------------------------------
                        */

                        shippingDisplay.textContent =
                            formatRupiah(fee);


                        /*
                        |--------------------------------------------------------------------------
                        | JARAK
                        |--------------------------------------------------------------------------
                        */

                        distanceDisplay.textContent =
                            formatDistance(distance);


                        /*
                        |--------------------------------------------------------------------------
                        | TOTAL
                        |--------------------------------------------------------------------------
                        */

                        const total =
                            subtotal + fee;

                        totalDisplay.textContent =
                            formatRupiah(total);


                    } else {

                        throw new Error(
                            data.message ||
                            'Ongkir tidak dapat dihitung.'
                        );

                    }

                } catch (error) {

                    console.error(
                        'Shipping calculation error:',
                        error
                    );

                    shippingDisplay.textContent =
                        'Tidak tersedia';

                    distanceDisplay.textContent =
                        '-';

                    totalDisplay.textContent =
                        formatRupiah(subtotal);

                } finally {

                    loading.classList.add('hidden');

                }

            }, 500);

    };


    /*
    |--------------------------------------------------------------------------
    | ALAMAT BERUBAH
    |--------------------------------------------------------------------------
    */

    if (alamatInput) {

        alamatInput.addEventListener(
            'input',
            window.calculateShipping
        );

    }


    /*
    |--------------------------------------------------------------------------
    | EKSPEDISI BERUBAH
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll(
            'input[name="shipping_provider"]'
        )
        .forEach(function (input) {

            input.addEventListener(
                'change',
                window.calculateShipping
            );

        });


    /*
    |--------------------------------------------------------------------------
    | HITUNG SAAT HALAMAN SELESAI
    |--------------------------------------------------------------------------
    */

    if (
        alamatInput &&
        alamatInput.value.trim().length >= 8
    ) {

        window.calculateShipping();

    }


    /*
    |--------------------------------------------------------------------------
    | SUBMIT CHECKOUT
    |--------------------------------------------------------------------------
    */

    if (checkoutForm) {

        checkoutForm.addEventListener(
            'submit',
            function () {

                if (
                    typeof Swal !== 'undefined'
                ) {

                    Swal.fire({

                        title: 'Memproses Pesanan',

                        text: 'Mohon tunggu sebentar...',

                        allowOutsideClick: false,

                        allowEscapeKey: false,

                        didOpen: function () {

                            Swal.showLoading();

                        }

                    });

                }

            }
        );

    }

})();
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\fhazr\Downloads\AtapIndonesia\AtapIndonesia\resources\views/checkout.blade.php ENDPATH**/ ?>