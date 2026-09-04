@extends('layouts.app')

@section('title', 'Pembayaran - ' . ($order->order_id ?? ''))

@section('content')
<section class="max-w-3xl mx-auto px-4 sm:px-6 py-8 sm:py-12">

    {{-- HEADER --}}
    <div class="mb-8">
        <span class="text-xs font-black text-orange-500 uppercase tracking-widest">
            Pembayaran
        </span>

        <h1 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mt-2">
            Selesaikan Pembayaran
        </h1>

        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">
            Silakan lakukan pembayaran sesuai metode yang Anda pilih.
        </p>
    </div>


    {{-- CARD UTAMA --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-4 sm:p-6 shadow-sm">

        {{-- NOMOR PESANAN --}}
        <div class="flex items-center justify-between mb-6">

            <div>
                <p class="text-xs font-black uppercase tracking-widest text-slate-500">
                    Nomor Pesanan
                </p>

                <p class="text-xl font-black text-slate-900 dark:text-white mt-1">
                    {{ $order->order_id }}
                </p>
            </div>

            <span class="rounded-full bg-amber-500/10 text-amber-600 px-3 py-1 text-xs font-black uppercase">
                Menunggu Bayar
            </span>

        </div>


        {{-- RINGKASAN PEMBAYARAN --}}
        <div class="space-y-3 text-sm mb-6">

            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                <span>Subtotal</span>

                <span>
                    Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                </span>
            </div>


            <div class="flex justify-between text-slate-600 dark:text-slate-400">
                <span>Ongkos Kirim</span>

                <span>
                    Rp {{ number_format($order->shipping_fee, 0, ',', '.') }}
                </span>
            </div>


            <div class="flex justify-between text-base font-black text-slate-900 dark:text-white border-t border-slate-200 dark:border-slate-800 pt-3">

                <span>Total Bayar</span>

                <span>
                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                </span>

            </div>

        </div>


        {{-- DATA PENERIMA --}}
        <div class="bg-slate-50 dark:bg-slate-950 rounded-2xl p-4 mb-6">

            <p class="text-xs font-bold text-slate-500 mb-2">
                Penerima
            </p>

            <p class="text-sm font-bold text-slate-900 dark:text-white">
                {{ $order->customer_name }}
            </p>

            <p class="text-xs text-slate-500">
                {{ $order->customer_email }}
            </p>

            <p class="text-xs text-slate-500">
                {{ $order->telepon }}
            </p>

            <p class="text-xs text-slate-500 mt-1">
                {{ $order->alamat }}
            </p>

        </div>


        {{-- =========================================================
             QRIS
        ========================================================== --}}
        @if($order->payment_method === 'qris')

            <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900 rounded-2xl p-6 mb-6 text-center">

                <p class="text-xs font-bold text-emerald-700 dark:text-emerald-400 uppercase mb-3">
                    Bayar dengan QRIS
                </p>


                <div class="w-48 h-48 bg-white rounded-xl mx-auto flex items-center justify-center text-6xl shadow-sm mb-3">
                    📱
                </div>


                <p class="text-xs text-emerald-600 dark:text-emerald-400">
                    Scan QR Code dengan aplikasi pembayaran Anda.
                </p>


                <p class="text-sm font-black text-emerald-700 dark:text-emerald-400 mt-2">
                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                </p>

            </div>


        {{-- =========================================================
             BCA VIRTUAL ACCOUNT SIMULASI
        ========================================================== --}}
        @elseif($order->payment_method === 'va')

            <div class="bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-900 rounded-2xl p-6 mb-6">

                <div class="text-center">

                    <p class="text-xs font-bold text-blue-700 dark:text-blue-400 uppercase mb-3">
                        Bayar dengan BCA Virtual Account
                    </p>


                    <div class="w-24 h-24 bg-white rounded-2xl mx-auto flex items-center justify-center text-5xl shadow-sm mb-4">
                        🏦
                    </div>


                    <p class="text-xs text-blue-600 dark:text-blue-400 mb-2">
                        Gunakan nomor Virtual Account berikut untuk melakukan pembayaran.
                    </p>


                    {{-- NOMOR VIRTUAL ACCOUNT --}}
                    @if(!empty($order->virtual_account))

                        <div class="bg-white dark:bg-slate-900 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mt-4">

                            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Nomor Virtual Account
                            </p>


                            <div class="flex items-center justify-center gap-2 mt-2">

                                <p
                                    id="virtualAccountNumber"
                                    class="text-2xl sm:text-3xl font-black text-blue-700 dark:text-blue-400 tracking-wider font-mono"
                                >
                                    {{ $order->virtual_account }}
                                </p>

                                <button
                                    type="button"
                                    onclick="copyVirtualAccount()"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-xs font-bold transition"
                                >
                                    Salin
                                </button>

                            </div>

                        </div>


                        {{-- TOTAL --}}
                        <div class="mt-4">

                            <p class="text-xs text-slate-500">
                                Total Pembayaran
                            </p>

                            <p class="text-xl font-black text-blue-700 dark:text-blue-400 mt-1">
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </p>

                        </div>


                        {{-- PETUNJUK --}}
                        <div class="mt-5 bg-white/70 dark:bg-slate-900/70 rounded-xl p-4 text-left">

                            <p class="text-xs font-black text-slate-700 dark:text-slate-300 uppercase mb-3">
                                Cara Pembayaran
                            </p>


                            <ol class="text-xs text-slate-600 dark:text-slate-400 space-y-2">

                                <li>
                                    <span class="font-bold">1.</span>
                                    Buka aplikasi BCA Mobile atau ATM BCA.
                                </li>

                                <li>
                                    <span class="font-bold">2.</span>
                                    Pilih menu pembayaran Virtual Account.
                                </li>

                                <li>
                                    <span class="font-bold">3.</span>
                                    Masukkan nomor Virtual Account di atas.
                                </li>

                                <li>
                                    <span class="font-bold">4.</span>
                                    Periksa nominal pembayaran.
                                </li>

                                <li>
                                    <span class="font-bold">5.</span>
                                    Konfirmasi pembayaran.
                                </li>

                            </ol>

                        </div>


                        {{-- CATATAN SIMULASI --}}
                        <div class="mt-4 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-xl p-3 text-left">

                            <p class="text-xs text-amber-700 dark:text-amber-400">

                                <span class="font-black">
                                    Catatan:
                                </span>

                                Virtual Account ini merupakan nomor simulasi untuk pengujian sistem dan
                                bukan Virtual Account BCA sungguhan.

                            </p>

                        </div>

                    @else

                        <div class="bg-white dark:bg-slate-900 rounded-xl p-5 mt-4">

                            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                Virtual Account sedang dibuat...
                            </p>

                            <p class="text-xs text-slate-500 mt-1">
                                Silakan kembali ke halaman detail pesanan beberapa saat lagi.
                            </p>

                        </div>

                    @endif

                </div>

            </div>


        {{-- =========================================================
             TRANSFER BANK
        ========================================================== --}}
        @elseif($order->payment_method === 'bank')

            <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl p-6 mb-6 text-center">

                <p class="text-xs font-bold text-gray-500 uppercase mb-3">
                    Transfer Bank BCA
                </p>


                <div class="bg-gray-50 dark:bg-slate-950 rounded-xl p-4 text-left inline-block">

                    <p class="text-sm font-bold text-gray-900 dark:text-white">
                        Bank BCA
                    </p>


                    <p class="text-sm text-gray-700 dark:text-slate-300">
                        No. Rekening:
                        <span class="font-mono font-bold">
                            0953459580
                        </span>
                    </p>


                    <p class="text-sm text-gray-700 dark:text-slate-300">
                        Atas Nama:
                        <span class="font-bold">
                            Selalu Berkilau CV
                        </span>
                    </p>


                    <p class="text-xs text-gray-500 mt-2">
                        Total:

                        <span class="font-bold text-gray-900 dark:text-white">
                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                        </span>
                    </p>

                </div>

            </div>

        @endif


        {{-- KEMBALI KE DETAIL PESANAN --}}
        <div class="mt-6 text-center">

            <a
                href="{{ route('orders.show', $order->order_id) }}"
                class="text-orange-600 hover:text-orange-800 font-bold text-sm"
            >
                &larr; Lihat Detail Pesanan
            </a>

        </div>

    </div>

</section>


{{-- =========================================================
     JAVASCRIPT
========================================================== --}}
<script>

function copyVirtualAccount() {

    const element = document.getElementById(
        'virtualAccountNumber'
    );

    if (!element) {
        return;
    }

    const virtualAccount =
        element.innerText.trim();


    if (
        navigator.clipboard &&
        window.isSecureContext
    ) {

        navigator.clipboard
            .writeText(virtualAccount)
            .then(function () {

                showCopySuccess();

            })
            .catch(function () {

                fallbackCopy(virtualAccount);

            });

    } else {

        fallbackCopy(virtualAccount);

    }

}


function fallbackCopy(text) {

    const textarea =
        document.createElement('textarea');

    textarea.value = text;

    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';

    document.body.appendChild(textarea);

    textarea.focus();
    textarea.select();

    try {

        document.execCommand('copy');

        showCopySuccess();

    } catch (error) {

        alert(
            'Nomor Virtual Account: ' + text
        );

    }

    document.body.removeChild(textarea);

}


function showCopySuccess() {

    const button =
        document.querySelector(
            'button[onclick="copyVirtualAccount()"]'
        );

    if (!button) {
        return;
    }

    const originalText =
        button.innerText;

    button.innerText = 'Tersalin!';

    button.classList.remove(
        'bg-blue-600',
        'hover:bg-blue-700'
    );

    button.classList.add(
        'bg-emerald-600'
    );


    setTimeout(function () {

        button.innerText =
            originalText;

        button.classList.remove(
            'bg-emerald-600'
        );

        button.classList.add(
            'bg-blue-600',
            'hover:bg-blue-700'
        );

    }, 2000);

}

</script>

@endsection