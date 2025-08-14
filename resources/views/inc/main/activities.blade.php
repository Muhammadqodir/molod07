<section class="py-16 bg-white">
    <div class="max-w-screen-xl mx-auto px-6">
        <h2 class="text-3xl md:text-4xl font-semibold text-gray-800 mb-10">
            Лента активности
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Карточка 1: Новости --}}
            <div class="relative bg-[#F7F9FB] rounded-2xl p-6 overflow-hidden min-h-[220px]">
                <h3 class="text-xl font-semibold mb-2">Новости</h3>
                <p class="text-gray-700">Узнавайте об актуальных событиях вашего региона</p>
                <div class="absolute pb-3 pr-3 bottom-0 right-0 w-24 h-24 bg-[#FFEFA4] rounded-full flex items-center justify-center translate-x-1/4 translate-y-1/4">
                    <x-lucide-file-text class="w-9 h-9 text-gray-800" stroke-width="1" />
                </div>
            </div>

            {{-- Карточка 2: Мероприятия --}}
            <div class="relative bg-[#F7F9FB] rounded-2xl p-6 overflow-hidden min-h-[220px]">
                <h3 class="text-xl font-semibold mb-2">Мероприятия</h3>
                <p class="text-gray-700">Все интересные мероприятия для молодежи</p>
                <div class="absolute pb-3 pr-3 bottom-0 right-0 w-24 h-24 bg-[#BDE1FF] rounded-full flex items-center justify-center translate-x-1/4 translate-y-1/4">
                    <x-lucide-calendar-heart class="w-9 h-9 text-gray-800" stroke-width="1" />
                </div>
            </div>

            {{-- Карточка 3: Гранты --}}
            <div class="relative bg-[#F7F9FB] rounded-2xl p-6 overflow-hidden min-h-[220px]">
                <h3 class="text-xl font-semibold mb-2">Гранты</h3>
                <p class="text-gray-700">Узнайте как получить финансовую поддержку вашей идеи</p>
                <div class="absolute pb-3 pr-3 bottom-0 right-0 w-24 h-24 bg-[#DDFDC1] rounded-full flex items-center justify-center translate-x-1/4 translate-y-1/4">
                    <x-lucide-award class="w-9 h-9 text-gray-800" stroke-width="1" />
                </div>
            </div>

            {{-- Карточка 4: Подкасты --}}
            <div class="relative bg-[#F7F9FB] rounded-2xl p-6 overflow-hidden min-h-[220px]">
                <h3 class="text-xl font-semibold mb-2">Подкасты</h3>
                <p class="text-gray-700">Слушайте интересные подкасты на интересующую тематику</p>
                <div class="absolute pb-3 pr-3 bottom-0 right-0 w-24 h-24 bg-[#FFD4ED] rounded-full flex items-center justify-center translate-x-1/4 translate-y-1/4">
                    <x-lucide-mic class="w-9 h-9 text-gray-800" stroke-width="1" />
                </div>
            </div>
        </div>
    </div>
</section>