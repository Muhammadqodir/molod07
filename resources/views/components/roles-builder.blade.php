@props([
    // Заголовок секции
    'label' => 'Роли',
    // Имя textarea, в которое уйдёт JSON
    'name' => 'roles',
    // Начальное значение (JSON или массив)
    'value' => '[]',
    // Максимум ролей
    'maxItems' => 10,
])

@php
    $initial = is_array($value) ? $value : (json_decode($value, true) ?: []);
@endphp

<div x-data="rolesBuilder({
    initial: @js($initial),
    maxItems: @js((int) $maxItems),
    name: @js($name)
})" class="w-full">
    <div class="mb-4">
        <h2 class="text-2xl font-semibold">{{ $label }}</h2>
    </div>

    <!-- список ролей -->
    <div class="space-y-4">
        <template x-for="(role, i) in roles" :key="role._id">
            <div class="rounded-2xl border border-gray-200 bg-white px-4 py-2">
                <div class="flex items-center justify-between">
                    <div class="text-gray-800 font-medium">
                        Роль <span x-text="i + 1"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" class="p-2 rounded-xl hover:bg-gray-50"
                            @click="role._collapsed = !role._collapsed">
                            <x-lucide-chevron-up x-show="!role._collapsed" class="w-5 h-5 text-gray-500" />
                            <x-lucide-chevron-down x-show="role._collapsed" class="w-5 h-5 text-gray-500" />
                        </button>
                        <button type="button" class="p-2 rounded-xl hover:bg-red-50" @click="remove(i)">
                            <x-lucide-trash-2 class="w-5 h-5 text-red-500" />
                        </button>
                    </div>
                </div>

                <div x-show="!role._collapsed" class="mt-4 space-y-6">

                    {{-- Название, задача --}}
                    <div class="space-y-3">
                        <x-input label="Название роли" placeholder="Укажите название" x-model="role.title" />
                        <x-input label="Задача" placeholder="Опишите кратко задачу" x-model="role.task" />
                    </div>

                    {{-- Баллы + период отбора --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input label="Баллы" type="number" placeholder="Количество" x-model="role.points" />

                        <div class="grid grid-cols-2 gap-3">
                            <x-input label="Начало отбора" type="date" x-model="role.selection_start" />
                            <x-input label="Конец отбора" type="date" x-model="role.selection_end" />
                        </div>
                    </div>

                    {{-- Описание (multiline input вместо rich text) --}}
                    <x-multiline-input label="Описание" placeholder="Введите описание" x-model="role.description" />

                    {{-- Занятость (смены) --}}
                    <div class="space-y-3">
                        <div class="text-gray-800 font-medium">Занятость (смены)</div>

                        <template x-for="(shift, si) in role.shifts" :key="shift._id">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                <x-input label="Дата начало" type="date" x-model="shift.date_start" />
                                <x-input label="Дата конец" type="date" x-model="shift.date_end" />
                                <x-input label="Время начало" type="time" x-model="shift.time_start" />
                                <x-input label="Время конец" type="time" x-model="shift.time_end" />

                                <div class="md:col-span-4 flex justify-end">
                                    <button type="button" class="text-sm text-red-600 hover:underline"
                                        @click="removeShift(i, si)">Удалить смену</button>
                                </div>
                            </div>
                        </template>

                        <button type="button" class="text-primary text-sm inline-flex items-center gap-1"
                            @click="addShift(i)">
                            <x-lucide-plus class="w-4 h-4" /> Добавить смену
                        </button>
                    </div>

                    {{-- Требования / Условия --}}
                    <x-input label="Требования" placeholder="Опишите требования" x-model="role.requirements" />
                    <x-input label="Предлагаемые условия" placeholder="Опишите предлагаемые условия"
                        x-model="role.benefits" />
                </div>
            </div>
        </template>
    </div>

    {{-- Кнопка добавить роль --}}
    <div class="mt-4">
        <button type="button"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-primary text-primary hover:bg-primary/5"
            @click="add()" x-bind:disabled="roles.length >= maxItems">
            <x-lucide-plus class="w-4 h-4" />
            Добавить роль
        </button>
        <span class="ml-3 text-sm text-gray-500" x-show="roles.length >= maxItems">
            Достигнут лимит ролей
        </span>
    </div>

    {{-- Скрытое поле с JSON --}}
    <textarea class="hidden" :name="name" x-model="json"></textarea>
</div>

@once
    @push('scripts')
        <script>
            function rolesBuilder({
                initial = [],
                maxItems = 10,
                name = 'roles'
            }) {
                const uid = () => Math.random().toString(36).slice(2, 9);

                const emptyRole = () => ({
                    _id: uid(),
                    _collapsed: false,
                    title: '',
                    task: '',
                    points: null,
                    selection_start: '',
                    selection_end: '',
                    description: '',
                    shifts: [], // [{date_start, date_end, time_start, time_end}]
                    requirements: '',
                    benefits: '',
                });

                const normalize = (arr) => (Array.isArray(arr) && arr.length) ?
                    arr.map(r => ({
                        _id: uid(),
                        _collapsed: false,
                        title: r.title ?? '',
                        task: r.task ?? '',
                        points: r.points ?? null,
                        selection_start: r.selection_start ?? '',
                        selection_end: r.selection_end ?? '',
                        description: r.description ?? '',
                        shifts: Array.isArray(r.shifts) ? r.shifts.map(s => ({
                            _id: uid(),
                            date_start: s.date_start ?? '',
                            date_end: s.date_end ?? '',
                            time_start: s.time_start ?? '',
                            time_end: s.time_end ?? '',
                        })) : [],
                        requirements: r.requirements ?? '',
                        benefits: r.benefits ?? '',
                    })) : [emptyRole()];

                return {
                    name,
                    maxItems,
                    roles: normalize(initial),

                    get json() {
                        // убираем служебные поля перед отправкой
                        return JSON.stringify(this.roles.map(r => ({
                            title: r.title,
                            task: r.task,
                            points: r.points,
                            selection_start: r.selection_start,
                            selection_end: r.selection_end,
                            description: r.description,
                            shifts: r.shifts.map(s => ({
                                date_start: s.date_start,
                                date_end: s.date_end,
                                time_start: s.time_start,
                                time_end: s.time_end,
                            })),
                            requirements: r.requirements,
                            benefits: r.benefits,
                        })));
                    },

                    add() {
                        if (this.roles.length >= this.maxItems) return;
                        this.roles.push(emptyRole());
                    },

                    remove(i) {
                        this.roles.splice(i, 1);
                        if (this.roles.length === 0) this.roles.push(emptyRole());
                    },

                    addShift(ri) {
                        this.roles[ri].shifts.push({
                            _id: uid(),
                            date_start: '',
                            date_end: '',
                            time_start: '',
                            time_end: ''
                        });
                    },

                    removeShift(ri, si) {
                        this.roles[ri].shifts.splice(si, 1);
                    },
                }
            }
        </script>

        {{-- Мини‑шина событий для x-rich-text-area:
    Когда компонент инициализируется — он шлёт 'quill-init', а при изменении — 'quill-change'.
    Выше мы привязались к ним через target = role._id --}}
    @endpush
@endonce
