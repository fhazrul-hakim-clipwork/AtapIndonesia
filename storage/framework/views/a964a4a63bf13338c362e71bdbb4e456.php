<?php $__env->startSection('title', 'Detail Pesanan ' . $order->order_id . ' | AtapIndonesia'); ?>

<?php $__env->startSection('content'); ?>

<?php

    /*
    |--------------------------------------------------------------------------
    | STATUS PESANAN
    |--------------------------------------------------------------------------
    */

    $status = strtolower($order->status ?? '');


    $statusLabel = match ($status) {

        'menunggu_pembayaran'
            => 'Menunggu Pembayaran',

        'dibayar'
            => 'Pembayaran Berhasil',

        'dikemas'
            => 'Sedang Dikemas',

        'dikirim'
            => 'Sedang Dikirim',

        'selesai'
            => 'Pesanan Selesai',

        'dibatalkan'
            => 'Pesanan Dibatalkan',

        default
            => ucwords(
                str_replace(
                    '_',
                    ' ',
                    $status ?: 'Diproses'
                )
            ),

    };


    /*
    |--------------------------------------------------------------------------
    | STATUS CLASS
    |--------------------------------------------------------------------------
    */

    $statusClass = match ($status) {

        'menunggu_pembayaran'
            => 'bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-500/10 dark:text-orange-400 dark:border-orange-500/20',

        'dibayar'
            => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20',

        'dikemas'
            => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20',

        'dikirim'
            => 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-500/10 dark:text-purple-400 dark:border-purple-500/20',

        'selesai'
            => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20',

        'dibatalkan'
            => 'bg-red-50 text-red-700 border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20',

        default
            => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',

    };


    /*
    |--------------------------------------------------------------------------
    | METODE PEMBAYARAN
    |--------------------------------------------------------------------------
    */

    $paymentMethod = strtolower(
        trim($order->payment_method ?? '')
    );


    /*
    |--------------------------------------------------------------------------
    | VIRTUAL ACCOUNT
    |--------------------------------------------------------------------------
    */

    $isVirtualAccount = in_array(

        $paymentMethod,

        [
            'va',
            'bca',
            'bca_va',
            'virtual_account',
            'virtual-account',
        ],

        true

    );


    /*
    |--------------------------------------------------------------------------
    | QRIS
    |--------------------------------------------------------------------------
    */

    $isQris = in_array(

        $paymentMethod,

        [
            'qris',
            'qr',
            'qr_code',
        ],

        true

    );


    /*
    |--------------------------------------------------------------------------
    | DATA QR
    |--------------------------------------------------------------------------
    |
    | QR ini adalah QR SIMULASI.
    |
    */

    $qrPaymentData = '';

    if ($isQris) {

        $qrPaymentData = json_encode(

            [
                'merchant' => 'AtapIndonesia',

                'order_id' => $order->order_id,

                'payment_method' => 'QRIS',

                'amount' => (int) $order->grand_total,

                'currency' => 'IDR',

            ],

            JSON_UNESCAPED_SLASHES

        );

    }

?>




