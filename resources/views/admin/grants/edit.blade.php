@extends('layouts.sidebar-layout')

@section('title', 'Редактировать грант')

@section('content')
    <div class="flex items-center justify-between mb-0">
        <h1 class="text-3xl">Редактировать грант</h1>
    </div>
    <form action="{{ route(auth()->user()->role . '.grants.update', $grant->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Категория --}}
        <section class="space-y-3">

            <div class="text-lg font-medium">Организатор гранта</div>

            <div class="space-y-3 relative">
                <x-input label="Поиск партнера" name="partner_search" placeholder="Введите название партнера" autocomplete="off"
                    :value="$grant->partner->name ?? ''" />
                <input type="hidden" name="user_id" value="{{ old('user_id', $grant->user_id) }}" />
                <div id="partner_suggestions" class="hidden absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto z-50 mt-1"></div>
                <small id="partner_status" class="text-gray-600">Партнер выбран</small>
                <div id="selected_partner" class="p-3 bg-blue-50 border border-blue-200 rounded-md">
                    <span class="text-sm text-blue-800">Выбранный партнер: </span>
                    <span id="selected_partner_name" class="font-medium">{{ $grant->partner->name ?? 'Не указан' }}</span>
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

                        // Show selected partner if ID is already set
                        if (partnerIdInput.value) {
                            selectedPartnerDiv.classList.remove('hidden');
                            statusElem.textContent = 'Партнер выбран';
                        }

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
                    });
                </script>
            @endpush

            <hr>

            <div class="text-lg font-medium">Направление гранта</div>

            @php
                $categories = config('grants.categories');
            @endphp

            <div class="space-y-2">
                <x-multi-choice name="category" :options="$categories" :value="old('category', $grant->category)" :multiple="false" title="Категория"
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
            @if($grant->cover)
                <div class="mb-3">
                    <img src="{{ asset($grant->cover) }}" alt="Текущая обложка" class="w-32 h-32 object-cover rounded-lg">
                    <p class="text-sm text-gray-600 mt-1">Текущая обложка (оставьте пустым для сохранения)</p>
                </div>
            @endif
            <x-image-selector name="cover" label="Загрузить новую обложку" :value="old('cover_url')" :max-mb="2" :accept="['image/png', 'image/jpeg']" />
            @error('cover')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </section>

        <hr>

        {{-- Описание --}}
        <section class="space-y-4">
            <div class="text-lg font-medium">Описание гранта</div>

            <x-input label="Название" name="title" placeholder="Укажите название" maxlength="255"
                value="{{ old('title', $grant->title) }}" />

            <x-input label="Короткое описание" name="short_description" placeholder="Кратко опишите" maxlength="500"
                value="{{ old('short_description', $grant->short_description) }}" />

            <x-rich-text-area label="Описание" name="description" placeholder="Полное описание"
                value="{{ old('description', $grant->description) }}" />
        </section>

        <hr>

        {{-- Условия и требования --}}
        <section class="space-y-3">
            <div class="text-lg font-medium">Условия и требования</div>

            <x-multiline-input label="Условия участия" name="conditions" placeholder="Опишите условия участия в гранте"
                value="{{ old('conditions', $grant->conditions) }}" />

            <x-multiline-input label="Требования" name="requirements" placeholder="Опишите требования к участникам"
                value="{{ old('requirements', $grant->requirements) }}" />

            <x-multiline-input label="Награда/Поощрение" name="reward" placeholder="Опишите размер гранта или другие поощрения"
                value="{{ old('reward', $grant->reward) }}" />
        </section>

        <hr>

        {{-- Место и дедлайн --}}
        <section class="space-y-3">
            <div class="text-lg font-medium">Место и сроки подачи заявок</div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Населённый пункт" name="settlement" placeholder="Укажите населённый пункт"
                    value="{{ old('settlement', $grant->settlement) }}" />
                <x-input label="Адрес" name="address" placeholder="Укажите улицу, дом"
                    value="{{ old('address', $grant->address) }}" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input type="date" label="Дедлайн подачи заявок" name="deadline"
                    value="{{ old('deadline', $grant->deadline ? $grant->deadline->format('Y-m-d') : '') }}" />
            </div>
        </section>

        <hr>

        {{-- Медиафайлы --}}
        <section class="space-y-3">
            @if($grant->docs && count($grant->docs) > 0)
                <div class="mb-3">
                    <p class="text-sm font-medium text-gray-700 mb-2">Загруженные документы:</p>
                    <div class="space-y-2">
                        @foreach($grant->docs as $index => $doc)
                            <div class="flex items-center gap-2 p-2 bg-gray-50 rounded">
                                <x-lucide-file class="w-4 h-4 text-gray-600" />
                                <a href="{{ asset($doc) }}" target="_blank" class="text-blue-600 hover:underline text-sm">
                                    Документ {{ $index + 1 }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

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
                <x-input label="Сайт" name="web" placeholder="https://" value="{{ old('web', $grant->web) }}"
                    :help="'не обязательно к заполнению'" />
                <x-input label="Telegram" name="telegram" placeholder="@username" value="{{ old('telegram', $grant->telegram) }}"
                    :help="'не обязательно к заполнению'" />
                <x-input label="Vkontakte" name="vk" placeholder="ссылка или @" value="{{ old('vk', $grant->vk) }}"
                    :help="'не обязательно к заполнению'" />
            </div>
        </section>

        {{-- Кнопки --}}
        <div class="pt-2 flex justify-start gap-3">
            <x-button type="submit">Сохранить изменения</x-button>
            <a href="{{ route(auth()->user()->role . '.grants.index') }}"
               class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-3 py-3 text-[16px] rounded-xl transition border-2 border-gray-300 text-gray-700 hover:bg-gray-50">
                Отмена
            </a>
        </div>

        {{-- Примечание --}}
        <p class="text-xs text-gray-500">
            Нажимая «Сохранить изменения», вы соглашаетесь на обработку персональных данных и с правилами пользования платформой.
        </p>
    </form>

@endsection
