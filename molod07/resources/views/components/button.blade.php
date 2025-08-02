@props(['type' => 'button'])

<button type="{{ $type }}"
    {{ $attributes->merge(['class' => 'px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold']) }}>
    {{ $slot }}
</button>