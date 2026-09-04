@extends('dashboards.layout')

@section('title', 'Edit Customer - ' . $user->name)

@section('header-title', 'Edit Customer')

@section('dashboard-content')
    <div class="mb-4">
        <a href="{{ route('admin.customers.index') }}" class="text-sm font-semibold text-slate-500 hover:text-orange-500 transition">
            ← Kembali ke Daftar Customer
        </a>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm max-w-2xl">
        <div class="p-6 border-b border-slate-100 dark:border-slate-800">
            <h2 class="text-lg font-black text-slate-900 dark:text-white">Edit Data Pelanggan</h2>
            <p class="text-xs text-slate-500">Perbarui informasi profil dan alamat pelanggan.</p>
        </div>

        <form action="{{ route('admin.customers.update', $user->id) }}" method="POST" class="p-6">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-lg p-2.5 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition text-sm dark:text-white" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-lg p-2.5 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition text-sm dark:text-white" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $user->telepon) }}" class="w-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-lg p-2.5 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition text-sm dark:text-white">
                    @error('telepon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kota / Kabupaten</label>
                    <input type="text" name="kota" value="{{ old('kota', $user->kota) }}" class="w-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-lg p-2.5 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition text-sm dark:text-white">
                    @error('kota') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Alamat Lengkap</label>
                    <textarea name="alamat" class="w-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-lg p-2.5 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition text-sm dark:text-white h-24">{{ old('alamat', $user->alamat) }}</textarea>
                    @error('alamat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('admin.customers.index') }}" class="px-5 py-2.5 rounded-lg text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 font-semibold transition text-sm">Batal</a>
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2.5 px-6 rounded-lg text-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
