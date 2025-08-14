@props([
    'label' => '',
    'help' => '',
    'name' => null,
    'id' => null,
    'disabled' => false,
    'options' => [],
    'placeholder' => 'Выберите значение',
    'value' => '',
])

<div class="space-y-1 w-full mb-3">
    @if ($label)
        <label class="block text-sm font-medium text-gray-800">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        <select
            name="{{ $name }}"
            id="{{ $id ?? $name }}"
            {{ $disabled ? 'disabled' : '' }}
            class="block text-sm w-full rounded-xl border border-gray-200 bg-[#f7f9fc] px-2 py-2 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#0056D2] disabled:bg-gray-100 disabled:cursor-not-allowed appearance-none pr-10"
        >
            <option disabled selected hidden>{{ $placeholder }}</option>
            @foreach ($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>

        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
            <x-lucide-chevron-down class="w-4 h-4" />
        </div>
    </div>

    @if ($help)
        <small class="text-[12px] text-gray-500">
            {{ $help }}
        </small>
    @endif
</div>
