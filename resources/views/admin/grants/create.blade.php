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

            <x-input label="ID партнера" name="user_id" placeholder="ID партнера" value="{{ old('user_id') }}" />
            <small id="partner_name">Введите ID партнера</small>

            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const partnerIdInput = document.querySelector('[name="user_id"]');
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

            <div class="text-lg font-medium">Направление гранта</div>

            @php
                $categories = config('grants.categories');
            @endphp

            <div class="space-y-2">
                <x-multi-choice name="category" :options="$categories" :value="old('category', '')" :multiple="false" title="Категория"
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
