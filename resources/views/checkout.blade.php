@extends('layouts.app')

@section('title', 'Checkout - AtapIndonesia')

@section('content')

<section class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12">

```
{{-- HEADER --}}
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


    {{-- =========================================================
         FORM CHECKOUT
    ========================================================== --}}
    <form
        method="POST"
        action="{{ route('checkout.process') }}"
        class="space-y-6 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-4 sm:p-6 shadow-sm"
        id="checkout-form"
    >

        @csrf


        {{-- NAMA & EMAIL --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                    Nama Lengkap
                </label>

                <input
                    type="text"
                    name="nama"
                    value="{{ old('nama', $userName) }}"
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
                    value="{{ old('email') }}"
                    required
                    class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-4 py-3 text-sm"
                />
            </div>

        </div>


        {{-- TELEPON & ALAMAT --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
                    Nomor Telepon
                </label>

                <input
                    type="text"
                    name="telepon"
                    value="{{ old('telepon') }}"
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
                    value="{{ old('alamat') }}"
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


        {{-- =====================================================
             EKSPEDISI
        ====================================================== --}}
        <div>

            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">
                Ekspedisi Pengiriman
            </label>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                {{-- LALAMOVE --}}
                <label class="rounded-2xl border border-slate-200 dark:border-slate-800 p-3 cursor-pointer hover:border-orange-500 transition flex items-center gap-3">

                    <input
                        type="radio"
                        name="shipping_provider"
                        value="lalamove"
                        checked
                        class="mr-2"
                    />

                    <img
                        src="{{ asset('img/logos/lalamove.svg') }}"
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


                {{-- DELIVEREE --}}
                <label class="rounded-2xl border border-slate-200 dark:border-slate-800 p-3 cursor-pointer hover:border-orange-500 transition flex items-center gap-3">

                    <input
                        type="radio"
                        name="shipping_provider"
                        value="deliveree"
                        class="mr-2"
                    />

                    <img
                        src="{{ asset('img/logos/deliveree.svg') }}"
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


        {{-- =====================================================
             METODE PEMBAYARAN
        ====================================================== --}}
        <div>

            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">
                Metode Pembayaran
            </label>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                {{-- VA --}}
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
                            src="{{ asset('img/logos/bca.svg') }}"
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


                {{-- QRIS --}}
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


                {{-- TRANSFER BANK --}}
                <label class="rounded-2xl border border-slate-200 dark:border-slate-800 p-3 cursor-pointer hover:border-orange-500 transition flex items-center gap-3">

                    <input
                        type="radio"
                        name="metode_pembayaran"
                        value="bank"
                        class="mr-2"
                    />

                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center">

                        <img
                            src="{{ asset('img/logos/bca.svg') }}"
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


        {{-- SUBMIT --}}
        <button
            type="submit"
            class="w-full rounded-3xl bg-orange-500 text-slate-950 text-sm font-black uppercase tracking-widest py-3 hover:bg-orange-600 transition"
        >
            Buat Pesanan
        </button>

    </form>


    {{-- =========================================================
         RINGKASAN PESANAN
    ========================================================== --}}
    <aside class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-4 sm:p-6 shadow-sm h-fit mt-2 lg:mt-0">

        <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">
            Ringkasan Pesanan
        </p>


        {{-- =====================================================
             PRODUK
        ====================================================== --}}
        <div class="mt-4 space-y-3">

            @foreach($summary['items'] as $item)

                <div class="flex items-center justify-between text-sm gap-4">

                    <div class="min-w-0">

                        <span class="text-slate-700 dark:text-slate-300 block">
                            {{ $item['nama'] }} × {{ $item['quantity'] }}
                        </span>

                        {{-- PROMO PRODUK --}}
                        @if(
                            isset($item['promo']) &&
                            strtolower(trim((string) $item['promo'])) === 'diskon 10%'
                        )

                            <span class="inline-flex items-center mt-1 text-[9px] font-bold text-emerald-600 dark:text-emerald-400">
                                Diskon 10%
                            </span>

                        @elseif(
                            isset($item['promo']) &&
                            strtolower(trim((string) $item['promo'])) === 'gratis ongkir'
                        )

                            <span class="inline-flex items-center mt-1 text-[9px] font-bold text-blue-600 dark:text-blue-400">
                                Gratis Ongkir
                            </span>

                        @endif

                    </div>


                    <span class="font-semibold text-slate-900 dark:text-white whitespace-nowrap">
                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                    </span>

                </div>

            @endforeach

        </div>


        {{-- =====================================================
             DETAIL HARGA
        ====================================================== --}}
        <div class="mt-6 border-t border-slate-200 dark:border-slate-800 pt-4 space-y-3 text-sm">


            {{-- SUBTOTAL --}}
            <div class="flex justify-between text-slate-500 dark:text-slate-400">

                <span>
                    Subtotal
                </span>

                <span class="font-semibold text-slate-900 dark:text-white">
                    Rp {{ number_format($summary['subtotal'], 0, ',', '.') }}
                </span>

            </div>


            {{-- DISKON --}}
            <div
                id="discount-row"
                class="flex justify-between text-emerald-600 dark:text-emerald-400
                {{ (($summary['discount'] ?? 0) > 0) ? '' : 'hidden' }}"
            >

                <span class="font-semibold">
                    Diskon 10%
                </span>

                <span
                    id="discount-display"
                    class="font-bold"
                >
                    - Rp {{ number_format($summary['discount'] ?? 0, 0, ',', '.') }}
                </span>

            </div>


            {{-- SUBTOTAL SETELAH DISKON --}}
            <div
                id="subtotal-after-discount-row"
                class="flex justify-between text-slate-500 dark:text-slate-400
                {{ (($summary['discount'] ?? 0) > 0) ? '' : 'hidden' }}"
            >

                <span>
                    Subtotal Setelah Diskon
                </span>

                <span
                    id="subtotal-after-discount-display"
                    class="font-semibold text-slate-900 dark:text-white"
                >
                    Rp
                    {{ number_format(
                        max(
                            0,
                            ($summary['subtotal'] ?? 0) -
                            ($summary['discount'] ?? 0)
                        ),
                        0,
                        ',',
                        '.'
                    ) }}
                </span>

            </div>


            {{-- EKSPEDISI --}}
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


            {{-- JARAK --}}
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


            {{-- ONGKIR --}}
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


            {{-- TOTAL --}}
            <div class="flex justify-between text-base font-black text-slate-900 dark:text-white border-t border-slate-200 dark:border-slate-800 pt-3 mt-3">

                <span>
                    Total Pembayaran
                </span>

                <span
                    id="total-display"
                    class="text-orange-500"
                >
                    Rp
                    {{ number_format(
                        max(
                            0,
                            ($summary['subtotal'] ?? 0) -
                            ($summary['discount'] ?? 0)
                        ),
                        0,
                        ',',
                        '.'
                    ) }}
                </span>

            </div>

        </div>


        {{-- LOADING --}}
        <div
            id="shipping-loading"
            class="mt-3 text-[10px] text-slate-400 hidden"
        >
            Menghitung ongkir...
        </div>


        {{-- INFO DISKON --}}
        @if(($summary['discount'] ?? 0) > 0)

            <div class="mt-5 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 p-3">

                <p class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400">
                    Promo Aktif
                </p>

                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                    Anda mendapatkan diskon 10% untuk produk yang sedang memiliki promo Diskon 10%.
                </p>

            </div>

        @endif


        {{-- INFO GRATIS ONGKIR --}}
        @if(!empty($summary['has_free_shipping']))

            <div class="mt-3 rounded-2xl bg-blue-500/10 border border-blue-500/20 p-3">

                <p class="text-[11px] font-bold text-blue-600 dark:text-blue-400">
                    Gratis Ongkir Aktif
                </p>

                <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                    Pesanan ini mendapatkan gratis ongkir karena terdapat produk dengan promo Gratis Ongkir.
                </p>

            </div>

        @endif


        {{-- INFO ONGKIR --}}
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
```

