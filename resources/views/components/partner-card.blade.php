@props([
    'id',
    'name',
    'director',
    'address',
    'pic',
    'phone',
    'web',
    'eventsCount' => 0,
    'vacanciesCount' => 0,
    'link' => '#',
])
<a href="{{ $link }}" class="no-underline">
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-lg transition cursor-pointer">
        <div class="flex flex-col sm:flex-row gap-4 items-center">
            <!-- Фото партнера -->
            <div class="flex-shrink-0">
                @if ($pic)
                    <img src="{{ asset('uploads/' . $pic) }}" alt="{{ $name }}"
                        class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover">
                @else
                    <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-xl flex items-center justify-center">
                        <x-lucide-building-2 class="w-8 h-8 text-gray-400" />
                    </div>
                @endif
            </div>

            <!-- Информация о партнере -->
            <div class="flex-1 min-w-0">
                <h3 class="text-lg font-semibold text-gray-900 leading-snug mb-1">
                    {{ $name }}
                </h3>

                @if ($director)
                    <p class="text-sm text-gray-600 mb-2">
                        Руководитель: {{ $director }}
                    </p>
                @endif

                @if ($address)
                    <p class="text-sm text-gray-500 mb-3 flex items-center gap-2">
                        <x-lucide-map-pin class="w-4 h-4 text-gray-400 flex-shrink-0" />
                        <span class="truncate">{{ $address }}</span>
                    </p>
                @endif

                <!-- Статистика -->
                <div class="flex gap-4">
                    <div class="flex items-center gap-2 text-center">
                        <x-lucide-calendar-days class="w-4 h-4 text-gray-500" />
                        <div class="text-xs text-gray-500">{{ $eventsCount }} мероприятий</div>
                    </div>
                    <div class="flex items-center gap-2 text-center">
                        <x-lucide-briefcase class="w-4 h-4 text-gray-500" />
                        <div class="text-xs text-gray-500">{{ $vacanciesCount }} Вакансий</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</a>
