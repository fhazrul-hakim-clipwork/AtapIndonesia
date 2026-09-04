@extends('dashboards.layout')

@section('title', 'Detail Pesanan ' . $order->order_id)

@section('header-title', 'Detail Pesanan ' . $order->order_id)

@section('dashboard-content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-slate-500">Nomor Pesanan</p>
                    <p class="text-xl font-black text-slate-900 dark:text-white mt-1">{{ $order->order_id }}</p>
                </div>
                <span class="rounded-full px-3 py-1 text-xs font-black uppercase
                    @if(in_array($order->status, ['dibayar', 'dikemas', 'dikirim', 'selesai'])) bg-emerald-500/10 text-emerald-600
                    @elseif(in_array($order->status, ['dibatalkan'])) bg-red-500/10 text-red-600
                    @else bg-amber-500/10 text-amber-600 @endif">
                    {{ str_replace('_', ' ', ucwords(str_replace('_', ' ', $order->status))) }}
                </span>
            </div>

            <div class="border-t border-slate-200 pt-4 space-y-3">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Pelanggan</p>
                        <p class="font-bold text-slate-900">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Email</p>
                        <p class="font-bold text-slate-900">{{ $order->customer_email }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Telepon</p>
                        <p class="font-bold text-slate-900">{{ $order->telepon }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Alamat</p>
                        <p class="font-bold text-slate-900">{{ $order->alamat }}</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-200 mt-6 pt-6">
                <h4 class="text-sm font-bold text-slate-900 mb-4">Rincian Item</h4>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                            <div>
                                <p class="text-xs font-bold text-slate-900">{{ $item->product_name }}</p>
                                <p class="text-[10px] text-slate-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <p class="text-xs font-black text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-slate-200 mt-6 pt-6">
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between text-slate-600"><span>Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between text-slate-600"><span>Ongkir</span><span>Rp {{ number_format($order->shipping_fee, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between text-base font-black text-slate-900 border-t border-slate-200 pt-2"><span>Total</span><span>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span></div>
                </dl>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 h-fit">
            <h4 class="text-sm font-bold text-slate-900 mb-4">Update Pengiriman</h4>
            <form method="POST" action="{{ route('admin.orders.update_shipping', $order) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Kurir</label>
                    <input type="text" name="courier" value="{{ $order->courier }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">No. Resi</label>
                    <input type="text" name="tracking_code" value="{{ $order->tracking_code }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Metode Pembayaran</label>
                    <select name="payment_method" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs">
                        <option value="va" @selected($order->payment_method == 'va')>Virtual Account</option>
                        <option value="qris" @selected($order->payment_method == 'qris')>QRIS</option>
                        <option value="bank" @selected($order->payment_method == 'bank')>Transfer Bank (BCA)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Status</label>
                    <select name="status" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs" required>
                        @foreach(['menunggu_pembayaran', 'dibayar', 'dikemas', 'dikirim', 'selesai', 'dibatalkan'] as $status)
                            <option value="{{ $status }}" @selected($order->status == $status)>{{ str_replace('_', ' ', ucwords(str_replace('_', ' ', $status))) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full rounded-xl bg-orange-500 text-white text-xs font-black py-2.5 hover:bg-orange-600 transition">Simpan Perubahan</button>
            </form>
        </div>
    </div>
@endsection
