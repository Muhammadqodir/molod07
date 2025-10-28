@extends('layouts.sidebar-layout')

@section('title', 'Редактировать новость')

@section('content')
    <div class="flex items-center justify-between mb-0">
        <h1 class="text-3xl">Редактировать новость</h1>
    </div>
    <form action="{{ route(Auth::user()->role . '.news.update', $news->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Обложка --}}
        <section class="space-y-3">
            <div class="text-lg font-medium">Обложка</div>
            @if($news->cover)
                <div class="mb-3">
                    <p class="text-sm text-gray-600 mb-2">Текущая обложка:</p>
                    <img src="{{ asset($news->cover) }}" alt="Текущая обложка" class="w-32 h-32 object-cover rounded-lg">
                </div>
            @endif
            <x-image-selector name="cover" label="" :value="old('cover_url')" :max-mb="2" :accept="['image/png', 'image/jpeg']" />
            <p class="text-xs text-gray-500">Оставьте пустым, если не хотите менять обложку</p>
        </section>

        <hr>

        {{-- Категория --}}
        <section class="space-y-3">

            <div class="text-lg font-medium">Категория</div>

            @php
                $categories = config('events.categories');
            @endphp

            <div class="space-y-2">
                <x-multi-choice name="category" :options="$categories" :value="old('category', $news->category)" :multiple="false" title=""
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
                    value="{{ old('publication_date', $news->publication_date ?: date('Y-m-d')) }}"
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
                value="{{ old('title', $news->title) }}" />

            <x-input label="Короткое описание" name="short_description" placeholder="Кратко опишите" maxlength="500"
                value="{{ old('short_description', $news->short_description) }}" />

            <x-rich-text-area label="Описание" name="description" placeholder="Полное описание"
                value="{{ old('description', $news->description) }}" />
        </section>

        {{-- Кнопки --}}
        <div class="pt-2 flex justify-start gap-3">
            <x-button type="submit">Обновить новость</x-button>
            <a href="{{ route(Auth::user()->role . '.news.index') }}"
               class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-3 py-3 text-[16px] rounded-xl transition border-2 border-gray-300 text-gray-600 hover:bg-gray-50">
                Отмена
            </a>
        </div>

        {{-- Примечание --}}
        <p class="text-xs text-gray-500">
            Нажимая «Обновить», вы соглашаетесь на обработку персональных данных и с правилами пользования платформой.
        </p>
    </form>

@endsection
