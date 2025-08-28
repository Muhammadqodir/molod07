@props([
    /** Имя hidden-поля, куда уходит JSON */
    'name',

    /** Массив опций: [['label' => 'Конкурс', 'value' => 'contest'], ...] */
    'options' => [],

    /** Начальное значение: массив значений или JSON-строка */
    'value' => [],

    /** Множественный выбор? (true|false) */
    'multiple' => true,

    /** Заголовок/подсказка над кнопками (необяз.) */
    'title' => null,
    'hint' => null,
    'bgColor' => 'bg-primary/10',
])

@php
    // нормализуем начальное значение
    $initial = is_string($value) ? (json_decode($value, true) ?: []) : (is_array($value) ? $value : []);
@endphp

<div x-data="multiChoice({
    multiple: @js($multiple),
    initial: @js(array_values($initial)),
    name: @js($name)
})" class="space-y-2 mb-3">
    @if ($title || $hint)
        <div>
            @if ($title)
                <div class="text-sm font-medium text-gray-800">{{ $title }}</div>
            @endif
            @if ($hint)
                <div class="text-xs text-gray-500">{{ $hint }}</div>
            @endif
        </div>
    @endif

    <div class="flex flex-wrap gap-2">
        @foreach ($options as $opt)
            @php
                $label = $opt['label'] ?? (is_array($opt) ? reset($opt) : (string) $opt);
                $val = $opt['value'] ?? (is_array($opt) ? $opt['value'] ?? Str::slug($label) : (string) $opt);
            @endphp

            <button type="button"
                class="px-4 py-2 rounded-xl text-sm
                       transition-colors
                       data-pill
                       {{ $bgColor }} text-gray-500"
                :class="isSelected(@js($val)) ?
                    'bg-primary/30 text-primary' :
                    ''"
                @click="toggle(@js($val))">
                @if (isset($opt['icon']))
                    <x-dynamic-component :component="'lucide-' . $opt['icon']" class="w-4 h-4 inline mr-0.5 mb-1" />
                @endif
                {{ $label }}
            </button>
        @endforeach
    </div>

    <input type="hidden" :name="name" :value="inputValue" />
</div>

@once
    @push('scripts')
        <script>
            function multiChoice({
                multiple = true,
                initial = [],
                name
            }) {
                return {
                    name,
                    selected: Array.isArray(initial) ? [...initial] : [],
                    get inputValue() {
                        return multiple ? JSON.stringify(this.selected) : (this.selected[0] ?? '');
                    },
                    isSelected(v) {
                        return this.selected.includes(v);
                    },
                    toggle(v) {
                        if (multiple) {
                            this.isSelected(v) ?
                                this.selected = this.selected.filter(x => x !== v) :
                                this.selected.push(v);
                        } else {
                            this.selected = this.isSelected(v) ? [] : [v];
                        }
                    },
                }
            }
        </script>
    @endpush
@endonce
