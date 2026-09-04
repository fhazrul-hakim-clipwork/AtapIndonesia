@extends('dashboards.layout')

@section('title', 'Dashboard Admin - AtapIndonesia')

@section('header-title', 'Dashboard Admin')

@section('sidebar')
    <a href="{{ route('dashboard.admin') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard.admin') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
        <span>📊</span> Dashboard
    </a>
    <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.orders.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
        <span>📦</span> Kelola Pesanan
    </a>
    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
        <span>🏷️</span> Kelola Produk
    </a>
    <a href="{{ route('admin.invoices.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.invoices.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
        <span>🧾</span> Kelola Invoice
    </a>
    <a href="{{ route('tickets.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('tickets.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
        <span>🎫</span> Kelola Pengaduan
    </a>
@endsection

@section('dashboard-content')
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Order</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total_orders'] ?? 0 }}</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Revenue</p>
            <h3 class="text-2xl font-black text-emerald-600 mt-1">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Produk</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total_products'] ?? 0 }}</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Pembeli</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $stats['total_customers'] ?? 0 }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <h3 class="text-sm font-bold text-slate-900 mb-4">Pesanan Terbaru</h3>
            <div class="space-y-3">
                @forelse($orders->take(5) as $order)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <div>
                            <p class="text-xs font-bold text-slate-900">{{ $order->order_id }}</p>
                            <p class="text-[10px] text-slate-500">{{ $order->customer_name }}</p>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-1 rounded-full
                            @if(in_array($order->status, ['dibayar', 'dikemas', 'dikirim', 'selesai'])) bg-green-100 text-green-700
                            @elseif(in_array($order->status, ['dibatalkan'])) bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700 @endif">
                            {{ str_replace('_', ' ', ucwords(str_replace('_', ' ', $order->status))) }}
                        </span>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 text-center">Belum ada pesanan.</p>
                @endforelse
            </div>
            <a href="{{ route('admin.orders.index') }}" class="block mt-4 text-center text-xs text-orange-600 font-bold hover:underline">Lihat Semua Pesanan &rarr;</a>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <h3 class="text-sm font-bold text-slate-900 mb-4">Manajemen Produk</h3>
            <div class="space-y-3">
                @forelse($products->take(5) as $product)
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                        <div>
                            <p class="text-xs font-bold text-slate-900">{{ $product->nama }}</p>
                            <p class="text-[10px] text-slate-500">Stok: {{ $product->stok }} • Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-1 rounded-full bg-slate-100 text-slate-700">Kategori {{ $product->kategori_id }}</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 text-center">Belum ada produk.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