<div
    class="min-h-screen
           bg-slate-50
           dark:bg-slate-950
           transition-colors">


    <main
        class="max-w-7xl
               mx-auto
               px-4
               sm:px-6
               py-8
               sm:py-12">


        

        <div class="mb-8">

            <div
                class="flex
                       flex-col
                       md:flex-row
                       md:items-end
                       md:justify-between
                       gap-5">


                <div>

                    <span
                        class="text-xs
                               font-black
                               text-orange-500
                               uppercase
                               tracking-widest">

                        Pesanan

                    </span>


                    <h1
                        class="text-3xl
                               md:text-4xl
                               font-black
                               text-slate-900
                               dark:text-white
                               mt-2">

                        Detail Pesanan

                    </h1>


                    <p
                        class="text-sm
                               text-slate-500
                               dark:text-slate-400
                               mt-2">

                        Informasi pesanan dan pembayaran Anda.

                    </p>

                </div>


                

                <div
                    class="inline-flex
                           items-center
                           gap-2
                           self-start
                           md:self-auto
                           px-4
                           py-2.5
                           rounded-2xl
                           border
                           font-black
                           text-sm
                           <?php echo e($statusClass); ?>">

                    <span
                        class="w-2
                               h-2
                               rounded-full
                               bg-current">
                    </span>

                    <?php echo e($statusLabel); ?>


                </div>

            </div>

        </div>


        

        <?php if(session('sukses')): ?>

            <div
                class="mb-6
                       rounded-3xl
                       border
                       border-emerald-200
                       bg-emerald-50
                       dark:bg-emerald-500/10
                       dark:border-emerald-500/20
                       p-5">

                <div class="flex gap-4">

                    <div
                        class="w-10
                               h-10
                               rounded-2xl
                               bg-emerald-100
                               dark:bg-emerald-500/20
                               text-emerald-600
                               dark:text-emerald-400
                               flex
                               items-center
                               justify-center
                               font-black
                               flex-shrink-0">

                        ✓

                    </div>


                    <div>

                        <p
                            class="font-black
                                   text-emerald-800
                                   dark:text-emerald-300">

                            Berhasil

                        </p>


                        <p
                            class="text-sm
                                   text-emerald-700
                                   dark:text-emerald-400
                                   mt-1">

                            <?php echo e(session('sukses')); ?>


                        </p>

                    </div>

                </div>

            </div>

        <?php endif; ?>


        

        <?php if(session('error')): ?>

            <div
                class="mb-6
                       rounded-3xl
                       border
                       border-red-200
                       bg-red-50
                       dark:bg-red-500/10
                       dark:border-red-500/20
                       p-5">

                <div class="flex gap-4">

                    <div
                        class="w-10
                               h-10
                               rounded-2xl
                               bg-red-100
                               dark:bg-red-500/20
                               text-red-600
                               dark:text-red-400
                               flex
                               items-center
                               justify-center
                               font-black">

                        !

                    </div>


                    <div>

                        <p
                            class="font-black
                                   text-red-800
                                   dark:text-red-300">

                            Terjadi Kesalahan

                        </p>


                        <p
                            class="text-sm
                                   text-red-700
                                   dark:text-red-400
                                   mt-1">

                            <?php echo e(session('error')); ?>


                        </p>

                    </div>

                </div>

            </div>

        <?php endif; ?>


        

        <div
            class="bg-white
                   dark:bg-slate-900
                   rounded-3xl
                   border
                   border-slate-200
                   dark:border-slate-800
                   shadow-sm
                   overflow-hidden
                   mb-6">


            <div class="p-5 sm:p-6">

                <div
                    class="flex
                           flex-col
                           sm:flex-row
                           sm:items-center
                           sm:justify-between
                           gap-5">


                    <div>

                        <p
                            class="text-[10px]
                                   font-black
                                   uppercase
                                   tracking-widest
                                   text-slate-400
                                   dark:text-slate-500">

                            Nomor Pesanan

                        </p>


                        <h2
                            class="text-2xl
                                   md:text-3xl
                                   font-black
                                   text-slate-900
                                   dark:text-white
                                   mt-1">

                            <?php echo e($order->order_id); ?>


                        </h2>

                    </div>


                    <div
                        class="text-left
                               sm:text-right">

                        <p
                            class="text-[10px]
                                   font-black
                                   uppercase
                                   tracking-widest
                                   text-slate-400
                                   dark:text-slate-500">

                            Total Pesanan

                        </p>


                        <p
                            class="text-xl
                                   md:text-2xl
                                   font-black
                                   text-orange-500
                                   mt-1">

                            Rp <?php echo e(number_format($order->grand_total, 0, ',', '.')); ?>


                        </p>

                    </div>

                </div>

            </div>

        </div>


        

        <?php if($isVirtualAccount): ?>

            <div
                class="bg-white
                       dark:bg-slate-900
                       rounded-3xl
                       border
                       border-slate-200
                       dark:border-slate-800
                       shadow-sm
                       overflow-hidden
                       mb-6">


                <div
                    class="p-5
                           sm:p-6
                           border-b
                           border-slate-200
                           dark:border-slate-800">

                    <span
                        class="text-[10px]
                               font-black
                               uppercase
                               tracking-widest
                               text-orange-500">

                        Metode Pembayaran

                    </span>


                    <h2
                        class="text-xl
                               md:text-2xl
                               font-black
                               text-slate-900
                               dark:text-white
                               mt-1">

                        BCA Virtual Account

                    </h2>


                    <p
                        class="text-sm
                               text-slate-500
                               dark:text-slate-400
                               mt-1">

                        Gunakan nomor Virtual Account untuk pembayaran.

                    </p>

                </div>


                <div class="p-5 sm:p-6">


                    <?php if($status === 'menunggu_pembayaran'): ?>

                        <div
                            class="mb-6
                                   rounded-3xl
                                   border
                                   border-orange-200
                                   bg-orange-50
                                   dark:bg-orange-500/10
                                   dark:border-orange-500/20
                                   p-5">

                            <div class="flex gap-4">

                                <div
                                    class="w-10
                                           h-10
                                           rounded-2xl
                                           bg-orange-100
                                           dark:bg-orange-500/20
                                           text-orange-600
                                           dark:text-orange-400
                                           flex
                                           items-center
                                           justify-center
                                           font-black
                                           flex-shrink-0">

                                    !

                                </div>


                                <div>

                                    <p
                                        class="font-black
                                               text-orange-800
                                               dark:text-orange-300">

                                        Menunggu Pembayaran

                                    </p>


                                    <p
                                        class="text-sm
                                               text-orange-700
                                               dark:text-orange-400
                                               mt-1
                                               leading-relaxed">

                                        Silakan lakukan pembayaran menggunakan
                                        nomor Virtual Account yang tertera di bawah.

                                    </p>

                                </div>

                            </div>

                        </div>

                    <?php elseif($status === 'dibayar'): ?>

                        <div
                            class="mb-6
                                   rounded-3xl
                                   border
                                   border-emerald-200
                                   bg-emerald-50
                                   dark:bg-emerald-500/10
                                   dark:border-emerald-500/20
                                   p-5">

                            <div class="flex gap-4">

                                <div
                                    class="w-10
                                           h-10
                                           rounded-2xl
                                           bg-emerald-100
                                           dark:bg-emerald-500/20
                                           text-emerald-600
                                           dark:text-emerald-400
                                           flex
                                           items-center
                                           justify-center
                                           font-black">

                                    ✓

                                </div>


                                <div>

                                    <p
                                        class="font-black
                                               text-emerald-800
                                               dark:text-emerald-300">

                                        Pembayaran Berhasil

                                    </p>


                                    <p
                                        class="text-sm
                                               text-emerald-700
                                               dark:text-emerald-400
                                               mt-1">

                                        Pembayaran untuk pesanan ini telah diterima.

                                    </p>

                                </div>

                            </div>

                        </div>

                    <?php endif; ?>


                    <?php if(!empty($order->virtual_account)): ?>

                        <p
                            class="text-xs
                                   font-black
                                   uppercase
                                   tracking-widest
                                   text-slate-500
                                   dark:text-slate-400
                                   mb-3">

                            Nomor Virtual Account BCA

                        </p>


                        <div
                            class="flex
                                   flex-col
                                   sm:flex-row
                                   gap-3">


                            <div
                                id="virtual-account-number"
                                data-va="<?php echo e($order->virtual_account); ?>"
                                class="flex-1
                                       min-h-[68px]
                                       bg-slate-50
                                       dark:bg-slate-950
                                       border
                                       border-slate-200
                                       dark:border-slate-800
                                       rounded-2xl
                                       px-5
                                       py-4
                                       flex
                                       items-center">

                                <span
                                    class="text-xl
                                           sm:text-2xl
                                           font-black
                                           text-slate-900
                                           dark:text-white
                                           tracking-[0.14em]">

                                    <?php echo e($order->virtual_account); ?>


                                </span>

                            </div>


                            <button
                                type="button"
                                onclick="copyVirtualAccount()"
                                class="sm:min-w-[140px]
                                       bg-orange-500
                                       hover:bg-orange-600
                                       text-slate-950
                                       px-6
                                       py-4
                                       rounded-2xl
                                       font-black
                                       text-sm
                                       transition">

                                Salin VA

                            </button>

                        </div>


                        <div
                            id="copy-va-message"
                            class="hidden
                                   mt-3
                                   text-xs
                                   text-emerald-600
                                   dark:text-emerald-400
                                   font-bold">

                            ✓ Nomor Virtual Account berhasil disalin.

                        </div>


                        <div
                            class="mt-6
                                   rounded-3xl
                                   border
                                   border-slate-200
                                   dark:border-slate-800
                                   bg-slate-50
                                   dark:bg-slate-950
                                   p-5">

                            <div
                                class="flex
                                       items-center
                                       justify-between
                                       gap-4">

                                <span
                                    class="text-sm
                                           font-semibold
                                           text-slate-500
                                           dark:text-slate-400">

                                    Total Pembayaran

                                </span>


                                <span
                                    class="text-xl
                                           md:text-2xl
                                           font-black
                                           text-orange-500">

                                    Rp <?php echo e(number_format($order->grand_total, 0, ',', '.')); ?>


                                </span>

                            </div>

                        </div>


                        <div class="mt-8">

                            <span
                                class="text-[10px]
                                       font-black
                                       uppercase
                                       tracking-widest
                                       text-orange-500">

                                Panduan

                            </span>


                            <h3
                                class="text-lg
                                       font-black
                                       text-slate-900
                                       dark:text-white
                                       mt-1">

                                Cara Pembayaran

                            </h3>


                            <div class="mt-5 space-y-5">

                                <?php $__currentLoopData = [

                                    [
                                        '1',
                                        'Buka layanan BCA',
                                        'Gunakan BCA Mobile, myBCA, atau ATM BCA.'
                                    ],

                                    [
                                        '2',
                                        'Pilih Virtual Account',
                                        'Pilih menu pembayaran Virtual Account.'
                                    ],

                                    [
                                        '3',
                                        'Masukkan nomor VA',
                                        'Masukkan nomor Virtual Account yang tertera di atas.'
                                    ],

                                    [
                                        '4',
                                        'Periksa pembayaran',
                                        'Pastikan nomor VA dan nominal pembayaran sudah benar.'
                                    ],

                                    [
                                        '5',
                                        'Selesaikan pembayaran',
                                        'Selesaikan transaksi dan simpan bukti pembayaran.'
                                    ]

                                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                    <div class="flex gap-4">

                                        <div
                                            class="flex-shrink-0
                                                   w-9
                                                   h-9
                                                   rounded-2xl
                                                   bg-orange-500
                                                   text-slate-950
                                                   flex
                                                   items-center
                                                   justify-center
                                                   font-black
                                                   text-sm">

                                            <?php echo e($step[0]); ?>


                                        </div>


                                        <div>

                                            <p
                                                class="font-bold
                                                       text-slate-900
                                                       dark:text-white">

                                                <?php echo e($step[1]); ?>


                                            </p>


                                            <p
                                                class="text-sm
                                                       text-slate-500
                                                       dark:text-slate-400
                                                       mt-1">

                                                <?php echo e($step[2]); ?>


                                            </p>

                                        </div>

                                    </div>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>

                        </div>


                        <div
                            class="mt-7
                                   rounded-3xl
                                   border
                                   border-orange-200
                                   bg-orange-50
                                   dark:bg-orange-500/10
                                   dark:border-orange-500/20
                                   p-5">

                            <p
                                class="font-black
                                       text-orange-800
                                       dark:text-orange-300
                                       text-sm">

                                Informasi Pembayaran

                            </p>


                            <p
                                class="text-xs
                                       text-orange-700
                                       dark:text-orange-400
                                       mt-1
                                       leading-relaxed">

                                Virtual Account ini merupakan nomor pembayaran
                                lokal untuk kebutuhan pengujian aplikasi.

                            </p>

                        </div>

                    <?php else: ?>

                        <div
                            class="rounded-3xl
                                   border
                                   border-orange-200
                                   bg-orange-50
                                   dark:bg-orange-500/10
                                   dark:border-orange-500/20
                                   p-5">

                            <p
                                class="font-black
                                       text-orange-800
                                       dark:text-orange-300">

                                Nomor Virtual Account belum tersedia.

                            </p>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        <?php endif; ?>


        

        <?php if($isQris): ?>

            <div
                class="bg-white
                       dark:bg-slate-900
                       rounded-3xl
                       border
                       border-slate-200
                       dark:border-slate-800
                       shadow-sm
                       overflow-hidden
                       mb-6">


                

                <div
                    class="p-5
                           sm:p-6
                           border-b
                           border-slate-200
                           dark:border-slate-800">

                    <span
                        class="text-[10px]
                               font-black
                               uppercase
                               tracking-widest
                               text-orange-500">

                        Metode Pembayaran

                    </span>


                    <h2
                        class="text-xl
                               md:text-2xl
                               font-black
                               text-slate-900
                               dark:text-white
                               mt-1">

                        QRIS

                    </h2>


                    <p
                        class="text-sm
                               text-slate-500
                               dark:text-slate-400
                               mt-1">

                        Scan QR Code untuk informasi pembayaran.

                    </p>

                </div>


                

                <div class="p-5 sm:p-8">


                    <div class="flex justify-center">

                        <div
                            class="bg-white
                                   p-5
                                   rounded-3xl
                                   border
                                   border-slate-200
                                   shadow-lg">


                            <div
                                id="qris-payment-qrcode"
                                class="w-[260px]
                                       h-[260px]
                                       flex
                                       items-center
                                       justify-center">

                            </div>


                        </div>

                    </div>


                    <div
                        class="text-center
                               mt-5">

                        <p
                            class="font-black
                                   text-slate-900
                                   dark:text-white">

                            Scan QR Code

                        </p>


                        <p
                            class="text-xs
                                   text-slate-500
                                   dark:text-slate-400
                                   mt-1">

                            Gunakan kamera HP atau aplikasi scanner QR.

                        </p>

                    </div>


                    

                    <div
                        class="mt-6
                               rounded-3xl
                               bg-slate-50
                               dark:bg-slate-950
                               border
                               border-slate-200
                               dark:border-slate-800
                               p-5
                               text-center">

                        <p
                            class="text-sm
                                   text-slate-500
                                   dark:text-slate-400">

                            Total Pembayaran

                        </p>


                        <p
                            class="text-2xl
                                   font-black
                                   text-orange-500
                                   mt-1">

                            Rp <?php echo e(number_format($order->grand_total, 0, ',', '.')); ?>


                        </p>

                    </div>


                    

                    <div
                        class="mt-5
                               grid
                               grid-cols-1
                               sm:grid-cols-2
                               gap-3">


                        <div
                            class="rounded-2xl
                                   bg-slate-50
                                   dark:bg-slate-950
                                   border
                                   border-slate-200
                                   dark:border-slate-800
                                   p-4">

                            <p
                                class="text-[10px]
                                       uppercase
                                       tracking-widest
                                       font-black
                                       text-slate-400">

                                Nomor Pesanan

                            </p>


                            <p
                                class="font-black
                                       text-slate-900
                                       dark:text-white
                                       mt-1">

                                <?php echo e($order->order_id); ?>


                            </p>

                        </div>


                        <div
                            class="rounded-2xl
                                   bg-slate-50
                                   dark:bg-slate-950
                                   border
                                   border-slate-200
                                   dark:border-slate-800
                                   p-4">

                            <p
                                class="text-[10px]
                                       uppercase
                                       tracking-widest
                                       font-black
                                       text-slate-400">

                                Metode

                            </p>


                            <p
                                class="font-black
                                       text-slate-900
                                       dark:text-white
                                       mt-1">

                                QRIS

                            </p>

                        </div>

                    </div>


                    

                    <div
                        class="mt-5
                               rounded-3xl
                               border
                               border-orange-200
                               bg-orange-50
                               dark:bg-orange-500/10
                               dark:border-orange-500/20
                               p-5">

                        <div class="flex gap-4">

                            <div
                                class="text-orange-500
                                       text-xl
                                       flex-shrink-0">

                                ℹ

                            </div>


                            <div>

                                <p
                                    class="font-black
                                           text-orange-800
                                           dark:text-orange-300
                                           text-sm">

                                    QR Code Simulasi

                                </p>


                                <p
                                    class="text-xs
                                           text-orange-700
                                           dark:text-orange-400
                                           mt-1
                                           leading-relaxed">

                                    QR Code ini dibuat khusus untuk pengujian
                                    aplikasi AtapIndonesia dan belum terhubung
                                    dengan sistem pembayaran QRIS asli.

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        <?php endif; ?>


        

        <?php if(
            $paymentMethod === 'bank' ||
            $paymentMethod === 'transfer_bank' ||
            $paymentMethod === 'transfer'
        ): ?>

            <div
                class="bg-white
                       dark:bg-slate-900
                       rounded-3xl
                       border
                       border-slate-200
                       dark:border-slate-800
                       shadow-sm
                       overflow-hidden
                       mb-6">


                <div
                    class="p-5
                           sm:p-6
                           border-b
                           border-slate-200
                           dark:border-slate-800">

                    <span
                        class="text-[10px]
                               font-black
                               uppercase
                               tracking-widest
                               text-orange-500">

                        Metode Pembayaran

                    </span>


                    <h2
                        class="text-xl
                               font-black
                               text-slate-900
                               dark:text-white
                               mt-1">

                        Transfer Bank BCA

                    </h2>


                    <p
                        class="text-sm
                               text-slate-500
                               dark:text-slate-400
                               mt-1">

                        Silakan transfer ke rekening berikut.

                    </p>

                </div>


                <div class="p-5 sm:p-6">


                    <div
                        class="bg-slate-50
                               dark:bg-slate-950
                               rounded-3xl
                               border
                               border-slate-200
                               dark:border-slate-800
                               p-5">

                        <div
                            class="grid
                                   sm:grid-cols-3
                                   gap-5">


                            <div>

                                <p
                                    class="text-[10px]
                                           uppercase
                                           tracking-widest
                                           font-black
                                           text-slate-400">

                                    Bank

                                </p>


                                <p
                                    class="font-black
                                           text-slate-900
                                           dark:text-white
                                           mt-1">

                                    BCA

                                </p>

                            </div>


                            <div>

                                <p
                                    class="text-[10px]
                                           uppercase
                                           tracking-widest
                                           font-black
                                           text-slate-400">

                                    Nomor Rekening

                                </p>


                                <p
                                    class="font-black
                                           text-slate-900
                                           dark:text-white
                                           mt-1
                                           tracking-wider">

                                    1234567890

                                </p>

                            </div>


                            <div>

                                <p
                                    class="text-[10px]
                                           uppercase
                                           tracking-widest
                                           font-black
                                           text-slate-400">

                                    Atas Nama

                                </p>


                                <p
                                    class="font-black
                                           text-slate-900
                                           dark:text-white
                                           mt-1">

                                    AtapIndonesia

                                </p>

                            </div>

                        </div>

                    </div>


                    <div
                        class="mt-4
                               flex
                               items-center
                               justify-between
                               gap-4
                               bg-slate-50
                               dark:bg-slate-950
                               border
                               border-slate-200
                               dark:border-slate-800
                               rounded-3xl
                               p-5">

                        <span
                            class="text-sm
                                   text-slate-500
                                   dark:text-slate-400
                                   font-semibold">

                            Total Pembayaran

                        </span>


                        <span
                            class="text-xl
                                   font-black
                                   text-orange-500">

                            Rp <?php echo e(number_format($order->grand_total, 0, ',', '.')); ?>


                        </span>

                    </div>

                </div>

            </div>

        <?php endif; ?>


        

        <div
            class="grid
                   grid-cols-1
                   lg:grid-cols-2
                   gap-6
                   mb-6">


            

            <div
                class="bg-white
                       dark:bg-slate-900
                       rounded-3xl
                       border
                       border-slate-200
                       dark:border-slate-800
                       shadow-sm
                       overflow-hidden">


                <div
                    class="px-5
                           sm:px-6
                           py-5
                           border-b
                           border-slate-200
                           dark:border-slate-800">

                    <span
                        class="text-[10px]
                               font-black
                               uppercase
                               tracking-widest
                               text-orange-500">

                        Informasi

                    </span>


                    <h2
                        class="text-lg
                               font-black
                               text-slate-900
                               dark:text-white
                               mt-1">

                        Data Pesanan

                    </h2>

                </div>


                <div class="p-5 sm:p-6">

                    <div class="space-y-5">


                        <div>

                            <p
                                class="text-[10px]
                                       uppercase
                                       tracking-widest
                                       font-black
                                       text-slate-400">

                                Nama

                            </p>


                            <p
                                class="font-bold
                                       text-slate-900
                                       dark:text-white
                                       mt-1">

                                <?php echo e($order->customer_name); ?>


                            </p>

                        </div>


                        <div>

                            <p
                                class="text-[10px]
                                       uppercase
                                       tracking-widest
                                       font-black
                                       text-slate-400">

                                Email

                            </p>


                            <p
                                class="font-bold
                                       text-slate-900
                                       dark:text-white
                                       mt-1
                                       break-all">

                                <?php echo e($order->customer_email ?? '-'); ?>


                            </p>

                        </div>


                        <div>

                            <p
                                class="text-[10px]
                                       uppercase
                                       tracking-widest
                                       font-black
                                       text-slate-400">

                                Nomor Telepon

                            </p>


                            <p
                                class="font-bold
                                       text-slate-900
                                       dark:text-white
                                       mt-1">

                                <?php echo e($order->telepon ?? $order->customer_phone ?? '-'); ?>


                            </p>

                        </div>


                        <div>

                            <p
                                class="text-[10px]
                                       uppercase
                                       tracking-widest
                                       font-black
                                       text-slate-400">

                                Alamat Pengiriman

                            </p>


                            <p
                                class="font-bold
                                       text-slate-900
                                       dark:text-white
                                       mt-1
                                       leading-relaxed">

                                <?php echo e($order->alamat ?? '-'); ?>


                            </p>

                        </div>

                    </div>

                </div>

            </div>


            

            <div
                class="bg-white
                       dark:bg-slate-900
                       rounded-3xl
                       border
                       border-slate-200
                       dark:border-slate-800
                       shadow-sm
                       overflow-hidden">


                <div
                    class="px-5
                           sm:px-6
                           py-5
                           border-b
                           border-slate-200
                           dark:border-slate-800">

                    <span
                        class="text-[10px]
                               font-black
                               uppercase
                               tracking-widest
                               text-orange-500">

                        Status

                    </span>


                    <h2
                        class="text-lg
                               font-black
                               text-slate-900
                               dark:text-white
                               mt-1">

                        Pengiriman & Pembayaran

                    </h2>

                </div>


                <div class="p-5 sm:p-6">

                    <div class="space-y-5">


                        <div>

                            <p
                                class="text-[10px]
                                       uppercase
                                       tracking-widest
                                       font-black
                                       text-slate-400">

                                Kurir

                            </p>


                            <p
                                class="font-bold
                                       text-slate-900
                                       dark:text-white
                                       mt-1">

                                <?php echo e($order->courier ?? 'Belum ditentukan'); ?>


                            </p>

                        </div>


                        <div>

                            <p
                                class="text-[10px]
                                       uppercase
                                       tracking-widest
                                       font-black
                                       text-slate-400">

                                Kode Tracking

                            </p>


                            <p
                                class="font-bold
                                       text-slate-900
                                       dark:text-white
                                       mt-1">

                                <?php echo e($order->tracking_code ?? 'Belum tersedia'); ?>


                            </p>

                        </div>


                        <div>

                            <p
                                class="text-[10px]
                                       uppercase
                                       tracking-widest
                                       font-black
                                       text-slate-400">

                                Metode Pembayaran

                            </p>


                            <p
                                class="font-bold
                                       text-slate-900
                                       dark:text-white
                                       mt-1">

                                <?php if($isVirtualAccount): ?>

                                    BCA Virtual Account

                                <?php elseif($isQris): ?>

                                    QRIS

                                <?php elseif(
                                    $paymentMethod === 'bank' ||
                                    $paymentMethod === 'transfer_bank' ||
                                    $paymentMethod === 'transfer'
                                ): ?>

                                    Transfer Bank BCA

                                <?php else: ?>

                                    <?php echo e(strtoupper($paymentMethod ?: '-')); ?>


                                <?php endif; ?>

                            </p>

                        </div>


                        <div>

                            <p
                                class="text-[10px]
                                       uppercase
                                       tracking-widest
                                       font-black
                                       text-slate-400">

                                Status

                            </p>


                            <span
                                class="inline-flex
                                       items-center
                                       gap-2
                                       mt-2
                                       px-3
                                       py-2
                                       rounded-xl
                                       border
                                       text-xs
                                       font-black
                                       <?php echo e($statusClass); ?>">

                                <span
                                    class="w-1.5
                                           h-1.5
                                           rounded-full
                                           bg-current">
                                </span>

                                <?php echo e($statusLabel); ?>


                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        

        <div
            class="bg-white
                   dark:bg-slate-900
                   rounded-3xl
                   border
                   border-slate-200
                   dark:border-slate-800
                   shadow-sm
                   overflow-hidden
                   mb-6">


            <div
                class="px-5
                       sm:px-6
                       py-5
                       border-b
                       border-slate-200
                       dark:border-slate-800">

                <span
                    class="text-[10px]
                           font-black
                           uppercase
                           tracking-widest
                           text-orange-500">

                    Pesanan

                </span>


                <h2
                    class="text-lg
                           font-black
                           text-slate-900
                           dark:text-white
                           mt-1">

                    Produk yang Dibeli

                </h2>

            </div>


            <div
                class="divide-y
                       divide-slate-100
                       dark:divide-slate-800">


                <?php $__empty_1 = true; $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <div class="p-5 sm:p-6">

                        <div
                            class="flex
                                   items-start
                                   justify-between
                                   gap-5">


                            <div class="min-w-0">

                                <h3
                                    class="font-black
                                           text-slate-900
                                           dark:text-white">

                                    <?php echo e($item->product_name); ?>


                                </h3>


                                <p
                                    class="text-sm
                                           text-slate-500
                                           dark:text-slate-400
                                           mt-1">

                                    <?php echo e($item->quantity); ?>

                                    ×
                                    Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?>


                                </p>

                            </div>


                            <div
                                class="text-right
                                       flex-shrink-0">

                                <p
                                    class="font-black
                                           text-slate-900
                                           dark:text-white">

                                    Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?>


                                </p>

                            </div>

                        </div>

                    </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <div
                        class="p-8
                               text-center
                               text-sm
                               text-slate-400">

                        Belum ada item pesanan.

                    </div>

                <?php endif; ?>

            </div>


            

            <div
                class="border-t
                       border-slate-200
                       dark:border-slate-800
                       bg-slate-50
                       dark:bg-slate-950
                       p-5
                       sm:p-6">


                <div class="space-y-3">


                    <div
                        class="flex
                               justify-between
                               gap-4
                               text-sm">

                        <span
                            class="text-slate-500
                                   dark:text-slate-400">

                            Subtotal

                        </span>


                        <span
                            class="font-bold
                                   text-slate-900
                                   dark:text-white">

                            Rp <?php echo e(number_format($order->subtotal, 0, ',', '.')); ?>


                        </span>

                    </div>


                    <div
                        class="flex
                               justify-between
                               gap-4
                               text-sm">

                        <span
                            class="text-slate-500
                                   dark:text-slate-400">

                            Ongkos Kirim

                        </span>


                        <span
                            class="font-bold
                                   text-slate-900
                                   dark:text-white">

                            Rp <?php echo e(number_format($order->shipping_fee, 0, ',', '.')); ?>


                        </span>

                    </div>


                    <div
                        class="border-t
                               border-slate-200
                               dark:border-slate-800
                               pt-4
                               flex
                               justify-between
                               items-center
                               gap-4">

                        <span
                            class="font-black
                                   text-slate-900
                                   dark:text-white">

                            Total Pembayaran

                        </span>


                        <span
                            class="text-xl
                                   md:text-2xl
                                   font-black
                                   text-orange-500">

                            Rp <?php echo e(number_format($order->grand_total, 0, ',', '.')); ?>


                        </span>

                    </div>

                </div>

            </div>

        </div>


        

        <?php if(
            $isVirtualAccount &&
            $status === 'menunggu_pembayaran' &&
            !empty($order->virtual_account)
        ): ?>

            <div
                class="bg-white
                       dark:bg-slate-900
                       rounded-3xl
                       border
                       border-slate-200
                       dark:border-slate-800
                       shadow-sm
                       p-5
                       sm:p-6
                       mb-6">


                <div
                    class="flex
                           flex-col
                           md:flex-row
                           md:items-center
                           md:justify-between
                           gap-5">


                    <div>

                        <span
                            class="text-[10px]
                                   font-black
                                   uppercase
                                   tracking-widest
                                   text-orange-500">

                            Pembayaran

                        </span>


                        <h3
                            class="font-black
                                   text-lg
                                   text-slate-900
                                   dark:text-white
                                   mt-1">

                            Lanjutkan Pembayaran

                        </h3>


                        <p
                            class="text-sm
                                   text-slate-500
                                   dark:text-slate-400
                                   mt-1">

                            Gunakan nomor BCA Virtual Account
                            yang tertera pada halaman ini.

                        </p>

                    </div>


                    <button
                        type="button"
                        onclick="copyVirtualAccount()"
                        class="inline-flex
                               justify-center
                               items-center
                               bg-orange-500
                               hover:bg-orange-600
                               text-slate-950
                               font-black
                               px-6
                               py-3
                               rounded-2xl
                               transition">

                        Salin Nomor VA

                    </button>

                </div>

            </div>

        <?php endif; ?>


        

        <div
            class="flex
                   flex-col
                   sm:flex-row
                   gap-3
                   no-print">


            <a
                href="<?php echo e(url('/')); ?>"
                class="flex-1
                       text-center
                       bg-slate-900
                       hover:bg-slate-800
                       dark:bg-white
                       dark:hover:bg-slate-100
                       text-white
                       dark:text-slate-950
                       font-black
                       py-3
                       rounded-2xl
                       transition">

                Kembali ke Beranda

            </a>


            <?php if($isVirtualAccount): ?>

                <button
                    type="button"
                    onclick="window.location.reload()"
                    class="flex-1
                           bg-orange-500
                           hover:bg-orange-600
                           text-slate-950
                           font-black
                           py-3
                           rounded-2xl
                           transition">

                    ↻ Refresh Status

                </button>

            <?php endif; ?>


            

            <button
                type="button"
                onclick="printReceipt()"
                class="flex-1
                       bg-white
                       hover:bg-slate-50
                       dark:bg-slate-900
                       dark:hover:bg-slate-800
                       border
                       border-slate-200
                       dark:border-slate-800
                       text-slate-700
                       dark:text-slate-300
                       font-black
                       py-3
                       rounded-2xl
                       transition">

                🖨 Cetak Struk

            </button>

        </div>


    </main>

</div>




<div id="print-receipt">

    <div class="receipt-container">


        

        <div class="receipt-header">

            <div class="receipt-logo">

                ATAPINDONESIA

            </div>


            <div class="receipt-subtitle">

                Solusi Atap Terpercaya

            </div>


            <div class="receipt-title">

                STRUK PEMBAYARAN

            </div>

        </div>


        <div class="receipt-line"></div>


        

        <div class="receipt-info">


            <div class="receipt-row">

                <span>
                    No. Pesanan
                </span>

                <strong>
                    <?php echo e($order->order_id); ?>

                </strong>

            </div>


            <div class="receipt-row">

                <span>
                    Tanggal
                </span>

                <strong>

                    <?php echo e($order->created_at
                        ? $order->created_at->format('d/m/Y H:i')
                        : date('d/m/Y H:i')); ?>


                </strong>

            </div>


            <div class="receipt-row">

                <span>
                    Pelanggan
                </span>

                <strong>

                    <?php echo e($order->customer_name); ?>


                </strong>

            </div>


            <?php if(!empty($order->telepon)): ?>

                <div class="receipt-row">

                    <span>
                        Telepon
                    </span>

                    <strong>

                        <?php echo e($order->telepon); ?>


                    </strong>

                </div>

            <?php elseif(!empty($order->customer_phone)): ?>

                <div class="receipt-row">

                    <span>
                        Telepon
                    </span>

                    <strong>

                        <?php echo e($order->customer_phone); ?>


                    </strong>

                </div>

            <?php endif; ?>


        </div>


        <div class="receipt-line"></div>


        

        <div class="receipt-section-title">

            PRODUK

        </div>


        <?php $__empty_1 = true; $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <div class="receipt-product">


                <div class="receipt-product-name">

                    <?php echo e($item->product_name); ?>


                </div>


                <div class="receipt-product-detail">

                    <span>

                        <?php echo e($item->quantity); ?>

                        ×
                        Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?>


                    </span>


                    <strong>

                        Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?>


                    </strong>

                </div>


            </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

            <div class="receipt-empty">

                Tidak ada produk.

            </div>

        <?php endif; ?>


        <div class="receipt-line"></div>


        

        <div class="receipt-total">


            <div class="receipt-row">

                <span>
                    Subtotal
                </span>

                <strong>

                    Rp <?php echo e(number_format($order->subtotal, 0, ',', '.')); ?>


                </strong>

            </div>


            <div class="receipt-row">

                <span>
                    Ongkos Kirim
                </span>

                <strong>

                    Rp <?php echo e(number_format($order->shipping_fee, 0, ',', '.')); ?>


                </strong>

            </div>


            <div class="receipt-total-main">

                <span>
                    TOTAL
                </span>

                <strong>

                    Rp <?php echo e(number_format($order->grand_total, 0, ',', '.')); ?>


                </strong>

            </div>


        </div>


        <div class="receipt-line"></div>


        

        <div class="receipt-payment">


            <div class="receipt-section-title">

                METODE PEMBAYARAN

            </div>


            <div class="receipt-payment-method">


                <?php if($isQris): ?>

                    QRIS

                <?php elseif($isVirtualAccount): ?>

                    BCA VIRTUAL ACCOUNT

                <?php elseif(
                    $paymentMethod === 'bank' ||
                    $paymentMethod === 'transfer_bank' ||
                    $paymentMethod === 'transfer'
                ): ?>

                    TRANSFER BANK BCA

                <?php else: ?>

                    <?php echo e(strtoupper($paymentMethod ?: '-')); ?>


                <?php endif; ?>


            </div>


            <div class="receipt-status">


                <?php if(
                    $status === 'dibayar' ||
                    $status === 'selesai'
                ): ?>

                    ✓ PEMBAYARAN BERHASIL

                <?php elseif($status === 'dibatalkan'): ?>

                    ✕ PESANAN DIBATALKAN

                <?php else: ?>

                    ○ MENUNGGU PEMBAYARAN

                <?php endif; ?>


            </div>

        </div>


        

        <?php if($isQris): ?>

            <div class="receipt-qr">

                <div id="print-qrcode"></div>


                <p>

                    QR Code Simulasi

                </p>

            </div>

        <?php endif; ?>


        

        <?php if(
            $isVirtualAccount &&
            !empty($order->virtual_account)
        ): ?>

            <div class="receipt-va">


                <div>

                    Nomor Virtual Account

                </div>


                <strong>

                    <?php echo e($order->virtual_account); ?>


                </strong>


            </div>

        <?php endif; ?>


        <div class="receipt-line"></div>


        

        <div class="receipt-footer">


            <strong>

                ATAPINDONESIA

            </strong>


            <p>

                Terima kasih telah berbelanja

            </p>


            <p>

                Simpan struk ini sebagai bukti transaksi.

            </p>


            <div class="receipt-order-code">

                <?php echo e($order->order_id); ?>


            </div>


        </div>


    </div>

</div>




<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>


<script>


/* =========================================================
   QR CODE QRIS
========================================================= */

document.addEventListener(
    'DOMContentLoaded',
    function () {


        const qrContainer =
            document.getElementById(
                'qris-payment-qrcode'
            );


        if (!qrContainer) {

            return;

        }


        if (
            typeof QRCode === 'undefined'
        ) {

            console.error(
                'QRCode.js tidak berhasil dimuat.'
            );


            qrContainer.innerHTML = `

                <div
                    style="
                        text-align:center;
                        font-size:13px;
                        color:#ef4444;
                        padding:20px;
                    "
                >

                    QR Code gagal dimuat.

                </div>

            `;

            return;

        }


        const qrData =
            <?php echo json_encode($qrPaymentData, 15, 512) ?>;


        if (!qrData) {

            return;

        }


        try {


            new QRCode(

                qrContainer,

                {

                    text: qrData,

                    width: 240,

                    height: 240,

                    colorDark: '#000000',

                    colorLight: '#ffffff',

                    correctLevel:
                        QRCode.CorrectLevel.H

                }

            );


        } catch (error) {


            console.error(
                'QR Code error:',
                error
            );


        }

    }
);


/* =========================================================
   GENERATE QR UNTUK STRUK
========================================================= */

function generatePrintQR() {


    const container =
        document.getElementById(
            'print-qrcode'
        );


    if (!container) {

        return;

    }


    container.innerHTML = '';


    if (
        typeof QRCode === 'undefined'
    ) {

        console.error(
            'QRCode.js belum tersedia.'
        );

        return;

    }


    const qrData =
        <?php echo json_encode($qrPaymentData, 15, 512) ?>;


    if (!qrData) {

        return;

    }


    try {


        new QRCode(

            container,

            {

                text: qrData,

                width: 240,

                height: 240,

                colorDark: '#000000',

                colorLight: '#ffffff',

                correctLevel:
                    QRCode.CorrectLevel.H

            }

        );


    } catch (error) {


        console.error(
            'Gagal membuat QR struk:',
            error
        );


    }

}


/* =========================================================
   CETAK STRUK
========================================================= */

function printReceipt() {


    /*
    |--------------------------------------------------------------------------
    | Buat QR terlebih dahulu
    |--------------------------------------------------------------------------
    */

    generatePrintQR();


    /*
    |--------------------------------------------------------------------------
    | Tunggu QR selesai dibuat
    |--------------------------------------------------------------------------
    */

    setTimeout(

        function () {

            window.print();

        },

        400

    );

}


/* =========================================================
   COPY VIRTUAL ACCOUNT
========================================================= */

function copyVirtualAccount() {


    const element =
        document.getElementById(
            'virtual-account-number'
        );


    if (!element) {

        showVaWarning();

        return;

    }


    const va =
        element.dataset.va
            ? element.dataset.va.trim()
            : '';


    if (!va) {

        showVaWarning();

        return;

    }


    if (
        navigator.clipboard &&
        window.isSecureContext
    ) {


        navigator.clipboard
            .writeText(va)

            .then(

                function () {

                    showCopySuccess();

                }

            )

            .catch(

                function () {

                    fallbackCopy(va);

                }

            );


    } else {


        fallbackCopy(va);

    }

}


/* =========================================================
   VA WARNING
========================================================= */

function showVaWarning() {


    if (
        typeof Swal !== 'undefined'
    ) {


        Swal.fire({

            icon: 'warning',

            title: 'VA Belum Tersedia',

            text:
                'Nomor Virtual Account belum tersedia.',

            confirmButtonText: 'OK'

        });


    }

}


/* =========================================================
   COPY SUCCESS
========================================================= */

function showCopySuccess() {


    const message =
        document.getElementById(
            'copy-va-message'
        );


    if (message) {


        message.classList.remove(
            'hidden'
        );


        setTimeout(

            function () {

                message.classList.add(
                    'hidden'
                );

            },

            2500

        );

    }


    if (
        typeof Swal !== 'undefined'
    ) {


        Swal.fire({

            icon: 'success',

            title: 'Berhasil Disalin',

            text:
                'Nomor Virtual Account berhasil disalin.',

            timer: 1800,

            showConfirmButton: false

        });


    }

}


/* =========================================================
   FALLBACK COPY
========================================================= */

function fallbackCopy(text) {


    const textarea =
        document.createElement(
            'textarea'
        );


    textarea.value = text;

    textarea.style.position =
        'fixed';

    textarea.style.left =
        '-9999px';

    textarea.style.top =
        '0';

    textarea.style.opacity =
        '0';


    document.body.appendChild(
        textarea
    );


    textarea.focus();

    textarea.select();

    textarea.setSelectionRange(
        0,
        textarea.value.length
    );


    try {


        const success =
            document.execCommand(
                'copy'
            );


        if (success) {

            showCopySuccess();

        } else {

            showCopyError(text);

        }


    } catch (error) {


        showCopyError(text);


    }


    document.body.removeChild(
        textarea
    );

}


/* =========================================================
   COPY ERROR
========================================================= */

function showCopyError(text) {


    if (
        typeof Swal !== 'undefined'
    ) {


        Swal.fire({

            icon: 'info',

            title: 'Salin Manual',

            html:
                'Silakan salin nomor VA berikut:<br><br>' +

                '<strong style="' +
                'font-size:20px;' +
                'letter-spacing:2px;' +
                '">' +

                text +

                '</strong>',

            confirmButtonText: 'OK'

        });


    } else {


        alert(
            'Nomor Virtual Account: ' +
            text
        );


    }

}


/* =========================================================
   AUTO REFRESH STATUS VA
========================================================= */

<?php if(
    $isVirtualAccount &&
    $status === 'menunggu_pembayaran'
): ?>

    let autoRefreshTimer = null;


    function startAutoRefresh() {


        autoRefreshTimer =
            setTimeout(

                function () {

                    window.location.reload();

                },

                30000

            );

    }


    startAutoRefresh();


    window.addEventListener(

        'beforeunload',

        function () {


            if (autoRefreshTimer) {

                clearTimeout(
                    autoRefreshTimer
                );

            }


        }

    );

<?php endif; ?>

</script>




<style>


/* =========================================================
   STRUK TIDAK TERLIHAT DI LAYAR
========================================================= */

#print-receipt {

    display: none;

}


