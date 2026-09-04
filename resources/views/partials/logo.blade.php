@php
    $size = $size ?? 'md';
    $sizes = [
        'sm' => 'h-8',
        'md' => 'h-10',
        'lg' => 'h-12',
        'xl' => 'h-16',
    ];
    $logoHeight = $sizes[$size] ?? $sizes['md'];
@endphp
<a href="{{ url('/') }}"
   class="flex items-center gap-2 group transition-transform hover:scale-105 shrink-0 {{ $containerClass ?? '' }}"
   title="Kembali ke Beranda AtapIndonesia">
    <img src="{{ asset('img/logo-brand.svg') }}?v={{ filemtime(public_path('img/logo-brand.svg')) }}"
         alt="Logo AtapIndonesia"
         class="{{ $logoHeight }} w-auto object-contain drop-shadow-sm transition group-hover:drop-shadow-md shrink-0"
         onerror="this.onerror=null;this.src='{{ asset('img/logo-brand.svg') }}';">
    @if(!empty($showText))
        <div class="leading-tight min-w-0">
            <span class="block {{ $textSize ?? 'text-base' }} font-black tracking-wider whitespace-nowrap {{ ($textColor ?? '') === 'white' ? 'text-white' : 'text-slate-900' }}">
                ATAP<span class="text-orange-500">INDONESIA</span>
            </span>
            @if(!empty($tagline))
                <span class="block text-[9px] font-bold {{ ($textColor ?? '') === 'white' ? 'text-slate-300' : 'text-slate-500' }} uppercase tracking-widest whitespace-nowrap">{{ $tagline }}</span>
            @endif
        </div>
    @endif
</a>
