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
                class="px-4 py-2 rounded-xl border text-sm
                       transition-colors
                       data-pill
                       border-gray-200 bg-gray-50 text-gray-700
                       hover:bg-gray-100"
                :class="isSelected(@js($val)) ?
                    'bg-primary/10 text-primary border-primary' :
                    ''"
                @click="toggle(@js($val))">
                {{ $label }}
            </button>
        @endforeach
    </div>

    <!-- скрытое поле с JSON -->
    <input type="hidden" :name="name" x-model="json" />
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
                    get json() {
                        return JSON.stringify(this.selected);
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
