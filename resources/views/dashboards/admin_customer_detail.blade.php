@extends('dashboards.layout')

@section('title', 'Detail Customer - ' . $user->name)

@section('header-title', 'Detail Customer')

@section('dashboard-content')
    <div class="mb-4">
        <a href="{{ route('admin.customers.index') }}" class="text-sm font-semibold text-slate-500 hover:text-orange-500 transition">
            ← Kembali ke Daftar Customer
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- PROFIL CARD --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 lg:col-span-1 h-fit">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-amber-500 rounded-full flex items-center justify-center text-white text-2xl font-black shadow-lg">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-900 dark:text-white">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Customer #{{ $user->id }}</p>
                </div>
            </div>

            <div class="space-y-4 text-sm">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Email</p>
                    <p class="font-semibold text-slate-900 dark:text-white">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Telepon / WhatsApp</p>
                    <p class="font-semibold text-slate-900 dark:text-white">{{ $user->telepon ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kota</p>
                    <p class="font-semibold text-slate-900 dark:text-white">{{ $user->kota ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Alamat Lengkap</p>
                    <p class="font-semibold text-slate-900 dark:text-white">{{ $user->alamat ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Bergabung Pada</p>
                    <p class="font-semibold text-slate-900 dark:text-white">{{ $user->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

            <div class="mt-8 flex gap-3">
                <a href="{{ route('admin.customers.edit', $user->id) }}" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white font-bold py-2 px-4 rounded-lg text-center transition">
                    Edit Profil
                </a>
            </div>
        </div>

        {{-- RIWAYAT PESANAN --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm p-6 lg:col-span-2">
            <h3 class="text-lg font-black text-slate-900 dark:text-white mb-4 border-b border-slate-100 dark:border-slate-800 pb-3">Riwayat Pesanan ({{ $orders->count() }})</h3>
            
            <div class="space-y-4">
                @forelse($orders as $order)
                    @php
                        $statusClass = match($order->status) {
                            'dikirim' => 'bg-amber-100 text-amber-700',
                            'selesai' => 'bg-emerald-100 text-emerald-700',
                            'dibatalkan', 'gagal' => 'bg-red-100 text-red-700',
                            default => 'bg-blue-100 text-blue-700',
                        };
                    @endphp
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl gap-4 border border-slate-100 dark:border-slate-700">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-sm font-black text-slate-900 dark:text-white hover:text-orange-500 transition">#{{ $order->order_id }}</a>
                                <span class="{{ $statusClass }} text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">{{ str_replace('_', ' ', $order->status) }}</span>
                            </div>
                            <p class="text-xs text-slate-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="text-left md:text-right">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Total</p>
                            <p class="text-sm font-black text-slate-900 dark:text-white">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center p-8 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                        <span class="text-4xl">🛒</span>
                        <p class="text-sm font-semibold text-slate-600 dark:text-slate-400 mt-3">Pelanggan ini belum pernah melakukan pesanan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