/* =========================================================
   RECEIPT
========================================================= */

.receipt-container {

    width: 80mm;

    max-width: 80mm;

    margin: 0 auto;

    padding: 5mm;

    box-sizing: border-box;

    background: #ffffff;

    color: #111111;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    font-size: 11px;

    line-height: 1.45;

}


.receipt-header {

    text-align: center;

}


.receipt-logo {

    font-size: 21px;

    font-weight: 900;

    letter-spacing: 1px;

}


.receipt-subtitle {

    font-size: 9px;

    color: #555555;

    margin-top: 2px;

}


.receipt-title {

    font-size: 13px;

    font-weight: 900;

    margin-top: 8px;

}


.receipt-line {

    border-top: 1px dashed #555555;

    margin: 9px 0;

}


.receipt-info {

    margin-top: 5px;

}


.receipt-row {

    display: flex;

    justify-content: space-between;

    align-items: flex-start;

    gap: 8px;

    margin-bottom: 4px;

}


.receipt-row span {

    color: #555555;

}


.receipt-row strong {

    text-align: right;

    font-weight: 700;

    max-width: 60%;

    word-break: break-word;

}


.receipt-section-title {

    font-size: 10px;

    font-weight: 900;

    letter-spacing: .4px;

    margin-bottom: 7px;

}


