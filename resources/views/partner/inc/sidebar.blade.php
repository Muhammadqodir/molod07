<x-sidebar>
    <x-sidebar-link route="partner.profile" icon="user-circle" label="Личные данные" />
    {{-- <x-sidebar-link icon="ticket" label="Купоны" /> --}}
    <hr class="my-2">

    {{-- Группа: Мероприятия --}}
    <x-sidebar-group icon="calendar" label="Мероприятия" :open="request()->routeIs('partner.events.*')">
        <x-sidebar-leaf route="partner.events.index" label="Мероприятия" />
        <x-sidebar-leaf route="partner.events.participants" label="Участники" />
    </x-sidebar-group>
    <x-sidebar-link icon="newspaper" label="Новости" route="partner.news.index" />

    {{-- Группа: Вакансии --}}
    <x-sidebar-group icon="briefcase-business" label="Вакансии" :open="request()->routeIs('partner.vacancies.*')">
        <x-sidebar-leaf route="partner.vacancies.index" label="Вакансии" />
        <x-sidebar-leaf route="partner.vacancies.responses" label="Отклики" />
    </x-sidebar-group>

    <hr class="my-2">
    <x-sidebar-link icon="settings" label="Настройки" />
    {{-- <x-sidebar-link icon="bell" label="Уведомления" /> --}}
    <x-sidebar-link icon="help-circle" label="Поддержка" />
</x-sidebar>
