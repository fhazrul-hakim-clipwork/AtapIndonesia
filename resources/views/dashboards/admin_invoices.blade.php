@extends('dashboards.layout')

@section('title', 'Kelola Invoice - AtapIndonesia')

@section('header-title', 'Manajemen Invoice')

@section('dashboard-content')

```
{{-- =========================================================
     HEADER INVOICE
========================================================== --}}

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

    <div>
        <h2 class="text-xl font-black text-slate-900">
            Semua Invoice
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Kelola seluruh invoice dan data pesanan pelanggan.
        </p>
    </div>


    {{-- =====================================================
         HAPUS SEMUA INVOICE
    ====================================================== --}}

    @if($orders->total() > 0)

        <form
            method="POST"
            action="{{ route('admin.invoices.delete_all') }}"
            onsubmit="return confirm(
                'PERINGATAN!\\n\\n' +
                'Semua invoice akan dihapus.\\n' +
                'Semua data pesanan juga akan ikut dihapus.\\n\\n' +
                'Tindakan ini TIDAK DAPAT DIBATALKAN.\\n\\n' +
                'Apakah Anda benar-benar ingin melanjutkan?'
            );"
        >
            @csrf

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-red-500 px-4 py-2.5 text-xs font-black text-white shadow-sm transition hover:bg-red-600 sm:w-auto"
            >

                {{-- ICON DELETE --}}
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-9 0h14"
                    />
                </svg>

                Hapus Semua Invoice

            </button>

        </form>

    @endif

</div>


{{-- =========================================================
     TABEL INVOICE
========================================================== --}}

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

    {{-- HEADER TABEL --}}

    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">

        <h3 class="text-sm font-bold text-slate-900">
            Semua Invoice
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

                    <th class="pb-2 px-5">
                        ID
                    </th>

                    <th class="pb-2 px-5">
                        Pelanggan
                    </th>

                    <th class="pb-2 px-5">
                        Total
                    </th>

                    <th class="pb-2 px-5">
                        Pembayaran
                    </th>

                    <th class="pb-2 px-5">
                        Status
                    </th>

                    <th class="pb-2 px-5">
                        Tanggal
                    </th>

                    <th class="pb-2 px-5">
                        Aksi
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-slate-100 font-medium">

                @forelse($orders as $order)

                    <tr class="hover:bg-slate-50">

                        {{-- ID --}}

                        <td class="py-3 px-5 font-mono font-bold text-slate-900">
                            {{ $order->order_id }}
                        </td>


                        {{-- PELANGGAN --}}

                        <td class="py-3 px-5 text-slate-600">
                            {{ $order->customer_name }}
                        </td>


                        {{-- TOTAL --}}

                        <td class="py-3 px-5 font-bold text-slate-900">
                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                        </td>


                        {{-- PEMBAYARAN --}}

                        <td class="py-3 px-5">

                            <span
                                class="px-2 inline-flex text-[10px] leading-5 font-bold rounded-full
                                @if($order->payment_method == 'qris')
                                    bg-purple-100 text-purple-700
                                @elseif($order->payment_method == 'bank')
                                    bg-blue-100 text-blue-700
                                @else
                                    bg-slate-100 text-slate-700
                                @endif"
                            >
                                {{ strtoupper($order->payment_method ?? 'VA') }}
                            </span>

                        </td>


                        {{-- STATUS --}}

                        <td class="py-3 px-5">

                            <span
                                class="px-2 inline-flex text-[10px] leading-5 font-bold rounded-full
                                @if(
                                    $order->status == 'dibayar' ||
                                    $order->status == 'dikemas' ||
                                    $order->status == 'dikirim' ||
                                    $order->status == 'selesai'
                                )
                                    bg-green-100 text-green-800
                                @elseif($order->status == 'dibatalkan')
                                    bg-red-100 text-red-800
                                @else
                                    bg-yellow-100 text-yellow-800
                                @endif"
                            >
                                {{ str_replace('_', ' ', ucwords(str_replace('_', ' ', $order->status))) }}
                            </span>

                        </td>


                        {{-- TANGGAL --}}

                        <td class="py-3 px-5 text-slate-600">
                            {{ $order->created_at->format('d M Y') }}
                        </td>


                        {{-- AKSI --}}

                        <td class="py-3 px-5">

                            <div class="flex items-center gap-3">

                                {{-- DETAIL --}}

                                <a
                                    href="{{ route('admin.invoices.show', $order) }}"
                                    class="text-orange-600 hover:text-orange-800 font-bold text-[11px]"
                                >
                                    Detail
                                </a>


                                {{-- CETAK --}}

                                <a
                                    href="{{ route('admin.invoices.show', $order) }}"
                                    class="text-indigo-600 hover:text-indigo-800 font-bold text-[11px] flex items-center gap-1"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-3 w-3"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"
                                        />
                                    </svg>

                                    Cetak

                                </a>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="7"
                            class="py-10 text-center text-slate-500"
                        >
                            Belum ada invoice.
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
