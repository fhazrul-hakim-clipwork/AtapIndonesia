@extends('dashboards.layout')

@section('title', $product ? 'Edit Produk' : 'Tambah Produk')

@section('header-title', $product ? 'Edit Produk' : 'Tambah Produk')

@section('dashboard-content')

    <div class="max-w-2xl">

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">

            <form
                method="POST"
                action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}"
                enctype="multipart/form-data"
                class="space-y-4"
            >
                @csrf

                {{-- =====================================================
                     NAMA PRODUK
                ====================================================== --}}
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">
                        Nama Produk
                    </label>

                    <input
                        type="text"
                        name="nama"
                        value="{{ old('nama', $product->nama ?? '') }}"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm"
                    >
                </div>

                {{-- =====================================================
                     HARGA & STOK
                ====================================================== --}}
                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">
                            Harga (Rp)
                        </label>

                        <input
                            type="number"
                            name="harga"
                            value="{{ old('harga', $product->harga ?? '') }}"
                            required
                            min="0"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm"
                        >
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">
                            Stok
                        </label>

                        <input
                            type="number"
                            name="stok"
                            value="{{ old('stok', $product->stok ?? '') }}"
                            required
                            min="0"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm"
                        >
                    </div>

                </div>

                {{-- =====================================================
                     KATEGORI & MATERIAL
                ====================================================== --}}
                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">
                            Kategori ID (1-5)
                        </label>

                        <input
                            type="number"
                            name="kategori_id"
                            value="{{ old('kategori_id', $product->kategori_id ?? '1') }}"
                            required
                            min="1"
                            max="5"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm"
                        >
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">
                            Material
                        </label>

                        <input
                            type="text"
                            name="material"
                            value="{{ old('material', $product->material ?? '') }}"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm"
                            placeholder="Contoh: Galvanis"
                        >
                    </div>

                </div>

                {{-- =====================================================
                     SELLER & PROMO
                ====================================================== --}}
                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">
                            Seller / Toko
                        </label>

                        <input
                            type="text"
                            name="seller"
                            value="{{ old('seller', $product->seller ?? '') }}"
                            required
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm"
                        >
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">
                            Promo
                        </label>

                        <input
                            type="text"
                            name="promo"
                            value="{{ old('promo', $product->promo ?? '') }}"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm"
                            placeholder="Opsional"
                        >
                    </div>

                </div>

                {{-- =====================================================
                     RATING & GAMBAR
                ====================================================== --}}
                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">
                            Rating (0-5)
                        </label>

                        <input
                            type="number"
                            step="0.1"
                            name="rating"
                            value="{{ old('rating', $product->rating ?? '4.5') }}"
                            required
                            min="0"
                            max="5"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm"
                        >
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">
                            Gambar Produk
                        </label>

                        <input
                            type="file"
                            name="image"
                            accept="image/*"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm"
                        >

                        @if($product && $product->image)
                            <img
                                src="{{ asset('storage/' . $product->image) }}"
                                class="mt-2 h-16 w-16 object-cover rounded-lg"
                                alt="{{ $product->nama }}"
                            >
                        @endif
                    </div>

                </div>

                {{-- =====================================================
                     BEST SELLER
                ====================================================== --}}
                <div>
                    <label class="flex items-center gap-2 text-xs text-slate-700">

                        <input
                            type="checkbox"
                            name="best"
                            value="1"
                            {{ old('best', $product->best ?? false) ? 'checked' : '' }}
                            class="rounded border-slate-300"
                        >

                        <span class="font-bold">
                            Best Seller
                        </span>

                    </label>
                </div>

                {{-- =====================================================
                     BUTTON
                ====================================================== --}}
                <div class="flex items-center gap-3 pt-2">

                    <button
                        type="submit"
                        class="rounded-xl bg-orange-500 text-white text-xs font-black px-6 py-2.5 hover:bg-orange-600 transition"
                    >
                        Simpan
                    </button>

                    <a
                        href="{{ route('admin.products.index') }}"
                        class="rounded-xl border border-slate-200 text-slate-700 text-xs font-black px-6 py-2.5 hover:bg-slate-50 transition"
                    >
                        Batal
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection