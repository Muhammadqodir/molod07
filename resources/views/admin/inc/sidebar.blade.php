<x-sidebar>
    {{-- простые пункты --}}
    <x-sidebar-link route="admin.profile" icon="user-circle" label="Личные данные" />
    <x-sidebar-link route="admin.feedback.index" icon="message-circle" label="Обратная связь" />

    {{-- Группа: Управление --}}
    <x-sidebar-group icon="user-cog" label="Управление" :open="request()->routeIs('admin.manage.*')">
        <x-sidebar-leaf route="admin.manage.administrators" label="Администраторы" />
        <x-sidebar-leaf route="admin.manage.youth" label="Пользователи" />
        <x-sidebar-leaf route="admin.manage.partners" label="Партнеры" />
    </x-sidebar-group>

    {{-- Группа: Мероприятия --}}
    <x-sidebar-group icon="calendar-check" label="Мероприятия" :open="request()->routeIs('admin.events.*')">
        <x-sidebar-leaf route="admin.events.index" label="Активные" />
        <x-sidebar-leaf route="admin.events.requests" label="Заявки" />
        <x-sidebar-leaf route="admin.events.archive" label="Архив" />
    </x-sidebar-group>

    {{-- Группа: Образование --}}
    <x-sidebar-group icon="book-marked" label="Образование" :open="request()->routeIs('admin.education.*')">
        <x-sidebar-leaf route="admin.education.index" label="Активные" />
        <x-sidebar-leaf route="admin.education.requests" label="Заявки" />
        <x-sidebar-leaf route="admin.education.archive" label="Архив" />
    </x-sidebar-group>

    {{-- Группа: Книжная полка --}}
    <x-sidebar-group icon="book-open" label="Книжная полка" :open="request()->routeIs('admin.books.*')">
        <x-sidebar-leaf route="admin.books.index" label="Активные" />
        <x-sidebar-leaf route="admin.books.requests" label="Заявки" />
        <x-sidebar-leaf route="admin.books.archive" label="Архив" />
    </x-sidebar-group>

    {{-- Группа: Новости --}}
    <x-sidebar-group icon="newspaper" label="Новости" :open="request()->routeIs('admin.news.*')">
        <x-sidebar-leaf route="admin.news.index" label="Активные" />
        <x-sidebar-leaf route="admin.news.requests" label="Заявки" />
        <x-sidebar-leaf route="admin.news.archive" label="Архив" />
    </x-sidebar-group>

    {{-- Группа: Комментарии --}}
    {{-- <x-sidebar-group icon="message-circle" label="Комментарии" :open="request()->routeIs('admin.comments.*')">
        <x-sidebar-leaf route="admin.comments.index" label="Одобренные" />
        <x-sidebar-leaf route="admin.comments.requests" label="На модерации" />
        <x-sidebar-leaf route="admin.comments.rejected" label="Отклоненные" />
    </x-sidebar-group> --}}
    <x-sidebar-link icon="message-circle" route="admin.comments.index" label="Комментарии" />

    {{-- Группа: Гранты --}}
    <x-sidebar-group icon="file-badge" label="Гранты" :open="request()->routeIs('admin.grants.*')">
        <x-sidebar-leaf route="admin.grants.index" label="Активные" />
        <x-sidebar-leaf route="admin.grants.requests" label="Заявки" />
        <x-sidebar-leaf route="admin.grants.archive" label="Архив" />
    </x-sidebar-group>

    {{-- Группа: Возможности --}}
    <x-sidebar-group icon="sparkles" label="Возможности" :open="request()->routeIs('admin.ministries.*') || request()->routeIs('admin.opportunities.*')">
        <x-sidebar-leaf route="admin.ministries.index" label="Министерства" />
        <x-sidebar-leaf route="admin.opportunities.index" label="Программы" />
    </x-sidebar-group>

    {{-- Группа: Подкасты --}}
    <x-sidebar-group icon="podcast" label="Подкасты" :open="request()->routeIs('admin.podcasts.*')">
        <x-sidebar-leaf route="admin.podcasts.index" label="Активные" />
        <x-sidebar-leaf route="admin.podcasts.requests" label="Заявки" />
        <x-sidebar-leaf route="admin.podcasts.archive" label="Архив" />
    </x-sidebar-group>

    {{-- Группа: Вакансии --}}
    <x-sidebar-group icon="briefcase-business" label="Вакансии" :open="request()->routeIs('admin.vacancies.*')">
        <x-sidebar-leaf route="admin.vacancies.index" label="Активные" />
        <x-sidebar-leaf route="admin.vacancies.requests" label="Заявки" />
        <x-sidebar-leaf route="admin.vacancies.archive" label="Архив" />
        <x-sidebar-leaf route="admin.vacancies.responses" label="Отклики" />
    </x-sidebar-group>

    <hr class="my-2">
    <x-sidebar-link icon="file-text" label="Документы" />
    <x-sidebar-link icon="settings" label="Настройки" />
    {{-- <x-sidebar-link icon="bell" label="Уведомления" /> --}}
    <x-sidebar-link icon="ban" label="Чёрный список" />

</x-sidebar>
