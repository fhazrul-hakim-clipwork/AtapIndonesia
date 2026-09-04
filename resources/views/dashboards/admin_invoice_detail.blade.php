@extends('dashboards.layout')

@section('title', 'Detail Invoice ' . $order->order_id)

@section('header-title', 'Detail Invoice ' . $order->order_id)

@push('styles')
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #printable-invoice, #printable-invoice * {
        visibility: visible;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    #printable-invoice {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 0;
        background: white;
    }
    #print-btn-container {
        display: none !important;
    }
    /* Hide specific tailwind shadows and borders that look bad in print */
    .print-no-border { border: none !important; box-shadow: none !important; }
}
</style>
@endpush

@section('dashboard-content')
    <div class="mb-4 flex justify-end" id="print-btn-container">
        <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 transition shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak / Simpan PDF
        </button>
    </div>

    <div id="printable-invoice" class="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden max-w-4xl mx-auto print-no-border">
        
        {{-- DECORATIVE TOP BAR --}}
        <div class="h-3 w-full bg-gradient-to-r from-orange-400 via-orange-500 to-amber-500"></div>

        <div class="p-8 sm:p-10">
            {{-- HEADER INVOICE --}}
            <div class="flex flex-col sm:flex-row justify-between items-start border-b-2 border-slate-100 pb-8 mb-8">
                <div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tighter uppercase">INVOICE</h1>
                    <p class="text-sm font-bold text-slate-500 mt-1 tracking-widest">#{{ $order->order_id }}</p>
                    <div class="mt-3">
                        <span class="inline-block px-3 py-1 text-[10px] font-black uppercase rounded-full tracking-widest border
                            @if(in_array($order->status, ['dibayar', 'dikemas', 'dikirim', 'selesai'])) bg-emerald-50 text-emerald-600 border-emerald-200
                            @elseif(in_array($order->status, ['dibatalkan'])) bg-red-50 text-red-600 border-red-200
                            @else bg-amber-50 text-amber-600 border-amber-200 @endif">
                            {{ str_replace('_', ' ', ucwords(str_replace('_', ' ', $order->status))) }}
                        </span>
                    </div>
                </div>
                <div class="text-right mt-6 sm:mt-0 flex flex-col items-end">
                    <div class="flex items-center gap-3">
                        {{-- LOGO (CROPPED) --}}
                        <div class="w-12 h-12 rounded overflow-hidden shrink-0" style="width: 48px; height: 48px; overflow: hidden; display: block; flex-shrink: 0;">
                            <img src="{{ asset('img/logo-brand.svg') }}" class="w-full h-full object-cover object-left" alt="Logo" style="width: 100%; height: 100%; object-fit: cover; object-position: left;">
                        </div>
                        <div class="text-left">
                            <span class="block text-2xl font-black tracking-wider text-slate-900">
                                ATAP<span class="text-orange-500">INDONESIA</span>
                            </span>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-500 mt-3 max-w-[260px] leading-relaxed font-medium">
                        Jalan Karang Duren, RT 002 RW 003, Sokaraja Lor, Sokaraja (Gerbang Hitam), Kab. Banyumas, Jawa Tengah
                    </p>
                    <p class="text-[10px] font-bold text-slate-600 mt-1">atap.idn@gmail.com <span class="mx-1 text-slate-300">|</span> +62 812-3456-7890</p>
                </div>
            </div>

            {{-- INFO BILLING --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-10 p-6 bg-slate-50 rounded-2xl border border-slate-100">
                <div>
                    <p class="text-[10px] font-black text-orange-500 uppercase tracking-widest mb-2 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Ditagihkan Kepada:
                    </p>
                    <p class="text-lg font-black text-slate-900">{{ $order->customer_name }}</p>
                    <p class="text-xs text-slate-600 mt-1 font-medium leading-relaxed">{{ $order->alamat }}</p>
                    <p class="text-xs text-slate-600 mt-1">{{ $order->customer_email }}</p>
                    <p class="text-xs text-slate-600 font-bold mt-1">{{ $order->telepon }}</p>
                </div>
                <div class="sm:text-right">
                    <p class="text-[10px] font-black text-orange-500 uppercase tracking-widest mb-2 flex items-center sm:justify-end gap-1">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Detail Order:
                    </p>
                    <div class="space-y-1.5 text-xs">
                        <p class="text-slate-600"><span class="font-bold text-slate-900 inline-block w-24 sm:w-auto sm:mr-2">Tanggal:</span> {{ $order->created_at->format('d M Y, H:i') }}</p>
                        <p class="text-slate-600"><span class="font-bold text-slate-900 inline-block w-24 sm:w-auto sm:mr-2">Pembayaran:</span> <span class="bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">{{ $order->payment_method ?? 'Transfer' }}</span></p>
                        <p class="text-slate-600"><span class="font-bold text-slate-900 inline-block w-24 sm:w-auto sm:mr-2">Kurir:</span> <span class="uppercase font-medium">{{ $order->courier ?? '-' }}</span></p>
                        <p class="text-slate-600"><span class="font-bold text-slate-900 inline-block w-24 sm:w-auto sm:mr-2">Resi:</span> <span class="font-mono bg-slate-200/50 px-1.5 py-0.5 rounded">{{ $order->tracking_code ?? '-' }}</span></p>
                    </div>
                </div>
            </div>

            {{-- RINCIAN ITEM --}}
            <div class="mb-10">
                <div class="rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-white text-[10px] uppercase tracking-widest font-black">
                                <th class="p-4">Deskripsi Produk</th>
                                <th class="p-4 text-center">Harga</th>
                                <th class="p-4 text-center">Qty</th>
                                <th class="p-4 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach($order->items as $index => $item)
                            <tr class="border-b border-slate-100 last:border-none {{ $index % 2 == 0 ? 'bg-white' : 'bg-slate-50/50' }}">
                                <td class="p-4 font-bold text-slate-800">{{ $item->product_name }}</td>
                                <td class="p-4 text-center text-slate-600 font-medium">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="p-4 text-center text-slate-800 font-bold bg-slate-50 border-x border-slate-100/50">{{ $item->quantity }}</td>
                                <td class="p-4 text-right font-black text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- TOTAL --}}
            <div class="flex justify-end">
                <div class="w-full sm:w-1/2 lg:w-5/12">
                    <div class="bg-orange-50 rounded-xl p-5 border border-orange-100">
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-slate-600 font-medium">
                                <span>Subtotal Item</span>
                                <span class="font-bold text-slate-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-slate-600 font-medium pb-4 border-b border-orange-200/50">
                                <span>Ongkos Kirim</span>
                                <span class="font-bold text-slate-900">Rp {{ number_format($order->shipping_fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-xl font-black text-slate-900 pt-2 items-center">
                                <span>TOTAL</span>
                                <span class="text-orange-600 bg-white px-3 py-1 rounded-lg border border-orange-200 shadow-sm">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FOOTER CATATAN --}}
            <div class="mt-16 pt-6 border-t-2 border-dashed border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3 opacity-60">
                    <svg class="w-6 h-6 text-orange-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-800">ATAPINDONESIA GUARANTEE</p>
                        <p class="text-[9px] font-medium text-slate-500">Material asli dan berkualitas tinggi.</p>
                    </div>
                </div>
                <div class="text-center sm:text-right">
                    <p class="text-xs text-slate-700 font-bold">Terima kasih atas kepercayaan Anda.</p>
                    <p class="text-[9px] text-slate-400 mt-1 uppercase tracking-widest">Invoice ini sah dan digenerate otomatis oleh sistem.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
