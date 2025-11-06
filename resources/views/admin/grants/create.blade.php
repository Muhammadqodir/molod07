@extends('layouts.sidebar-layout')

@section('title', 'Добавить грант')

@section('content')
    <div class="flex items-center justify-between mb-0">
        <h1 class="text-3xl">Добавить грант</h1>
    </div>
    <form action="{{ route('admin.grants.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Категория --}}
        <section class="space-y-3">

            <div class="text-lg font-medium">Организатор гранта</div>

            <div class="space-y-3 relative">
                <x-input label="Поиск партнера" name="partner_search" placeholder="Введите название партнера" autocomplete="off" />
                <input type="hidden" name="user_id" value="{{ old('user_id') }}" />
                <div id="partner_suggestions" class="hidden absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto z-50 mt-1"></div>
                <small id="partner_status" class="text-gray-600">Введите название партнера для поиска</small>
                <div id="selected_partner" class="hidden p-3 bg-blue-50 border border-blue-200 rounded-md">
                    <span class="text-sm text-blue-800">Выбранный партнер: </span>
                    <span id="selected_partner_name" class="font-medium"></span>
                    <button type="button" id="clear_partner" class="ml-2 text-red-600 hover:text-red-800 text-lg leading-none">×</button>
                </div>
            </div>

            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const partnerSearchInput = document.querySelector('[name="partner_search"]');
                        const partnerIdInput = document.querySelector('[name="user_id"]');
                        const suggestionsContainer = document.getElementById('partner_suggestions');
                        const statusElem = document.getElementById('partner_status');
                        const selectedPartnerDiv = document.getElementById('selected_partner');
                        const selectedPartnerName = document.getElementById('selected_partner_name');
                        const clearButton = document.getElementById('clear_partner');
                        let timeout = null;

                        // Поиск партнеров по названию
                        partnerSearchInput.addEventListener('input', function() {
                            clearTimeout(timeout);
                            const query = partnerSearchInput.value.trim();

                            if (query.length < 2) {
                                suggestionsContainer.classList.add('hidden');
                                statusElem.textContent = 'Введите название партнера для поиска';
                                return;
                            }

                            statusElem.textContent = 'Поиск...';

                            timeout = setTimeout(() => {
                                fetch(`/admin/search-partners?q=${encodeURIComponent(query)}`)
                                    .then(res => res.json())
                                    .then(data => {
                                        suggestionsContainer.innerHTML = '';

                                        if (data.partners && data.partners.length > 0) {
                                            statusElem.textContent = `Найдено ${data.partners.length} партнеров`;

                                            data.partners.forEach(partner => {
                                                const item = document.createElement('div');
                                                item.className = 'p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0';
                                                item.innerHTML = `
                                                    <div class="font-medium">${partner.name}</div>
                                                    <div class="text-sm text-gray-600">ID: ${partner.id}</div>
                                                `;

                                                item.addEventListener('click', function() {
                                                    selectPartner(partner);
                                                });

                                                suggestionsContainer.appendChild(item);
                                            });

                                            suggestionsContainer.classList.remove('hidden');
                                        } else {
                                            statusElem.textContent = 'Партнеры не найдены';
                                            suggestionsContainer.classList.add('hidden');
                                        }
                                    })
                                    .catch(() => {
                                        statusElem.textContent = 'Ошибка поиска партнеров';
                                        suggestionsContainer.classList.add('hidden');
                                    });
                            }, 300);
                        });

                        // Выбор партнера
                        function selectPartner(partner) {
                            partnerIdInput.value = partner.id;
                            partnerSearchInput.value = '';
                            selectedPartnerName.textContent = partner.name;
                            selectedPartnerDiv.classList.remove('hidden');
                            suggestionsContainer.classList.add('hidden');
                            statusElem.textContent = 'Партнер выбран';
                        }

                        // Очистка выбора
                        clearButton.addEventListener('click', function() {
                            partnerIdInput.value = '';
                            selectedPartnerDiv.classList.add('hidden');
                            statusElem.textContent = 'Введите название партнера для поиска';
                        });

                        // Скрытие предложений при клике вне
                        document.addEventListener('click', function(e) {
                            if (!partnerSearchInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                                suggestionsContainer.classList.add('hidden');
                            }
                        });

                        // Показать выбранного партнера если ID уже установлен
                        if (partnerIdInput.value) {
                            fetch(`/admin/get-partner/${partnerIdInput.value}`)
                                .then(res => res.json())
                                .then(data => {
                                    if (data.name) {
                                        selectedPartnerName.textContent = data.name;
                                        selectedPartnerDiv.classList.remove('hidden');
                                        statusElem.textContent = 'Партнер выбран';
                                    }
                                })
                                .catch(() => {
                                    console.log('Ошибка получения данных партнера');
                                });
                        }
                    });
                </script>
            @endpush

            <hr>

            <div class="text-lg font-medium">Направление гранта</div>

            @php
                $categories = config('grants.categories');
            @endphp

            <div class="space-y-2">
                <x-multi-choice name="category" :options="$categories" :value="old('category') ? [old('category')] : []" :multiple="false" title="Категория"
                    hint="Выберите один вариант" />
                @error('category')
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
            <div class="text-lg font-medium">Описание гранта</div>

            <x-input label="Название" name="title" placeholder="Укажите название" maxlength="255"
                value="{{ old('title') }}" />

            <x-input label="Короткое описание" name="short_description" placeholder="Кратко опишите" maxlength="500"
                value="{{ old('short_description') }}" />

            <x-rich-text-area label="Описание" name="description" placeholder="Полное описание"
                value="{{ old('description') }}" />
        </section>

        <hr>

        {{-- Условия и требования --}}
        <section class="space-y-3">
            <div class="text-lg font-medium">Условия и требования</div>

            <x-multiline-input label="Условия участия" name="conditions" placeholder="Опишите условия участия в гранте"
                value="{{ old('conditions') }}" />

            <x-multiline-input label="Требования" name="requirements" placeholder="Опишите требования к участникам"
                value="{{ old('requirements') }}" />

            <x-multiline-input label="Награда/Поощрение" name="reward" placeholder="Опишите размер гранта или другие поощрения"
                value="{{ old('reward') }}" />
        </section>

        <hr>

        {{-- Место и дедлайн --}}
        <section class="space-y-3">
            <div class="text-lg font-medium">Место и сроки подачи заявок</div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Населённый пункт" name="settlement" placeholder="Укажите населённый пункт"
                    value="{{ old('settlement') }}" />
                <x-input label="Адрес" name="address" placeholder="Укажите улицу, дом" value="{{ old('address') }}" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input type="date" label="Дедлайн подачи заявок" name="deadline" value="{{ old('deadline') }}" />
            </div>
        </section>

        <hr>

        {{-- Медиафайлы --}}
        <section class="space-y-3">
            <x-files-selector label="Добавление медиафайлов" docs-name="docs" images-name="images" videos-name="videos"
                :max-docs="5" :max-images="0" :max-videos="0" :max-doc-mb="5" :max-img-mb="2"
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
            <div class="text-lg font-medium">Ссылки на соцсети гранта</div>
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
