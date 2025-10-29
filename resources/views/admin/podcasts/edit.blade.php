@extends('layouts.sidebar-layout')

@section('title', 'Редактировать подкаст')

@section('content')
    <div class="flex items-center justify-between mb-0">
        <h1 class="text-3xl">Редактировать подкаст</h1>
    </div>

    <form action="{{ route('admin.podcasts.update', $podcast->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Автор подкаста --}}
        {{-- <section class="space-y-3">
            <div class="text-lg font-medium">Автор подкаста</div>

            <x-input label="ID пользователя" name="user_id" placeholder="ID пользователя (молодежь или партнер)"
                value="{{ old('user_id', $podcast->user_id) }}" />
            <small id="user_name">
                @if($podcast->creator)
                    Текущий автор: {{ $podcast->creator->name ?? 'ID: ' . $podcast->user_id }}
                @else
                    Введите ID пользователя
                @endif
            </small>

            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const userIdInput = document.querySelector('[name="user_id"]');
                        const userNameElem = document.getElementById('user_name');
                        let timeout = null;

                        userIdInput.addEventListener('input', function() {
                            clearTimeout(timeout);
                            const id = userIdInput.value.trim();
                            if (!id) {
                                userNameElem.textContent = 'Введите ID пользователя';
                                return;
                            }
                            timeout = setTimeout(() => {
                                fetch(`/admin/get-user/${id}`)
                                    .then(res => res.json())
                                    .then(data => {
                                        if (data.error || !data.name) {
                                            userNameElem.textContent = 'Пользователь не найден';
                                            return;
                                        }
                                        userNameElem.textContent =
                                            `${data.name} ${data.l_name} (${data.email})`;
                                    })
                                    .catch(() => {
                                        userNameElem.textContent = 'Ошибка поиска пользователя';
                                    });
                            }, 500);
                        });
                    });
                </script>
            @endpush
        </section> --}}

        {{-- Обложка --}}
        <section class="space-y-3">
            <div class="text-lg font-medium">Обложка</div>
            @if($podcast->cover)
                <div class="mb-3">
                    <img src="{{ asset($podcast->cover) }}" alt="Текущая обложка" class="w-32 h-32 object-cover rounded-lg">
                    <p class="text-sm text-gray-600 mt-1">Текущая обложка (оставьте пустым для сохранения)</p>
                </div>
            @endif
            <x-image-selector name="cover" label="Загрузить новую обложку" :value="old('cover_url')" :max-mb="2" :accept="['image/png', 'image/jpeg']" />
            @error('cover')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </section>

        <hr>

        {{-- Категория --}}
        <section class="space-y-3">

            <div class="text-lg font-medium">Категория</div>

            @php
                $categories = config('events.categories');
            @endphp

            <div class="space-y-2">
                <x-multi-choice name="category" :options="$categories" :value="old('category', $podcast->category)" :multiple="false" title=""
                    hint="Выберите один вариант" />
                @error('category')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </section>

        <hr>

        {{-- Основная информация --}}
        <section class="space-y-3">
            <div class="text-lg font-medium">Основная информация</div>
            <x-input label="Название" name="title" placeholder="Название подкаста" value="{{ old('title', $podcast->title) }}" />
            @error('title')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <x-multiline-input label="Краткое описание" name="short_description"
                placeholder="Краткое описание подкаста" value="{{ old('short_description', $podcast->short_description) }}" />
            @error('short_description')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input label="Продолжительность" name="length" placeholder="Например: 45:30"
                        value="{{ old('length', $podcast->length) }}" />
                    @error('length')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <x-input label="Кол-во эпизодов" name="episode_numbers" type="number" placeholder="1"
                        value="{{ old('episode_numbers', $podcast->episode_numbers) }}" />
                    @error('episode_numbers')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <x-input label="Ссылка на подкаст" name="link" type="url" placeholder="https://example.com/podcast"
                value="{{ old('link', $podcast->link) }}" />
            @error('link')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </section>

        {{-- Кнопки --}}
        <div class="flex items-center gap-3 pt-4">
            <button type="submit"
                class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-6 py-2 text-[16px] rounded-xl transition bg-[#1E44A3] text-white hover:bg-[#1E44A3]/90">
                Сохранить изменения
            </button>
            <a href="{{ route('admin.podcasts.index') }}"
               class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-3 py-3 text-[16px] rounded-xl transition border-2 border-gray-300 text-gray-700 hover:bg-gray-50">
                Отмена
            </a>
        </div>
    </form>

    {{-- Display validation errors --}}
    @if ($errors->any())
        <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="text-red-800 font-medium mb-2">Пожалуйста, исправьте следующие ошибки:</div>
            <ul class="text-red-700 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection
