@extends('layouts.sidebar-layout')

@section('title', 'Добавить подкаст')

@section('content')
    <div class="flex items-center justify-between mb-0">
        <h1 class="text-3xl">Добавить подкаст</h1>
    </div>

    <form action="{{ route('admin.podcasts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Автор подкаста --}}
        {{-- <section class="space-y-3">
            <div class="text-lg font-medium">Автор подкаста</div>

            <x-input label="ID пользователя" name="user_id" placeholder="ID пользователя (молодежь или партнер)"
                value="{{ old('user_id') }}" />
            <small id="user_name">Введите ID пользователя</small>

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
            <x-image-selector name="cover" label="" :value="old('cover_url')" :max-mb="2" :accept="['image/png', 'image/jpeg']" />
        </section>

        <hr>

        {{-- Категория --}}
        <section class="space-y-3">

            <div class="text-lg font-medium">Категория</div>

            @php
                $categories = config('events.categories');
            @endphp

            <div class="space-y-2">
                <x-multi-choice name="category" :options="$categories" :value="old('category') ? [old('category')] : []" :multiple="false" title=""
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
            <x-input label="Название" name="title" placeholder="Название подкаста" value="{{ old('title') }}" />

            <x-multiline-input label="Краткое описание" name="short_description"
                placeholder="Краткое описание подкаста">{{ old('short_description') }}</x-textarea>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input label="Продолжительность" name="length" placeholder="Например: 45:30"
                        value="{{ old('length') }}" />
                    <x-input label="Кол-во эпизодов" name="episode_numbers" type="number" placeholder="1"
                        value="{{ old('episode_numbers') }}" />
                </div>

                <x-input label="Ссылка на подкаст" name="link" type="url" placeholder="https://example.com/podcast"
                    value="{{ old('link') }}" />
        </section>

        {{-- Кнопки --}}
        <div class="flex items-center gap-3 pt-4">
            <button type="submit"
                class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-6 py-2 text-[16px] rounded-xl transition bg-[#1E44A3] text-white hover:bg-[#1E44A3]/90">
                {{-- <x-lucide-save class="h-4 w-4" /> --}}
                Создать подкаст
            </button>
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
