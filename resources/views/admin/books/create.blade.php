@extends('layouts.sidebar-layout')

@section('title', 'Добавить книгу')

@section('content')
    <div class="flex items-center justify-between mb-0">
        <h1 class="text-3xl">Добавить книгу</h1>
    </div>

    <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Обложка --}}
        <section class="space-y-3">
            <div class="text-lg font-medium">Обложка книги</div>
            <x-image-selector name="cover" label="" :value="old('cover_url')" :max-mb="2" :accept="['image/png', 'image/jpeg']" />
        </section>

        <hr>

        {{-- Основная информация --}}
        <section class="space-y-3">
            <div class="text-lg font-medium">Основная информация</div>

            <x-input label="Название книги" name="title" placeholder="Введите название книги" value="{{ old('title') }}" required />

            <x-input label="Автор" name="author" placeholder="Введите имя автора" value="{{ old('author') }}" required />

            <x-multiline-input label="Краткое описание / аннотация" name="description"
                placeholder="Введите краткое описание или аннотацию книги">{{ old('description') }}</x-multiline-input>

            <x-input label="Ссылка на электронную версию" name="link" type="url" placeholder="https://example.com/book.pdf"
                value="{{ old('link') }}" />

            <p class="text-sm text-gray-500">Укажите ссылку на электронную версию книги или страницу для чтения</p>
        </section>

        {{-- Кнопки --}}
        <div class="flex items-center gap-3 pt-4">
            <button type="submit"
                class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-6 py-2 text-[16px] rounded-xl transition bg-[#1E44A3] text-white hover:bg-[#1E44A3]/90">
                Добавить книгу
            </button>

            <a href="{{ route('admin.books.index') }}"
                class="inline-flex items-center justify-center h-10 cursor-pointer gap-1 px-6 py-2 text-[16px] rounded-xl transition border-2 border-gray-300 text-gray-700 hover:bg-gray-50">
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
