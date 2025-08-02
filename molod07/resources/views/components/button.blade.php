@props([
    'type' => 'button',
    'variant' => 'primary', // primary | outline | text
])

@php
    $base = 'inline-flex items-center justify-center h-10 gap-0 px-6 py-3 text-[16px] rounded-xl transition';

    $variants = [
        'primary' => 'bg-primary text-white hover:bg-[#173a8c]',
        'outline' => 'border-2 border-[#1E44A3] text-primary hover:bg-[#1E44A3]/10',
        'text'    => 'text-[#1E44A3] hover:bg-primary/10',
    ];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "$base {$variants[$variant]}"]) }}>
    {{ $slot }}
</button>