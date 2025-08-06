<aside class="w-full max-w-xs space-y-6">
    <!-- Profile section -->
    <div class="flex bg-white p-4 rounded-2xl shadow-sm flex-col items-left text-center space-y-2">
        <div class="w-16 h-16 bg-[#e5efff] text-gray-700 font-bold text-2xl flex items-center justify-center rounded-lg">
            {{ strtoupper(substr(Auth::user()->getFullName() ?? 'm', 0, 1)) }}
        </div>
        <div class="text-base font-semibold flex items-center gap-1">
            {{ Auth::user()->getFullName() ?? 'Имя и Фамилия' }}
            <x-lucide-badge-check class="text-blue-500 w-4 h-4" />
        </div>
        <div class="text-sm text-gray-500">
            <div class="flex justify-between items-center">
                ID
                <div class="flex ml-2">
                    {{ sprintf('%06d', Auth::user()->id) }}
                    <button type="button" class="ml-2 text-gray-400 hover:text-blue-500 focus:outline-none"
                        onclick="navigator.clipboard.writeText('{{ sprintf('%06d', Auth::user()->id) }}')" title="Скопировать ID">
                        <x-lucide-copy class="w-4 h-4" />
                    </button>
                </div>

            </div>
            <div class="flex justify-between">Баллы <span class="ml-2">100</span></div>
        </div>
    </div>

    <!-- Menu -->
    <ul class="space-y-1 bg-white p-4 rounded-2xl shadow-sm text-sm">
        <x-sidebar-link icon="user-circle" label="Личные данные" active />
        <x-sidebar-link icon="database" label="Баллы" />
        <x-sidebar-link icon="ticket" label="Купоны" />
        <x-sidebar-link icon="calendar" label="Мероприятия" />
        <x-sidebar-link icon="newspaper" label="Новости" />
        <x-sidebar-link icon="graduation-cap" label="Образование" />
        <x-sidebar-link icon="search" label="Вакансии" />
        <hr class="my-2">
        <x-sidebar-link icon="settings" label="Настройки" />
        <x-sidebar-link icon="bell" label="Уведомления" />
        <x-sidebar-link icon="help-circle" label="Поддержка" />
        <x-sidebar-link icon="log-out" label="Выйти" :href="route('logout')" />
    </ul>
</aside>
