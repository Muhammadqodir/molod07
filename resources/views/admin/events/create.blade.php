@extends('layouts.sidebar-layout')

@section('title', 'Добавить мероприятие')

@section('content')
    <div class="flex items-center justify-between mb-0">
        <h1 class="text-3xl">Добавить мероприятие</h1>
    </div>
    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Категория --}}
        <section class="space-y-3">

            <div class="text-lg font-medium">Организатор мероприятия</div>

            <x-input label="ID партнера" name="partner_id" placeholder="ID партнера" value="{{ old('partner_id') }}" />
            <small id="partner_name">Введите ID партнера</small>

            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const partnerIdInput = document.querySelector('[name="partner_id"]');
                        const partnerNameElem = document.getElementById('partner_name');
                        let timeout = null;

                        partnerIdInput.addEventListener('input', function() {
                            clearTimeout(timeout);
                            const id = partnerIdInput.value.trim();
                            if (!id) {
                                partnerNameElem.textContent = 'Введите ID партнера';
                                return;
                            }
                            timeout = setTimeout(() => {
                                fetch(`/admin/get-partner/${id}`)
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.error || !data.name) {
                                            partnerNameElem.textContent = 'Партнер не найден';
                                            return;
                                        }
                                        partnerNameElem.textContent = data.name;
                                    })
                                    .catch(() => {
                                        partnerNameElem.textContent = 'Ошибка поиска партнера';
                                    });
                            }, 500);
                        });
                    });
                </script>
            @endpush

            <hr>

            <div class="text-lg font-medium">Направление мероприятия</div>

            @php
                // можно передавать из контроллера
                $categories = [
                    ['icon' => 'atom', 'label' => 'Наука', 'value' => 'science'],
                    ['icon' => 'leaf', 'label' => 'Экология', 'value' => 'ecology'],
                    ['icon' => 'dumbbell', 'label' => 'Спорт', 'value' => 'sport'],
                    ['icon' => 'cpu', 'label' => 'Технологии', 'value' => 'tech'],
                    ['icon' => 'handshake', 'label' => 'Патриотизм', 'value' => 'patriot'],
                    ['icon' => 'sparkles', 'label' => 'Креатив', 'value' => 'creative'],
                    ['icon' => 'palette', 'label' => 'Творчество', 'value' => 'art'],
                    ['icon' => 'briefcase', 'label' => 'Бизнес', 'value' => 'business'],
                    ['icon' => 'badge-russian-ruble', 'label' => 'Трудоустройство', 'value' => 'employment'],
                    ['icon' => 'heart-handshake', 'label' => 'Добровольчество', 'value' => 'volunteering'],
                    ['icon' => 'ellipsis', 'label' => 'Другое', 'value' => 'other'],
                ];

                $types = [
                    ['label' => 'Конкурс', 'value' => 'contest'],
                    ['label' => 'Форум', 'value' => 'forum'],
                    ['label' => 'Встреча', 'value' => 'meeting'],
                    ['label' => 'Семинар', 'value' => 'seminar'],
                    ['label' => 'Лекция', 'value' => 'lecture'],
                    ['label' => 'Конференция', 'value' => 'conference'],
                    ['label' => 'Выставка', 'value' => 'exhibition'],
                    ['label' => 'Мастер‑класс', 'value' => 'masterclass'],
                    ['label' => 'Акция', 'value' => 'action'],
                    ['label' => 'Флешмоб', 'value' => 'flashmob'],
                    ['label' => 'Тренинг', 'value' => 'training'],
                    ['label' => 'Презентация', 'value' => 'presentation'],
                    ['label' => 'Круглый стол', 'value' => 'roundtable'],
                ];
            @endphp

            <div class="space-y-2">
                <x-multi-choice name="category" :options="$categories" :value="old('category', '')" :multiple="false" title="Категория"
                    hint="Выберите один вариант" />
                @error('category')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <x-multi-choice name="type" :options="$types" :value="old('type', '')" :multiple="false" title="Тип"
                    hint="Выберите один вариант" />
                @error('type')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </section>

        <hr>

        {{-- Обложка --}}
        <section class="space-y-3">
            <div class="text-lg font-medium">Обложка</div>
            <x-image-selector name="cover" label="" :value="old('cover_url')" :max-mb="2" :accept="['image/png', 'image/jpeg']" />
            @error('cover')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </section>

        <hr>

        {{-- Описание --}}
        <section class="space-y-4">
            <div class="text-lg font-medium">Описание мероприятия</div>

            <x-input label="Название" name="title" placeholder="Укажите название" maxlength="255"
                value="{{ old('title') }}" />

            <x-input label="Короткое описание" name="short_description" placeholder="Кратко опишите" maxlength="500"
                value="{{ old('short_description') }}" />

            <x-rich-text-area label="Описание" name="description" placeholder="Полное описание"
                value="{{ old('description') }}" />
        </section>

        <hr>

        {{-- Роли --}}
        <section class="space-y-3">
            <x-roles-builder label="Роли" name="roles" {{-- одно поле textarea с JSON --}} :value="old('roles', '[]')" {{-- для редактирования/валидации --}}
                :max-items="10" />
            @error('roles')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </section>

        <hr>

        {{-- Место и даты --}}
        <section class="space-y-3">
            <div class="text-lg font-medium">Место и дата проведения</div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Населённый пункт" name="settlement" placeholder="Укажите населённый пункт"
                    value="{{ old('settlement') }}" />
                <x-input label="Адрес" name="address" placeholder="Укажите улицу, дом" value="{{ old('address') }}" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input type="date" label="Начало" name="start" value="{{ old('start') }}" />
                <x-input type="date" label="Конец" name="end" value="{{ old('end') }}" />
            </div>
        </section>

        <hr>

        {{-- Руководитель --}}
        <section class="space-y-3">
            <div class="text-lg font-medium">Руководитель</div>

            <x-input label="ID (если пользователь зарегистрирован, укажите ID)" name="supervisor_id"
                placeholder="ID пользователя" value="{{ old('supervisor_id') }}" />

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-input label="Имя" name="supervisor_name" placeholder="Укажите имя"
                    value="{{ old('supervisor_name') }}" />
                <x-input label="Фамилия" name="supervisor_l_name" placeholder="Укажите фамилию"
                    value="{{ old('supervisor_l_name') }}" />
                <x-input type="tel" label="Номер телефона" name="supervisor_phone" placeholder="+7(999)999-99-99"
                    value="{{ old('supervisor_phone') }}" />
            </div>

            <x-input type="email" label="E‑mail" name="supervisor_email" placeholder="E‑mail"
                value="{{ old('supervisor_email') }}" />
        </section>

        <hr>

        {{-- Медиафайлы --}}
        <section class="space-y-3">
            <x-files-selector label="Добавление медиафайлов" docs-name="docs" images-name="images" videos-name="videos"
                :max-docs="5" :max-images="6" :max-videos="1" :max-doc-mb="5" :max-img-mb="2"
                :max-vid-mb="100" :doc-accept="['application/pdf']" :img-accept="['image/png', 'image/jpeg']" :vid-accept="['video/mp4', 'video/quicktime']" />
            @error('docs')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('images')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('videos')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </section>

        <hr>

        {{-- Соцсети --}}
        <section class="space-y-3">
            <div class="text-lg font-medium">Ссылки на соцсети мероприятия</div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-input label="Сайт" name="web" placeholder="https://" value="{{ old('web') }}"
                    :help="'не обязательно к заполнению'" />
                <x-input label="Telegram" name="telegram" placeholder="@username" value="{{ old('telegram') }}"
                    :help="'не обязательно к заполнению'" />
                <x-input label="Vkontakte" name="vk" placeholder="ссылка или @" value="{{ old('vk') }}"
                    :help="'не обязательно к заполнению'" />
            </div>
        </section>

        {{-- Кнопка --}}
        <div class="pt-2 flex justify-start">
            <x-button type="submit">Отправить на модерацию</x-button>
        </div>

        {{-- Примечание --}}
        <p class="text-xs text-gray-500">
            Нажимая «Отправить», вы соглашаетесь на обработку персональных данных и с правилами пользования платформой.
        </p>
    </form>


@endsection


@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const idInput = document.querySelector('[name="supervisor_id"]');
                const nameInput = document.querySelector('[name="supervisor_name"]');
                const lnameInput = document.querySelector('[name="supervisor_l_name"]');
                const phoneInput = document.querySelector('[name="supervisor_phone"]');
                const emailInput = document.querySelector('[name="supervisor_email"]');

                let timeout = null;

                idInput.addEventListener('input', function() {
                    clearTimeout(timeout);

                    const id = idInput.value.trim();
                    if (!id) return;

                    timeout = setTimeout(() => {
                        fetch(`/admin/get-user/${id}`)
                            .then(res => res.json())
                            .then(data => {
                                if (data.error) {
                                    console.log(data.error);
                                    return;
                                }
                                nameInput.value = data.name || '';
                                lnameInput.value = data.l_name || '';
                                phoneInput.value = data.phone || '';
                                emailInput.value = data.email || '';
                            })
                            .catch(err => console.error(err));
                    }, 500); // задержка, чтобы не спамить сервер
                });
            });
        </script>
    @endpush
@endonce
