@extends('layouts.sidebar-layout')

@section('title', 'Добавить новость')

@section('content')
    <div class="flex items-center justify-between mb-0">
        <h1 class="text-3xl">Добавить новость</h1>
    </div>
    <form action="{{ route(Auth::user()->role . '.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
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
                <x-multi-choice name="category" :options="$categories" :value="old('category', '')" :multiple="false" title=""
                    hint="Выберите один вариант" />
                @error('category')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </section>

        <hr>

        {{-- Дата публикации --}}
        <section class="space-y-3">
            <div class="text-lg font-medium">Дата публикации</div>
            <div class="space-y-2">
                <x-input
                    label="Дата публикации"
                    name="publication_date"
                    type="date"
                    placeholder="Выберите дату публикации"
                    value="{{ old('publication_date', date('Y-m-d')) }}"
                />
                @error('publication_date')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500">Если не выбрана, будет использована текущая дата</p>
            </div>
        </section>

        <hr>

        {{-- Основная информация --}}
        <section class="space-y-4">
            <div class="text-lg font-medium">Основная информация</div>

            <x-input label="Заголовок" name="title" placeholder="Укажите заголовок" maxlength="255"
                value="{{ old('title') }}" />

            <x-input label="Короткое описание" name="short_description" placeholder="Кратко опишите" maxlength="500"
                value="{{ old('short_description') }}" />

            <x-rich-text-area label="Описание" name="description" placeholder="Полное описание"
                value="{{ old('description') }}" />
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
