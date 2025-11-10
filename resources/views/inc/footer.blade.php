<footer class="bg-white border-t border-gray-100 text-gray-700 text-sm">
    <div class="max-w-screen-xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-10">
        {{-- Левая колонка --}}
        <div class="space-y-4">
            <x-logo />
            <p class="text-base">Приложение Минмолодёжи КБР</p>
            <div class="flex gap-4">
                <a href="https://play.google.com/store/apps/details?id=ru.alfocus.molod07&hl=en" target="_blank">
                    <img src="/images/google-play.png" alt="Google Play" class="h-10">
                </a>
                <a href="https://apps.apple.com/uz/app/%D0%B1%D0%B8%D0%B7%D0%BD%D0%B5%D1%8107/id6752689949" target="_blank">
                    <img src="/images/app-store.png" alt="App Store" class="h-10">
                </a>
            </div>
        </div>

        {{-- Центральная колонка --}}
        <div>
            <h3 class="font-semibold mb-4">Разделы сайта</h3>
            <div class="grid grid-cols-2 gap-y-2">
                <a href="{{ route('main') }}" class="hover:text-primary">Главная</a>
                <a href="{{ route('vacancies.list') }}" class="hover:text-primary">Вакансии</a>
                <a href="{{ route('events.list') }}" class="hover:text-primary">Мероприятия</a>
                <a href="{{ route('documents') }}" class="hover:text-primary">Документы</a>
                <a href="{{ route('partners') }}" class="hover:text-primary">Организаторы</a>
                <a href="{{ route('about') }}" class="hover:text-primary">О нас</a>
                <a href="{{ route('courses.list') }}" class="hover:text-primary">Образование</a>
                <a href="{{ route('contacts') }}" class="hover:text-primary">Контакты</a>
                <a href="{{ route('privacy-policy') }}" class="hover:text-primary">Политика конфиденциальности</a>
                <a href="{{ route('news.list') }}" class="hover:text-primary">Новости</a>
            </div>
        </div>

        {{-- Правая колонка --}}
        <div class="space-y-4">
            <h3 class="font-semibold">Контакты</h3>
            <p>
                Пн–Пт: 10:00 – 20:00; Сб, Вс: 11:00 – 20:00<br>
                КБР, г. Нальчик, пр-т Кулиева, 12
            </p>
            <div class="flex flex-row gap-5">
                <div>
                    <p class="font-medium">Социальные сети</p>
                    <div class="flex gap-3">
                        <a href="https://vk.com/minmol07" target="_blank"> <x-icon-button icon="vk" /> </a>

                        <a href="https://t.me/minmolkbr" target="_blank"> <x-icon-button icon="telega" /> </a>
                    </div>
                </div>
                <div>
                    <p class="font-medium">Техподдержка</p>
                    <div class="flex gap-3">
                        <a href="mailto:mmckbr@mail.ru">
                            <x-icon-button icon="mail" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Нижняя линия --}}
    <div class="border-t border-gray-200 mt-6">
        <div
            class="max-w-screen-xl mx-auto px-6 py-4 flex flex-col md:flex-row justify-between text-gray-500 text-xs gap-2">
            <p>
                База данных о мероприятиях. Использование сайта означает согласие с
                <a href="#" class="text-primary hover:underline">Пользовательским соглашением</a>
                и
                <a href="{{ route('privacy-policy') }}" class="text-primary hover:underline">Политикой
                    конфиденциальности</a>.
            </p>
            <p class="text-right">Разработчик: <a href="https://alfocus.uz/">AL-FOCUS GROUP</a> </p>
        </div>
    </div>
</footer>
