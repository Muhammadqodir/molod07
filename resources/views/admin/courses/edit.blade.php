@extends('layouts.sidebar-layout')

@section('title', 'Редактировать курс')

@section('content')
    <div class="flex items-center justify-between mb-0">
        <h1 class="text-3xl">Редактировать курс</h1>
    </div>

    <form action="{{ route('admin.education.update', $course->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- Обложка --}}
        <section class="space-y-3">
            <div class="text-lg font-medium">Обложка</div>
            @if($course->cover)
                <div class="mb-3">
                    <div class="text-sm text-gray-600 mb-2">Текущая обложка:</div>
                    <img src="{{ asset($course->cover) }}" alt="Current cover" class="w-32 h-32 object-cover rounded-lg border">
                </div>
            @endif
            <x-image-selector name="cover" label="" :value="old('cover_url')" :max-mb="2" :accept="['image/png', 'image/jpeg']" />
            <div class="text-sm text-gray-500">Оставьте пустым, чтобы сохранить текущую обложку</div>
        </section>

        <hr>

        {{-- Категория --}}
        <section class="space-y-3">

            <div class="text-lg font-medium">Категория</div>

            @php
                $categories = config('events.categories');
            @endphp

            <div class="space-y-2">
                <x-multi-choice name="category" :options="$categories" :value="old('category', $course->category)" :multiple="false" title=""
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
            <x-input label="Название" name="title" placeholder="Название курса" value="{{ old('title', $course->title) }}" />

            <x-multiline-input label="Краткое описание" name="short_description"
                placeholder="Краткое описание курса">{{ old('short_description', $course->short_description) }}</x-multiline-input>

            <x-multiline-input label="Полное описание" name="description"
                placeholder="Подробное описание курса">{{ old('description', $course->description) }}</x-multiline-input>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Длительность" name="length" placeholder="Например: 8 недель"
                    value="{{ old('length', $course->length) }}" />
                <x-input label="Количество модулей" name="module_count" type="number" placeholder="8"
                    value="{{ old('module_count', $course->module_count) }}" />
            </div>

            <x-input label="Ссылка на курс" name="link" type="url" placeholder="https://example.com/course"
                value="{{ old('link', $course->link) }}" />
        </section>

        {{-- Кнопки --}}
        <div class="flex items-center gap-3 pt-4">
            <button type="submit"
                class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-6 py-2 text-[16px] rounded-xl transition bg-[#1E44A3] text-white hover:bg-[#1E44A3]/90">
                Обновить курс
            </button>
            <a href="{{ route('admin.education.index') }}"
                class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-6 py-2 text-[16px] rounded-xl transition bg-gray-500 text-white hover:bg-gray-600">
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
