@extends('dashboards.layout')

@section('title', 'Detail Pesanan ' . $order->order_id)

@section('header-title', 'Detail Pesanan ' . $order->order_id)

@section('dashboard-content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- =====================================================
         INFORMASI PESANAN
    ====================================================== --}}

    <div class="lg:col-span-2 space-y-6">

        {{-- =================================================
             HEADER PESANAN
        ================================================== --}}

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">

            <div class="flex items-center justify-between gap-4 mb-5">

                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-slate-500">
                        Nomor Pesanan
                    </p>

                    <p class="text-xl font-black text-slate-900 mt-1">
                        {{ $order->order_id }}
                    </p>

                    <p class="text-[10px] text-slate-400 mt-1">
                        {{ optional($order->created_at)->format('d M Y H:i') }}
                    </p>
                </div>

                <span
                    class="rounded-full px-3 py-1 text-xs font-black uppercase whitespace-nowrap
                    @if(in_array($order->status, ['dibayar', 'dikemas', 'dikirim', 'selesai']))
                        bg-emerald-500/10 text-emerald-600
                    @elseif($order->status === 'dibatalkan')
                        bg-red-500/10 text-red-600
                    @else
                        bg-amber-500/10 text-amber-600
                    @endif"
                >
                    {{ str_replace('_', ' ', ucwords($order->status)) }}
                </span>

            </div>


            {{-- =============================================
                 CUSTOMER
            ============================================== --}}

            <div class="border-t border-slate-200 pt-5">

                <h4 class="text-sm font-bold text-slate-900 mb-4">
                    Informasi Pelanggan
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase">
                            Pelanggan
                        </p>

                        <p class="font-bold text-slate-900 mt-1">
                            {{ $order->customer_name }}
                        </p>
                    </div>


                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase">
                            Email
                        </p>

                        <p class="font-bold text-slate-900 mt-1 break-all">
                            {{ $order->customer_email }}
                        </p>
                    </div>


                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase">
                            Telepon
                        </p>

                        <p class="font-bold text-slate-900 mt-1">
                            {{ $order->telepon }}
                        </p>
                    </div>


                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase">
                            Alamat
                        </p>

                        <p class="font-bold text-slate-900 mt-1">
                            {{ $order->alamat }}
                        </p>
                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
             PEMBAYARAN
        ================================================== --}}

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">

            <div class="flex items-center justify-between mb-5">

                <div>
                    <h4 class="text-sm font-bold text-slate-900">
                        Informasi Pembayaran
                    </h4>

                    <p class="text-xs text-slate-500 mt-1">
                        Periksa pembayaran sebelum memproses pesanan.
                    </p>
                </div>


                @if($order->status === 'menunggu_pembayaran')

                    <span class="rounded-full bg-amber-100 text-amber-700 px-3 py-1 text-[10px] font-black uppercase">
                        Menunggu Pembayaran
                    </span>

                @elseif(in_array($order->status, ['dibayar', 'dikemas', 'dikirim', 'selesai']))

                    <span class="rounded-full bg-emerald-100 text-emerald-700 px-3 py-1 text-[10px] font-black uppercase">
                        Pembayaran Dikonfirmasi
                    </span>

                @elseif($order->status === 'dibatalkan')

                    <span class="rounded-full bg-red-100 text-red-700 px-3 py-1 text-[10px] font-black uppercase">
                        Dibatalkan
                    </span>

                @endif

            </div>


            {{-- =============================================
                 PAYMENT METHOD
            ============================================== --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">

                    <p class="text-[10px] font-bold text-slate-400 uppercase">
                        Metode Pembayaran
                    </p>

                    <p class="text-sm font-black text-slate-900 mt-1">

                        @if($order->payment_method === 'va')

                            Virtual Account BCA

                        @elseif($order->payment_method === 'qris')

                            QRIS

                        @elseif($order->payment_method === 'bank')

                            Transfer Bank BCA

                        @else

                            {{ strtoupper($order->payment_method ?? 'VA') }}

                        @endif

                    </p>

                </div>


                {{-- =========================================
                     VA BCA
                ========================================== --}}

                @if($order->payment_method === 'va')

                    <div class="rounded-xl bg-blue-50 border border-blue-200 p-4">

                        <p class="text-[10px] font-bold text-blue-500 uppercase">
                            Virtual Account BCA
                        </p>

                        @if(!empty($order->virtual_account))

                            <p class="text-xl font-black tracking-widest text-blue-700 mt-1">
                                {{ $order->virtual_account }}
                            </p>

                        @else

                            <p class="text-sm font-bold text-red-600 mt-1">
                                Nomor VA belum dibuat.
                            </p>

                        @endif

                    </div>

                @endif

            </div>


            {{-- =============================================
                 TOTAL
            ============================================== --}}

            <div class="border-t border-slate-200 mt-5 pt-5">

                <dl class="space-y-2 text-sm">

                    {{-- SUBTOTAL --}}

                    <div class="flex justify-between text-slate-600">

                        <span>
                            Subtotal
                        </span>

                        <span>
                            Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                        </span>

                    </div>


                    {{-- DISKON --}}

                    @if(($order->discount ?? 0) > 0)

                        <div class="flex justify-between text-emerald-600">

                            <span>
                                Diskon
                            </span>

                            <span class="font-bold">
                                - Rp {{ number_format($order->discount, 0, ',', '.') }}
                            </span>

                        </div>

                    @endif


                    {{-- ONGKIR --}}

                    <div class="flex justify-between text-slate-600">

                        <span>
                            Ongkir
                        </span>

                        <span>
                            Rp {{ number_format($order->shipping_fee, 0, ',', '.') }}
                        </span>

                    </div>


                    {{-- TOTAL --}}

                    <div
                        class="flex justify-between text-base font-black text-slate-900 border-t border-slate-200 pt-3"
                    >

                        <span>
                            Total Pembayaran
                        </span>

                        <span>
                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                        </span>

                    </div>

                </dl>

            </div>

        </div>


        {{-- =================================================
             RINCIAN ITEM
        ================================================== --}}

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">

            <h4 class="text-sm font-bold text-slate-900 mb-4">
                Rincian Item
            </h4>

            <div class="space-y-3">

                @forelse($order->items as $item)

                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">

                        <div>

                            <p class="text-xs font-bold text-slate-900">
                                {{ $item->product_name }}
                            </p>

                            <p class="text-[10px] text-slate-500 mt-1">
                                {{ $item->quantity }}
                                x
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </p>

                        </div>


                        <p class="text-xs font-black text-slate-900">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </p>

                    </div>

                @empty

                    <div class="py-6 text-center text-sm text-slate-500">
                        Tidak ada item pesanan.
                    </div>

                @endforelse

            </div>

        </div>

    </div>


    {{-- =====================================================
         SIDEBAR ADMIN
    ====================================================== --}}

    <div class="space-y-6">


        {{-- =================================================
             KONFIRMASI PEMBAYARAN
        ================================================== --}}

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">

            <h4 class="text-sm font-bold text-slate-900">
                Konfirmasi Pembayaran
            </h4>

            <p class="text-xs text-slate-500 mt-1 mb-4">
                Konfirmasi pembayaran setelah Anda memastikan pembayaran pelanggan telah diterima.
            </p>


            {{-- MENUNGGU PEMBAYARAN --}}

            @if($order->status === 'menunggu_pembayaran')

                <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 mb-4">

                    <div class="flex gap-3">

                        <div class="text-xl">
                            💳
                        </div>

                        <div>

                            <p class="text-xs font-black text-amber-800">
                                Menunggu Pembayaran
                            </p>

                            <p class="text-[11px] text-amber-700 mt-1">
                                Pastikan nominal yang diterima sesuai dengan total pembayaran.
                            </p>

                        </div>

                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route('admin.orders.confirm_payment', $order) }}"
                    onsubmit="return confirm('Apakah Anda yakin pembayaran pesanan ini sudah diterima?');"
                >

                    @csrf

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-emerald-500 text-white text-xs font-black py-3 hover:bg-emerald-600 transition"
                    >
                        ✓ Konfirmasi Pembayaran
                    </button>

                </form>


            {{-- SUDAH DIBAYAR --}}

            @elseif(in_array($order->status, ['dibayar', 'dikemas', 'dikirim', 'selesai']))

                <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4">

                    <div class="flex items-center gap-3">

                        <div
                            class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center font-black"
                        >
                            ✓
                        </div>

                        <div>

                            <p class="text-xs font-black text-emerald-700">
                                Pembayaran Dikonfirmasi
                            </p>

                            <p class="text-[10px] text-emerald-600 mt-1">
                                Pesanan dapat diproses.
                            </p>

                        </div>

                    </div>

                </div>


            {{-- DIBATALKAN --}}

            @elseif($order->status === 'dibatalkan')

                <div class="rounded-xl bg-red-50 border border-red-200 p-4">

                    <p class="text-xs font-black text-red-700">
                        Pesanan Dibatalkan
                    </p>

                    <p class="text-[10px] text-red-600 mt-1">
                        Pembayaran tidak dapat dikonfirmasi.
                    </p>

                </div>

            @endif

        </div>


        {{-- =================================================
             UPDATE PENGIRIMAN
        ================================================== --}}

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">

            <h4 class="text-sm font-bold text-slate-900 mb-1">
                Update Pengiriman
            </h4>

            <p class="text-xs text-slate-500 mb-4">
                Isi informasi pengiriman setelah pembayaran dikonfirmasi.
            </p>


            {{-- ERROR VALIDASI --}}

            @if($errors->any())

                <div class="rounded-xl bg-red-50 border border-red-200 p-4 mb-4">

                    <div class="flex gap-3">

                        <div class="text-lg">
                            ⚠️
                        </div>

                        <div>

                            <p class="text-xs font-black text-red-700">
                                Data belum dapat disimpan
                            </p>

                            <ul class="mt-2 space-y-1">

                                @foreach($errors->all() as $error)

                                    <li class="text-[11px] text-red-600">
                                        • {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    </div>

                </div>

            @endif


            <form
                method="POST"
                action="{{ route('admin.orders.update_shipping', $order) }}"
                class="space-y-3"
            >

                @csrf


                {{-- =========================================
                     KURIR
                ========================================== --}}

                <div>

                    <label
                        class="block text-[10px] font-bold text-slate-500 uppercase mb-1"
                    >
                        Kurir
                    </label>

                    <input
                        type="text"
                        name="courier"
                        value="{{ old('courier', $order->courier) }}"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400"
                        placeholder="Contoh: Lalamove"
                        required
                    >

                </div>


                {{-- =========================================
                     NO RESI
                ========================================== --}}

                <div>

                    <label
                        class="block text-[10px] font-bold text-slate-500 uppercase mb-1"
                    >
                        No. Resi
                    </label>

                    <input
                        type="text"
                        name="tracking_code"
                        value="{{ old('tracking_code', $order->tracking_code) }}"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400"
                        placeholder="Nomor resi pengiriman"
                        required
                    >

                </div>


                {{-- =========================================
                     METODE PEMBAYARAN
                ========================================== --}}

                <div>

                    <label
                        class="block text-[10px] font-bold text-slate-500 uppercase mb-1"
                    >
                        Metode Pembayaran
                    </label>

                    <select
                        name="payment_method"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400"
                    >

                        {{-- VA BCA --}}

                        <option
                            value="va"
                            @selected(old('payment_method', $order->payment_method) === 'va')
                        >
                            Virtual Account BCA
                        </option>


                        {{-- QRIS --}}

                        <option
                            value="qris"
                            @selected(old('payment_method', $order->payment_method) === 'qris')
                        >
                            QRIS
                        </option>


                        {{-- TRANSFER BANK --}}

                        <option
                            value="bank"
                            @selected(old('payment_method', $order->payment_method) === 'bank')
                        >
                            Transfer Bank (BCA)
                        </option>

                    </select>

                </div>


                {{-- =========================================
                     STATUS PESANAN
                ========================================== --}}

                <div>

                    <label
                        class="block text-[10px] font-bold text-slate-500 uppercase mb-1"
                    >
                        Status
                    </label>

                    <select
                        name="status"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400"
                        required
                    >

                        @foreach([
                            'menunggu_pembayaran',
                            'dibayar',
                            'dikemas',
                            'dikirim',
                            'selesai',
                            'dibatalkan'
                        ] as $status)

                            <option
                                value="{{ $status }}"
                                @selected(old('status', $order->status) === $status)
                            >
                                {{ str_replace('_', ' ', ucwords($status)) }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- =========================================
                     BUTTON
                ========================================== --}}

                <button
                    type="submit"
                    class="w-full rounded-xl bg-orange-500 text-white text-xs font-black py-2.5 hover:bg-orange-600 transition"
                >
                    Simpan Perubahan Pengiriman
                </button>

            </form>

        </div>

    </div>

</div>

@endsection