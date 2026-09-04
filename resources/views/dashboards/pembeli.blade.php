@extends('dashboards.layout')

@section('title', 'Dashboard Pembeli - AtapIndonesia')

@section('sidebar')
    <p class="text-[10px] text-slate-500 uppercase tracking-wider font-bold mb-2 px-2">Menu Saya</p>
    <a href="#overview" class="flex items-center gap-3 px-3 py-2.5 bg-orange-500 text-white rounded-lg transition">
        <span>🏠</span> Beranda Dashboard
    </a>
    <a href="#pesanan-terbaru" class="flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg transition">
        <span>🛒</span> Riwayat Pesanan
    </a>
    <a href="#daftar-belanja" class="flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg transition">
        <span>📋</span> Daftar Belanja
    </a>
    <a href="{{ url('/') }}#simulator" class="flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg transition">
        <span>🧮</span> Sistem Pakar Talang
    </a>

    <p class="text-[10px] text-slate-500 uppercase tracking-wider font-bold pt-4 mb-2 px-2">Akun</p>
    <a href="#profil" class="flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-lg transition">
        <span>👤</span> Profil & Alamat
    </a>

    <a href="{{ route('katalog') }}" target="_blank" class="flex items-center gap-3 px-3 py-2.5 text-orange-400 hover:bg-orange-500/10 rounded-lg border border-orange-500/20 transition">
        <span>🏬</span> Belanja Material Baru
    </a>
@endsection

@section('header-title', 'Dashboard Pembeli')

@section('dashboard-content')
    <div id="overview" class="bg-gradient-to-r from-blue-900 to-slate-900 p-6 rounded-2xl shadow-lg text-white">
        <h2 class="text-xl font-black">Halo, {{ explode(' ', $user->name)[0] }}! 👋</h2>
        <p class="text-xs text-slate-300 mt-1">Selamat datang di panel kendali belanja material atap Anda.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
        @foreach($stats as $stat)
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-1">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $stat['label'] }}</p>
                <span class="text-lg">{{ $stat['icon'] }}</span>
            </div>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $stat['value'] }}</h3>
            @if(isset($stat['desc']))
                <p class="text-[10px] {{ $stat['desc_class'] }} font-bold mt-1">{{ $stat['desc'] }}</p>
            @endif
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <div id="pesanan-terbaru" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 lg:col-span-2">
            <div class="flex justify-between items-center border-b border-slate-100 pb-3 mb-4">
                <h3 class="text-sm font-bold text-slate-900">Pesanan Terbaru</h3>
                <a href="#pesanan-terbaru" class="text-xs text-orange-500 hover:underline font-bold">Refresh</a>
            </div>
            <div class="space-y-3">
                @forelse($orders as $order)
                    @php
                        $statusClass = match($order['status']) {
                            'dikirim' => 'bg-amber-100 text-amber-700',
                            'selesai' => 'bg-emerald-100 text-emerald-700',
                            'dibatalkan', 'gagal' => 'bg-red-100 text-red-700',
                            default => 'bg-blue-100 text-blue-700',
                        };
                    @endphp
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 bg-slate-50 rounded-lg gap-3">
                        <div>
                            <p class="text-xs font-bold text-slate-900">#{{ $order['id'] }}</p>
                            <p class="text-[10px] text-slate-500 mb-2">{{ $order['item'] }}</p>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('orders.show', $order['id']) }}" class="text-[10px] bg-slate-200 text-slate-700 px-3 py-1.5 rounded font-bold hover:bg-slate-300 transition">Detail Pesanan</a>
                                @if($order['status'] === 'menunggu_pembayaran')
                                    <a href="{{ $order['snap_token'] ?? route('orders.show', $order['id']) }}" class="text-[10px] bg-orange-500 text-white px-3 py-1.5 rounded font-bold hover:bg-orange-600 transition shadow-sm">Lakukan Pembayaran</a>
                                @endif
                            </div>
                        </div>
                        <span class="{{ $statusClass }} text-[10px] font-bold px-3 py-1.5 rounded self-start sm:self-center">{{ ucfirst(str_replace('_', ' ', $order['status'])) }}</span>
                    </div>
                @empty
                    <div class="p-3 text-center text-xs text-slate-500">Belum ada pesanan.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">Rekomendasi Cerdas</h3>
            <p class="text-[11px] text-slate-500 mb-3">Berdasarkan analisis Sistem Pakar:</p>
            <div class="bg-orange-50 border border-orange-200 p-3 rounded-lg">
                <p class="text-[11px] text-orange-700 font-bold">💡 Hitung Kebutuhan</p>
                <p class="text-[10px] text-orange-600 mt-1">Coba gunakan sistem pakar kami untuk mengetahui material atap dan talang yang tepat.</p>
                <a href="{{ url('/') }}#simulator" class="inline-block mt-2 text-[10px] text-orange-700 hover:underline font-bold">Mulai Kalkulasi →</a>
            </div>
        </div>
    </div>

    <div id="daftar-belanja" class="mt-6 bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-slate-900">Daftar Belanja (Keranjang)</h3>
            <a href="{{ route('cart.index') }}" class="text-[10px] text-orange-500 hover:underline font-bold">Lihat Semua</a>
        </div>
        <div class="space-y-3 text-[11px] text-slate-600">
            @forelse($cartItems as $item)
                <div class="flex items-center justify-between bg-slate-50 rounded-xl p-3">
                    <div>
                        <p class="font-bold text-slate-900">{{ $item['nama'] }}</p>
                        <p class="text-[10px] text-slate-500">{{ $item['quantity'] }} item</p>
                    </div>
                    <span class="text-[10px] text-slate-500 font-bold">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                </div>
            @empty
                <div class="p-3 text-center text-xs text-slate-500">Keranjang Anda masih kosong.</div>
            @endforelse
        </div>
        @if(count($cartItems) > 0)
        <div class="mt-4 pt-4 border-t border-slate-100 flex justify-end">
            <a href="{{ route('checkout.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-lg text-xs transition shadow-sm">
                Lanjutkan Checkout →
            </a>
        </div>
        @endif
    </div>

    <div id="profil" class="mt-6 bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-slate-900">Profil & Alamat Pengiriman</h3>
        </div>
        <form action="{{ route('pembeli.update_profile') }}" method="POST" class="space-y-4 text-sm text-slate-700">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border border-slate-200 rounded-lg p-2 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition text-xs" required>
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border border-slate-200 rounded-lg p-2 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition text-xs" required>
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">No. WhatsApp</label>
                    <input type="text" name="telepon" value="{{ old('telepon', $user->telepon) }}" class="w-full border border-slate-200 rounded-lg p-2 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition text-xs" required>
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">Kota / Kabupaten</label>
                    <input type="text" name="kota" value="{{ old('kota', $user->kota) }}" class="w-full border border-slate-200 rounded-lg p-2 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition text-xs" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold mb-1">Alamat Lengkap</label>
                    <textarea name="alamat" class="w-full border border-slate-200 rounded-lg p-2 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 outline-none transition text-xs h-20" required>{{ old('alamat', $user->alamat) }}</textarea>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-lg text-xs transition">Simpan Profil</button>
            </div>
        </form>
    </div>
@endsection
