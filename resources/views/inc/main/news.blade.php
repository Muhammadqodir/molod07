<section class="py-16 bg-[#F7F9FB]" x-data="{ selectedTab: 'Все' }" id="news-section"
    @tab-changed.window="selectedTab = $event.detail">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-semibold text-gray-800">Последние новости</h2>
            <a href="{{ route('news.list') }}">
                <x-button variant="text">
                    Смотреть все
                </x-button>
            </a>
        </div>

        @php
            $tabs = [];

            foreach (config('news.categories', []) as $category) {
                $tabs[$category['label']] = $category['icon'];
            }
            $tabs = ['Все' => 'grid', ...$tabs];
        @endphp

        <x-radio-tabs :tabs="$tabs" active="Все" />

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($news as $newsItem)
                <template x-if="selectedTab === '{{ $newsItem->category }}' || selectedTab === 'Все'">
                    <x-news-card
                        image="{{ asset($newsItem->cover) }}"
                        category="{{ $newsItem->category }}"
                        title="{{ $newsItem->title }}"
                        description="{{ $newsItem->short_description }}"
                        date="{{ $newsItem->publication_date ? \Carbon\Carbon::parse($newsItem->publication_date)->format('d.m.Y') : $newsItem->created_at->format('d.m.Y') }}"
                        link="{{ route('news', $newsItem->id) }}" />
                </template>
            @endforeach
            <template
                x-if="@js($news).filter(n => selectedTab === n.category || selectedTab === 'Все').length === 0">
                <div class="col-span-1 sm:col-span-2 lg:col-span-3">
                    <x-empty class="w-full" title="В этой категории пока нет новостей." />
                </div>
            </template>
        </div>
    </div>
</section>
