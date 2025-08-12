<x-sidebar>
    {{-- простые пункты --}}
    <x-sidebar-link route="admin.profile" icon="user-circle" label="Личные данные" />
    <x-sidebar-link route="admin.support" icon="headphones" label="Поддержка" />

    {{-- Группа: Управление --}}
    <x-sidebar-group icon="user-cog" label="Управление" :open="request()->routeIs('admin.manage.*')">
        <x-sidebar-leaf route="admin.manage.administrators" label="Администраторы" />
        <x-sidebar-leaf route="admin.manage.youth" label="Пользователи" />
        <x-sidebar-leaf label="Партнеры" />
    </x-sidebar-group>


    {{-- Группа: Мероприятия --}}
    <x-sidebar-group icon="calendar-check" label="Мероприятия" :open="request()->routeIs('admin.events.*')">
        <x-sidebar-leaf route="admin.events.index" label="Активные" />
        <x-sidebar-leaf route="admin.events.requests" label="Заявки" />
        <x-sidebar-leaf route="admin.events.archive" label="Архив" />

    </x-sidebar-group>

    {{-- Группа: Лента --}}
    <x-sidebar-group icon="newspaper" label="Лента">
        <x-sidebar-leaf label="Мероприятия" />
        <x-sidebar-leaf label="Новости" />
        <x-sidebar-leaf label="Гранты" />
        <x-sidebar-leaf label="Курсы" />
        <x-sidebar-leaf label="Тесты" />
    </x-sidebar-group>

    {{-- Группа: Сервис --}}
    <x-sidebar-group icon="shapes" label="Сервис">
        <x-sidebar-leaf label="Купоны" />
        <x-sidebar-leaf label="Баллы" />
    </x-sidebar-group>

    {{-- Группа: Трудоустройство --}}
    <x-sidebar-group icon="briefcase" label="Трудоустройство">
        <x-sidebar-leaf label="Вакансии" />
        <x-sidebar-leaf label="Отклики" />
    </x-sidebar-group>

    <hr class="my-2">
    <x-sidebar-link icon="file-text" label="Документы" />
    <x-sidebar-link icon="settings" label="Настройки" />
    <x-sidebar-link icon="bell" label="Уведомления" />
    <x-sidebar-link icon="ban" label="Чёрный список" />

</x-sidebar>
