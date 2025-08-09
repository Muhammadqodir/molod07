@props(['icon' => null, 'label' => '', 'open' => false])

<div x-data="{ open: @js($open) }">
  <button type="button" @click="open = !open"
          class="w-full flex items-center justify-between px-3 py-3 rounded-xl hover:bg-gray-50 text-gray-700">
    <div class="flex items-center gap-2">
      @if($icon)
        <x-dynamic-component :component="'lucide-' . $icon" class="w-5 h-5" />
      @endif
      <span class="font-medium">{{ $label }}</span>
    </div>
    <x-lucide-chevron-down class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': open }" />
  </button>

  <ul x-show="open" x-transition class="mt-1 pl-0 space-y-1">
    {{ $slot }}
  </ul>
</div>
