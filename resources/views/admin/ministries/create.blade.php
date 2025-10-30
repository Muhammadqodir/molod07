@extends('layouts.sidebar-layout')

@section('title', 'Создать министерство')

@section('content')
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.ministries.index') }}"
            class="inline-flex items-center justify-center w-10 h-10 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition">
            <x-lucide-arrow-left class="h-5 w-5" />
        </a>
        <h1 class="text-3xl">Создать министерство</h1>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.ministries.store') }}">
            @csrf

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Название <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Описание
                    </label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-8">
                <a href="{{ route('admin.ministries.index') }}"
                    class="px-4 py-2 text-gray-600 hover:text-gray-800 transition">
                    Отмена
                </a>
                <x-button type="submit">
                    Создать
                </x-button>
            </div>
        </form>
    </div>
@endsection
