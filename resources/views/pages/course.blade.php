@extends('layouts.app')

@section('title', $course->title)

@section('content')
    @php
        use Carbon\Carbon;
    @endphp

    <section class="bg-accentbg mt-[-80px] pt-[80px]">
        {{-- Flash messages as alerts --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                class="fixed z-[9999999999] top-[80px] right-6 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
                <div class="flex items-center gap-2">
                    <x-lucide-check-circle class="w-5 h-5" />
                    <strong>Успешно!</strong>
                </div>
                <div class="text-sm mt-1">{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                class="fixed z-[9999999999] top-[80px] right-6 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
                <div class="flex items-center gap-2">
                    <x-lucide-alert-circle class="w-5 h-5" />
                    <strong>Ошибка!</strong>
                </div>
                <div class="text-sm mt-1">{{ session('error') }}</div>
            </div>
        @endif

        <div class="container mx-auto px-4 py-8" x-data="{ tab: 'info' }">
            {{-- Header section --}}
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-6 mb-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Course Cover --}}
                    <div class="lg:col-span-1">
                        <img src="{{ asset($course->cover) }}" alt="{{ $course->title }}"
                            class="w-full h-64 object-cover rounded-2xl">
                    </div>

                    {{-- Course Info --}}
                    <div class="lg:col-span-2 flex flex-col">
                        <div class="flex flex-wrap items-center gap-2 mb-4">
                            <span class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-3 py-1 rounded-xl">
                                <x-lucide-graduation-cap class="w-4 h-4" />
                                {{ $course->category }}
                            </span>

                            <span class="inline-flex items-center gap-2 bg-green-50 text-green-700 px-3 py-1 rounded-xl">
                                <x-lucide-clock class="w-4 h-4" />
                                {{ $course->length }}
                            </span>

                            <span class="inline-flex items-center gap-2 bg-purple-50 text-purple-700 px-3 py-1 rounded-xl">
                                <x-lucide-layers class="w-4 h-4" />
                                {{ $course->module_count }} модул{{ $course->module_count > 1 ? 'ей' : 'ь' }}
                            </span>
                        </div>

                        <h1 class="text-2xl md:text-3xl font-semibold leading-tight">
                            {{ $course->title }}
                        </h1>

                        @if ($course->short_description)
                            <p class="text-gray-600 text-sm md:text-base mt-4">
                                {{ $course->short_description }}
                            </p>
                        @endif

                        <div class="mt-auto flex items-center justify-between gap-3 pt-6">
                            @if ($course->link)
                                <a href="{{ $course->link }}" target="_blank"
                                    class="inline-flex items-center justify-center h-12 cursor-pointer gap-2 px-6 py-3 text-[16px] rounded-xl transition bg-[#1E44A3] text-white hover:bg-[#1E44A3]/90">
                                    <x-lucide-external-link class="w-5 h-5" />
                                    Перейти к курсу
                                </a>
                            @endif

                            <div class="flex items-center gap-2 text-gray-500">
                                <button type="button" class="share-button p-2 hover:bg-gray-100 rounded-xl"
                                        title="Поделиться"
                                        data-share-title="{{ $course->title }}"
                                        data-share-url="{{ request()->fullUrl() }}">
                                    <x-lucide-share-2 class="w-5 h-5" />
                                </button>
                                <button type="button" class="p-2 hover:bg-gray-100 rounded-xl" title="В избранное">
                                    <x-lucide-heart class="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section tabs --}}
            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-6">

                {{-- Info tab --}}
                <div x-show="tab === 'info'" class="space-y-6">
                    @if ($course->description)
                        <div>
                            <h3 class="text-lg font-semibold mb-3">Описание курса</h3>
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($course->description)) !!}
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">Детали курса</h3>

                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <x-lucide-clock class="w-5 h-5 text-gray-400" />
                                    <div>
                                        <div class="text-sm text-gray-500">Длительность</div>
                                        <div class="font-medium">{{ $course->length }}</div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <x-lucide-layers class="w-5 h-5 text-gray-400" />
                                    <div>
                                        <div class="text-sm text-gray-500">Количество модулей</div>
                                        <div class="font-medium">{{ $course->module_count }}</div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <x-lucide-tag class="w-5 h-5 text-gray-400" />
                                    <div>
                                        <div class="text-sm text-gray-500">Категория</div>
                                        <div class="font-medium">{{ $course->category }}</div>
                                    </div>
                                </div>

                                @if ($course->link)
                                    <div class="flex items-center gap-3">
                                        <x-lucide-external-link class="w-5 h-5 text-gray-400" />
                                        <div>
                                            <div class="text-sm text-gray-500">Ссылка на курс</div>
                                            <a href="{{ $course->link }}" target="_blank"
                                                class="font-medium text-blue-600 hover:text-blue-800 break-all">
                                                {{ $course->link }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
