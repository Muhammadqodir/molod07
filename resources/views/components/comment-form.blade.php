@props([
    'action' => '#',
    'method' => 'POST',
    'name'   => 'body',
    'placeholder' => 'Введите текст комментария',
    'button' => 'Отправить',
])

<form action="{{ $action }}" method="POST" class="w-full">
    @csrf
    @if (strtoupper($method) !== 'POST')
        @method($method)
    @endif

    <div
        class="flex items-center w-full rounded-lg border border-gray-200 bg-white
               h-10 pl-3 pr-3 text-sm shadow-sm">

        {{-- Поле ввода (прозрачное, без рамок) --}}
        <input
            type="text"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            class="flex-1 bg-transparent ml-3 placeholder:text-gray-400 text-gray-800
                   focus:outline-none focus:ring-0 border-0"
            autocomplete="off"
        />

        {{-- Кнопка отправки --}}
        <button type="submit"
                class="ml-2 inline-flex items-center gap-1 text-primary hover:opacity-90
                       transition-opacity">
            <x-lucide-send class="w-4 h-4" />
            <span class="font-medium">{{ $button }}</span>
        </button>
    </div>
</form>