.receipt-product {

    margin-bottom: 8px;

}


.receipt-product-name {

    font-weight: 700;

    word-break: break-word;

}


.receipt-product-detail {

    display: flex;

    justify-content: space-between;

    align-items: flex-start;

    gap: 8px;

    margin-top: 2px;

    font-size: 10px;

}


.receipt-product-detail span {

    color: #555555;

}


.receipt-product-detail strong {

    font-weight: 700;

    white-space: nowrap;

}


.receipt-total {

    margin-top: 3px;

}


.receipt-total-main {

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 8px;

    border-top: 1px solid #111111;

    margin-top: 7px;

    padding-top: 7px;

    font-size: 14px;

    font-weight: 900;

}


.receipt-payment {

    text-align: center;

}


.receipt-payment-method {

    font-size: 12px;

    font-weight: 900;

}


.receipt-status {

    font-size: 10px;

    font-weight: 900;

    margin-top: 4px;

}


.receipt-qr {

    text-align: center;

    margin-top: 10px;

}


.receipt-qr #print-qrcode {

    display: flex;

    justify-content: center;

    align-items: center;

}


.receipt-qr img {

    width: 38mm !important;

    height: 38mm !important;

    display: block;

}


.receipt-qr canvas {

    width: 38mm !important;

    height: 38mm !important;

    display: block;

}


