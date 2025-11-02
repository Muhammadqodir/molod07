<section class="py-16 bg-[#F7F9FB]" x-data="{ selectedTab: 'Все' }" id="events-section"
    @tab-changed.window="selectedTab = $event.detail">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-semibold text-gray-800">Популярные мероприятия</h2>
            <a href="{{ route('events.list') }}">
                <x-button variant="text">
                    Смотреть все
                </x-button>
            </a>
        </div>

        @php
            $tabs = [];

            foreach (config('events.categories') as $category) {
                $tabs[$category['label']] = $category['icon'];
            }
            $tabs = ['Все' => 'grid', ...$tabs];
        @endphp

        <x-radio-tabs :tabs="$tabs" active="Все" />

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($events as $event)
                <template x-if="selectedTab === '{{ $event->category }}' || selectedTab === 'Все'">
                    <x-event-card image="{{ asset($event->cover) }}" :tags="[$event->category, $event->type]"
                        points="{{ $event->getPoints() }}" title="{{ $event->title }}"
                        location="{{ $event->getAddress() }}" date="{{ $event->created_at->format('d.m.Y') }}"
                        link="{{ route('event', $event->id) }}" />
                </template>
            @endforeach
            <template
                x-if="@js($events).filter(e => selectedTab === e.category || selectedTab === 'Все').length === 0">
                <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                    <x-empty class="w-full" title="В этой категории пока нет мероприятий." />
                </div>
            </template>
        </div>
    </div>
</section>
