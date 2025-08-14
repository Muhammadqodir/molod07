@props([
    /** массив строк или массива вида [['label'=>'...'], ...] */
    'items' => [],
    /** сколько показывать сразу */
    'limit' => 2,
    /** заголовок модалки */
    'title' => 'Все элементы',
    /** класс для чипов */
    'itemClass' => 'inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-2 py-0.5 rounded-md text-sm',
    /** текст для кнопки +N, авто-склонение по-русски */
    'noun' => ['привилегия', 'привилегии', 'привилегий'], // 1, 2-4, 5+
])

@php
    // нормализуем в массив строк
    $normalized = collect($items)
        ->map(function ($it) {
            if (is_array($it)) {
                return $it['label'] ?? ($it['value'] ?? reset($it));
            }
            return (string) $it;
        })
        ->filter()
        ->values()
        ->all();

    $visible = array_slice($normalized, 0, (int) $limit);
    $hidden = array_slice($normalized, (int) $limit);
@endphp

<div x-data="{ open: false }" class="relative">
    <div class="flex flex-wrap gap-2">
        @foreach ($visible as $txt)
            <span class="{{ $itemClass }}">{{ $txt }}</span>
        @endforeach

        @if (count($hidden) > 0)
            <button type="button" @click="open = true" class="text-sm text-primary hover:underline">
                + {{ count($hidden) }}
                <span x-data
                    x-text="(function(n){
                    const f = [{{ json_encode($noun[0]) }}, {{ json_encode($noun[1]) }}, {{ json_encode($noun[2]) }}];
                    return (n%10==1&&n%100!=11)?f[0]:(n%10>=2&&n%10<=4&&(n%100<10||n%100>=20))?f[1]:f[2];
                })({{ count($hidden) }})"></span>
            </button>
        @endif
    </div>

    <div x-show="open" x-transition.opacity class="fixed inset-0 z-[9999999999] flex items-center justify-center"
        @keydown.escape.window="open=false" @click.self="open=false" style="background: rgba(0,0,0,.35)">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">{{ $title }}</h3>
                <button class="p-2 rounded-md hover:bg-gray-100" @click="open=false">
                    <x-lucide-x class="w-5 h-5 text-gray-500" />
                </button>
            </div>

            <div class="flex flex-wrap gap-2 max-h-[50vh] overflow-auto">
                @foreach ($normalized as $txt)
                    <span class="{{ $itemClass }}">{{ $txt }}</span>
                @endforeach
            </div>

            <div class="mt-6 text-right">
                <button class="px-4 py-2 rounded-xl bg-primary text-white hover:bg-primary/90"
                    @click="open=false">Закрыть</button>
            </div>
        </div>
    </div>
</div>
