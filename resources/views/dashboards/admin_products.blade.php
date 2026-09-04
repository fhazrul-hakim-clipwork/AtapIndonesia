@extends('dashboards.layout')

@section('title', 'Kelola Produk - AtapIndonesia')

@section('header-title', 'Manajemen Produk')

@section('dashboard-content')
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Semua Produk</h3>
            <a href="{{ route('admin.products.create') }}" class="text-xs bg-orange-500 text-white px-3 py-1.5 rounded-lg font-bold hover:bg-orange-600 transition">+ Tambah Produk</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-400 font-bold">
                        <th class="pb-2 px-5">ID</th>
                        <th class="pb-2 px-5">Produk</th>
                        <th class="pb-2 px-5">Harga</th>
                        <th class="pb-2 px-5">Stok</th>
                        <th class="pb-2 px-5">Kategori</th>
                        <th class="pb-2 px-5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-5 font-mono font-bold text-slate-900">#{{ $product->id }}</td>
                            <td class="py-3 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 overflow-hidden shrink-0">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-full object-cover" alt="">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-lg">📦</div>
                                        @endif
                                    </div>
                                    <span class="text-slate-900">{{ $product->nama }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-5 font-bold text-slate-900">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                            <td class="py-3 px-5">
                                <span class="font-bold {{ $product->stok < 10 ? 'text-red-600' : 'text-emerald-600' }}">
                                    {{ $product->stok }}
                                </span>
                            </td>
                            <td class="py-3 px-5 text-slate-600">{{ $product->kategori_id }}</td>
                            <td class="py-3 px-5">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-orange-600 hover:text-orange-800 font-bold text-[11px]">Edit</a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')" class="inline">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-bold text-[11px]">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-10 text-center text-slate-500">Belum ada produk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
