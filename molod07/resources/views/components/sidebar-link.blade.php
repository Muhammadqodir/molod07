@props(['icon', 'label', 'active' => false, 'href' => '#'])

<li>
    <a href="{{ $href }}"
       class="flex items-center space-x-2 px-3 py-3 rounded-xl
              {{ $active ? 'bg-primary/10 text-primary font-medium' : 'hover:bg-gray-50 text-gray-700' }}">
        <x-dynamic-component :component="'lucide-' . $icon" class="w-5 h-5" />
        <span>{{ $label }}</span>
    </a>
</li>
