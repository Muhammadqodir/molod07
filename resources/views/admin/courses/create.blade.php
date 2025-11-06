@extends('layouts.sidebar-layout')

@section('title', 'Добавить курс')

@section('content')
    <div class="flex items-center justify-between mb-0">
        <h1 class="text-3xl">Добавить курс</h1>
    </div>

    <form action="{{ route('admin.education.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
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
            <x-input label="Название" name="title" placeholder="Название курса" value="{{ old('title') }}" />

            <x-multiline-input label="Краткое описание" name="short_description"
                placeholder="Краткое описание курса">{{ old('short_description') }}</x-multiline-input>

            <x-multiline-input label="Полное описание" name="description"
                placeholder="Подробное описание курса">{{ old('description') }}</x-multiline-input>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Длительность" name="length" placeholder="Например: 8 недель"
                    value="{{ old('length') }}" />
                <x-input label="Количество модулей" name="module_count" type="number" placeholder="8"
                    value="{{ old('module_count') }}" />
            </div>

            <x-input label="Ссылка на курс" name="link" type="url" placeholder="https://example.com/course"
                value="{{ old('link') }}" />
        </section>

        {{-- Кнопки --}}
        <div class="flex items-center gap-3 pt-4">
            <button type="submit"
                class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-6 py-2 text-[16px] rounded-xl transition bg-[#1E44A3] text-white hover:bg-[#1E44A3]/90">
                Создать курс
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
