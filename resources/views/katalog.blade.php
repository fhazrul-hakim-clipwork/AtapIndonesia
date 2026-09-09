@extends('layouts.app')

@section('title', 'Katalog Material Atap - AtapIndonesia')

@section('content')

<section class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

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


    {{-- =========================================================
         PRODUCT GRID
    ========================================================== --}}

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4">

        @forelse($data['produk'] as $produk)

            @php

                /*
                |--------------------------------------------------------------------------
                | NORMALISASI DATA PRODUK
                |--------------------------------------------------------------------------
                */

                $namaProduk = strtolower(
                    trim(
                        (string) ($produk['nama'] ?? '')
                    )
                );


                /*
                |--------------------------------------------------------------------------
                | GAMBAR DARI DATABASE
                |--------------------------------------------------------------------------
                */

                $gambarProduk = $produk['image'] ?? null;


                /*
                |--------------------------------------------------------------------------
                | BERSIHKAN PATH GAMBAR
                |--------------------------------------------------------------------------
                */

                if (is_string($gambarProduk)) {

                    $gambarProduk = trim($gambarProduk);

                }


                /*
                |--------------------------------------------------------------------------
                | FORCE IMAGE UNTUK PIPA
                |--------------------------------------------------------------------------
                */

                if (str_contains($namaProduk, 'pipa')) {

                    $gambarProduk = 'products/pipa_pvc.jpg';

                }


                /*
                |--------------------------------------------------------------------------
                | FALLBACK GAMBAR
                |--------------------------------------------------------------------------
                */

                if (empty($gambarProduk)) {

                    $gambarProduk = match (true) {

                        str_contains($namaProduk, 'sepatu'),
                        str_contains($namaProduk, 'safety boot')
                            => 'products/sepatu_safety.jpg',

                        str_contains($namaProduk, 'selang')
                            => 'products/selang_air.jpg',

                        str_contains($namaProduk, 'karpet')
                            => 'products/karpet_talang.jpg',

                        str_contains($namaProduk, 'pipa')
                            => 'products/pipa_pvc.jpg',

                        str_contains($namaProduk, 'talang'),
                        str_contains($namaProduk, 'galvanis'),
                        str_contains($namaProduk, 'galvalum'),
                        str_contains($namaProduk, 'spandek'),
                        str_contains($namaProduk, 'bitumen'),
                        str_contains($namaProduk, 'atap')
                            => 'products/atap_galvalum.jpg',

                        default
                            => null,

                    };

                }


                /*
                |--------------------------------------------------------------------------
                | BENTUKKAN URL GAMBAR
                |--------------------------------------------------------------------------
                */

                $gambarUrl = null;


                if (!empty($gambarProduk)) {

                    /*
                    |--------------------------------------------------------------------------
                    | 1. URL LENGKAP
                    |--------------------------------------------------------------------------
                    */

                    if (
                        \Illuminate\Support\Str::startsWith(
                            $gambarProduk,
                            [
                                'http://',
                                'https://'
                            ]
                        )
                    ) {

                        $gambarUrl = $gambarProduk;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | 2. PATH storage/
                    |--------------------------------------------------------------------------
                    */

                    elseif (
                        \Illuminate\Support\Str::startsWith(
                            ltrim($gambarProduk, '/'),
                            'storage/'
                        )
                    ) {

                        $gambarUrl = asset(
                            ltrim($gambarProduk, '/')
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | 3. PATH products/
                    |--------------------------------------------------------------------------
                    */

                    elseif (
                        \Illuminate\Support\Str::startsWith(
                            ltrim($gambarProduk, '/'),
                            'products/'
                        )
                    ) {

                        $gambarUrl = asset(
                            'storage/' .
                            ltrim($gambarProduk, '/')
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | 4. HANYA NAMA FILE
                    |--------------------------------------------------------------------------
                    */

                    else {

                        $gambarUrl = asset(
                            'storage/products/' .
                            ltrim($gambarProduk, '/')
                        );

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | DESKRIPSI PRODUK
                |--------------------------------------------------------------------------
                */

                $deskripsiProduk = $produk['deskripsi'] ?? null;


                /*
                |--------------------------------------------------------------------------
                | FALLBACK DESKRIPSI
                |--------------------------------------------------------------------------
                */

                if (empty($deskripsiProduk)) {

                    $deskripsiProduk = match (true) {

                        str_contains($namaProduk, 'talang galvanis 6')
                            => 'Talang galvanis ukuran 6 inci untuk membantu menyalurkan air hujan dari area atap.',

                        str_contains($namaProduk, 'talang galvanis 8')
                            => 'Talang galvanis ukuran 8 inci dengan konstruksi heavy duty untuk kebutuhan penyaluran air hujan.',

                        str_contains($namaProduk, 'karpet pvc')
                            => 'Karpet PVC roll sepanjang 50 meter untuk kebutuhan pelapis dan perlindungan pada area talang.',

                        str_contains($namaProduk, 'spandek bitumen')
                            => 'Spandek bitumen ukuran 1 x 5 meter untuk kebutuhan material penutup atap.',

                        str_contains($namaProduk, 'talang galvalum')
                            => 'Talang galvalum premium ukuran 6 inci untuk membantu menyalurkan air hujan dari atap.',

                        str_contains($namaProduk, 'pipa')
                            => 'Pipa PVC berkualitas untuk kebutuhan saluran air, pembuangan, dan berbagai instalasi perpipaan.',

                        str_contains($namaProduk, 'selang')
                            => 'Selang air untuk kebutuhan pengaliran air di rumah maupun area kerja.',

                        str_contains($namaProduk, 'sepatu'),
                        str_contains($namaProduk, 'safety boot')
                            => 'Sepatu safety boot untuk perlindungan kaki saat bekerja di lingkungan konstruksi.',

                        default
                            => 'Material untuk kebutuhan konstruksi atap dan talang.',

                    };

                }

            @endphp


            {{-- =================================================
                 PRODUCT CARD
            ================================================== --}}

            <div
                class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition group overflow-hidden"
            >

                {{-- =================================================
                     PRODUCT IMAGE
                ================================================== --}}

                <div
                    class="aspect-square bg-slate-50 dark:bg-slate-950 relative overflow-hidden"
                >

                    @if(!empty($gambarUrl))

                        <img
                            src="{{ $gambarUrl }}"
                            alt="{{ $produk['nama'] ?? 'Produk' }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                            loading="lazy"
                            onerror="
                                this.style.display='none';
                                this.parentElement.querySelector('.image-fallback').classList.remove('hidden');
                                this.parentElement.querySelector('.image-fallback').classList.add('flex');
                            "
                        >

                        <div
                            class="image-fallback hidden absolute inset-0 w-full h-full items-center justify-center text-4xl text-slate-200 dark:text-slate-700"
                        >
                            📦
                        </div>

                    @else

                        <div
                            class="w-full h-full flex items-center justify-center text-4xl text-slate-200 dark:text-slate-700"
                        >
                            📦
                        </div>

                    @endif


                    {{-- =================================================
                         PROMO
                    ================================================== --}}

                    @if(!empty($produk['promo']))

                        <div class="absolute top-2 left-2">

                            <span
                                class="text-[9px] font-black uppercase tracking-wider bg-red-500 text-white px-1.5 py-0.5 rounded-md"
                            >
                                {{ $produk['promo'] }}
                            </span>

                        </div>

                    @endif


                    {{-- =================================================
                         BEST PRODUCT
                    ================================================== --}}

                    @if(!empty($produk['best']))

                        <div class="absolute top-2 right-2">

                            <span
                                class="text-[9px] font-black uppercase tracking-wider bg-amber-500 text-white px-1.5 py-0.5 rounded-md"
                            >
                                Best
                            </span>

                        </div>

                    @endif

                </div>


                {{-- =================================================
                     PRODUCT INFORMATION
                ================================================== --}}

                <div class="p-3">

                    {{-- RATING + STOK --}}

                    <div class="flex items-center gap-1 mb-1">

                        <span class="text-amber-500 text-[10px]">
                            ⭐
                        </span>

                        <span class="text-[10px] font-bold text-slate-600 dark:text-slate-300">
                            {{ $produk['rating'] ?? '5.0' }}
                        </span>

                        <span class="text-[10px] text-slate-400">
                            •
                        </span>

                        <span class="text-[10px] text-slate-500 dark:text-slate-400">
                            Stok {{ $produk['stok'] ?? 0 }}
                        </span>

                    </div>


                    {{-- NAMA PRODUK --}}

                    <h3
                        class="text-xs font-black text-slate-900 dark:text-white leading-tight mb-2 line-clamp-2"
                        title="{{ $produk['nama'] ?? '' }}"
                    >
                        {{ $produk['nama'] ?? 'Produk' }}
                    </h3>


                    {{-- MATERIAL --}}

                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mb-1">
                        {{ $produk['material'] ?? 'Material konstruksi' }}
                    </p>


                    {{-- DESKRIPSI --}}

                    @if(!empty($deskripsiProduk))

                        <p
                            class="text-[9px] leading-relaxed text-slate-400 dark:text-slate-500 mb-2 line-clamp-2"
                            title="{{ $deskripsiProduk }}"
                        >
                            {{ $deskripsiProduk }}
                        </p>

                    @endif


                    {{-- PRICE --}}

                    <div class="flex items-center justify-between">

                        <p class="text-sm font-black text-orange-600">

                            Rp
                            {{ number_format(
                                $produk['harga'] ?? 0,
                                0,
                                ',',
                                '.'
                            ) }}

                        </p>

                    </div>


                    {{-- =================================================
                         ACTION BUTTONS
                    ================================================== --}}

                    <div class="mt-2">

                        @auth

                            {{-- =================================================
                                 USER SUDAH LOGIN
                            ================================================== --}}

                            <div class="grid grid-cols-2 gap-1.5">

                                {{-- BELI SEKARANG --}}

                                <form
                                    method="POST"
                                    action="{{ route('cart.buy_now') }}"
                                    class="inline"
                                >

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="product_id"
                                        value="{{ $produk['id'] }}"
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


                                {{-- TAMBAH KE KERANJANG --}}

                                <form
                                    method="POST"
                                    action="{{ route('cart.add') }}"
                                    class="inline add-to-cart-form"
                                >

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="product_id"
                                        value="{{ $produk['id'] }}"
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

                        @else

                            {{-- =================================================
                                 USER BELUM LOGIN
                            ================================================== --}}

                            <a
                                href="{{ route('login', ['redirect' => url()->current()]) }}"
                                class="w-full flex items-center justify-center gap-1.5 rounded-lg bg-slate-900 text-white text-[10px] font-black py-2 hover:bg-slate-800 transition"
                            >
                                🔐 Login untuk Belanja
                            </a>

                        @endauth

                    </div>

                </div>

            </div>

        @empty

            {{-- =================================================
                 EMPTY PRODUCT
            ================================================== --}}

            <div class="col-span-full">

                <div
                    class="rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 p-10 text-center"
                >

                    <div class="text-5xl mb-3">
                        📦
                    </div>

                    <h3 class="font-black text-slate-800 dark:text-white">
                        Belum ada produk
                    </h3>

                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Produk belum tersedia di katalog.
                    </p>

                </div>

            </div>

        @endforelse

    </div>

</section>


{{-- =============================================================
     JAVASCRIPT KERANJANG
============================================================= --}}

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
    | SEMUA FORM ADD TO CART
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

                event.preventDefault();


                const button =
                    form.querySelector(
                        'button[type="submit"]'
                    );


                if (!button) {
                    return;
                }


                const originalText =
                    button.innerHTML;


                button.disabled = true;

                button.innerHTML = `
                    <span class="inline-flex items-center gap-1">
                        <span class="animate-spin">⟳</span>
                        Menambahkan
                    </span>
                `;


                try {

                    const formData =
                        new FormData(form);


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


                    const data =
                        await response.json();


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
                    | UPDATE COUNTER
                    |--------------------------------------------------------------------------
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
                    | SUCCESS ALERT
                    |--------------------------------------------------------------------------
                    */

                    if (
                        typeof Swal !== 'undefined'
                    ) {

                        Swal.fire({

                            icon: 'success',

                            title: 'Berhasil',

                            text:
                                data.message ||
                                'Produk berhasil dimasukkan ke keranjang.',

                            showConfirmButton: false,

                            timer: 1800,

                            timerProgressBar: true,

                            allowOutsideClick: true,

                            allowEscapeKey: true,

                            toast: false

                        });

                    }


                } catch (error) {

                    console.error(
                        'Add to cart error:',
                        error
                    );


                    if (
                        typeof Swal !== 'undefined'
                    ) {

                        Swal.fire({

                            icon: 'error',

                            title: 'Gagal',

                            text:
                                error.message ||
                                'Gagal menambahkan produk ke keranjang.',

                            confirmButtonText:
                                'Mengerti',

                            showConfirmButton: true,

                            allowOutsideClick: true,

                            allowEscapeKey: true,

                            focusConfirm: true

                        });

                    }

                } finally {

                    button.disabled = false;

                    button.innerHTML =
                        originalText;

                }

            }
        );

    });

});

</script>

@endsection