</section>

{{-- ================================================================
JAVASCRIPT
================================================================ --}}

<script>
(function () {

    'use strict';


    /*
    |--------------------------------------------------------------------------
    | ELEMENT
    |--------------------------------------------------------------------------
    */

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

    const discountDisplay =
        document.getElementById('discount-display');

    const discountRow =
        document.getElementById('discount-row');

    const subtotalAfterDiscountRow =
        document.getElementById('subtotal-after-discount-row');

    const subtotalAfterDiscountDisplay =
        document.getElementById('subtotal-after-discount-display');


    /*
    |--------------------------------------------------------------------------
    | DATA AWAL DARI SERVER
    |--------------------------------------------------------------------------
    */

    const subtotal =
        Number(@json($summary['subtotal'] ?? 0));

    const initialDiscount =
        Number(@json($summary['discount'] ?? 0));


    /*
    |--------------------------------------------------------------------------
    | DEBOUNCE
    |--------------------------------------------------------------------------
    */

    let debounceTimer = null;


    /*
    |--------------------------------------------------------------------------
    | FORMAT RUPIAH
    |--------------------------------------------------------------------------
    */

    function formatRupiah(number) {

        const value =
            Number(number || 0);

        return 'Rp ' +
            value.toLocaleString(
                'id-ID'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT JARAK
    |--------------------------------------------------------------------------
    */

    function formatDistance(distance) {

        const value =
            Number(distance || 0);

        if (
            !Number.isFinite(value) ||
            value <= 0
        ) {

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
    | UPDATE DISCOUNT
    |--------------------------------------------------------------------------
    */

    function updateDiscount(discount) {

        discount =
            Math.max(
                0,
                Number(discount || 0)
            );


        const subtotalAfterDiscount =
            Math.max(
                0,
                subtotal - discount
            );


        /*
        |--------------------------------------------------------------------------
        | BARIS DISKON
        |--------------------------------------------------------------------------
        */

        if (
            discount > 0
        ) {

            discountRow.classList.remove(
                'hidden'
            );

            discountDisplay.textContent =
                '- ' + formatRupiah(discount);

            subtotalAfterDiscountRow.classList.remove(
                'hidden'
            );

            subtotalAfterDiscountDisplay.textContent =
                formatRupiah(
                    subtotalAfterDiscount
                );

        } else {

            discountRow.classList.add(
                'hidden'
            );

            subtotalAfterDiscountRow.classList.add(
                'hidden'
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE TOTAL
    |--------------------------------------------------------------------------
    */

    function updateTotal(
        shippingFee,
        discount
    ) {

        shippingFee =
            Math.max(
                0,
                Number(shippingFee || 0)
            );

        discount =
            Math.max(
                0,
                Number(discount || 0)
            );


        const subtotalAfterDiscount =
            Math.max(
                0,
                subtotal - discount
            );


        const total =
            subtotalAfterDiscount +
            shippingFee;


        updateDiscount(
            discount
        );


        totalDisplay.textContent =
            formatRupiah(
                total
            );

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

        if (
            address.length < 8
        ) {

            shippingDisplay.textContent =
                'Masukkan alamat';

            distanceDisplay.textContent =
                '-';


            updateTotal(
                0,
                initialDiscount
            );


            return;

        }


        clearTimeout(
            debounceTimer
        );


        debounceTimer =
            setTimeout(
                async function () {

                    loading.classList.remove(
                        'hidden'
                    );


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
                            '{{ csrf_token() }}'
                        );


                        const response =
                            await fetch(
                                '{{ route('api.shipping.calculate') }}',
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
                            response.headers.get(
                                'content-type'
                            ) || '';


                        if (
                            !contentType.includes(
                                'application/json'
                            )
                        ) {

                            throw new Error(
                                'Server tidak mengembalikan JSON.'
                            );

                        }


                        const data =
                            await response.json();


                        if (
                            !response.ok
                        ) {

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


                            const discount =
                                Number(
                                    data.data.discount || 0
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | ONGKIR
                            |--------------------------------------------------------------------------
                            */

                            shippingDisplay.textContent =
                                formatRupiah(
                                    fee
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | JARAK
                            |--------------------------------------------------------------------------
                            */

                            distanceDisplay.textContent =
                                formatDistance(
                                    distance
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | DISKON + TOTAL
                            |--------------------------------------------------------------------------
                            */

                            updateTotal(
                                fee,
                                discount
                            );


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


                        /*
                        |--------------------------------------------------------------------------
                        | JIKA API GAGAL
                        |--------------------------------------------------------------------------
                        | Tetap tampilkan total berdasarkan
                        | subtotal dan diskon yang sudah diketahui.
                        */

                        updateTotal(
                            0,
                            initialDiscount
                        );


                    } finally {

                        loading.classList.add(
                            'hidden'
                        );

                    }

                },
                500
            );

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
        .forEach(
            function (input) {

                input.addEventListener(
                    'change',
                    window.calculateShipping
                );

            }
        );


    /*
    |--------------------------------------------------------------------------
    | INITIAL DISCOUNT
    |--------------------------------------------------------------------------
    */

    updateDiscount(
        initialDiscount
    );


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

    } else {

        updateTotal(
            0,
            initialDiscount
        );

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

                /*
                |--------------------------------------------------------------------------
                | POPUP LOADING
                |--------------------------------------------------------------------------
                | Hanya tampil saat pesanan sedang diproses.
                | Popup sukses/error ditangani oleh sistem global.
                */

                if (typeof Swal === 'undefined') {
                    return;
                }

                Swal.fire({

                    title: 'Memproses Pesanan',

                    text: 'Mohon tunggu sebentar...',

                    icon: 'info',

                    showConfirmButton: false,

                    allowOutsideClick: false,

                    allowEscapeKey: false,

                    backdrop: true,

                    didOpen: function () {

                        Swal.showLoading();

                    }

                });

            }
        );

    }

})();
</script>

@endsection
