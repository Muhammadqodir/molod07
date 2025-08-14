@props([
    'steps' => 4,
    'submit' => 'Завершить'
])

<div x-data="{ step: 1 }" class="w-full space-y-8">
    <!-- Step Indicator -->
    <div class="flex items-center justify-between w-full mx-auto">
      <template x-for="i in {{ $steps }}" :key="i">
        <div class="flex items-center" :class="i < {{ $steps }} ? 'w-full' : 'flex-none'">
          <div
            class="w-8 h-8 flex items-center justify-center rounded-full border text-sm font-medium"
            :class="{
              'bg-primary text-white border-bg-primary': step >= i,
              'bg-white text-gray-500 border-gray-300': step < i
            }"
            {{-- x-text="step <= i ? i : '✓'" --}}
          >

        <template x-if="step <= i">
            <span x-text="i"></span>
        </template>
        <template x-if="step > i">
            <x-lucide-check class="w-5 h-5" />
        </template>

        </div>
          <template x-if="i < {{ $steps }}">
            <div class="flex-1 h-px border-t-2 border-dotted mx-2"></div>
          </template>
        </div>
      </template>
    </div>

    <!-- Slot for steps content -->
    <div>
        {{ $slot }}
    </div>

    <!-- Navigation Buttons -->
    <div 
      class="flex space-x-4"
      :class="{
        'justify-end': (step === 1 || step === {{ $steps }}),
        'justify-between': (step > 1 && step <= {{ $steps }})
      }"
    >
      <button
        type="button"
        class="text-gray-600 text-sm px-6 py-2 rounded-xl hover:bg-primary/10"
        x-show="step > 1"
        @click="step--"
      >
        Назад
      </button>

      <button
        type="button"
        class="bg-primary text-sm text-white px-6 py-2 rounded-xl"
        x-show="step < {{ $steps }}"
        @click="step++"
      >
        Далее
      </button>

      <button
        type="submit"
        class="bg-primary text-sm text-white px-6 py-2 rounded-xl"
        x-show="step === {{ $steps }}"
      >
        {{ $submit }}
      </button>
    </div>
</div>
