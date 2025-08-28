@props(['entity', 'count'])

<form method="GET" action="{{ route($entity . '.list') }}" class="mb-6 flex flex-wrap gap-4 items-end">
    @php
        $categories = config('events.categories');
        array_unshift($categories, ['icon' => 'layout-grid', 'label' => 'Все', 'value' => 'Все']);
        $types = config($entity . '.types');
        $bgColor = 'bg-white';
        $currentCategory = request('category', 'Все');
        $currentSort = request('sort', 'popular');
    @endphp

    <div class="flex flex-wrap gap-2">
        @foreach ($categories as $category)
            @php
                $selected = $currentCategory === $category['value'];
            @endphp
            <form method="GET" action="{{ route($entity . '.list') }}" style="display:inline;">
                <input type="hidden" name="sort" value="{{ $currentSort }}">
                <button type="submit" name="category" value="{{ $category['value'] }}"
                    class="inline-flex gap-1 px-4 py-2 rounded-xl text-sm transition-colors {{ $bgColor }} text-gray-500 {{ $selected ? 'bg-primary/30 text-primary' : '' }}">
                    @if (!empty($category['icon']))
                        <x-dynamic-component :component="'lucide-' . $category['icon']" class="w-4 h-4 inline mr-0.5 mb-1" />
                    @endif
                    {{ $category['label'] }}
                </button>
            </form>
        @endforeach
    </div>

    <div class="flex items-center justify-between gap-4 w-full">
        {{-- Левая часть: счётчик --}}
        <div class="text-sm text-gray-600">
            Найдено <span class="font-medium text-gray-800">{{ $count ?? 0 }}</span>
        </div>

        {{-- Правая часть: сортировка --}}
        <div class="flex items-center gap-2 ml-auto">
            <label for="sort" class="text-sm text-gray-700 mr-2">Сортировать:</label>
            <form method="GET" action="{{ route($entity . '.list') }}" style="display:inline;">
                <input type="hidden" name="category" value="{{ $currentCategory }}">
                <button type="submit" name="sort" value="popular"
                    class="flex items-center px-3 py-1 rounded bg-transparent text-gray-800 hover:text-blue-600 {{ $currentSort === 'popular' ? 'font-semibold text-blue-600' : '' }}">
                    <x-lucide-star class="w-4 h-4 mr-1" />
                    По популярности
                </button>
            </form>
            <form method="GET" action="{{ route($entity . '.list') }}" style="display:inline;">
                <input type="hidden" name="category" value="{{ $currentCategory }}">
                <button type="submit" name="sort" value="date"
                    class="flex items-center px-3 py-1 rounded bg-transparent text-gray-800 hover:text-blue-600 {{ $currentSort === 'date' ? 'font-semibold text-blue-600' : '' }}">
                    <x-lucide-calendar class="w-4 h-4 mr-1" />
                    По дате
                </button>
            </form>
        </div>
    </div>
</form>
