@extends('dashboards.layout')

@section('title', 'Detail Pengaduan #' . $ticket->id)

@section('header-title', 'Detail Pengaduan')

@section('dashboard-content')
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-black text-slate-900">{{ $ticket->subject }}</h3>
                <p class="text-xs text-slate-500 mt-1">Dibuat oleh {{ $ticket->user_name }} ({{ $ticket->user_email }}) • {{ $ticket->created_at->format('d M Y H:i') }}</p>
            </div>
            <span class="text-[10px] font-bold px-2 py-1 rounded-full
                @if($ticket->status === 'open') bg-blue-100 text-blue-700
                @elseif($ticket->status === 'answered') bg-amber-100 text-amber-700
                @else bg-emerald-100 text-emerald-700 @endif">
                {{ ucfirst($ticket->status) }}
            </span>
        </div>

        <div class="bg-slate-50 rounded-xl p-4 mb-6">
            <p class="text-xs font-bold text-slate-500 mb-1">Pesan Awal</p>
            <p class="text-sm text-slate-700 leading-relaxed">{{ $ticket->message }}</p>
        </div>

        <div class="space-y-4 mb-6">
            @foreach($ticket->replies as $reply)
                <div class="rounded-xl p-4 border @if($reply->replier_role === 'admin') bg-emerald-50 border-emerald-200 @else bg-slate-50 border-slate-200 @endif">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-black text-slate-900">{{ $reply->replier_name }}</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full @if($reply->replier_role === 'admin') bg-emerald-200 text-emerald-800 @else bg-slate-200 text-slate-700 @endif">{{ ucfirst($reply->replier_role) }}</span>
                        <span class="text-[10px] text-slate-400">{{ $reply->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <p class="text-sm text-slate-700 leading-relaxed">{{ $reply->message }}</p>
                </div>
            @endforeach
        </div>

        @if($ticket->status !== 'closed')
            <form method="POST" action="{{ route('tickets.reply', $ticket) }}" class="bg-slate-50 rounded-xl p-4">
                @csrf
                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Balas Pengaduan</label>
                <textarea name="message" rows="3" required class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm" placeholder="Tulis balasan Anda..."></textarea>
                <div class="mt-3 flex items-center gap-3">
                    <button type="submit" class="rounded-xl bg-orange-500 text-white text-xs font-black px-4 py-2 hover:bg-orange-600 transition">Kirim Balasan</button>
                    @if(session('user_role') === 'admin' || (Auth::check() && Auth::user()->role === 'admin'))
                        <form method="POST" action="{{ route('tickets.close', $ticket) }}" onsubmit="return confirm('Tutup tiket ini?')">
                            @csrf
                            <button type="submit" class="rounded-xl bg-red-500 text-white text-xs font-black px-4 py-2 hover:bg-red-600 transition">Tutup Tiket</button>
                        </form>
                    @endif
                </div>
            </form>
        @else
            <p class="text-sm text-slate-500 text-center py-4">Tiket ini telah ditutup.</p>
        @endif
    </div>
@endsection
