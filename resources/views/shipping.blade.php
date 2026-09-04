@extends('layouts.app')

@section('title', 'Shipping - AtapIndonesia')

@section('content')
<section class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
    <div class="mb-8">
        <span class="text-xs font-black text-orange-500 uppercase tracking-widest">Pengiriman</span>
        <h1 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white mt-2">Lacak Pesanan Anda</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Status pengiriman dan estimasi sampai barang ke tangan Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1.1fr_0.9fr] gap-6">
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-4 sm:p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-slate-500">Nomor Pesanan</p>
                    <p class="text-xl font-black text-slate-900 dark:text-white mt-1">{{ $pendingOrder['order_id'] }}</p>
                </div>
                <span class="rounded-full bg-emerald-500/10 text-emerald-600 px-3 py-1 text-xs font-black uppercase">{{ $tracking['status'] }}</span>
            </div>

            <div class="mt-6 space-y-4">
                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 p-4">
                    <p class="text-sm font-black text-slate-900 dark:text-white">Status Saat Ini</p>
                    <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">Pesanan Anda sedang {{ strtolower($tracking['status']) }} dan akan segera diteruskan ke kurir.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-950 p-4">
                        <p class="text-[11px] uppercase tracking-widest text-slate-500">Kurir</p>
                        <p class="text-sm font-black text-slate-900 dark:text-white mt-1">{{ $tracking['courier'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-950 p-4">
                        <p class="text-[11px] uppercase tracking-widest text-slate-500">Estimasi</p>
                        <p class="text-sm font-black text-slate-900 dark:text-white mt-1">{{ $tracking['estimated_days'] }} hari</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-950 p-4">
                        <p class="text-[11px] uppercase tracking-widest text-slate-500">Kode</p>
                        <p class="text-sm font-black text-slate-900 dark:text-white mt-1">{{ $tracking['tracking_code'] }}</p>
                    </div>
                </div>
                <div class="rounded-2xl border border-amber-500/20 bg-amber-500/10 p-4">
                    <p class="text-sm font-black text-slate-900 dark:text-white">Estimasi</p>
                    <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">Barang diperkirakan sampai pada {{ $tracking['eta'] }}.</p>
                </div>
            </div>
        </div>

        <aside class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-4 sm:p-6 shadow-sm h-fit mt-2 lg:mt-0">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Detail Pengiriman</p>
            <div class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between text-slate-500 dark:text-slate-400"><span>Tujuan</span><span>{{ $tracking['address'] }}</span></div>
                <div class="flex justify-between text-slate-500 dark:text-slate-400"><span>Estimasi Sampai</span><span>{{ $tracking['eta'] }}</span></div>
                <div class="flex justify-between text-slate-500 dark:text-slate-400"><span>Metode Bayar</span><span>{{ $pendingOrder['payment_method'] }}</span></div>
            </div>
            <div class="mt-6 border-t border-slate-200 dark:border-slate-800 pt-4">
                <a href="{{ route('katalog') }}" class="block w-full rounded-3xl bg-slate-900 text-white text-sm font-black uppercase tracking-widest py-3 text-center hover:bg-slate-800 transition">Kembali ke Katalog</a>
            </div>
        </aside>
    </div>
</section>
@endsection
