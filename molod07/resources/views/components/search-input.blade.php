@props([
    'placeholder' => 'Поиск',
    'name' => 'q',
    'value' => ''
])

<div class="relative" x-data="{ value: '{{ $value }}' }">
    <input
        x-ref="searchInput"
        type="text"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        x-model="value"
        :value="value"
        class="w-full h-10 bg-secondary rounded-xl pl-10 pr-10 text-gray-600 placeholder-gray-400 text-sm border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-200"
    />
    <x-lucide-search class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" />
    <button
        type="button"
        x-show="value"
        @click="
            value = '';
            $refs.searchInput.value = '';
            $el.closest('form').submit();
        "
        class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 focus:outline-none"
        style="display: none;"
    >
        <x-lucide-x class="w-5 h-5" />
    </button>
</div>
