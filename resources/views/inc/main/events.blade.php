<section class="py-16 bg-[#F7F9FB]" x-data="{ selectedTab: 'Экология' }" id="events-section" 
    @tab-changed.window="selectedTab = $event.detail">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-semibold text-gray-800">Популярные мероприятия</h2>
            <x-button variant="text">
                Смотреть все
            </x-button>
        </div>

        <x-radio-tabs :tabs="[
        'Все' => 'lucide-globe',
        'Экология' => 'lucide-shield-check',
        'Спорт' => 'lucide-dumbbell',
        'Технологии' => 'lucide-layout-dashboard',
        'Патриотизм' => 'lucide-flag',
        'Креатив' => 'lucide-lightbulb',
        'Наука' => 'lucide-atom'
    ]" active="Экология"/>


        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-if="selectedTab === 'Экология' || selectedTab === 'Все'">
                <x-event-card image="/images/hero-scroll/2.png" :tags="['Онлайн-акция', 'Трудоустройство']" points="300"
                    title="Отбор на участие в обучающей стажировке для Добро. Центро..." location="Нальчик"
                    date="01.01.23 — 01.01.23" />
            </template>
            <template x-if="selectedTab === 'Экология' || selectedTab === 'Все'">
                <x-event-card image="/images/hero-scroll/1.png" :tags="['Онлайн-акция', 'Трудоустройство']" points="300"
                    title="Отбор на участие в обучающей стажировке для Добро. Центро..." location="Нальчик"
                    date="01.01.23 — 01.01.23" />
            </template>
            <template x-if="selectedTab === 'Креатив' || selectedTab === 'Все'">
                <x-event-card image="/images/hero-scroll/1.png" :tags="['Онлайн-акция', 'Трудоустройство']" points="300"
                    title="Отбор на участие в обучающей стажировке для Добро. Центро..." location="Нальчик"
                    date="01.01.23 — 01.01.23" />
            </template>
        </div>

        <div class="text-center mt-10">
            <button
                class="px-6 py-2 rounded-xl border border-primary text-primary hover:bg-primary hover:text-white transition">
                Показать ещё
            </button>
        </div>
    </div>
</section>