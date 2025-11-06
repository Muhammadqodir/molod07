@extends('layouts.sidebar-layout')

@section('title', 'Редактировать мероприятие')

@section('content')
    <div class="flex items-center justify-between mb-0">
        <h1 class="text-3xl">Редактировать мероприятие</h1>
    </div>
    <form action="{{ route(Auth::user()->role . '.events.update', $event->id) }}" method="POST" enctype="multipart/form-data"
        class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Категория --}}
        <section class="space-y-3">

            @if (Auth::user()->role === 'admin')
                <div class="text-lg font-medium">Организатор мероприятия</div>

                <div class="space-y-3 relative">
                    <x-input label="Поиск партнера" name="partner_search" placeholder="Введите название партнера" autocomplete="off" />
                    <input type="hidden" name="user_id" value="{{ old('user_id', $event->user_id) }}" />
                    <div id="partner_suggestions" class="hidden absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-md shadow-lg max-h-48 overflow-y-auto z-50 mt-1"></div>
                    <small id="partner_status" class="text-gray-600">Введите название партнера для поиска</small>
                    <div id="selected_partner" class="{{ $event->user_id ? '' : 'hidden' }} p-3 bg-blue-50 border border-blue-200 rounded-md">
                        <span class="text-sm text-blue-800">Выбранный партнер: </span>
                        <span id="selected_partner_name" class="font-medium">{{ $event->partner->getFullName() ?? '' }}</span>
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
                                statusElem.textContent = 'Партнер выбран';
                            }
                        });
                    </script>
                @endpush

                <hr>
            @endif

            <div class="text-lg font-medium">Направление мероприятия</div>

            @php
                $categories = config('events.categories');
                $types = config('events.types');
            @endphp

            <div class="space-y-2">
                <x-multi-choice name="category" :options="$categories" :value="old('category') ? [old('category')] : ($event->category ? [$event->category] : [])" :multiple="false" title="Категория"
                    hint="Выберите один вариант" />
                @error('category')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <x-multi-choice name="type" :options="$types" :value="old('type') ? [old('type')] : ($event->type ? [$event->type] : [])" :multiple="false" title="Тип"
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
            @if($event->cover)
                <div class="mb-3">
                    <p class="text-sm text-gray-600 mb-2">Текущая обложка:</p>
                    <img src="{{ asset($event->cover) }}" alt="Текущая обложка" class="w-32 h-32 object-cover rounded-lg">
                </div>
            @endif
            <x-image-selector name="cover" label="" :value="old('cover_url')" :max-mb="2" :accept="['image/png', 'image/jpeg']" />
            <p class="text-xs text-gray-500">Оставьте пустым, если не хотите менять обложку</p>
            @error('cover')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </section>

        <hr>

        {{-- Описание --}}
        <section class="space-y-4">
            <div class="text-lg font-medium">Описание мероприятия</div>

            <x-input label="Название" name="title" placeholder="Укажите название" maxlength="255"
                value="{{ old('title', $event->title) }}" />

            <x-input label="Короткое описание" name="short_description" placeholder="Кратко опишите" maxlength="500"
                value="{{ old('short_description', $event->short_description) }}" />

            <x-rich-text-area label="Описание" name="description" placeholder="Полное описание"
                value="{{ old('description', $event->description) }}" />
        </section>

        <hr>

        {{-- Роли --}}
        <section class="space-y-3">
            <x-roles-builder label="Роли" name="roles" {{-- одно поле textarea с JSON --}}
                :value="old('roles', is_array($event->roles) ? json_encode($event->roles) : $event->roles)"
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
                    value="{{ old('settlement', $event->settlement) }}" />
                <x-input label="Адрес" name="address" placeholder="Укажите улицу, дом" value="{{ old('address', $event->address) }}" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input type="date" label="Начало" name="start" value="{{ old('start', $event->start?->format('Y-m-d')) }}" />
                <x-input type="date" label="Конец" name="end" value="{{ old('end', $event->end?->format('Y-m-d')) }}" />
            </div>
        </section>

        <hr>

        {{-- Руководитель --}}
        <section class="space-y-3">
            <div class="text-lg font-medium">Руководитель <span class="text-sm font-normal text-gray-500">(необязательно)</span></div>

            <x-input label="ID (если пользователь зарегистрирован, укажите ID)" name="supervisor_id"
                placeholder="ID пользователя" value="{{ old('supervisor_id', $event->supervisor_id) }}" :help="'не обязательно к заполнению'" />

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-input label="Имя" name="supervisor_name" placeholder="Укажите имя"
                    value="{{ old('supervisor_name', $event->supervisor_name) }}" :help="'не обязательно к заполнению'" />
                <x-input label="Фамилия" name="supervisor_l_name" placeholder="Укажите фамилию"
                    value="{{ old('supervisor_l_name', $event->supervisor_l_name) }}" :help="'не обязательно к заполнению'" />
                <x-input type="tel" label="Номер телефона" name="supervisor_phone" placeholder="+7(999)999-99-99"
                    value="{{ old('supervisor_phone', $event->supervisor_phone) }}" :help="'не обязательно к заполнению'" />
            </div>

            <x-input type="email" label="E‑mail" name="supervisor_email" placeholder="E‑mail"
                value="{{ old('supervisor_email', $event->supervisor_email) }}" :help="'не обязательно к заполнению'" />
        </section>

        <hr>

        {{-- Текущие медиафайлы --}}
        @if($event->docs || $event->images || $event->videos)
            <section class="space-y-3">
                <div class="text-lg font-medium">Текущие медиафайлы</div>

                @if($event->docs && count($event->docs) > 0)
                    <div class="space-y-2">
                        <div class="text-sm font-medium">Документы:</div>
                        <div class="grid grid-cols-1 gap-2">
                            @foreach($event->docs as $doc)
                                <div class="flex items-center gap-2 p-2 bg-gray-50 rounded">
                                    <x-lucide-file class="w-4 h-4" />
                                    <a href="{{ asset($doc) }}" target="_blank" class="text-blue-600 hover:underline text-sm">
                                        Документ {{ $loop->iteration }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($event->images && count($event->images) > 0)
                    <div class="space-y-2">
                        <div class="text-sm font-medium">Изображения:</div>
                        <div class="grid grid-cols-4 gap-2">
                            @foreach($event->images as $image)
                                <img src="{{ asset($image) }}" alt="Изображение {{ $loop->iteration }}"
                                     class="w-20 h-20 object-cover rounded">
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($event->videos && count($event->videos) > 0)
                    <div class="space-y-2">
                        <div class="text-sm font-medium">Видео:</div>
                        <div class="grid grid-cols-1 gap-2">
                            @foreach($event->videos as $video)
                                <div class="flex items-center gap-2 p-2 bg-gray-50 rounded">
                                    <x-lucide-play class="w-4 h-4" />
                                    <a href="{{ asset($video) }}" target="_blank" class="text-blue-600 hover:underline text-sm">
                                        Видео {{ $loop->iteration }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </section>
            <hr>
        @endif

        {{-- Медиафайлы --}}
        <section class="space-y-3">
            <x-files-selector label="Добавление новых медиафайлов" docs-name="docs" images-name="images" videos-name="videos"
                :max-docs="5" :max-images="6" :max-videos="1" :max-doc-mb="5" :max-img-mb="2"
                :max-vid-mb="100" :doc-accept="['application/pdf']" :img-accept="['image/png', 'image/jpeg']" :vid-accept="['video/mp4', 'video/quicktime']" />
            <p class="text-xs text-gray-500">Новые файлы будут добавлены к существующим</p>
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
                <x-input label="Сайт" name="web" placeholder="https://" value="{{ old('web', $event->web) }}"
                    :help="'не обязательно к заполнению'" />
                <x-input label="Telegram" name="telegram" placeholder="@username" value="{{ old('telegram', $event->telegram) }}"
                    :help="'не обязательно к заполнению'" />
                <x-input label="Vkontakte" name="vk" placeholder="ссылка или @" value="{{ old('vk', $event->vk) }}"
                    :help="'не обязательно к заполнению'" />
            </div>
        </section>

        {{-- Кнопка --}}
        <div class="pt-2 flex justify-start">
            <x-button type="submit">Сохранить изменения</x-button>
        </div>

        {{-- Примечание --}}
        <p class="text-xs text-gray-500">
            Нажимая «Сохранить», вы соглашаетесь на обработку персональных данных и с правилами пользования платформой.
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
