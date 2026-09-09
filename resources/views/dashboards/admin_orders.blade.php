@extends('dashboards.layout')

@section('title', 'Kelola Pesanan - AtapIndonesia')

@section('header-title', 'Manajemen Pesanan')

@section('dashboard-content')

```
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">

        <h3 class="text-sm font-bold text-slate-900">
            Semua Pesanan
        </h3>

        <span class="text-[10px] text-slate-500 font-bold">
            {{ $orders->total() }} total
        </span>

    </div>


    {{-- =====================================================
         TABLE
    ====================================================== --}}

    <div class="overflow-x-auto">

        <table class="w-full text-left border-collapse text-xs">

            <thead>

                <tr class="border-b border-slate-200 text-slate-400 font-bold">

                    <th class="py-3 px-5">
                        ID
                    </th>

                    <th class="py-3 px-5">
                        Pelanggan
                    </th>

                    <th class="py-3 px-5">
                        Total
                    </th>

                    <th class="py-3 px-5">
                        Pembayaran
                    </th>

                    <th class="py-3 px-5">
                        Status
                    </th>

                    <th class="py-3 px-5">
                        Kurir
                    </th>

                    <th class="py-3 px-5">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-slate-100 font-medium">

                @forelse($orders as $order)

                    <tr class="hover:bg-slate-50">


                        {{-- =================================
                             ORDER ID
                        ================================== --}}

                        <td class="py-3 px-5">

                            <div class="font-mono font-bold text-slate-900">
                                {{ $order->order_id }}
                            </div>

                            <div class="text-[9px] text-slate-400 mt-1">
                                {{ optional($order->created_at)->format('d/m/Y H:i') }}
                            </div>

                        </td>


                        {{-- =================================
                             CUSTOMER
                        ================================== --}}

                        <td class="py-3 px-5">

                            <div class="font-bold text-slate-700">
                                {{ $order->customer_name }}
                            </div>

                            <div class="text-[10px] text-slate-400 mt-1">
                                {{ $order->customer_email }}
                            </div>

                        </td>


                        {{-- =================================
                             TOTAL
                        ================================== --}}

                        <td class="py-3 px-5">

                            <div class="font-black text-slate-900">
                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                            </div>

                            @if(($order->discount ?? 0) > 0)

                                <div class="text-[10px] text-emerald-600 mt-1">
                                    Diskon Rp {{ number_format($order->discount, 0, ',', '.') }}
                                </div>

                            @endif

                        </td>


                        {{-- =================================
                             PAYMENT
                        ================================== --}}

                        <td class="py-3 px-5">

                            @if($order->payment_method === 'va')

                                <span class="px-2 inline-flex text-[10px] leading-5 font-bold rounded-full bg-blue-100 text-blue-700">
                                    BCA VA
                                </span>

                                @if(!empty($order->virtual_account))

                                    <div class="font-mono text-[10px] text-slate-500 mt-1">
                                        {{ $order->virtual_account }}
                                    </div>

                                @endif

                            @elseif($order->payment_method === 'qris')

                                <span class="px-2 inline-flex text-[10px] leading-5 font-bold rounded-full bg-purple-100 text-purple-700">
                                    QRIS
                                </span>

                            @elseif($order->payment_method === 'bank')

                                <span class="px-2 inline-flex text-[10px] leading-5 font-bold rounded-full bg-blue-100 text-blue-700">
                                    BCA BANK
                                </span>

                            @else

                                <span class="px-2 inline-flex text-[10px] leading-5 font-bold rounded-full bg-slate-100 text-slate-700">
                                    {{ strtoupper($order->payment_method ?? 'VA') }}
                                </span>

                            @endif

                        </td>


                        {{-- =================================
                             STATUS
                        ================================== --}}

                        <td class="py-3 px-5">

                            <span class="px-2 inline-flex text-[10px] leading-5 font-bold rounded-full

                                @if(in_array($order->status, [
                                    'dibayar',
                                    'dikemas',
                                    'dikirim',
                                    'selesai'
                                ]))

                                    bg-green-100 text-green-800

                                @elseif($order->status === 'dibatalkan')

                                    bg-red-100 text-red-800

                                @else

                                    bg-yellow-100 text-yellow-800

                                @endif
                            ">

                                {{ str_replace('_', ' ', ucwords($order->status)) }}

                            </span>


                            @if($order->status === 'menunggu_pembayaran')

                                <div class="text-[9px] text-amber-600 font-bold mt-1">
                                    Perlu konfirmasi
                                </div>

                            @elseif($order->status === 'dibayar')

                                <div class="text-[9px] text-emerald-600 font-bold mt-1">
                                    Sudah dibayar
                                </div>

                            @endif

                        </td>


                        {{-- =================================
                             COURIER
                        ================================== --}}

                        <td class="py-3 px-5 text-slate-600">

                            {{ $order->courier ?? '-' }}

                            @if(!empty($order->tracking_code))

                                <div class="text-[9px] text-slate-400 mt-1">
                                    {{ $order->tracking_code }}
                                </div>

                            @endif

                        </td>


                        {{-- =================================
                             ACTION
                        ================================== --}}

                        <td class="py-3 px-5">

                            <a
                                href="{{ route('admin.orders.show', $order) }}"
                                class="inline-flex items-center rounded-lg bg-orange-50 text-orange-600 hover:bg-orange-100 px-3 py-2 font-bold text-[11px] transition"
                            >
                                Detail
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="py-10 text-center text-slate-500"
                        >
                            Belum ada pesanan.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- =====================================================
         PAGINATION
    ====================================================== --}}

    <div class="px-5 py-3 border-t border-slate-100">

        {{ $orders->links() }}

    </div>

</div>
```

@endsection
