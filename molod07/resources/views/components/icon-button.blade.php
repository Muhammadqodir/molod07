@props(['icon' => null, 'alt' => 'icon'])

<div {{ $attributes->merge(['class' => "w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 cursor-pointer"]) }}>
    @if ($icon)
        <img src="{{ asset('images/' . $icon . '.svg') }}" alt="{{ $alt }}" class="w-5 h-5">
    @else
        {{-- Плейсхолдер --}}
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" />
            <path d="M12 8v4m0 4h.01"/>
        </svg>
    @endif
</div>