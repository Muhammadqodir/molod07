@props([
    'name' => '',
    'label' => '',
])

<label class="inline-flex items-center cursor-pointer select-none space-x-2">
    <input type="checkbox" hidden name="{{ $name }}"
        x-model="{{ $attributes->wire('model')->value() ?? '' }}" x-ref="checkbox" class="peer">
    <x-lucide-check
        class="w-5 h-5 flex items-center justify-center border-[2px] rounded-md
               border-gray-300 bg-white
               peer-checked:bg-blue-50 peer-checked:border-primary
               text-white peer-checked:text-primary transition-all duration-150 ease-in-out"
        stroke-width="3"
    />
    <span class="text-gray-800 text-sm flex items-center h-5">{{ $label }}</span>
</label>
