@props([
    'title',
    'category',
    'date',
    'salary',
    'location',
    'link' => '#'
])

<div class="bg-white rounded-2xl border border-gray-100 p-4 w-full max-w-sm shadow-sm">
    <div class="flex items-start justify-between mb-3">
        <span class="text-sm bg-blue/20 px-3 py-1 rounded-lg font-medium">
            #{{ $category }}
        </span>
        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
            <x-lucide-heart class="w-5 h-5 text-gray-500" />
        </div>
    </div>

    <h3 class="text-lg font-semibold text-gray-900 leading-snug">
        {{ $title }}
    </h3>

    <p class="text-sm text-gray-400 mt-1">
        Опубликовано {{ $date }}
    </p>

    <div class="mt-4 space-y-2 text-gray-500 text-sm">
        <div class="flex items-center gap-2">
            <x-lucide-coins class="w-5 h-5 text-gray-400" />
            <span>{{ $salary }}</span>
        </div>
        <div class="flex items-center gap-2">
            <x-lucide-map-pin class="w-5 h-5 text-gray-400" />
            <span>{{ $location }}</span>
        </div>
    </div>

    <a href="{{ $link }}" class="inline-block mt-4 text-primary font-medium hover:underline">
        Подробнее
    </a>
</div>