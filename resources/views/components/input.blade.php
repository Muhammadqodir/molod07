@props([
    'label' => '',
    'help' => '',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'maxlength' => null,
    'disabled' => false,
    'name' => null,
    'id' => null,
])

@php
    $isPassword = $type === 'password';
@endphp

<div
    x-data="{
        show: false,
        inputType: '{{ $isPassword ? 'password' : $type }}'
    }"
    class="space-y-1 w-full mb-3"
>
    @if ($label)
        <label class="block text-sm font-medium text-gray-800">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        <input
            {{ $attributes }}
            @if ($name)
                name="{{ $name }}"
                value="{{ old($name, $value) }}"
            @endif
            id="{{ $id ?? $name }}"
            x-bind:type="inputType === 'password' && show ? 'text' : inputType"
            x-mask="{{ $type === 'tel' ? '+7(999)999-99-99' : '' }}"
            @if (!is_null($maxlength))
                maxlength="{{ $maxlength }}"
            @endif
            placeholder="{{ $placeholder }}"
            {{ $disabled ? 'disabled' : '' }}
            class="block text-sm w-full rounded-xl border border-gray-200 bg-[#f7f9fc] px-3 py-2 text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0056D2] disabled:bg-gray-100 disabled:cursor-not-allowed"
        />

        @if ($isPassword)
            <button
                type="button"
                class="absolute inset-y-0 right-4 flex items-center text-gray-500"
                @click="show = !show"
            >
                <x-lucide-eye x-show="!show" class="w-5 h-5" />
                <x-lucide-eye-off x-show="show" class="w-5 h-5" />
            </button>
        @endif
    </div>

    @if ($help)
        <small class="text-[12px] text-gray-500">
            {{ $help }}
        </small>
    @endif
</div>