.receipt-qr p {

    margin-top: 4px;

    font-size: 8px;

    color: #555555;

}


.receipt-va {

    margin-top: 9px;

    padding: 7px;

    border: 1px solid #111111;

    text-align: center;

}


.receipt-va div {

    font-size: 8px;

    color: #555555;

}


.receipt-va strong {

    display: block;

    margin-top: 3px;

    font-size: 14px;

    letter-spacing: 1px;

}


.receipt-footer {

    text-align: center;

    margin-top: 8px;

    font-size: 8px;

}


.receipt-footer strong {

    font-size: 11px;

}


.receipt-footer p {

    margin: 2px 0;

    color: #555555;

}


.receipt-order-code {

    margin-top: 6px;

    font-weight: 900;

    letter-spacing: 1px;

}


.receipt-empty {

    text-align: center;

    color: #777777;

}


/* =========================================================
   PRINT
========================================================= */

@media print {


    @page {

        size: 80mm auto;

        margin: 0;

    }


    html,
    body {

        width: 80mm !important;

        min-width: 80mm !important;

        max-width: 80mm !important;

        margin: 0 !important;

        padding: 0 !important;

        background: #ffffff !important;

    }


    body * {

        visibility: hidden !important;

    }


    #print-receipt,
    #print-receipt * {

        visibility: visible !important;

    }


    #print-receipt {

        display: block !important;

        position: absolute !important;

        left: 0 !important;

        top: 0 !important;

        width: 80mm !important;

        max-width: 80mm !important;

        margin: 0 !important;

        padding: 0 !important;

        background: #ffffff !important;

    }


    .receipt-container {

        width: 80mm !important;

        max-width: 80mm !important;

        padding: 5mm !important;

        margin: 0 !important;

    }


    /*
    |----------------------------------------------------------
    | Hilangkan elemen halaman utama
    |----------------------------------------------------------
    */

    nav,
    header,
    footer,
    .no-print {

        display: none !important;

    }

}

</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\fhazr\Downloads\AtapIndonesia\AtapIndonesia\resources\views/orders/show.blade.php ENDPATH**/ ?>