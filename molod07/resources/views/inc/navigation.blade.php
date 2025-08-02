<div x-data="{ open: false }" class="fixed top-0 left-0 w-full bg-white border-b border-gray-100">

    {{-- Навигация (десктоп) --}}
    <div class="max-w-screen-xl mx-auto flex items-center justify-between gap-4 px-6 py-4 hidden md:flex">

        {{-- Логотип --}}
        <x-logo />

        {{-- Левая часть --}}
        <div class="flex items-center gap-4">
            <x-button variant="outline">Стать партнёром</x-button>
            <x-icon-button icon="vk" />
            <x-icon-button icon="telega" />
        </div>

        {{-- Поиск --}}
        <div class="relative flex-1 min-w-[150px]">
            <x-search-input />
        </div>

        {{-- Правая часть --}}
        <div class="flex items-center gap-3">
            {{-- Меню с выпадающим сайдбаром --}}
            <div x-data="{ open: false }" class="relative">
                {{-- Кнопка --}}
                <x-button @click="open = !open">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    Меню
                </x-button>

                {{-- Выпадающий блок меню --}}
                <div x-show="open" @click.away="open = false" x-transition
                    class="absolute right-0 mt-2 w-64 bg-[#fff] rounded-2xl shadow-xl p-4 z-50">
                    <ul class="space-y-0 text-gray-700">
                        <li
                            class="flex items-center gap-3 p-2 rounded-xl cursor-pointer hover:bg-secondary hover:text-primary transition">
                            <x-lucide-home class="w-5 h-5 text-inherit" /> Главная
                        </li>
                        <li
                            class="flex items-center gap-3 p-2 rounded-xl cursor-pointer hover:bg-secondary hover:text-primary transition">
                            <x-lucide-heart class="w-5 h-5 text-inherit" /> Лента активности
                        </li>
                        <li
                            class="flex items-center gap-3 p-2 rounded-xl cursor-pointer hover:bg-secondary hover:text-primary transition">
                            <x-lucide-users class="w-5 h-5 text-inherit" /> Организаторы
                        </li>
                        <li
                            class="flex items-center gap-3 p-2 rounded-xl cursor-pointer hover:bg-secondary hover:text-primary transition">
                            <x-lucide-graduation-cap class="w-5 h-5 text-inherit" /> Образование
                        </li>
                        <li
                            class="flex items-center gap-3 p-2 rounded-xl cursor-pointer hover:bg-secondary hover:text-primary transition">
                            <x-lucide-briefcase class="w-5 h-5 text-inherit" /> Вакансии
                        </li>
                        <li
                            class="flex items-center gap-3 p-2 rounded-xl cursor-pointer hover:bg-secondary hover:text-primary transition">
                            <x-lucide-headphones class="w-5 h-5 text-inherit" /> Подкасты
                        </li>
                        <li
                            class="flex items-center gap-3 p-2 rounded-xl cursor-pointer hover:bg-secondary hover:text-primary transition">
                            <x-lucide-file-text class="w-5 h-5 text-inherit" /> Документы
                        </li>
                        <li
                            class="flex items-center gap-3 p-2 rounded-xl cursor-pointer hover:bg-secondary hover:text-primary transition">
                            <x-lucide-smile class="w-5 h-5 text-inherit" /> О нас
                        </li>
                        <li
                            class="flex items-center gap-3 p-2 rounded-xl cursor-pointer hover:bg-secondary hover:text-primary transition">
                            <x-lucide-phone class="w-5 h-5 text-inherit" /> Контакты
                        </li>
                    </ul>
                </div>
            </div>
            <x-nav-icon>
                <x-lucide-heart class="h-5 w-5" />
            </x-nav-icon>
            <x-nav-icon>
                <x-lucide-user-plus class="h-5 w-5" />
            </x-nav-icon>
        </div>

    </div>

    {{-- Мобильная панель (верх) --}}
    <div class="flex items-center justify-between px-4 py-3 md:hidden">
        <x-logo />

        <div class="flex items-center gap-4">
            <x-nav-icon>
                <x-lucide-heart class="h-5 w-5" />
            </x-nav-icon>
            <x-nav-icon>
                <x-lucide-bell class="h-5 w-5" />
            </x-nav-icon>
            <x-nav-icon>
                <x-lucide-user-plus class="h-5 w-5" />
            </x-nav-icon>
            {{-- Открытие меню --}}
            <x-button @click="open = true" class="bg-primary text-white p-2 rounded-xl">
                <x-lucide-menu class="w-5 h-5" />
            </x-button>
        </div>
    </div>

    {{-- Мобильное меню (выпадающее полноэкранное) --}}
    <div x-show="open" x-transition
        class="fixed inset-0 bg-white z-50 p-6 flex flex-col gap-4 overflow-y-auto md:hidden">
        {{-- Верхняя панель с кнопкой закрытия --}}
        <div class="flex items-center justify-between">
            <x-logo />
            <x-button @click="open = false" class="bg-primary text-white p-2 rounded-xl">
                <x-lucide-x class="w-5 h-5" />
            </x-button>
        </div>

        {{-- Поиск --}}
        <div class="relative mt-4">
            <x-search-input class="w-full" />
        </div>

        {{-- Пункты меню --}}
        <ul class="mt-4 space-y-3 text-gray-700">
            <li
                class="flex items-center gap-3 p-2 rounded-xl cursor-pointer hover:bg-secondary hover:text-primary transition">
                <x-lucide-home class="w-5 h-5 text-inherit" /> Главная
            </li>
            <li
                class="flex items-center gap-3 p-2 rounded-xl cursor-pointer hover:bg-secondary hover:text-primary transition">
                <x-lucide-heart class="w-5 h-5 text-inherit" /> Лента активности
            </li>
            <li
                class="flex items-center gap-3 p-2 rounded-xl cursor-pointer hover:bg-secondary hover:text-primary transition">
                <x-lucide-users class="w-5 h-5 text-inherit" /> Организаторы
            </li>
            <li
                class="flex items-center gap-3 p-2 rounded-xl cursor-pointer hover:bg-secondary hover:text-primary transition">
                <x-lucide-graduation-cap class="w-5 h-5 text-inherit" /> Образование
            </li>
            <li
                class="flex items-center gap-3 p-2 rounded-xl cursor-pointer hover:bg-secondary hover:text-primary transition">
                <x-lucide-briefcase class="w-5 h-5 text-inherit" /> Вакансии
            </li>
            <li
                class="flex items-center gap-3 p-2 rounded-xl cursor-pointer hover:bg-secondary hover:text-primary transition">
                <x-lucide-headphones class="w-5 h-5 text-inherit" /> Подкасты
            </li>
            <li
                class="flex items-center gap-3 p-2 rounded-xl cursor-pointer hover:bg-secondary hover:text-primary transition">
                <x-lucide-file-text class="w-5 h-5 text-inherit" /> Документы
            </li>
            <li
                class="flex items-center gap-3 p-2 rounded-xl cursor-pointer hover:bg-secondary hover:text-primary transition">
                <x-lucide-smile class="w-5 h-5 text-inherit" /> О нас
            </li>
            <li
                class="flex items-center gap-3 p-2 rounded-xl cursor-pointer hover:bg-secondary hover:text-primary transition">
                <x-lucide-phone class="w-5 h-5 text-inherit" /> Контакты
            </li>
        </ul>

        {{-- Соц. сети и кнопка --}}
        <div class="mt-6 flex items-center gap-4">
            <x-icon-button icon="vk" />
            <x-icon-button icon="telega" />
        </div>

        <div class="mt-auto">
            <x-button variant="outline" class="w-full justify-center">
                Стать партнёром
            </x-button>
        </div>
    </div>
</div>