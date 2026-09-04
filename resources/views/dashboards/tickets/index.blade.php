@extends('dashboards.layout')

@section('title', 'Kelola Pengaduan - AtapIndonesia')

@section('header-title', 'Sistem Pengaduan')

@section('dashboard-content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                <h3 class="text-sm font-bold text-slate-900">Daftar Pengaduan</h3>
                <a href="{{ route('tickets.store') }}" class="text-xs bg-orange-500 text-white px-3 py-1.5 rounded-lg font-bold hover:bg-orange-600 transition">+ Buat Pengaduan</a>
            </div>

            @if($tickets->count() === 0)
                <p class="text-sm text-slate-500 text-center py-10">Belum ada pengaduan.</p>
            @else
                <div class="space-y-3">
                    @foreach($tickets as $ticket)
                        <a href="{{ route('tickets.show', $ticket) }}" class="flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-orange-50 transition">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-black text-slate-900 truncate">{{ $ticket->subject }}</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full
                                        @if($ticket->status === 'open') bg-blue-100 text-blue-700
                                        @elseif($ticket->status === 'answered') bg-amber-100 text-amber-700
                                        @else bg-emerald-100 text-emerald-700 @endif">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500">{{ $ticket->user_name }} • {{ $ticket->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-right shrink-0 ml-4">
                                <span class="text-[10px] text-slate-400">{{ $ticket->replies_count }} balasan</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">Buat Pengaduan Baru</h3>
            <form method="POST" action="{{ route('tickets.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Subjek</label>
                    <input type="text" name="subject" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs" placeholder="Contoh: Pesanan belum sampai">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Pesan</label>
                    <textarea name="message" rows="4" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs" placeholder="Jelaskan keluhan atau pertanyaan Anda..."></textarea>
                </div>
                <button type="submit" class="w-full rounded-xl bg-orange-500 text-white text-xs font-black py-2.5 hover:bg-orange-600 transition">Kirim Pengaduan</button>
            </form>
        </div>
    </div>
@endsection
