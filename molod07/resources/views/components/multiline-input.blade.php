@props([
    'label' => '',
    'help' => '',
    'value' => '',
    'placeholder' => '',
    'maxlength' => null,
    'disabled' => false,
    'name' => null,
    'id' => null,
    'rows' => 4,
])

<div class="space-y-1 w-full mb-3">
    @if ($label)
        <label class="block text-sm font-medium text-gray-800">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        <textarea
            {{ $attributes }}
            @if ($name)
                name="{{ $name }}"
            @endif
            id="{{ $id ?? $name }}"
            @if (!is_null($maxlength))
                maxlength="{{ $maxlength }}"
            @endif
            placeholder="{{ $placeholder }}"
            rows="{{ $rows }}"
            {{ $disabled ? 'disabled' : '' }}
            class="block text-sm w-full rounded-xl border border-gray-200 bg-[#f7f9fc] px-3 py-2 text-gray-800 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0056D2] disabled:bg-gray-100 disabled:cursor-not-allowed"
        >@if($name) {{ old($name, $value) }} @endif</textarea>
    </div>

    @if ($help)
        <small class="text-[12px] text-gray-500">
            {{ $help }}
        </small>
    @endif
</div>
