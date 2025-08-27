@props(['tabs' => [], 'active' => 'Все'])

<div
    x-data="{
        active: @js($active),
        scrollLeft() {
            this.$refs.scrollContainer.scrollBy({ left: -150, behavior: 'smooth' });
        },
        scrollRight() {
            this.$refs.scrollContainer.scrollBy({ left: 150, behavior: 'smooth' });
        },
        init() {
            this.$watch('active', value => {
                console.log('Active tab changed:', value);
                this.$dispatch('tab-changed', value);
            });
        }
    }"
    class="relative"
>
    <div x-ref="scrollContainer" class="flex ml-12 mr-12 gap-2 overflow-x-auto no-scrollbar overflow-x-scroll slider-scroll-hide">
        @foreach ($tabs as $tab => $icon)
            <button
                @click="active = @js($tab)"
                class="flex items-center gap-2 whitespace-nowrap px-4 py-2 rounded-xl transition-all duration-200"
                :class="active === @js($tab) ? 'bg-primary/10 text-primary' : 'bg-white text-gray-700'"
            >
                <x-dynamic-component
                    :component="'lucide-' . $icon"
                    class="'w-5 h-5 ' + (active === {{$tab}} ? 'text-primary' : 'text-gray-500')"
                />
                <span>{{ $tab }}</span>
            </button>
        @endforeach
    </div>

    <button @click="scrollLeft" class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full p-2">
        <x-lucide-chevron-left class="w-5 h-5 text-gray-600" />
    </button>

    <button @click="scrollRight" class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white rounded-full p-2">
        <x-lucide-chevron-right class="w-5 h-5 text-gray-600" />
    </button>
</div>
